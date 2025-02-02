<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingpageController;     //  LANDING PAGE CONTROLLER
use App\Http\Controllers\ManageAccessController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\ConfigureCoursesController;
use App\Http\Controllers\ConfigureCompanyController;
use App\Http\Controllers\ConfigureAccountController;
use App\Http\Controllers\UnavailabilityController;
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

    Route::delete('/delete_training/{id}', [TrainingController::class, 'destroy'])
    ->middleware(['auth', 'user:admin,coordinator'])
    ->name('delete_training');

    Route::get('/add_unavailability', [UnavailabilityController::class, 'create'])
    ->middleware(['auth', 'user:admin,coordinator,facilitator'])
    ->name('add_unavailability');

    Route::post('/add_unavailability/store', [UnavailabilityController::class, 'store'])
    ->middleware(['auth', 'user:admin,coordinator,facilitator'])
    ->name('add_unavailability.store');

    Route::get('/api/get/unavailability', [UnavailabilityController::class, 'getUnavailabilities'])
    ->name('get_unavailability');

    Route::post('/api/check-unavailability/{id}', [UnavailabilityController::class, 'checkUnavailability'])
    ->name('check_unavailability');
});

// Manage Access
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
    Route::get('/edit_user/{id}', [ManageAccessController::class, 'edit_user'])
        ->middleware(['auth', 'user:admin'])
        ->name('edit_user');
    Route::post('/update_user/{id}', [ManageAccessController::class, 'update_user'])
        ->middleware(['auth', 'user:admin'])
        ->name('update_user');
    Route::delete('/delete_user/{id}', [ManageAccessController::class, 'delete_user'])
        ->middleware(['auth', 'user:admin'])
        ->name('delete_user');
});

// Archived Accounts
Route::get('/access/archive', function () {
    return view('access.archived_accounts');})->name('archived_accounts');

// config - courses
Route::prefix('/config/courses')->group(function(){
    // view courses
    Route::get('/',[ConfigureCoursesController::class, 'showCourse'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('config_courses');
    // add courses
    Route::post('/add', [ConfigureCoursesController::class, 'addCourse'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('add_course');
    // show course entry
    Route::get('/{id}', [ConfigureCoursesController::class, 'showCourseDetails'])
        ->middleware(['auth', 'user:admin, coordinator']);
    // update course
    Route::patch('/update/{id}',[ConfigureCoursesController::Class, 'editCourse'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('edit_course');
    // delete course
    Route::delete('/delete/{id}',[ConfigureCoursesController::Class, 'deleteCourse'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('delete_course');

});

// config - accounts
Route::prefix('/config/accounts')->group(function () {
    Route::get('/',[ConfigureAccountController::class, 'showAccount'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('config_accounts');
    Route::post('/add', [ConfigureAccountController::class, 'addAccount'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('add_account');
    Route::get('/{id}', [ConfigureAccountController::class, 'showAccountDetails'])
        ->middleware(['auth', 'user:admin, coordinator']);
    Route::patch('/update/{id}',[ConfigureAccountController::Class, 'editAccount'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('edit_account');
    Route::delete('/delete/{id}',[ConfigureAccountController::Class, 'deleteAccount'])
        ->middleware(['auth', 'user:admin, coordinator'])
        ->name('delete_account');
});

// config - companies
Route::prefix('/config/companies')->group(function () {
    Route::get('/',[ConfigureCompanyController::Class, 'showCompany'])->middleware(['auth', 'user:admin, coordinator'])->name('config_companies');
    Route::post('/add',[ConfigureCompanyController::Class, 'addCompany'])->middleware(['auth', 'user:admin, coordinator'])->name('add_company');
    Route::get('/{id}', [ConfigureCompanyController::class, 'showCompanyDetails'])->middleware(['auth', 'user:admin, coordinator']);
    Route::patch('/update/{id}',[ConfigureCompanyController::Class, 'editCompany'])->middleware(['auth', 'user:admin, coordinator'])->name('edit_company');
    Route::delete('/delete/{id}',[ConfigureCompanyController::Class, 'deleteCompany'])->middleware(['auth', 'user:admin, coordinator'])->name('delete_company');
});

// manage access - change credentials
Route::get('/access/change_credentials', function () {
    return view('access.change_credentials');})->name('change_credentials');

