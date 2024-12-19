<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function admin()
    {
        return view('alpsadmin.landingpage');       // VIEW LANDINGPAGE.BLADEFILE
    }
    public function coordinator()
    {
        $user = auth()->user(); // Fetch the currently authenticated user
        return view('alpscoordinator.coordinatorLp', ['user' => $user]);       // VIEW LANDINGPAGE.BLADEFILE
    }
    public function trainer()
    {
        return view('alpstrainer.trainerLp');       // VIEW LANDINGPAGE.BLADEFILE
    }
}
