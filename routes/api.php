<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('verify/code', 'VerificationCodeController@get');

include __DIR__ . '/store.php';

Route::prefix('common')->group(function () {
    Route::get('area', 'CommonController@area');
    Route::get('industry', 'CommonController@industry');
    Route::get('config', 'CommonController@config');
});


Route::prefix('appVersion')->group(function () {
    Route::get('latest', 'AppVersionController@latest');
    Route::post('form', 'AppVersionController@form')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('upload', 'UploadController@form');

    Route::get('app/index', 'IndexController@index');
    Route::get('app/count', 'IndexController@count');
    Route::get('config', 'ConfigController@index');

    Route::prefix('menu')->group(function () {
        Route::get('list', 'MenuController@index');
        Route::post('form', 'MenuController@form');
        Route::post('delete', 'MenuController@delete');
    });

    Route::prefix('account')->group(function () {
        Route::get('detail', 'AccountController@detail');
        Route::post('form', 'AccountController@form');
        Route::post('destroy', 'AccountController@destroy');
    });

    Route::prefix('user')->group(function () {
        Route::get('list', 'UserController@index');
        Route::post('form', 'UserController@form');
        Route::get('getByRoles', 'UserController@getByRoles');
    });
});

Route::post('upload_', 'UploadController@form');

