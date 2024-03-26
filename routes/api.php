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
Route::get('area', 'AreaController@index');
Route::get('verify/code', 'VerificationCodeController@get');

Route::prefix('appVersion')->group(function () {
    Route::get('latest', 'AppVersionController@latest');
    Route::post('form', 'AppVersionController@form')->middleware('auth:sanctum');
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'AccountController@login');
    Route::post('register', 'AccountController@register');
    Route::post('logout', 'AccountController@logout')->middleware('auth:sanctum');
    Route::post('reset-password', 'AccountController@resetPassword');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('upload', 'UploadController@form');

    Route::get('app/index', 'IndexController@index');
    Route::get('app/count', 'IndexController@count');
    Route::get('config', 'ConfigController@index');

    Route::prefix('stats')->group(function () {
        Route::get('caseStats', 'StatsController@caseStats');
        Route::get('byStatus', 'StatsController@byStatus');
        Route::get('cost', 'StatsController@cost');
        Route::get('consumerCost', 'StatsController@consumerCost');
        Route::get('consumerCaseStats', 'StatsController@consumerCaseStats');
    });

    Route::prefix('enum')->group(function () {
        Route::get('goodsType', 'EnumController@goodsType');
        Route::get('companyType', 'EnumController@companyType');
        Route::get('insuranceType', 'EnumController@insuranceType');
        Route::get('menuType', 'EnumController@menuType');
        Route::get('orderStatus', 'EnumController@orderStatus');
        Route::get('orderCloseStatus', 'EnumController@orderCloseStatus');
        Route::get('messageType', 'EnumController@messageType');
        Route::get('wuSunCheckStatus', 'EnumController@wuSunCheckStatus');
        Route::get('orderPlanType', 'EnumController@orderPlanType');
        Route::get('approvalType', 'EnumController@approvalType');
    });

    Route::prefix('menu')->group(function () {
        Route::get('list', 'MenuController@index');
        Route::post('form', 'MenuController@form');
        Route::post('delete', 'MenuController@delete');
    });

    Route::prefix('role')->group(function () {
        Route::get('list', 'RoleController@index');
        Route::get('menus', 'RoleController@menus');
        Route::post('form', 'RoleController@form');
        Route::post('permission', 'RoleController@permission');
        Route::get('getByCompany', 'RoleController@getByCompany');
    });

    Route::prefix('account')->group(function () {
        Route::get('detail', 'AccountController@detail');
        Route::post('form', 'AccountController@form');
        Route::post('destroy', 'AccountController@destroy');
    });

    Route::prefix('insurer')->group(function () {
        Route::get('list', 'InsurerController@index');
    });

    Route::prefix('user')->group(function () {
        Route::get('list', 'UserController@index');
        Route::post('form', 'UserController@form');
        Route::get('getByRoles', 'UserController@getByRoles');
    });

    Route::prefix('goodsType')->group(function () {
        Route::get('list', 'GoodsTypeController@index');
        Route::post('form', 'GoodsTypeController@form');
    });

    Route::prefix('company')->group(function () {
        Route::get('list', 'CompanyController@index');
        Route::get('tree', 'CompanyController@tree');
        Route::post('form', 'CompanyController@form');
        Route::get('branch', 'CompanyController@branch');
        Route::get('items', 'CompanyController@items');
    });

    Route::prefix('provider')->group(function () {
        Route::get('list', 'ProviderController@index');
        Route::post('form', 'ProviderController@form');
        Route::get('items', 'ProviderController@items');
        Route::prefix('quotation')->group(function () {
            Route::get('list', 'ProviderQuotationController@index'); // 服务商报价管理 （保险公司）
            Route::get('detail', 'ProviderQuotationController@detail'); // 服务商报价详情 （保险公司）（核价、开标）
            Route::post('pick', 'ProviderQuotationController@pick'); // 手动开标
        });
    });

    Route::prefix('providerOption')->group(function () {
        Route::get('list', 'ProviderOptionController@index');
        Route::post('form', 'ProviderOptionController@form');
        Route::get('getRepeatRegion', 'ProviderOptionController@getRepeatRegion');
    });

    Route::prefix('customer')->group(function () {
        Route::get('list', 'CustomerController@index');
        Route::post('form', 'CustomerController@form');
    });

    Route::prefix('order')->group(function () {
        Route::get('customer', 'OrderController@customer');
        Route::get('list', 'OrderController@index');
        Route::post('form', 'OrderController@form');
        Route::get('detail', 'OrderController@detail');
        Route::post('dispatchCheckUser', 'OrderController@dispatchCheckUser');
        Route::post('dispatchProvider', 'OrderController@dispatchProvider');
        Route::post('check', 'OrderController@check'); // 物损查勘人员查勘
        Route::post('confirmPlan', 'OrderController@confirmPlan'); // 物损确认方案
        Route::post('confirmCost', 'OrderController@confirmCost'); // 成本核算
        Route::post('close', 'OrderController@close'); // 关闭
        Route::get('logs', 'OrderController@logs'); // 变更日志
        Route::get('export', 'OrderController@export'); // 导出 Excel
        Route::post('setQuota', 'OrderController@setQuota'); // 设置为维修方报价
        Route::post('reQuota', 'OrderController@reQuota'); // 保险公司重新进行报价
        Route::post('applyPayment', 'OrderController@applyPayment'); // 提交付款申请
        Route::get('paymentLog', 'OrderController@paymentLog'); // 付款记录


        Route::prefix('repair')->group(function () {
            Route::post('form', 'OrderRepairController@form');
            Route::get('detail', 'OrderRepairController@detail');
            Route::post('rollback', 'OrderRepairController@rollback');

            Route::prefix('dispatch')->group(function () {
                Route::post('form', 'OrderRepairDispatchController@form');
                Route::get('detail', 'OrderRepairDispatchController@detail');
            });
        });
    });

    Route::prefix('quotation')->group(function () {
        Route::get('list', 'OrderQuotationController@index'); // 报价大厅
        Route::get('getByOrderId', 'OrderQuotationController@getByOrderId'); // 获取当前公司某工单的报价详情 （物损公司）
        Route::post('form', 'OrderQuotationController@form'); // 提交报价（物损公司）
        Route::post('import', 'OrderQuotationController@import'); // 导入报价明细（物损公司）
        Route::post('confirm', 'OrderQuotationController@confirm'); // 核价、定损
        Route::get('export', 'OrderQuotationController@export'); // 导出报价单
    });

    Route::prefix('goodsPrice')->group(function () {
        Route::get('list', 'GoodsPriceController@index');
        Route::post('form', 'GoodsPriceController@form');
        Route::get('cats', 'GoodsPriceController@cats');
        Route::get('catsTree', 'GoodsPriceController@catsTree');
        Route::post('import', 'GoodsPriceController@import');
    });

    Route::prefix('history')->group(function () {
        Route::get('list', 'StoryController@index');
    });

    Route::prefix('bidOption')->group(function () {
        Route::get('list', 'BidOptionController@index');
        Route::post('form', 'BidOptionController@form');
    });

    Route::prefix('message')->group(function () {
        Route::get('list', 'MessageController@index');
        Route::post('accept', 'MessageController@accept');
    });

    Route::prefix('approval')->group(function () {
        Route::prefix('option')->group(function () {
            Route::get('list', 'ApprovalOptionController@index');
            Route::post('form', 'ApprovalOptionController@form')->middleware('throttle:1,0.03');
        });
        Route::get('list', 'ApprovalController@index');
        Route::get('detail', 'ApprovalController@detail');
        Route::post('form', 'ApprovalController@form');
    });

    Route::prefix('repair')->group(function () {
        Route::prefix('quotation')->group(function () {
            Route::get('list', 'RepairQuotationController@index'); // 报价大厅
            Route::get('detail', 'RepairQuotationController@detail'); // 详情
            Route::post('quota', 'RepairQuotationController@quota'); // 报价
        });
    });

    Route::prefix('bankAccount')->group(function () {
        Route::get('list', 'BankAccountController@index');
        Route::post('form', 'BankAccountController@form');
    });

    Route::prefix('financial')->group(function () {
        Route::get('list', 'FinancialController@index');
        Route::get('paymemtLog', 'FinancialController@paymemtLog');
        Route::prefix('invoice')->group(function () {
            Route::get('list', 'InvoiceController@index');
            Route::post('invoice', 'InvoiceController@invoice');
            Route::post('payment', 'InvoiceController@payment');
            Route::post('express', 'InvoiceController@express');
        });
    });

    Route::prefix('paymentAccount')->group(function () {
        Route::get('list', 'PaymentAccountController@index');
    });

    Route::prefix('comment')->group(function () {
        Route::get('list', 'CommentController@index');
        Route::post('form', 'CommentController@form');
        Route::post('delete', 'CommentController@delete');
    });
});

Route::post('upload_', 'UploadController@form');

Route::prefix('hjb')->group(function () {
    Route::post('upload', 'HuJiaBao\ServeController@upload');
    Route::post('receiveInvestigationTask', 'HuJiaBao\ServeController@receiveInvestigationTask');
    Route::post('receiveAppraisalPass', 'HuJiaBao\ServeController@receiveAppraisalPass');
    Route::post('receiveTaskCanceled', 'HuJiaBao\ServeController@receiveTaskCanceled');
    Route::post('receiveAppraisalTask', 'HuJiaBao\ServeController@receiveAppraisalTask');
    Route::post('receiveDocRefused', 'HuJiaBao\ServeController@receiveDocRefused');

    Route::get('investigationTask', 'HuJiaBao\ServeController@investigationTask');
    Route::get('appraisalTask', 'HuJiaBao\ServeController@appraisalTask');

    Route::post('investigation', 'HuJiaBao\ServeController@investigation');
    Route::post('appraisal', 'HuJiaBao\ServeController@appraisal');
});
