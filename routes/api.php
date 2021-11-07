<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('/user/register', 'App\Http\Controllers\AuthController@register')->name('register');
    Route::post('/user/login', 'App\Http\Controllers\AuthController@login')->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/user/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
        Route::post('/user/otp', 'App\Http\Controllers\AuthController@otp')->name('otp');
        Route::get('/user', 'App\Http\Controllers\AuthController@user')->name('user');
    });
});
