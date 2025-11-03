<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Http\Controllers\Pay;

use App\Exceptions\RuleValidationException;
use App\Http\Controllers\PayController;
use Yansongda\Pay\Pay;

class WepayController extends PayController
{
    public function gateway(string $payway, string $orderSN)
    {
        try {
            // 加载网关
            $this->loadGateWay($orderSN, $payway);
            $config = [
                'app_id' => $this->payGateway->merchant_id,
                'mch_id' => $this->payGateway->merchant_key,
                'key' => $this->payGateway->merchant_pem,
                'notify_url' => url($this->payGateway->pay_handleroute.'/notify_url'),
                'return_url' => route('order-info', ['order' => $this->order->order_sn]),
                'http' => [ // optional
                    'timeout' => 10.0,
                    'connect_timeout' => 10.0,
                ],
            ];
            $order = [
                'out_trade_no' => $this->order->order_sn,
                'total_fee' => bcmul((string) $this->order->actual_price, '100', 0),
                'body' => $this->order->order_sn,
            ];
            switch ($payway) {
                case 'wescan':
                    // QR code payments are handled by QrPay Livewire component
                    return redirect(route('qrpay', ['order' => $this->order->order_sn]));
            }
        } catch (RuleValidationException $exception) {
            return $this->err($exception->getMessage());
        }
    }

    /**
     * 异步通知
     */
    public function notifyUrl()
    {
        $xml = file_get_contents('php://input');
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $oid = $arr['out_trade_no'];
        $order = $this->orderService->detailOrderSN($oid);
        if (! $order) {
            return 'error';
        }
        $payGateway = $this->payService->detail($order->pay_id);
        if (! $payGateway) {
            return 'error';
        }
        if ($payGateway->pay_handleroute != '/pay/wepay') {
            return 'error';
        }
        $config = [
            'app_id' => $payGateway->merchant_id,
            'mch_id' => $payGateway->merchant_key,
            'key' => $payGateway->merchant_pem,
        ];
        $pay = Pay::wechat($config);
        try {
            // 验证签名
            $result = $pay->verify();
            $total_fee = (float) bcdiv((string) $result->total_fee, '100', 2);
            $this->orderProcessService->completedOrder($result->out_trade_no, $total_fee, $result->transaction_id);

            return 'success';
        } catch (\Exception $exception) {
            return 'fail';
        }
    }
}
