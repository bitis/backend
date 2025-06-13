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

        Route::prefix('account')->group(function () {
            Route::post('mobile', 'AccountController@mobile');
            Route::post('password', 'AccountController@password');
        });

        Route::prefix('dashboard')->group(function () {
            Route::post('consume-data', 'DashboardController@consumeData');
            Route::post('appIndex', 'DashboardController@appIndex');
        });

        Route::prefix('member')->group(function () {
            Route::post('list', 'MemberController@index');
            Route::post('form', 'MemberController@form');
            Route::post('detail', 'MemberController@detail');
            Route::post('avatar', 'MemberController@avatar');
            Route::post('destroy', 'MemberController@destroy');
            Route::post('transaction', 'MemberController@transaction');
            Route::post('qrcode', 'MemberController@qrcode');
            Route::post('filters', 'MemberController@filters');

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
            Route::prefix('note')->group(function () {
                Route::post('list', 'MemberNoteController@index');
                Route::post('detail', 'MemberNoteController@detail');
                Route::post('destroy', 'MemberNoteController@destroy');
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

        Route::prefix('credit')->group(function () {
            Route::post('list', 'CreditController@index');
            Route::post('form', 'CreditController@form');
            Route::post('detail', 'CreditController@detail');
            Route::post('destroy', 'CreditController@destroy');
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
            Route::post('types', 'CommissionController@types');
            Route::post('configured', 'CommissionController@configured');
            Route::post('configurable', 'CommissionController@configurable');
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
            Route::post('services', 'AppointmentController@services');
            Route::post('form', 'AppointmentController@form');
            Route::post('detail', 'AppointmentController@detail');
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

        Route::prefix('sms-batch')->group(function () {
            Route::post('send', 'SmsBatchController@form');
            Route::post('upload', 'SmsBatchController@upload');
            Route::post('history', 'SmsBatchController@history');
            Route::post('detailRecord', 'SmsBatchController@detailRecord');
            Route::post('createSignature', 'SmsBatchController@createSignature');
            Route::post('getSignatures', 'SmsBatchController@getSignatures');
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
            Route::prefix('sms')->group(function () { // 短信管理
                Route::post('detail', 'SmsConfigController@detail');
                Route::post('form', 'SmsConfigController@form');
                Route::post('logs', 'SmsConfigController@logs');
            });

            Route::prefix('stock')->group(function () { // 库存提醒设置
                Route::post('detail', 'SettingController@getStockWarning');
                Route::post('form', 'SettingController@setStockWarning');
            });
        });

        Route::prefix('sms-order')->group(function () {
            Route::post('packages', 'SmsOrderController@packages');
            Route::post('order', 'SmsOrderController@order');
        });

        /**
         * 反馈
         */
        Route::prefix('feedback')->group(function () {
            Route::post('list', 'FeedbackController@index');
            Route::post('form', 'FeedbackController@form');
        });
    });

});
