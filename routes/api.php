<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('admin.auth.register');
    Route::post('/login', 'login')->name('admin.auth.login');
    
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'logout')->name('admin.auth.logout');
        Route::get('/user', 'user')->name('admin.auth.user');
    });
});