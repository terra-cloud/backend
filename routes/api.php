<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('auth.register');
    Route::post('/login', 'login')->name('auth.login');
    
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'logout')->name('auth.logout');
        Route::get('/user', 'user')->name('auth.user');
        Route::get('/check-user', 'checkUser')->name('auth.checkUser');
    });
});