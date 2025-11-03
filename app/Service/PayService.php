<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Service;

use App\Models\Order;
use App\Models\Pay;
use Xhat\Payjs\Facades\Payjs;
use Yansongda\Pay\Pay as YansongdaPay;

class PayService
{
    /**
     * 加载支付网关
     *
     * @param  string|int  $payClient  支付场景客户端
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function pays(string|int $payClient = Pay::PAY_CLIENT_PC): ?array
    {
        $payGateway = Pay::query()
            ->whereIn('pay_client', [$payClient, Pay::PAY_CLIENT_ALL])
            ->where('is_open', Pay::STATUS_OPEN)
            ->get();

        return $payGateway ? $payGateway->toArray() : null;
    }

    /**
     * 通过支付标识获得支付配置
     *
     * @param  string  $check  支付标识
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function detailByCheck(string $check)
    {
        $gateway = Pay::query()
            ->where('pay_check', $check)
            ->where('is_open', Pay::STATUS_OPEN)
            ->first();

        return $gateway;
    }

    /**
     * 通过id查询支付网关
     *
     * @param  int  $id  支付网关id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function detail(int $id)
    {
        $gateway = Pay::query()
            ->where('id', $id)
            ->where('is_open', Pay::STATUS_OPEN)
            ->first();

        return $gateway;
    }

    /**
     * 生成支付二维码
     *
     * @param  Order  $order  订单对象
     * @return array 返回包含二维码URL的数组 ['qrcode' => $url]
     *
     * @throws \Exception
     */
    public function pay(Order $order): array
    {
        $payGateway = $this->detail($order->pay_id);

        if (! $payGateway) {
            throw new \Exception(__('dujiaoka.prompt.pay_gateway_does_not_exist'));
        }

        $payCheck = $payGateway->pay_check;

        // 支付宝扫码
        if (in_array($payCheck, ['zfbf2f', 'alipayscan'])) {
            return $this->generateAlipayQrcode($order, $payGateway);
        }

        // 微信扫码
        if ($payCheck === 'wescan') {
            return $this->generateWechatQrcode($order, $payGateway);
        }

        // Payjs 微信扫码
        if ($payCheck === 'payjswescan') {
            return $this->generatePayjsQrcode($order, $payGateway);
        }

        throw new \Exception('不支持的支付方式：'.$payCheck);
    }

    /**
     * 生成支付宝扫码二维码
     */
    private function generateAlipayQrcode(Order $order, Pay $payGateway): array
    {
        $config = [
            'app_id' => $payGateway->merchant_id,
            'ali_public_key' => $payGateway->merchant_key,
            'private_key' => $payGateway->merchant_pem,
            'notify_url' => url($payGateway->pay_handleroute.'/notify_url'),
            'return_url' => route('order-info', ['order' => $order->order_sn]),
            'http' => [
                'timeout' => 10.0,
                'connect_timeout' => 10.0,
            ],
        ];

        $orderData = [
            'out_trade_no' => $order->order_sn,
            'total_amount' => (float) $order->actual_price,
            'subject' => $order->order_sn,
        ];

        $result = YansongdaPay::alipay($config)->scan($orderData)->toArray();

        return [
            'qrcode' => $result['qr_code'] ?? '',
            'payname' => $order->order_sn,
            'actual_price' => (float) $order->actual_price,
            'orderid' => $order->order_sn,
        ];
    }

    /**
     * 生成微信扫码二维码
     */
    private function generateWechatQrcode(Order $order, Pay $payGateway): array
    {
        $config = [
            'app_id' => $payGateway->merchant_id,
            'mch_id' => $payGateway->merchant_key,
            'key' => $payGateway->merchant_pem,
            'notify_url' => url($payGateway->pay_handleroute.'/notify_url'),
            'return_url' => route('order-info', ['order' => $order->order_sn]),
            'http' => [
                'timeout' => 10.0,
                'connect_timeout' => 10.0,
            ],
        ];

        $orderData = [
            'out_trade_no' => $order->order_sn,
            'total_fee' => bcmul((string) $order->actual_price, '100', 0),
            'body' => $order->order_sn,
        ];

        $result = YansongdaPay::wechat($config)->scan($orderData)->toArray();

        return [
            'qrcode' => $result['code_url'] ?? '',
            'payname' => $payGateway->pay_name,
            'actual_price' => (float) $order->actual_price,
            'orderid' => $order->order_sn,
        ];
    }

    /**
     * 生成 Payjs 微信扫码二维码
     */
    private function generatePayjsQrcode(Order $order, Pay $payGateway): array
    {
        $data = [
            'body' => $order->order_sn,
            'total_fee' => bcmul((string) $order->actual_price, '100', 0),
            'out_trade_no' => $order->order_sn,
            'notify_url' => url($payGateway->pay_handleroute.'/notify_url'),
        ];

        config(['payjs.mchid' => $payGateway->merchant_id, 'payjs.key' => $payGateway->merchant_pem]);

        $payres = Payjs::native($data);

        if ($payres['return_code'] != 1) {
            throw new \Exception($payres['return_msg'] ?? '支付失败');
        }

        return [
            'qrcode' => $payres['code_url'] ?? '',
            'payname' => $payGateway->pay_name,
            'actual_price' => (float) $order->actual_price,
            'orderid' => $order->order_sn,
        ];
    }
}
