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

    public function get_user(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }

        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function delete_user($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    
        $user->delete();
    
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }    

    public function edit(Request $request,int $id)
    {
        return view('access.edit_user');
    }
}
