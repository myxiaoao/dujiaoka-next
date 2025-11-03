<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
use App\Http\Controllers\Home\OrderController;
use App\Livewire\Pages\Bill;
use App\Livewire\Pages\Buy;
use App\Livewire\Pages\Error;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\OrderInfo;
use App\Livewire\Pages\QrPay;
use App\Livewire\Pages\SearchOrder;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['dujiaoka.boot']], function () {
    // ====== Livewire 页面路由 ======
    // 注意：Livewire 3 直接使用 Route::get() 并将组件类作为目标

    // 首页
    Route::get('/', Home::class)->name('home');

    // 购买页
    Route::get('/buy/{id}', Buy::class)->name('buy');

    // 订单查询
    Route::get('/search-order', SearchOrder::class)->name('search-order');

    // 订单详情
    Route::get('/order/{order}', OrderInfo::class)->name('order-info');

    // 支付页
    Route::get('/bill/{order}', Bill::class)->name('bill');

    // 二维码支付
    Route::get('/qrpay/{order}', QrPay::class)->name('qrpay');

    // 错误页
    Route::get('/error', Error::class)->name('error');

    // ====== 保留的后端 API 路由（非页面） ======

    // 创建订单 (POST API)
    Route::post('create-order', [OrderController::class, 'createOrder'])->name('create-order');

    // 订单状态检查 (AJAX API)
    Route::get('check-order-status/{orderSN}', [OrderController::class, 'checkOrderStatus'])->name('check-order-status');

    // 订单搜索 (POST API)
    Route::post('search-order-by-sn', [OrderController::class, 'searchOrderBySN'])->name('search-order-by-sn');
    Route::post('search-order-by-email', [OrderController::class, 'searchOrderByEmail'])->name('search-order-by-email');
    Route::post('search-order-by-browser', [OrderController::class, 'searchOrderByBrowser'])->name('search-order-by-browser');

    // 通过订单号访问详情（可能需要保留用于外部链接）
    Route::get('detail-order-sn/{orderSN}', [OrderController::class, 'detailOrderSN'])->name('detail-order-sn');

    // 极验证验证（如果启用）
    Route::get('check-geetest', [\App\Http\Controllers\Home\HomeController::class, 'geetest'])->name('check-geetest');
});
