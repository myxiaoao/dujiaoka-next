<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
use App\Http\Controllers\Pay\AlipayController;
use App\Http\Controllers\Pay\CoinbaseController;
use App\Http\Controllers\Pay\EpusdtController;
use App\Http\Controllers\Pay\MapayController;
use App\Http\Controllers\Pay\PayjsController;
use App\Http\Controllers\Pay\PaypalPayController;
use App\Http\Controllers\Pay\PaysapiController;
use App\Http\Controllers\Pay\StripeController;
use App\Http\Controllers\Pay\TokenPayController;
use App\Http\Controllers\Pay\VpayController;
use App\Http\Controllers\Pay\WepayController;
use App\Http\Controllers\Pay\YipayController;
use App\Http\Controllers\PayController;
use Illuminate\Support\Facades\Route;

Route::get('pay-gateway/{handle}/{payway}/{orderSN}', [PayController::class, 'redirectGateway']);

// 支付相关
Route::group(['prefix' => 'pay', 'middleware' => ['dujiaoka.pay_gate_way']], function (): void {
    // 支付宝
    Route::get('alipay/{payway}/{orderSN}', [AlipayController::class, 'gateway']);
    Route::post('alipay/notify_url', [AlipayController::class, 'notifyUrl']);
    // 微信
    Route::get('wepay/{payway}/{orderSN}', [WepayController::class, 'gateway']);
    Route::post('wepay/notify_url', [WepayController::class, 'notifyUrl']);
    // 码支付
    Route::get('mapay/{payway}/{orderSN}', [MapayController::class, 'gateway']);
    Route::post('mapay/notify_url', [MapayController::class, 'notifyUrl']);
    // Paysapi
    Route::get('paysapi/{payway}/{orderSN}', [PaysapiController::class, 'gateway']);
    Route::post('paysapi/notify_url', [PaysapiController::class, 'notifyUrl']);
    Route::get('paysapi/return_url', [PaysapiController::class, 'returnUrl'])->name('paysapi-return');
    // payjs
    Route::get('payjs/{payway}/{orderSN}', [PayjsController::class, 'gateway']);
    Route::post('payjs/notify_url', [PayjsController::class, 'notifyUrl']);
    // 易支付
    Route::get('yipay/{payway}/{orderSN}', [YipayController::class, 'gateway']);
    Route::get('yipay/notify_url', [YipayController::class, 'notifyUrl']);
    Route::get('yipay/return_url', [YipayController::class, 'returnUrl'])->name('yipay-return');
    // paypal
    Route::get('paypal/{payway}/{orderSN}', [PaypalPayController::class, 'gateway']);
    Route::get('paypal/return_url', [PaypalPayController::class, 'returnUrl'])->name('paypal-return');
    Route::any('paypal/notify_url', [PaypalPayController::class, 'notifyUrl']);
    // V免签
    Route::get('vpay/{payway}/{orderSN}', [VpayController::class, 'gateway']);
    Route::get('vpay/notify_url', [VpayController::class, 'notifyUrl']);
    Route::get('vpay/return_url', [VpayController::class, 'returnUrl'])->name('vpay-return');
    // stripe
    Route::get('stripe/{payway}/{oid}', [StripeController::class, 'gateway']);
    Route::get('stripe/return_url', [StripeController::class, 'returnUrl']);
    Route::get('stripe/check', [StripeController::class, 'check']);
    Route::get('stripe/charge', [StripeController::class, 'charge']);
    // Coinbase
    Route::get('coinbase/{payway}/{orderSN}', [CoinbaseController::class, 'gateway']);
    Route::post('coinbase/notify_url', [CoinbaseController::class, 'notifyUrl']);
    // epusdt
    Route::get('epusdt/{payway}/{orderSN}', [EpusdtController::class, 'gateway']);
    Route::post('epusdt/notify_url', [EpusdtController::class, 'notifyUrl']);
    Route::get('epusdt/return_url', [EpusdtController::class, 'returnUrl'])->name('epusdt-return');
    // tokenpay
    Route::get('tokenpay/{payway}/{orderSN}', [TokenPayController::class, 'gateway']);
    Route::post('tokenpay/notify_url', [TokenPayController::class, 'notifyUrl']);
    Route::get('tokenpay/return_url', [TokenPayController::class, 'returnUrl'])->name('tokenpay-return');
});
