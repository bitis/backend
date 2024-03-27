<?php
use Illuminate\Support\Facades\Route;

Route::namespace('Store')->prefix('store')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', 'AccountController@login');
        Route::post('register', 'AccountController@register');
        Route::post('logout', 'AccountController@logout')->middleware('auth:sanctum');
        Route::post('reset-password', 'AccountController@resetPassword');
        Route::post('destroy', 'AccountController@destroy')->middleware('auth:sanctum');
    });
});
