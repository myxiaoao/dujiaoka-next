<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Http\Controllers;

use App\Exceptions\RuleValidationException;
use App\Models\Order;
use App\Service\OrderProcessService;
use App\Service\OrderService;
use App\Service\PayService;

class PayController extends BaseController
{
    /**
     * 支付网关
     *
     * @var \App\Models\Pay
     */
    protected $payGateway;

    /**
     * 订单
     *
     * @var \App\Models\Order
     */
    protected $order;

    public function __construct(
        protected OrderService $orderService,
        protected PayService $payService,
        protected OrderProcessService $orderProcessService,
    ) {}

    /**
     * 订单检测
     *
     * @throws RuleValidationException
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function checkOrder(string $orderSN)
    {
        // 订单
        $this->order = $this->orderService->detailOrderSN($orderSN);
        if (! $this->order) {
            throw new RuleValidationException(__('dujiaoka.prompt.order_does_not_exist'));
        }
        // 订单过期
        if ($this->order->status == Order::STATUS_EXPIRED) {
            throw new RuleValidationException(__('dujiaoka.prompt.order_is_expired'));
        }
        // 已经支付了
        if ($this->order->status > Order::STATUS_WAIT_PAY) {
            throw new RuleValidationException(__('dujiaoka.prompt.order_already_paid'));
        }
    }

    /**
     * 加载支付网关
     *
     * @param  string  $orderSN  订单号
     * @param  string  $payCheck  支付标识
     *
     * @throws RuleValidationException
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function loadGateWay(string $orderSN, string $payCheck)
    {
        $this->checkOrder($orderSN);
        // 支付配置
        $this->payGateway = $this->payService->detailByCheck($payCheck);
        if (! $this->payGateway) {
            throw new RuleValidationException(__('dujiaoka.prompt.pay_gateway_does_not_exist'));
        }
        // 临时保存支付方式
        $this->order->pay_id = $this->payGateway->id;
        $this->order->save();
    }

    /**
     * 网关处理.
     *
     * @param  string  $handle  跳转方法
     * @param  string  $payway  支付标识
     * @param  string  $orderSN  订单.
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function redirectGateway(string $handle, string $payway, string $orderSN)
    {
        try {
            $this->checkOrder($orderSN);
            $bccomp = bccomp((string) $this->order->actual_price, '0.00', 2);
            // 如果订单金额为0 代表无需支付，直接成功
            if ($bccomp == 0) {
                $this->orderProcessService->completedOrder($this->order->order_sn, '0.00');

                return redirect(route('order-info', ['order' => $this->order->order_sn]));
            }

            return redirect(url(urldecode($handle), ['payway' => $payway, 'orderSN' => $orderSN]));
        } catch (RuleValidationException $exception) {
            return $this->err($exception->getMessage());
        }

    }
}
