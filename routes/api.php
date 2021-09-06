<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusRouteController;
use App\Http\Controllers\Api\BusBookingController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google', [AuthController::class, 'google']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/update_password', [UserController::class, 'changePassword']);

    Route::prefix('buses')->name('buses.')->group(function () {
        Route::prefix('routes')->name('routes.')->group(function () {
            Route::get('/', [BusRouteController::class, 'all']);
        });

        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [BusBookingController::class, 'index']);
            Route::get('/step/one', [BusBookingController::class, 'stepOne']);
            Route::post('/step/two', [BusBookingController::class, 'stepTwo']);
            Route::post('/confirm', [BusBookingController::class, 'confirm']);
        });
    });

    Route::post('logout', [AuthController::class, 'logout']);
});
