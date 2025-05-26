<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn() => redirect('admin'));
Route::get('qrcode', 'QrCodeController@gen');

Route::prefix('agreement')->group(function () {
    Route::get('user', fn() => view('agreement.user'));
    Route::get('privacy', fn() => view('agreement.privacy'));
    Route::get('permission', fn() => view('agreement.permission'));
    Route::get('information_sharing', fn() => view('agreement.information_sharing'));
});
