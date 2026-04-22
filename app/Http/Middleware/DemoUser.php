<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DemoUser
{
    /**
     * Set a synthetic authenticated user for demo mode without database access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN) && !Auth::check()) {
            Auth::setUser(new GenericUser([
                'id' => 1,
                'name' => 'Demo Admin',
                'username' => 'demo.admin',
                'email' => 'demo@local.test',
                'usertype' => 'admin',
                'color' => '#0d6efd',
                'image' => null,
                'contact_number' => '09000000000',
                'remember_token' => null,
            ]));
        }

        return $next($request);
    }
}
