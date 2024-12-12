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
    public function handle(Request $request, Closure $next, string $requiredUserType)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if the user's usertype matches the required usertype
        $userType = Auth::user()->usertype;
        if ($userType !== $requiredUserType) {
            return redirect('/'); // Redirect to a default route if usertype mismatches
        }

        return $next($request);
    } 
}
