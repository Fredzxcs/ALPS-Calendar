<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
// {

//     public function create(): View
//     {
//         return view('alpsAdmin.accountregister');
//     }

//     /**
//      * Handle an incoming registration request.
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     public function store(Request $request): RedirectResponse
//     {

//         $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
//             'username' => ['required'],
//             'usertype' => ['required', 'string'],
//             'password' => ['required', 'confirmed', Rules\Password::defaults()],
//         ]);

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'username' => $request->username,
//             'usertype' => $request->usertype,
//             'password' => Hash::make($request->password),
//         ]);

//         event(new Registered($user));

//         return redirect()->route('index');
//     }

//     public function admin_store(Request $request)
//     {
//         // Validate the request
//         $validator = Validator::make($request->all(), [
//             'name' => ['required', 'string', 'max:255'],
//             'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
//             'username' => ['required', 'string', 'max:255'],
//             'usertype' => ['required', 'string'],
//             'color' => ['required', 'string'],
//             'contact_number' => ['required', 'string', 'max:15'], // Validation for contact_number
//             'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Validation for image
//             'password' => ['required'],
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'message' => 'Validation failed',
//                 'errors' => $validator->errors(),
//             ], 422);
//         }

//         // Handle image upload
//         $imagePath = null;
//         if ($request->hasFile('image')) {
//             $imagePath = $request->file('image')->store('images', 'public'); // Store in 'storage/app/public/images'
//         }

//         // Create the new user
//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'username' => $request->username,
//             'usertype' => $request->usertype,
//             'color' => $request->color,
//             'contact_number' => $request->contact_number,
//             'image' => $imagePath, // Save image path
//             'password' => Hash::make($request->password),
//         ]);

//         // Return a JSON response
//         return response()->json(['message' => '200'], 200);
//     }
// }
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required'],
            'usertype' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'usertype' => $request->usertype,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('index');
    }

    public function admin_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'usertype' => ['required', 'string'],
            'color' => ['required', 'string'],
            'contact_number' => ['required', 'string', 'max:15'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'min:8'],
        ], [
            'name.required' => 'Please enter the person\'s full name before saving the user.',
            'name.string' => 'The name needs to be plain text, not numbers or symbols.',
            'name.max' => 'The name is too long. Please keep it to 255 characters or fewer.',
            'email.required' => 'Please enter an email address so the user can be contacted.',
            'email.string' => 'The email address should be written as text.',
            'email.lowercase' => 'Please use a lowercase email address so sign-in works correctly.',
            'email.email' => 'Please enter a valid email address such as name@example.com.',
            'email.max' => 'The email address is too long. Please use 255 characters or fewer.',
            'email.unique' => 'That email address is already in use. Please choose another one.',
            'username.required' => 'Please choose a username before continuing.',
            'username.string' => 'The username should be plain text.',
            'username.max' => 'The username is too long. Please keep it to 255 characters or fewer.',
            'username.unique' => 'That username is already taken. Please pick a different one.',
            'usertype.required' => 'Please choose a user type before saving.',
            'usertype.string' => 'The user type should be written as text.',
            'color.required' => 'Please choose a color for this user first.',
            'color.string' => 'The color value should be text.',
            'contact_number.required' => 'Please enter a contact number for the user.',
            'contact_number.string' => 'The contact number should be entered as text.',
            'contact_number.max' => 'The contact number is too long. Please keep it to 15 characters or fewer.',
            'image.image' => 'Please upload a valid image file for the user photo.',
            'image.mimes' => 'The photo must be a JPG or PNG image.',
            'image.max' => 'The photo is too large. Please upload an image smaller than 2 MB.',
            'password.required' => 'Please enter a password for the new user.',
            'password.min' => 'The password should be at least 8 characters long for safety.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the highlighted fields and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Handle image upload
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        
        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'usertype' => $request->usertype,
            'color' => $request->color,
            'contact_number' => $request->contact_number,
            'image' => $imagePath,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created successfully.', 'user' => $user], 201);
    }
}
