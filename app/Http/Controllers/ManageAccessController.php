<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
//use Illuminate\Support\Facades\Crypt;

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
    
            \Log::info('User data:', $request->all()); // Log request data for debugging
    
            $validator = Validator::make($request->all(), [
                'usertype' => ['required', 'string', 'max:15'],
                'fullname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'contact_number' => ['required', 'numeric', 'digits_between:11,15'],
                'id_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ], [
                'usertype.required' => 'Please choose a user role before saving.',
                'usertype.string' => 'The user role should be written as text.',
                'usertype.max' => 'The user role is too long. Please keep it to 15 characters or fewer.',
                'fullname.required' => 'Please enter the user\'s full name before updating.',
                'fullname.string' => 'The full name should be written as text.',
                'fullname.max' => 'The full name is too long. Please keep it to 255 characters or fewer.',
                'email.required' => 'Please enter an email address for the user.',
                'email.email' => 'Please enter a valid email address such as name@example.com.',
                'email.max' => 'The email address is too long. Please keep it to 255 characters or fewer.',
                'email.unique' => 'That email address is already in use. Please choose another one.',
                'contact_number.required' => 'Please enter a contact number for the user.',
                'contact_number.numeric' => 'The contact number should contain only numbers.',
                'contact_number.digits_between' => 'The contact number should be between 11 and 15 digits long.',
                'id_picture.image' => 'Please upload a valid image file for the user photo.',
                'id_picture.mimes' => 'The photo must be a JPEG, PNG, JPG, or GIF image.',
                'id_picture.max' => 'The photo is too large. Please upload an image smaller than 2 MB.',
            ]);
    
            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Please review the user details and try again.',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            // Update User Fields
            $user->name = $request->fullname;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            $user->usertype = $request->usertype;
    
            //Handle ID Picture Upload (Optional)
            if ($request->hasFile('id_picture')) {
                // Delete old picture if exists
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
    
                // Save new picture
                $path = $request->file('id_picture')->store('images', 'public');
                $user->image = $path;
            }
    
            $user->save();
    
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'redirect_url' => route('manage_access'),
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Error updating user:', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error updating user: ' . $e->getMessage()
            ], 500);
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
        try {
            $validator = Validator::make($request->all(), [
                'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($id)],
                'color' => ['nullable', 'string'],
                'password' => ['nullable', 'min:8'],
            ], [
                'username.required' => 'Please choose a username before saving these credentials.',
                'username.string' => 'The username should be written as text.',
                'username.max' => 'The username is too long. Please keep it to 255 characters or fewer.',
                'username.unique' => 'That username is already taken. Please pick a different one.',
                'color.string' => 'The color should be written as text.',
                'password.min' => 'The password must be at least 8 characters long.',
            ]);
    
            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Please review the credential details and try again.',
                    'errors' => $validator->errors(),
                ], 422);
            }

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
    
            return response()->json([
                'success' => true,
                'message' => 'Credentials updated successfully.',
                'redirect_url' => route('manage_access') // Send route URL to manage_access page
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error updating user:', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
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

            // Construct the full name
            // $fullName = trim("{$request->first_name} " . 
            //     ($request->middle_name ? "{$request->middle_name} " : '') . 
            //     "{$request->last_name}" . 
            //     ($request->suffix ? ", {$request->suffix}" : ''));