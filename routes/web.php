<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['web','auth'])->group(function () {
    Route::view('/','index');
});

Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
Route::post('/buses/store', [BusController::class, 'store'])->name('buses.store');
Route::post('/buses/update', [BusController::class, 'update'])->name('buses.update');
Route::post('/buses/destroy', [BusController::class, 'destroy'])->name('buses.destroy');

// Route::resource('/buses', BusController::class);
// Route::resource('/users', UserController::class);

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
