<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('login.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        //  REDIRECT ACCORDING TO USERTYPE
        $redirectRoutes = [
            'admin' =>  'calendar',                //  REDIRECT TO ADMIN LANDING PAGE
            'coordinator' => 'calendar',   //  REDIRECT TO COORDINATOR LANDING PAGE
            'facilitator' => 'calendar',               //  REDIRECT TO TRAINER LANDING PAGE
        ];

        $usertype = $request->user()->usertype;

        if (array_key_exists($usertype, $redirectRoutes))
        {
            return redirect()->route($redirectRoutes[$usertype]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('index');
    }
}
