<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['locale'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
        });

        Route::controller(ResetPasswordController::class)->group(function () {
            Route::post('forgetPassword', 'forgetPassword')->name('forget.password');
            Route::post('resetPassword/{userId}', 'resetPassword')->name('reset.password');
        });

        Route::controller(VerificationController::class)->group(function () {
            Route::post('verifyAuthCode/{userId}', 'verifyAuthCode')->name('verify.auth.code');
            Route::post('verifyPasswordCode/{userId}', 'verifyPasswordCode')->name('verify.password.code');
            Route::get('refreshCode/{userId}', 'refreshCode')->name('refresh.code');
        });

        Route::middleware(['auth:sanctum'])->controller(AuthController::class)->group(function () {
            Route::get('logout', 'logout')->name('logout');
        });
    });
});


