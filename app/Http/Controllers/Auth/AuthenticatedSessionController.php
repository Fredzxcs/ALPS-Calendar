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
        return view('auth.login');
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
            'admin' =>  'admin/landingpage',                //  REDIRECT TO ADMIN LANDING PAGE
            'coordinator' => 'coordinator/coordinatorLp',   //  REDIRECT TO COORDINATOR LANDING PAGE
            'facilitator' => 'trainer/trainerLp',               //  REDIRECT TO TRAINER LANDING PAGE
        ];

        $usertype = $request->user()->usertype;

        // IDENTIFY USERTYPE
        if (array_key_exists($usertype, $redirectRoutes))
        {
            return redirect($redirectRoutes[$usertype]);
        }
    
    //     if($request->user()->usertype === 'admin')
    //     {
    //         return redirect('admin/landingpage');       //  REDIRECT TO ADMIN LANDING PAGE
    //     }

    //     elseif($request->user()->usertype === 'coordinator')
    //     {
    //         return redirect('admin/coordinatorLp');       //  REDIRECT TO ADMIN LANDING PAGE
    //     }

    //     elseif($request->user()->usertype === 'trainer')
    //     {
    //         return redirect('admin/trainerLp');       //  REDIRECT TO ADMIN LANDING PAGE
    //     }

    //     return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
