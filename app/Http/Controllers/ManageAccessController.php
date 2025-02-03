<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

    public function edit_user(Request $request, $id)
    {
        return view('access.edit_user', ['user' => $id]);        
    }

    // Update User Data
    public function update_user(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Validate Request
            $request->validate([
                'usertype' => 'required|string|max:15',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'contact_number' => 'required|numeric|digits_between:11,15',
                'id_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            ]);

            // Construct the full name
            $fullName = trim("{$request->first_name} " . 
                ($request->middle_name ? "{$request->middle_name} " : '') . 
                "{$request->last_name}" . 
                ($request->suffix ? ", {$request->suffix}" : ''));

            // Update User Fields
            $user->name = $fullName; // Store full name in 'name' column
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            $user->usertype = $request->usertype;
            // Handle ID Picture Upload
            if ($request->hasFile('id_picture')) {

                // Save new picture
                $path = $request->file('id_picture')->store('images', 'public');
                $user->image = $path;
            }

            $user->save();

            return response()->json(['success' => true, 'message' => 'User updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating user: ' . $e->getMessage()], 500);
        }
    }

    // 
    public function change_credentials($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
    
        return view('access.change_credentials', compact('user'));
    }

    public function update_credentials(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'color' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed' // Password is optional but must be confirmed
        ]);

        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Update fields
        $user->username = $request->username;
        $user->color = $request->color;

        // Only update password if it's provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Credentials updated successfully.');
    }
        
    // public function edit_user($encryptedId)
    // {
    //     try {
    //         // Decrypt the ID to get the raw user ID
    //         $id = Crypt::decrypt($encryptedId);
            
    //         // Fetch the user from the database
    //         $user = User::findOrFail($id);

    //         // Pass the user data to the view
    //         return view('access.edit_user', compact('user', 'encryptedId'));  // You can also pass the encryptedId if needed in JS
    //     } catch (\Exception $e) {
    //         // Handle the error (e.g., unauthorized access or invalid ID)
    //         return abort(403, 'Unauthorized Access');
    //     }
    // }

    
}
