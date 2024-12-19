<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class ManageAccessController extends Controller
{
    public function index(): View
    {
        $users = User::all();

        return view('access.manage_access', compact('users'));
    }

    public function create(): View
    {
        return view('access.add_user');
    }
}
