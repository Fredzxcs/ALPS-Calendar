<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class userType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$allowedUserTypes)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('index');
        }

        // Check if the user's usertype matches any of the allowed usertypes
        $userType = Auth::user()->usertype;
        if (!in_array($userType, $allowedUserTypes)) {
            return redirect()->route('calendar'); // Redirect to the calendar route
        }

        return $next($request);
    }
}
