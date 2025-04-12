<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Financial')->prefix('financial')->group(function () {
    Route::any('serve', 'ServeController@serve');
    Route::prefix('auth')->group(function () {
        Route::post('login', 'AuthController@login');
    });
    Route::prefix('account')->group(function () {
        Route::post('info', 'AccountController@index');
    });
    Route::prefix('we-bank')->group(function () {
        Route::post('products', 'WeBankController@index');
        Route::post('detail', 'WeBankController@detail');
    });
    Route::prefix('visa')->group(function () {
        Route::post('products', 'VisaController@index');
        Route::post('detail', 'VisaController@detail');
        Route::post('subscribe', 'VisaController@subscribe');
    });
});
