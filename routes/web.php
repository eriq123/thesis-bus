<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusBookingController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusRouteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserBookingController;
use Illuminate\Support\Facades\Route;


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::get('auth/google', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web','auth'])->group(function () {
    Route::view('/','index');

    Route::middleware('isAdmin')->group(function () {
        Route::prefix('buses')->name('buses.')->group(function () {
            Route::get('/', [BusController::class, 'index'])->name('index');
            Route::post('/store', [BusController::class, 'store'])->name('store');
            Route::post('/update', [BusController::class, 'update'])->name('update');
            Route::delete('/{id}', [BusController::class, 'destroy'])->name('destroy');

            Route::prefix('routes')->name('routes.')->group(function () {
                Route::get('/', [BusRouteController::class, 'index'])->name('index');
                Route::post('/store', [BusRouteController::class, 'store'])->name('store');
                Route::post('/update', [BusRouteController::class, 'update'])->name('update');
                Route::delete('/{id}', [BusRouteController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('schedules')->name('schedules.')->group(function () {
                Route::get('/', [ScheduleController::class, 'index'])->name('index');
                Route::post('/store', [ScheduleController::class, 'store'])->name('store');
                Route::post('/update', [ScheduleController::class, 'update'])->name('update');
                Route::delete('/{id}', [ScheduleController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('bookings')->name('bookings.')->group(function () {
                Route::get('/', [BusBookingController::class, 'index'])->name('index');
                Route::delete('/{id}', [BusBookingController::class, 'destroy'])->name('destroy');

                Route::get('/process', [BusBookingController::class, 'process'])->name('process');
                Route::post('/process', [BusBookingController::class, 'submitProcess'])->name('submit.process');
                Route::post('/findScheduleByRouteIDs', [BusBookingController::class, 'findScheduleByRouteIDs'])->name('findScheduleByRouteIDs');
            });


        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/store', [RoleController::class, 'store'])->name('store');
            Route::post('/update', [RoleController::class, 'update'])->name('update');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::post('/update', [UserController::class, 'update'])->name('update');
            Route::post('/changePassword', [UserController::class, 'changePassword'])->name('changePassword');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/searchByName', [UserController::class, 'searchByName'])->name('searchByName');
        });

    });

    Route::prefix('mybookings')->name('mybookings.')->group(function () {
        Route::get('/', [UserBookingController::class, 'index'])->name('index');
        Route::post('/store', [UserBookingController::class, 'store'])->name('store');
        Route::post('/update', [UserBookingController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserBookingController::class, 'destroy'])->name('destroy');
        Route::post('/scheduleByRouteID', [UserBookingController::class, 'scheduleByRouteID'])->name('scheduleByRouteID');
    });

});
