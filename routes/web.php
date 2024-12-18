<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('login.login'); // Replace with your actual login view
});


Route::get('/calendar', function () {
    return view('home.calendar'); // Loads Layout
});

Route::get('/calendar', function () {
    return view('home.calendar'); // Adjust based on your folder structure
});

// Access
Route::get('/access/manage-access', function () {
    return view('access.manage_access');
});

Route::get('/access/add-user/role', function () {
    return view('access.add_user_role');
});

Route::get('/access/add-user/information', function () {
    return view('access.add_user_information');
});

Route::get('/access/add-user/createacc', function () {
    return view('access.add_user_createacc');
});

// Add Training - Virtual
Route::get('/add_training', function () {
    return view('add_training.add_training');
})->name('add_training');


// Add Training - Face to Face
Route::get('/add_training/add_training_face_to_face', function () {
    return view('add_training.add_training_face_to_face');
});

// Add Training - In Person
Route::get('/add_training/add_training_in_person', function () {
    return view('add_training.add_training_in_person');
});

// Add Training - Public Course
Route::get('/add_training/add_training_public_course', function () {
    return view('add_training.add_training_public_course');
});

