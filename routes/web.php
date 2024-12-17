<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('login.login'); // Replace with your actual login view
});


Route::get('/layout', function () {
    return view('layout.layout'); // Loads Layout
});

Route::get('/layout', function () {
    return view('layout.maincontent'); // Adjust based on your folder structure
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

Route::get('/access/manage-access', function () {
    return view('access.manage_access');
});
