<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Financial')->prefix('financial')->group(function () {
    Route::prefix('we-bank')->group(function () {
        Route::post('index', 'WeBankController@index');
        Route::post('calendar', 'WeBankController@calendar');
    });
});
