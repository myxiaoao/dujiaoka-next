<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Http\Controllers\Pay;

use App\Exceptions\RuleValidationException;
use App\Http\Controllers\PayController;
use Illuminate\Http\Request;
use Xhat\Payjs\Facades\Payjs;

class PayjsController extends PayController
{
    public function gateway(string $payway, string $orderSN)
    {
        try {
            // 加载网关
            $this->loadGateWay($orderSN, $payway);
            // 构造订单基础信息
            $data = [
                'body' => $this->order->order_sn,                                // 订单标题
                'total_fee' => bcmul((string) $this->order->actual_price, '100', 0),    // 订单金额
                'out_trade_no' => $this->order->order_sn,                           // 订单号
                'notify_url' => url($this->payGateway->pay_handleroute.'/notify_url'),
            ];
            config(['payjs.mchid' => $this->payGateway->merchant_id, 'payjs.key' => $this->payGateway->merchant_pem]);
            switch ($payway) {
                case 'payjswescan':
                    // QR code payments are handled by QrPay Livewire component
                    return redirect(route('qrpay', ['order' => $this->order->order_sn]));
            }
        } catch (RuleValidationException $exception) {
            return $this->err($exception->getMessage());
        }
    }

    public function notifyUrl(Request $request)
    {
        $orderSN = $request->input('out_trade_no');
        $order = $this->orderService->detailOrderSN($orderSN);
        if (! $order) {
            return 'error';
        }
        $payGateway = $this->payService->detail($order->pay_id);
        if (! $payGateway) {
            return 'error';
        }
        if ($payGateway->pay_handleroute != '/pay/payjs') {
            return 'fail';
        }
        config(['payjs.mchid' => $payGateway->merchant_id, 'payjs.key' => $payGateway->merchant_pem]);
        $notify_info = Payjs::notify();
        $totalFee = (float) bcdiv((string) $notify_info['total_fee'], '100', 2);
        $this->orderProcessService->completedOrder($notify_info['out_trade_no'], $totalFee, $notify_info['payjs_order_id']);

        return 'success';
    }
}
