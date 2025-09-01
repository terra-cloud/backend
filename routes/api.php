<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationController;
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

Route::prefix('location')->controller(LocationController::class)->group(function () {
    Route::get('/get-states', 'getMapStates')->name('location.get_states');
    Route::get('/state/{id}/cities', 'getStateCities')->name('location.get_state_cities');
    Route::get('/city/{id}/barangays', 'getCityBarangays')->name('location.get_city_barangays');
});