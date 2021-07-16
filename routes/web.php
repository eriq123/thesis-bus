<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['web','auth'])->group(function () {
    Route::view('/','index');
});

// Route::resource('/buses', BusController::class);
// Route::resource('/users', UserController::class);

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
