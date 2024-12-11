<?php

use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('login.login'); // Loads Login
});

Route::get('/layout', function () {
    return view('layout.layout'); // Loads Layout
});

Route::get('/layout', function () {
    return view('layout.maincontent'); // Adjust based on your folder structure
});
