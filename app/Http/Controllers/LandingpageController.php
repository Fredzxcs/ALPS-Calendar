<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function admin()
    {
        $user = auth()->user();
        return view('main-content.calendar', ['user' => $user]);       // VIEW LANDINGPAGE.BLADEFILE
    }
    public function coordinator()
    {
        $user = auth()->user(); // Fetch the currently authenticated user
        return view('main-content.calendar', ['user' => $user]);       // VIEW LANDINGPAGE.BLADEFILE
    }
    public function trainer()
    {
        return view('alpstrainer.trainerLp');       // VIEW LANDINGPAGE.BLADEFILE
    }
}
