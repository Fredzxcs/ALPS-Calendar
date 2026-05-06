<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleCalendarService
{
    public function createEvent($user, $training, $schedule, array $attendeeEmails = [], $refreshToken = null, $accessToken = null)
    {
        // Get token from refresh token, access token, or user model (demo mode may only have access token)
        $token_to_use = $refreshToken
            ?? ($user && isset($user->google_refresh_token) ? $user->google_refresh_token : null)
            ?? $accessToken
            ?? ($user && isset($user->google_access_token) ? $user->google_access_token : null);
        
        if (!$token_to_use) {
            Log::info('User not connected to Google Calendar or no refresh token');
            return false;
        }

        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setAccessType('offline');

        if ($refreshToken || ($user && isset($user->google_refresh_token))) {
            // Exchange refresh token for access token
            $client->refreshToken($token_to_use);
        } else {
            $client->setAccessToken([
                'access_token' => $token_to_use,
                'expires_in' => 3600,
                'created' => time(),
            ]);
        }

        $token = $client->getAccessToken();

        if (!$token) {
            Log::error('Unable to refresh Google token for user ' . $user->id);
            return false;
        }

        $service = new Google_Service_Calendar($client);

        // Build event
        $summary = $training->course->course_name ?? 'Training';
        $description = 'Training for ' . ($training->company->company_name ?? '');

        $tz = config('app.timezone') ?: 'UTC';

        try {
            $startDt = Carbon::createFromFormat('Y-m-d H:i', $schedule->from_date . ' ' . $schedule->from_time, $tz);
        } catch (\Exception $e) {
            $startDt = Carbon::parse($schedule->from_date . ' ' . $schedule->from_time, $tz);
        }

        try {
            $endDt = Carbon::createFromFormat('Y-m-d H:i', $schedule->to_date . ' ' . $schedule->to_time, $tz);
        } catch (\Exception $e) {
            $endDt = Carbon::parse($schedule->to_date . ' ' . $schedule->to_time, $tz);
        }

        $start = $startDt->toRfc3339String();
        $end = $endDt->toRfc3339String();

        $event = new Google_Service_Calendar_Event([
            'summary' => $summary,
            'description' => $description,
            'start' => ['dateTime' => $start, 'timeZone' => $tz],
            'end' => ['dateTime' => $end, 'timeZone' => $tz],
            'attendees' => array_map(function ($email) { return ['email' => $email]; }, $attendeeEmails),
            'reminders' => ['useDefault' => true],
        ]);

        try {
            $created = $service->events->insert('primary', $event, ['sendUpdates' => 'all']);
            Log::info('Google Calendar event created', ['eventId' => $created->getId(), 'user' => $user->id]);
            return $created;
        } catch (\Exception $e) {
            Log::error('Google Calendar create event failed: ' . $e->getMessage());
            return false;
        }
    }
}
