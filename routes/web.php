<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusRouteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['web','auth'])->group(function () {
    Route::view('/','index');

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
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::post('/update', [RoleController::class, 'update'])->name('update');
    });
});
