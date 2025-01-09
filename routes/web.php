<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingpageController;     //  LANDING PAGE CONTROLLER
use App\Http\Controllers\ManageAccessController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;


// For login
Route::get('/', function () {
    return view('login.login'); // Replace with your actual login view
})->name('index');

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

Route::prefix('calendar')->group(function (){
    Route::get('/', [TrainingController::class, 'index'])->middleware(['auth', 'user:admin'])->name('calendar');
    Route::get('/add_training', [TrainingController::class, 'create'])->middleware(['auth', 'user:admin'])->name('add_training');
    Route::post('/add_training', [TrainingController::class, 'store'])->middleware(['auth', 'user:admin'])->name('add_training.store');
    Route::get('/api/get/training', [TrainingController::class, 'gettraining'])->name('get_training');

    //->middleware(['auth', 'user:admin'])
});

Route::prefix('access')->group(function (){
    Route::get('/', [ManageAccessController::class, 'index'])->middleware(['auth', 'user:admin'])->name('manage_access');
    Route::get('/add_user', [ManageAccessController::class, 'create'])->middleware(['auth', 'user:admin'])->name('add_user');
    Route::post('/add_user', [RegisteredUserController::class, 'admin_store'])->middleware(['auth', 'user:admin'])->name('add_user.store');
});

// Add User
Route::get('/add_user_createacc', function () {
    return view('access.add_user_createacc');
})->middleware(['auth', 'user:user:admin'])->name('add_user_createacc');

// Edit User - kim (paayos nalang po)
Route::get('/access/edit_user', function () {
    return view('access.edit_user');})->name('edit_user');

// Archived Accounts
Route::get('/access/archive', function () {
    return view('access.archived_accounts');})->name('archived_accounts');

// config - courses
Route::get('/config/courses', function () {
    return view('configuration.courses');})->name('config_courses');

// config - companies
Route::get('/config/companies', function () {
    return view('configuration.companies');})->name('config_companies');

// config - accounts
Route::get('/config/accounts', function () {
    return view('configuration.accounts');})->name('config_accounts');
