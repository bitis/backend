<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Store')->prefix('store')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', 'AccountController@login');
        Route::post('register', 'AccountController@register');
        Route::middleware('auth:sanctum')->post('logout', 'AccountController@logout');
        Route::post('reset-password', 'AccountController@resetPassword');
        Route::middleware('auth:sanctum')->post('destroy', 'AccountController@destroy');
        Route::middleware('auth:sanctum')->get('info', 'AccountController@info');
    });

    Route::middleware('auth:sanctum')->group(function (){

        Route::post('upload', 'UploadController@form');

        Route::prefix('dashboard')->group(function () {
            Route::get('consume-data', 'DashboardController@consumeData');
        });

        Route::prefix('member')->group(function () {
            Route::post('list', 'MemberController@index');
            Route::post('form', 'MemberController@form');
            Route::post('detail', 'MemberController@detail');
            Route::post('destroy', 'MemberController@destroy');
            Route::get('transaction', 'MemberController@transaction');

            Route::prefix('level')->group(function () {
                Route::post('list', 'LevelController@index');
                Route::post('form', 'LevelController@form');
                Route::post('destroy', 'LevelController@destroy');
            });

            Route::prefix('card')->group(function () {
                Route::get('list', 'MemberCardController@index');
                Route::post('open', 'MemberCardController@open');
                Route::get('products', 'MemberCardController@products');
            });

            Route::prefix('order')->group(function () {
                Route::get('list', 'MemberOrderController@index');
                Route::get('detail', 'MemberOrderController@detail');
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

            Route::prefix('unit')->group(function () {
                Route::get('list', 'UnitController@index');
                Route::post('form', 'UnitController@form');
                Route::post('destroy', 'UnitController@destroy');
            });

            Route::get('list', 'ProductController@index');
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

        Route::prefix('commission')->group(function () {
            Route::post('form', 'CommissionController@form');
            Route::get('detail', 'CommissionController@detail');
            Route::post('calc', 'CommissionController@calc');
        });

        Route::prefix('consume')->group(function () {
            Route::post('fast', 'ConsumeController@fast');
            Route::post('normal', 'ConsumeController@normal');
        });

        Route::prefix('message')->group(function () {
            Route::get('list', 'MessageController@index');
            Route::post('read', 'MessageController@read');
        });
    });

});
