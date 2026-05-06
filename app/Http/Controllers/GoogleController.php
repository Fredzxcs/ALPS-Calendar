<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Google_Client;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
        // Store the page the user came from to return after OAuth
        $from = $request->query('from', 'dashboard');
        $request->session()->put('oauth_previous_route', $from);

        $client = $this->getGoogleClient();

        $authUrl = $client->createAuthUrl();

        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return redirect()->back()->with('error', 'Google authorization failed');
        }

        $client = $this->getGoogleClient();

        try {
            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                Log::error('Google token error', $token);
                return redirect()->route('calendar')->with('error', 'Google token error');
            }

            $client->setAccessToken($token);

            // Get user info
            $oauth2 = new \Google_Service_Oauth2($client);
            $googleUser = $oauth2->userinfo->get();

            $user = $request->user();
            $isDemo = filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN);
            
            if (!$user) {
                if ($isDemo) {
                    // Store connection state and tokens in session for demo mode
                    $request->session()->put('google_connected', true);
                    if (isset($token['refresh_token'])) {
                        $request->session()->put('google_refresh_token', $token['refresh_token']);
                    }
                    if (isset($token['access_token'])) {
                        $request->session()->put('google_access_token', $token['access_token']);
                    }
                    if (isset($token['expires_in'])) {
                        $request->session()->put('google_token_expires_at', now()->addSeconds($token['expires_in'])->timestamp);
                    }
                    $previousRoute = $request->session()->pull('oauth_previous_route', 'add_training');
                    return redirect()->route($previousRoute)->with('success', 'Google Calendar connected in demo mode');
                }

                $previousRoute = $request->session()->pull('oauth_previous_route', 'dashboard');
                return redirect()->route($previousRoute)->with('error', 'Not authenticated');
            }

            // Only save tokens if user is a real User model (not GenericUser in demo)
            if ($user instanceof User) {
                $user->google_id = $googleUser->getId();
                if (isset($token['access_token'])) {
                    $user->google_access_token = $token['access_token'];
                }
                if (isset($token['refresh_token'])) {
                    $user->google_refresh_token = $token['refresh_token'];
                }
                if (isset($token['expires_in'])) {
                    $user->google_token_expires_at = now()->addSeconds($token['expires_in']);
                }
                $user->google_connected_at = now();
                $user->save();
            } else if ($isDemo) {
                // Store connection state and tokens in session for demo mode with GenericUser
                $request->session()->put('google_connected', true);
                if (isset($token['refresh_token'])) {
                    $request->session()->put('google_refresh_token', $token['refresh_token']);
                }
                if (isset($token['access_token'])) {
                    $request->session()->put('google_access_token', $token['access_token']);
                }
                if (isset($token['expires_in'])) {
                    $request->session()->put('google_token_expires_at', now()->addSeconds($token['expires_in'])->timestamp);
                }
            }

            if (!$request->session()->has('google_refresh_token') && $request->session()->has('google_access_token')) {
                $request->session()->put('google_access_token', $request->session()->get('google_access_token'));
            }

            // Return to the page user came from
            $previousRoute = $request->session()->pull('oauth_previous_route', 'dashboard');
            return redirect()->route($previousRoute)->with('success', 'Google Calendar connected');

        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage());
            $previousRoute = $request->session()->pull('oauth_previous_route', 'dashboard');
            return redirect()->route($previousRoute)->with('error', 'Google OAuth error');
        }
    }

    protected function getGoogleClient()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->addScope([
            \Google_Service_Calendar::CALENDAR_EVENTS,
            'email',
            'profile',
        ]);

        return $client;
    }
}
