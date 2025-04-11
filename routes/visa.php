<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Visa')->prefix('visa')->group(function () {
    Route::post('products', 'VisaController@index');
    Route::post('subscribe', 'VisaController@subscribe');
});
