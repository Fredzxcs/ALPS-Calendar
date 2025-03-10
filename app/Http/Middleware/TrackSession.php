<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class TrackSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated, request is GET, and is NOT an API or AJAX request
        if (
            Auth::check() &&
            $request->isMethod('get') &&
            !$request->isXmlHttpRequest() && // Exclude AJAX requests
            !str_contains($request->path(), 'api') && // Exclude API calls
            Route::currentRouteName() !== 'index' // Do not store if the route is 'index'
        ) {
            $lastVisitedPage = $request->url();
            Session::put('last_visited_page', $lastVisitedPage);

            // Log last visited page
            Log::info('Page Visited', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email ?? 'Guest',
                'last_visited_page' => $lastVisitedPage,
                'timestamp' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        // Redirect to last visited page only if:
        // - The user is on the index page
        // - The last visited page exists
        // - The last visited page is NOT the index itself
        if (Route::currentRouteName() === 'index' && Auth::check()) {
            $lastVisitedPage = Session::get('last_visited_page');

            if ($lastVisitedPage && $lastVisitedPage !== route('index')) {
                return redirect($lastVisitedPage);
            }
        }

        return $next($request);
    }
}
