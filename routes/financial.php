<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Financial')->prefix('financial')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', 'AuthController@login');
    });
    Route::prefix('we-bank')->group(function () {
        Route::post('products', 'WeBankController@index');
        Route::post('detail', 'WeBankController@detail');
    });
    Route::prefix('visa')->group(function () {
        Route::post('products', 'VisaController@index');
        Route::post('subscribe', 'VisaController@subscribe');
    });
});
