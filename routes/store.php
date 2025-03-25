<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Store')->prefix('store')->group(function () {

    Route::prefix('price')->group(function () {
        Route::any('alipayNotify', 'PriceController@alipayNotify')->name('order.price.alipay_notify');
        Route::any('wechatNotify', 'PriceController@wechatNotify')->name('order.price.wechat_notify');
    });

    Route::prefix('auth')->group(function () {
        Route::post('login', 'AccountController@login');
        Route::post('register', 'AccountController@register');
        Route::middleware('auth:sanctum')->post('logout', 'AccountController@logout');
        Route::post('reset-password', 'AccountController@resetPassword');
        Route::middleware('auth:sanctum')->post('destroy', 'AccountController@destroy');
        Route::middleware('auth:sanctum')->post('info', 'AccountController@info');
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('upload', 'UploadController@form');

        Route::post('detail', 'StoreController@detail');
        Route::post('form', 'StoreController@form');

        Route::prefix('dashboard')->group(function () {
            Route::post('consume-data', 'DashboardController@consumeData');
        });

        Route::prefix('member')->group(function () {
            Route::post('list', 'MemberController@index');
            Route::post('form', 'MemberController@form');
            Route::post('detail', 'MemberController@detail');
            Route::post('avatar', 'MemberController@avatar');
            Route::post('destroy', 'MemberController@destroy');
            Route::post('transaction', 'MemberController@transaction');
            Route::post('qrcode', 'MemberController@qrcode');

            Route::prefix('grade')->group(function () {
                Route::post('list', 'GradeController@index');
                Route::post('form', 'GradeController@form');
                Route::post('detail', 'GradeController@detail');
                Route::post('destroy', 'GradeController@destroy');
            });

            Route::prefix('card')->group(function () {
                Route::post('list', 'MemberCardController@index');
                Route::post('detail', 'MemberCardController@detail');
                Route::post('products', 'MemberCardController@products');
            });
        });

        /**
         * 交易
         */
        Route::prefix('deal')->group(function () {
            Route::post('applyCard', 'DealController@applyCard'); // 开卡
            Route::post('normal', 'DealController@normal'); // 普通消费
        });

        Route::prefix('product')->group(function () {
            Route::prefix('spec')->group(function () {
                Route::post('list', 'SpecController@index');
                Route::post('form', 'SpecController@form');
                Route::post('destroy', 'SpecController@destroy');
            });

            Route::prefix('category')->group(function () {
                Route::post('list', 'CategoryController@index');
                Route::post('form', 'CategoryController@form');
                Route::post('destroy', 'CategoryController@destroy');
            });

            Route::prefix('unit')->group(function () {
                Route::post('list', 'UnitController@index');
                Route::post('form', 'UnitController@form');
                Route::post('destroy', 'UnitController@destroy');
            });

            Route::post('list', 'ProductController@index');
            Route::post('detail', 'ProductController@detail');
            Route::post('form', 'ProductController@form');
            Route::post('destroy', 'ProductController@destroy');
        });

        Route::prefix('card')->group(function () {
            Route::post('list', 'CardController@index');
            Route::post('form', 'CardController@form');
            Route::post('detail', 'CardController@detail');
            Route::post('destroy', 'CardController@destroy');
        });

        Route::prefix('staff')->group(function () {
            Route::post('list', 'StaffController@index');
            Route::post('form', 'StaffController@form');
            Route::post('detail', 'StaffController@detail');
            Route::post('permission', 'StaffController@permission');
            Route::post('setStatus', 'StaffController@setStatus');
            Route::get('qrcode', 'StaffController@qrcode');
        });

        Route::prefix('job')->group(function () {
            Route::post('list', 'JobController@index');
            Route::post('form', 'JobController@form');
            Route::post('detail', 'JobController@detail');
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

        Route::prefix('order')->group(function () {
            Route::post('list', 'OrderController@index');
            Route::post('detail', 'OrderController@detail');
        });

        Route::prefix('appointment')->group(function () {
            Route::post('list', 'AppointmentController@index');
            Route::post('form', 'AppointmentController@form');
            Route::post('setStatus', 'AppointmentController@setStatus');
            Route::prefix('config')->group(function () { // 预约设置
                Route::post('detail', 'AppointmentController@config');
                Route::post('form', 'AppointmentController@configForm');
            });
        });

        Route::prefix('message')->group(function () {
            Route::get('list', 'MessageController@index');
            Route::post('read', 'MessageController@read');
        });

        Route::prefix('sms')->group(function () {
            Route::post('send', 'SmsController@form');
            Route::post('upload', 'SmsController@upload');
            Route::post('history', 'SmsController@history');
            Route::post('detailRecord', 'SmsController@detailRecord');
            Route::post('createSignature', 'SmsController@createSignature');
            Route::post('getSignatures', 'SmsController@getSignatures');
        });

        /**
         * 洗衣行业
         */
        Route::prefix('clothes')->group(function () {
            Route::prefix('param')->group(function () {
                Route::post('list', 'ClothesParamController@index');
                Route::post('form', 'ClothesParamController@form');
                Route::post('destroy', 'ClothesParamController@destroy');
            });
        });

        Route::prefix('setting')->group(function () {
            Route::prefix('printer')->group(function () {
                Route::post('list', 'PrinterController@index');
                Route::post('form', 'PrinterController@form');
                Route::post('destroy', 'PrinterController@destroy');
            });
        });
    });

});
