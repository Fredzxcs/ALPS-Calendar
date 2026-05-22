<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\ManageAccessController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ConfigureCoursesController;
use App\Http\Controllers\ConfigureCompanyController;
use App\Http\Controllers\ConfigureAccountController;
use App\Http\Controllers\UnavailabilityController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\DemoController;
use Illuminate\Support\Facades\Route;



if (filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN)) {
    Route::middleware(['sesh', 'demo_user'])->group(function () {
        Route::get('/', [DemoController::class, 'home'])->name('index');

        Route::prefix('calendar')->group(function () {
            Route::get('/', [DemoController::class, 'calendar'])->name('calendar');
            Route::get('/add_training', [DemoController::class, 'addTrainingForm'])->name('add_training');
            Route::post('/add_training', [DemoController::class, 'storeTraining'])->name('add_training.store');
            Route::get('/api/get/training', [DemoController::class, 'getTraining'])->name('get_training');
            Route::get('/api/get/holidays', [HolidayController::class, 'get_holidays'])->name('get_holidays');
            Route::get('/edit_training/{id}', [DemoController::class, 'editTraining'])->name('edit_training');
            Route::put('/edit_training/{id}', [DemoController::class, 'updateTraining'])->name('add_training.put');
            Route::delete('/delete_training/{id}', [DemoController::class, 'deleteTraining'])->name('delete_training');

            Route::get('/add_unavailability', [DemoController::class, 'addUnavailabilityForm'])->name('add_unavailability');
            Route::post('/add_unavailability/store', [DemoController::class, 'storeUnavailability'])->name('add_unavailability.store');
            Route::get('/api/get/unavailability', [DemoController::class, 'getUnavailabilities'])->name('get_unavailability');
            Route::post('/api/check-unavailability/{id}', [DemoController::class, 'checkUnavailability'])->name('check_unavailability');
            Route::delete('/delete_unavailability/{id}', [DemoController::class, 'deleteUnavailability'])->name('unavailability.destroy');
        });

        Route::get('/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
        Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

        Route::prefix('access')->group(function () {
            Route::get('/', [DemoController::class, 'manageAccess'])->name('manage_access');
            Route::get('/add_user', [DemoController::class, 'addUserForm'])->name('add_user');
            Route::post('/add_user', [DemoController::class, 'storeUser'])->name('add_user.store');
            Route::get('/api/get/user/{id}', [DemoController::class, 'getUser'])->name('get_user');
            Route::get('/avatar/{id}', [DemoController::class, 'avatar'])->name('access.avatar');
            Route::get('/edit_user/{id}', [DemoController::class, 'editUser'])->name('edit_user');
            Route::post('/update_user/{id}', [DemoController::class, 'updateUser']);
            Route::get('/change_credentials/{id}', [DemoController::class, 'changeCredentials'])->name('change_credential');
            Route::post('/update_credentials/{id}', [DemoController::class, 'updateCredentials'])->name('update_credentials');
            Route::delete('/delete_user/{id}', [DemoController::class, 'deleteUser'])->name('delete_user');
        });

        Route::prefix('/config/courses')->group(function () {
            Route::get('/', [DemoController::class, 'showCourses'])->name('config_courses');
            Route::post('/add', [DemoController::class, 'addCourse'])->name('add_course');
            Route::get('/{id}', [DemoController::class, 'showCourseDetails']);
            Route::patch('/update/{id}', [DemoController::class, 'editCourse'])->name('edit_course');
            Route::delete('/delete/{id}', [DemoController::class, 'deleteCourse'])->name('delete_course');
        });

        Route::prefix('/config/companies')->group(function () {
            Route::get('/', [DemoController::class, 'showCompanies'])->name('config_companies');
            Route::post('/add', [DemoController::class, 'addCompany'])->name('add_company');
            Route::get('/{id}', [DemoController::class, 'showCompanyDetails']);
            Route::patch('/update/{id}', [DemoController::class, 'editCompany'])->name('edit_company');
            Route::delete('/delete/{id}', [DemoController::class, 'deleteCompany'])->name('delete_company');
        });

        Route::prefix('/config/accounts')->group(function () {
            Route::get('/', [DemoController::class, 'showAccounts'])->name('config_accounts');
            Route::post('/add', [DemoController::class, 'addAccount'])->name('add_account');
            Route::get('/{id}', [DemoController::class, 'showAccountDetails']);
            Route::patch('/update/{id}', [DemoController::class, 'editAccount'])->name('edit_account');
            Route::delete('/delete/{id}', [DemoController::class, 'deleteAccount'])->name('delete_account');
        });

        Route::post('/logout', function () {
            if (auth()->check()) {
                auth()->logout();
            }
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect('/');
        })->name('logout');

        Route::get('/login', function () {
            return redirect('/');
        })->name('login');

        Route::post('/login', function () {
            return redirect('/calendar');
        })->name('loginPOST');
    });

    return;
}


Route::get('/', function () {
    return view('login.login');
})->middleware(['sesh'])->name('index');

Route::middleware(['auth', 'sesh'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth','sesh'])->group(function () {
    Route::get('/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});


Route::prefix('calendar')->middleware(['sesh'])->group(function () {
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

    Route::delete('/delete_unavailability/{unavailability}', [UnavailabilityController::class, 'destroy'])
        ->middleware(['auth', 'user:admin,coordinator,facilitator'])
        ->name('unavailability.destroy');

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

    Route::get('/api/get/holidays', [HolidayController::class, 'get_holidays'])
    ->name('get_holidays');

});

// Manage Access
Route::prefix('access')->middleware(['sesh'])->group(function (){
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

    Route::get('/avatar/{id}', [ManageAccessController::class, 'avatar'])
        ->middleware(['auth', 'user:admin'])
        ->name('access.avatar');

    Route::get('/edit_user/{id}', [ManageAccessController::class, 'edit_user'])
        ->middleware(['auth', 'user:admin'])
        ->name('edit_user');

    Route::post('/update_user/{id}', [ManageAccessController::class, 'update_user'])
        ->middleware(['auth', 'user:admin']);
    Route::get('/change_credentials/{id}', [ManageAccessController::class, 'change_credentials'])
        ->middleware(['auth', 'user:admin'])
        ->name('update_user');

    Route::get('/change_credentials/{id}', [ManageAccessController::class, 'change_credentials'])
        ->middleware(['auth', 'user:admin'])
        ->name('change_credential');

    Route::post('/update_credentials/{id}', [ManageAccessController::class, 'update_credentials'])
        ->middleware(['auth', 'user:admin'])
        ->name('update_credentials');

    Route::delete('/delete_user/{id}', [ManageAccessController::class, 'delete_user'])
        ->middleware(['auth', 'user:admin'])
        ->name('delete_user');
});

// Archived Accounts
Route::get('/access/archive', function () {
    return view('access.archived_accounts');})->name('archived_accounts');

// config - courses
Route::prefix('/config/courses')->middleware(['sesh'])->group(function(){

    Route::get('/',[ConfigureCoursesController::class, 'showCourse'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('config_courses');

    Route::post('/add', [ConfigureCoursesController::class, 'addCourse'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('add_course');

    Route::get('/{id}', [ConfigureCoursesController::class, 'showCourseDetails'])
        ->middleware(['auth', 'user:admin,coordinator']);

    Route::patch('/update/{id}',[ConfigureCoursesController::Class, 'editCourse'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('edit_course');

    Route::delete('/delete/{id}',[ConfigureCoursesController::Class, 'deleteCourse'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('delete_course');

});

// config - accounts
Route::prefix('/config/accounts')->middleware(['sesh'])->group(function () {
    Route::get('/',[ConfigureAccountController::class, 'showAccount'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('config_accounts');

    Route::post('/add', [ConfigureAccountController::class, 'addAccount'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('add_account');

    Route::get('/{id}', [ConfigureAccountController::class, 'showAccountDetails'])
        ->middleware(['auth', 'user:admin,coordinator']);

    Route::patch('/update/{id}',[ConfigureAccountController::Class, 'editAccount'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('edit_account');

    Route::delete('/delete/{id}',[ConfigureAccountController::Class, 'deleteAccount'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('delete_account');
});

// config - companies
Route::prefix('/config/companies')->middleware(['sesh'])->group(function () {
    Route::get('/',[ConfigureCompanyController::Class, 'showCompany'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('config_companies');

    Route::post('/add',[ConfigureCompanyController::Class, 'addCompany'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('add_company');

    Route::get('/{id}', [ConfigureCompanyController::class, 'showCompanyDetails'])
        ->middleware(['auth', 'user:admin,coordinator']);

    Route::patch('/update/{id}',[ConfigureCompanyController::Class, 'editCompany'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('edit_company');

    Route::delete('/delete/{id}',[ConfigureCompanyController::Class, 'deleteCompany'])
        ->middleware(['auth', 'user:admin,coordinator'])
        ->name('delete_company');
});

// manage access - change credentials
Route::get('/access/change_credentials', function () {
    return view('access.change_credentials');})->middleware(['sesh'])->name('change_credentials');

