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

include __DIR__ . '/store.php';

Route::get('verify/code', 'VerificationCodeController@get');

Route::prefix('common')->group(function () {
    Route::get('area', 'CommonController@area');
    Route::get('industry', 'CommonController@industry');
    Route::get('config', 'CommonController@config');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('upload', 'UploadController@form');
});

Route::any('wechat/official/{account}', "WechatController@official");
