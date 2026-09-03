<?php

use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated Application Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Main Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Hardware Officer Area
    |--------------------------------------------------------------------------
    */

    Route::middleware('management.area:hardware')->group(function () {

        Route::get('/hardware/dashboard', [DashboardController::class, 'hardware'])
            ->name('hardware.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | Administration Officer Area
    |--------------------------------------------------------------------------
    */

    Route::middleware('management.area:administration')->group(function () {

        Route::get('/administration/dashboard', [DashboardController::class, 'administration'])
            ->name('administration.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | System Administrator Area
    |--------------------------------------------------------------------------
    |
    | System Administrator is responsible for:
    |
    | - Staff management
    | - User management
    | - Department management
    | - System monitoring
    | - System oversight
    |
    | System Administrator does NOT perform operational asset management.
    |
    */

    Route::middleware('management.area:system_admin')->group(function () {

        /*
        |----------------------------------------------------------------------
        | System Administrator Dashboard
        |----------------------------------------------------------------------
        */

        Route::get('/system-admin/dashboard', [DashboardController::class, 'systemAdmin'])
            ->name('system-admin.dashboard');


        /*
        |----------------------------------------------------------------------
        | Staff & Users
        |----------------------------------------------------------------------
        */

        Route::resource('users', UserManagementController::class)
            ->only([
                'index',
                'create',
                'store',
                'edit',
                'update',
            ]);


        /*
        |----------------------------------------------------------------------
        | Departments
        |----------------------------------------------------------------------
        */

        Route::resource('departments', DepartmentController::class);


        /*
        |----------------------------------------------------------------------
        | Staff / Employees
        |----------------------------------------------------------------------
        */

        Route::resource('employees', EmployeeController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | These routes will remain available to operational officers.
    |
    | IMPORTANT:
    | AssetController must separately enforce:
    |
    | Hardware Officer        → Hardware assets
    | Administration Officer  → Administration assets
    | System Administrator    → READ-ONLY oversight
    |
    */
    Route::get('/assets/tags/bulk', [AssetController::class, 'bulkTags'])
        ->name('assets.tags.bulk');
    Route::resource('assets', AssetController::class);
    Route::get('/assets/{asset}/tag', [AssetController::class, 'tag'])
    ->name('assets.tag');

    /*
    |--------------------------------------------------------------------------
    | Asset Categories
    |--------------------------------------------------------------------------
    |
    | We will later restrict category management to System Administrator.
    |
    */

    Route::resource(
        'asset-categories',
        AssetCategoryController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';