<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingpageController;     //  LANDING PAGE CONTROLLER
use Illuminate\Support\Facades\Route;

// INITIAL ROUTE FOR HOMEPAGE
Route::get('/', function () {
    return view('login.login'); // Replace with your actual login view
});
// INITIAL ROUTE FOR DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::controller(LandingpageController::class)->group(function () {
    Route::get('admin/landingpage', 'admin');               //  ADMIN LANDINGPAGE ROUTE
    Route::get('coordinator/coordinatorLp', 'coordinator'); //  COORDINATOR LANDINGPAGE ROUTE
    Route::get('trainer/trainerLp', 'trainer');             //  TRAINER LANDINGPAGE ROUTE
});

route::get('admin/landingpage',[LandingpageController::class,'admin'])->middleware(['auth','user:admin']);                   //  ADMIN LANDINGPAGE ROUTE
route::get('coordinator/coordinatorLp',[LandingpageController::class,'coordinator'])->middleware(['auth','user:coordinator']);     //  COORDINATOR LANDINGPAGE ROUTE
route::get('trainer/trainerLp',[LandingpageController::class,'trainer'])->middleware(['auth','user:trainer']);                 //  TRAINER LANDINGPAGE ROUTE


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
