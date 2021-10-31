<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusBookingController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusRouteController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AjaxController;

use Illuminate\Support\Facades\Route;


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::get('auth/google', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web','auth'])->group(function () {
    Route::get('/', [MainController::class,'index']);

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
         Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
           
        });
        Route::prefix('ajax')->name('ajax.')->group(function () {
            Route::post('/fetch', [AjaxController::class, 'fetchData'])->name('fetching');
            Route::post('/approve', [AjaxController::class, 'approveBooking'])->name('approve.booking');
           
        });

                                    
    });
     Route::prefix('ajax')->name('ajax.')->group(function () {
            Route::post('/location', [AjaxController::class, 'getLocation'])->name('get.location');
           
        });

    Route::prefix('buses')->name('buses.')->group(function () {
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [BusBookingController::class, 'index'])->name('index');
            Route::get('/add', [BusBookingController::class, 'add'])->name('add');
            Route::get('/edit/{id}', [BusBookingController::class, 'edit'])->name('edit');
            Route::post('/update', [BusBookingController::class, 'updateStatus'])->name('update.status');
            Route::get('/busLocation/{id}', [BusBookingController::class, 'busLocation'])->name('bus.location');

            Route::get('/updateBookingSuccess/{id}', [BusBookingController::class, 'updateStatusBookingSuccess'])->name('update.status.booking.success');
            Route::get('/updateBookingFail/{id}', [BusBookingController::class, 'updateStatusBookingFail'])->name('update.status.booking.fail');
            Route::post('/payment', [BusBookingController::class, 'payingBooking'])->name('paying.booking');

            Route::delete('/{id}', [BusBookingController::class, 'destroy'])->name('destroy');

            Route::post('/process', [BusBookingController::class, 'submitProcess'])->name('submit.process');
            Route::post('/scheduleByBookingDetails', [BusBookingController::class, 'scheduleByBookingDetails'])->name('scheduleByBookingDetails');
        });

        Route::prefix('payments')->name('payments.')->group(function () {
            Route::post('/', [BusBookingController::class, 'payment'])->name('payment.view');
            Route::post('/paymentProcess', [BusBookingController::class, 'paymentProcessing'])->name('payment.processing');
            
        });
    });

    Route::middleware('isPassenger')->group(function () {

    });
});
