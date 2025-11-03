<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Http\Controllers\Home;

use App\Exceptions\RuleValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Order;
use App\Service\OrderProcessService;
use App\Service\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

/**
 * 订单控制器
 *
 * Class OrderController
 *
 * @author: Assimon
 *
 * @email: Ashang@utf8.hk
 *
 * @blog: https://utf8.hk
 * Date: 2021/5/30
 */
class OrderController extends BaseController
{
    public function __construct(
        private OrderService $orderService,
        private OrderProcessService $orderProcessService,
    ) {}

    /**
     * 创建订单
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function createOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->orderService->validatorCreateOrder($request);
            $goods = $this->orderService->validatorGoods($request);
            $this->orderService->validatorLoopCarmis($request);
            // 设置商品
            $this->orderProcessService->setGoods($goods);
            // 优惠码
            $coupon = $this->orderService->validatorCoupon($request);
            // 设置优惠码
            $this->orderProcessService->setCoupon($coupon);
            $otherIpt = $this->orderService->validatorChargeInput($goods, $request);
            $this->orderProcessService->setOtherIpt($otherIpt);
            // 数量
            $this->orderProcessService->setBuyAmount($request->input('by_amount'));
            // 支付方式
            $this->orderProcessService->setPayID($request->input('payway'));
            // 下单邮箱
            $this->orderProcessService->setEmail($request->input('email'));
            // ip地址
            $this->orderProcessService->setBuyIP($request->getClientIp());
            // 查询密码
            $this->orderProcessService->setSearchPwd($request->input('search_pwd', ''));
            // 创建订单
            $order = $this->orderProcessService->createOrder();
            DB::commit();
            // 设置订单cookie
            $this->queueCookie($order->order_sn);

            return redirect(route('bill', ['order' => $order->id]));
        } catch (RuleValidationException $exception) {
            DB::rollBack();

            return $this->err($exception->getMessage());
        }
    }

    /**
     * 设置订单cookie.
     *
     * @param  string  $orderSN  订单号.
     */
    private function queueCookie(string $orderSN): void
    {
        // 设置订单cookie
        $cookies = Cookie::get('dujiaoka_orders');
        if (empty($cookies)) {
            Cookie::queue('dujiaoka_orders', json_encode([$orderSN]));
        } else {
            $cookies = json_decode($cookies, true);
            array_push($cookies, $orderSN);
            Cookie::queue('dujiaoka_orders', json_encode($cookies));
        }
    }

    /**
     * 订单状态监测
     *
     * @param  string  $orderSN  订单号
     * @return \Illuminate\Http\JsonResponse
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function checkOrderStatus(string $orderSN)
    {
        $order = $this->orderService->detailOrderSN($orderSN);
        // 订单不存在或者已经过期
        if (! $order || $order->status == Order::STATUS_EXPIRED) {
            return response()->json(['msg' => 'expired', 'code' => 400001]);
        }
        // 订单已经支付
        if ($order->status == Order::STATUS_WAIT_PAY) {
            return response()->json(['msg' => 'wait....', 'code' => 400000]);
        }
        // 成功
        if ($order->status > Order::STATUS_WAIT_PAY) {
            return response()->json(['msg' => 'success', 'code' => 200]);
        }
    }
}
