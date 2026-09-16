<?php

use App\Http\Controllers\AssetAssignmentController;
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

        Route::get(
            '/system-admin/dashboard',
            [DashboardController::class, 'systemAdmin']
        )->name('system-admin.dashboard');


        /*
        |----------------------------------------------------------------------
        | Users
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
    | Assignments Register
    |--------------------------------------------------------------------------
    |
    | The register is available to:
    |
    | - Hardware Officer
    | - Administration Officer
    | - System Administrator
    |
    | The controller handles the role-based access restrictions.
    |
    */

    Route::get(
        '/assignments',
        [AssetAssignmentController::class, 'index']
    )->name('assignments.index');


    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | AssetController separately enforces:
    |
    | Hardware Officer       → Hardware assets
    | Administration Officer → Administration assets
    | System Administrator   → Read-only oversight
    |
    */

    /*
    | Bulk asset tags MUST come before the resource route.
    */

    Route::get(
        '/assets/tags/bulk',
        [AssetController::class, 'bulkTags']
    )->name('assets.tags.bulk');

    Route::resource('assets', AssetController::class);

    /*
    | Print single asset tag
    */

    Route::get(
        '/assets/{asset}/tag',
        [AssetController::class, 'tag']
    )->name('assets.tag');


    /*
    |--------------------------------------------------------------------------
    | Asset Assignments
    |--------------------------------------------------------------------------
    */

    /*
    | Assign an asset
    */

    Route::get(
        '/assets/{asset}/assign',
        [AssetAssignmentController::class, 'create']
    )->name('assets.assign');

    Route::post(
        '/assets/{asset}/assign',
        [AssetAssignmentController::class, 'store']
    )->name('assets.assign.store');

    /*
    | Return an assigned asset
    */

    Route::post(
        '/assets/{asset}/return',
        [AssetAssignmentController::class, 'returnAsset']
    )->name('assets.return');


    /*
    |--------------------------------------------------------------------------
    | Asset Categories
    |--------------------------------------------------------------------------
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

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';