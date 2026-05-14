<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use App\Models\User;
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
            Log::error('Unable to refresh Google token for user ' . ($user->id ?? 'unknown'));
            return false;
        }

        $service = new Google_Service_Calendar($client);

        // Define timezone before using it
        $tz = config('app.timezone') ?: 'UTC';

        // Build event title and formatted description from the training details
        $courseTitle = $training->course->course_name ?? 'Training';
        $summary = 'Training: ' . $courseTitle;
        $description = $this->buildDescription($training, $schedule, $attendeeEmails, $tz);

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
            Log::info('Google Calendar event created', ['eventId' => $created->getId(), 'user' => ($user->id ?? 'unknown')]);
            return $created->getId();
        } catch (\Exception $e) {
            Log::error('Google Calendar create event failed: ' . $e->getMessage());
            return false;
        }
    }

    public function updateEvent($user, $eventId, $training, $schedule, array $attendeeEmails = [], $refreshToken = null, $accessToken = null)
    {
        if (empty($eventId)) {
            return false;
        }

        $token_to_use = $refreshToken
            ?? ($user && isset($user->google_refresh_token) ? $user->google_refresh_token : null)
            ?? $accessToken
            ?? ($user && isset($user->google_access_token) ? $user->google_access_token : null);

        if (!$token_to_use) {
            Log::info('User not connected to Google Calendar or no refresh token for update');
            return false;
        }

        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setAccessType('offline');

        if ($refreshToken || ($user && isset($user->google_refresh_token))) {
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
            Log::error('Unable to refresh Google token for user (update) ' . ($user->id ?? 'unknown'));
            return false;
        }

        $service = new Google_Service_Calendar($client);

        // Define timezone before using it
        $tz = config('app.timezone') ?: 'UTC';

        $courseTitle = $training->course->course_name ?? 'Training';
        $summary = 'Training: ' . $courseTitle;
        $description = $this->buildDescription($training, $schedule, $attendeeEmails, $tz);

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
            $updated = $service->events->update('primary', $eventId, $event, ['sendUpdates' => 'all']);
            Log::info('Google Calendar event updated', ['eventId' => $eventId, 'user' => ($user->id ?? 'unknown')]);
            return $updated->getId();
        } catch (\Exception $e) {
            Log::error('Google Calendar update event failed: ' . $e->getMessage());
            return false;
        }
    }

    private function buildDescription($training, $schedule, array $attendeeEmails, string $timezone): string
    {
        $facilitator = $training->facilitator?->name ?? 'N/A';
        $company = $training->company?->company_name ?? 'N/A';
        $course = $training->course?->course_name ?? 'N/A';
        $account = $training->account?->account_email ?? 'N/A';
        $assistantNames = $this->resolveAssistantNames($training->assistant ?? '');
        $driverNeeded = $training->need_transportation ? 'Yes' : 'No';
        // Normalize platform: treat null/empty/'null' as empty
        $platform = null;
        if (isset($training->platform)) {
            $plat = trim((string) $training->platform);
            if ($plat !== '' && strtolower($plat) !== 'null') {
                $platform = $training->platform;
            }
        }
        $returnTripNeeded = $training->return_trip_needed ? 'Yes' : 'No';
        $notifyCoordinator = $training->notify_coordinator ? 'Yes' : 'No';

        // Resolve coordinator name: prefer related model, otherwise try to look up by coordinator_to_notify id
        $coordinator = null;
        if (!empty($training->coordinator?->name)) {
            $coordinator = $training->coordinator->name;
        } elseif (!empty($training->coordinator_to_notify)) {
            // Attempt to resolve user id to name
            $coordId = $training->coordinator_to_notify;
            if (is_numeric($coordId)) {
                $coordUser = User::find((int) $coordId);
                if ($coordUser) {
                    $coordinator = $coordUser->name;
                } else {
                    $coordinator = 'User ID ' . $coordId;
                }
            } else {
                $coordinator = (string) $coordId;
            }
        }

        $lines = [];

        // Only add lines if the value is not 'N/A' or empty
        if ($course !== 'N/A') $lines[] = 'Course: ' . $course;
        if ($company !== 'N/A') $lines[] = 'Company: ' . $company;
        if ($facilitator !== 'N/A') $lines[] = 'Facilitator: ' . $facilitator;
        if ($assistantNames !== '') $lines[] = 'Assistants: ' . $assistantNames;
        if (!empty($training->location)) $lines[] = 'Location: ' . $training->location;
        if (!empty($platform)) $lines[] = 'Platform: ' . $platform;
        if (!empty($training->conference_link)) $lines[] = 'Conference Link: ' . $training->conference_link;
        if ($account !== 'N/A') $lines[] = 'Account: ' . $account;
        if (!empty($schedule->from_date) && !empty($schedule->from_time) && !empty($schedule->to_date) && !empty($schedule->to_time)) {
            $lines[] = 'Schedule: ' . $schedule->from_date . ' ' . $schedule->from_time . ' to ' . $schedule->to_date . ' ' . $schedule->to_time;
        }

        // Driver Arrangement section - only show if transportation is needed or if any driver field is filled
        if ($training->need_transportation || !empty($training->outbound_pickup_time) || !empty($training->outbound_contact_number) ||
            !empty($training->outbound_pickup_location) || !empty($training->outbound_dropoff_location) ||
            !empty($training->return_pickup_time) || !empty($training->return_contact_number) ||
            !empty($training->return_pickup_location) || !empty($training->return_dropoff_location)) {
            
            $lines[] = 'Driver Arrangement:';
            $lines[] = '  Transportation Needed: ' . $driverNeeded;
            if (!empty($training->outbound_pickup_time)) $lines[] = '  Outbound Pickup Time: ' . $training->outbound_pickup_time;
            if (!empty($training->outbound_contact_number)) $lines[] = '  Outbound Contact Number: ' . $training->outbound_contact_number;
            if (!empty($training->outbound_pickup_location)) $lines[] = '  Outbound Pickup Location: ' . $training->outbound_pickup_location;
            if (!empty($training->outbound_dropoff_location)) $lines[] = '  Outbound Dropoff Location: ' . $training->outbound_dropoff_location;
            $lines[] = '  Return Trip Needed: ' . $returnTripNeeded;
            if (!empty($training->return_pickup_time)) $lines[] = '  Return Pickup Time: ' . $training->return_pickup_time;
            if (!empty($training->return_contact_number)) $lines[] = '  Return Contact Number: ' . $training->return_contact_number;
            if (!empty($training->return_pickup_location)) $lines[] = '  Return Pickup Location: ' . $training->return_pickup_location;
            if (!empty($training->return_dropoff_location)) $lines[] = '  Return Dropoff Location: ' . $training->return_dropoff_location;
        }

        if ($notifyCoordinator === 'Yes') $lines[] = 'Notify Coordinator: Yes';
        if (!empty($coordinator)) $lines[] = 'Coordinator to Notify: ' . $coordinator;

        return implode("\n", $lines);
    }

    private function resolveAssistantNames(string $assistantValue): string
    {
        if (trim($assistantValue) === '') {
            return '';
        }

        $names = [];
        $assistantIds = array_filter(array_map('trim', explode(',', $assistantValue)));

        foreach ($assistantIds as $assistantId) {
            if (!is_numeric($assistantId)) {
                continue;
            }

            $user = User::find((int) $assistantId);
            if ($user) {
                $names[] = $user->name;
            } else {
                $names[] = 'User ID ' . $assistantId;
            }
        }

        return implode(', ', $names);
    }
}
