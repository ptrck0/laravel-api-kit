<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/**
 * Unauthenticated routes
 */

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'environment' => config('app.env'),
    ]);
});

Route::name('auth.')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});

/**
 * Authenticated routes
 */

Route::middleware('auth:sanctum')->group(function () {
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::apiResources([
            'users' => UserController::class,
            'permissions' => PermissionController::class,
            'roles' => RoleController::class,
        ]);
    });
});
