<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Store')->prefix('store')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', 'AccountController@login');
        Route::post('register', 'AccountController@register');
        Route::middleware('auth:sanctum')->post('logout', 'AccountController@logout');
        Route::post('reset-password', 'AccountController@resetPassword');
        Route::middleware('auth:sanctum')->post('destroy', 'AccountController@destroy');
    });

    Route::middleware('auth:sanctum')->group(function (){

        Route::prefix('member')->group(function () {
            Route::get('list', 'MemberController@index');
            Route::post('form', 'MemberController@form');
            Route::post('destroy', 'MemberController@destroy');

            Route::prefix('level')->group(function () {
                Route::get('list', 'LevelController@index');
                Route::post('form', 'LevelController@form');
                Route::post('destroy', 'LevelController@destroy');
            });
        });

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

            Route::get('list', 'ProductController@index');
            Route::get('units', 'ProductController@units');
            Route::post('form', 'ProductController@form');
            Route::post('destroy', 'ProductController@destroy');
        });

        Route::prefix('card')->group(function () {
            Route::get('list', 'CardController@index');
            Route::post('form', 'CardController@form');
            Route::get('detail', 'CardController@detail');
            Route::post('destroy', 'CardController@destroy');
        });

        Route::prefix('staff')->group(function () {
            Route::get('list', 'StaffController@index');
            Route::post('form', 'StaffController@form');
            Route::get('detail', 'StaffController@detail');
            Route::post('permission', 'StaffController@permission');
            Route::post('destroy', 'StaffController@destroy');
        });

        Route::prefix('job')->group(function () {
            Route::get('list', 'JobController@index');
            Route::post('form', 'JobController@form');
            Route::post('destroy', 'JobController@destroy');
        });

        Route::prefix('menu')->group(function () {
            Route::get('list', 'MenuController@index');
            Route::post('form', 'MenuController@form');
            Route::post('destroy', 'MenuController@destroy');
        });
    });

});
