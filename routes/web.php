<?php

use Illuminate\Support\Facades\Route;

// For login
Route::get('/', function () {
    return view('login.login'); // Replace with your actual login view
});

// For the main content
Route::get('/calendar', function () {
    return view('main-content.calendar');
});

// Access
Route::get('/access', function () {
    return view('access.manage_access');
});

Route::get('/add_training', function () {
    return view('add_training.add_training');
})->name('add_training');


// Add User
Route::get('/add_user', function () {
    return view('access.add_user');
})->name('add_user');

// Add User
Route::get('/add_user_createacc', function () {
    return view('access.add_user_createacc');
})->name('add_user_createacc');
