<?php
/**
 * The file was created by Assimon.
 *
 * @author    assimon<ashang@utf8.hk>
 * @copyright assimon<ashang@utf8.hk>
 * @link      http://utf8.hk/
 */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\OrderController;

Route::group(['middleware' => ['dujiaoka.boot']], function () {
    // 首页
    Route::get('/', [HomeController::class, 'index']);
    // 极验效验
    Route::get('check-geetest', [HomeController::class, 'geetest']);
    // 商品详情
    Route::get('buy/{id}', [HomeController::class, 'buy']);
    // 提交订单
    Route::post('create-order', [OrderController::class, 'createOrder']);
    // 结算页
    Route::get('bill/{orderSN}', [OrderController::class, 'bill']);
    // 通过订单号详情页
    Route::get('detail-order-sn/{orderSN}', [OrderController::class, 'detailOrderSN']);
    // 订单查询页
    Route::get('order-search', [OrderController::class, 'orderSearch']);
    // 检查订单状态
    Route::get('check-order-status/{orderSN}', [OrderController::class, 'checkOrderStatus']);
    // 通过订单号查询
    Route::post('search-order-by-sn', [OrderController::class, 'searchOrderBySN']);
    // 通过邮箱查询
    Route::post('search-order-by-email', [OrderController::class, 'searchOrderByEmail']);
    // 通过浏览器查询
    Route::post('search-order-by-browser', [OrderController::class, 'searchOrderByBrowser']);
});

Route::group(['middleware' => ['install.check']], function () {
    // 安装
    Route::get('install', [HomeController::class, 'install']);
    // 执行安装
    Route::post('do-install', [HomeController::class, 'doInstall']);
});

