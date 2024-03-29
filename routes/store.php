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

    Route::prefix('member')->group(function () {
        Route::get('list', 'MemberController@index');
        Route::post('form', 'MemberController@form');
        Route::post('destroy', 'MemberController@destroy');

        Route::prefix('level')->group(function () {
            Route::get('list', 'LevelController@index');
            Route::post('form', 'LevelController@form');
            Route::post('destroy', 'LevelController@destroy');
        });
    })->middleware('auth:sanctum');

    Route::prefix('product')->group(function () {
        Route::prefix('spec')->group(function () {
            Route::get('list', 'SpecController@index');
            Route::post('form', 'SpecController@form');
        });

        Route::prefix('category')->group(function () {
            Route::get('list', 'CategoryController@index');
            Route::post('form', 'CategoryController@form');
            Route::post('destroy', 'CategoryController@destroy');
        });
    })->middleware('auth:sanctum');
});
