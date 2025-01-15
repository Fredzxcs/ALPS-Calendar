<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingpageController;     //  LANDING PAGE CONTROLLER
use App\Http\Controllers\ManageAccessController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('login.login');
})->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::prefix('calendar')->group(function () {
    Route::get('/', [TrainingController::class, 'index'])
        ->middleware(['auth', 'user:admin,coordinator,facilitator'])
        ->name('calendar');

    Route::get('/add_training', [TrainingController::class, 'create'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('add_training');

    Route::post('/add_training', [TrainingController::class, 'store'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('add_training.store');

    Route::get('/api/get/training', [TrainingController::class, 'gettraining'])
        ->name('get_training');

    Route::get('/edit_training/{id}', [TrainingController::class, 'edit'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('edit_training');

    Route::put('/edit_training/{id}', [TrainingController::class, 'update'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('add_training.put');


});

Route::prefix('access')->group(function (){
    Route::get('/', [ManageAccessController::class, 'index'])
        ->middleware(['auth', 'user:admin'])
        ->name('manage_access');
    Route::get('/add_user', [ManageAccessController::class, 'create'])
        ->middleware(['auth', 'user:admin'])
        ->name('add_user');
    Route::post('/add_user', [RegisteredUserController::class, 'admin_store'])
        ->middleware(['auth', 'user:admin'])
        ->name('add_user.store');
    Route::get('/api/get/user/{id}', [ManageAccessController::class, 'get_user'])
        ->name('get_user');
    Route::get('/edit_user/{id}', [ManageAccessController::class, 'edit'])
        ->middleware(['auth', 'user:admin'])
        ->name('edit_user');
});

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

