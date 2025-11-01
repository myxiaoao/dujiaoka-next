<?php

namespace App\Http\Controllers\Pay;

use App\Exceptions\RuleValidationException;
use App\Http\Controllers\PayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Models\AmountWithBreakdown;
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\Models\OrderRequest;
use PaypalServerSdkLib\Models\PurchaseUnitRequest;
use PaypalServerSdkLib\Controllers\OrdersController;
use PaypalServerSdkLib\Exceptions\ApiException;

class PaypalPayController extends PayController
{
    const Currency = 'USD'; // 货币单位

    /**
     * 创建 PayPal 客户端
     */
    private function createPayPalClient()
    {
        return PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                $this->payGateway->merchant_key,    // Client ID
                $this->payGateway->merchant_pem     // Client Secret
            )
            ->environment('production') // 使用 production 环境，测试环境用 'sandbox'
            ->build();
    }

    /**
     * 支付网关
     */
    public function gateway(string $payway, string $orderSN)
    {
        try {
            // 加载网关
            $this->loadGateWay($orderSN, $payway);

            $client = $this->createPayPalClient();
            $ordersController = $client->getOrdersController();

            // 货币转换 (如果需要的话，这里假设使用 USD)
            $total = $this->order->actual_price; // 您可能需要转换为 USD

            // 构建订单请求
            $orderRequest = OrderRequest::init(
                CheckoutPaymentIntent::CAPTURE,
                [
                    PurchaseUnitRequest::init(
                        AmountWithBreakdown::init(
                            self::Currency,
                            number_format($total, 2, '.', '')
                        )
                    )
                    ->referenceId($this->order->order_sn)
                    ->description($this->order->title)
                ]
            )
            ->applicationContext([
                'return_url' => route('paypal-return', ['success' => 'ok', 'orderSN' => $this->order->order_sn]),
                'cancel_url' => route('paypal-return', ['success' => 'no', 'orderSN' => $this->order->order_sn])
            ]);

            // 创建订单
            $response = $ordersController->ordersCreate([], $orderRequest);

            if ($response->getStatusCode() == 201) {
                $order = $response->getResult();

                // 获取批准链接
                $approvalUrl = null;
                foreach ($order->getLinks() as $link) {
                    if ($link->getRel() === 'approve') {
                        $approvalUrl = $link->getHref();
                        break;
                    }
                }

                if ($approvalUrl) {
                    return redirect($approvalUrl);
                }
            }

            return $this->err('创建 PayPal 订单失败');

        } catch (ApiException $e) {
            Log::error('PayPal API Error: ' . $e->getMessage());
            return $this->err('PayPal 支付错误: ' . $e->getMessage());
        } catch (RuleValidationException $exception) {
            return $this->err($exception->getMessage());
        } catch (\Exception $e) {
            Log::error('PayPal General Error: ' . $e->getMessage());
            return $this->err('支付处理错误');
        }
    }

    /**
     * PayPal 同步回调
     */
    public function returnUrl(Request $request)
    {
        $success = $request->input('success');
        $token = $request->input('token'); // PayPal 订单 ID
        $orderSN = $request->input('orderSN');

        if ($success == 'no' || empty($token)) {
            // 取消支付
            return redirect(url('detail-order-sn', ['orderSN' => $orderSN]));
        }

        $order = $this->orderService->detailOrderSN($orderSN);
        if (!$order) {
            return 'error';
        }

        $payGateway = $this->payService->detail($order->pay_id);
        if (!$payGateway) {
            return 'error';
        }

        if ($payGateway->pay_handleroute != '/pay/paypal') {
            return 'error';
        }

        try {
            $client = PaypalServerSdkClientBuilder::init()
                ->clientCredentialsAuthCredentials(
                    $payGateway->merchant_key,
                    $payGateway->merchant_pem
                )
                ->environment('production')
                ->build();

            $ordersController = $client->getOrdersController();

            // 捕获订单付款
            $response = $ordersController->ordersCapture($token, null, []);

            if ($response->getStatusCode() == 201) {
                $capturedOrder = $response->getResult();

                // 检查捕获状态
                if ($capturedOrder->getStatus() === 'COMPLETED') {
                    $this->orderProcessService->completedOrder(
                        $orderSN,
                        $order->actual_price,
                        $token
                    );
                    Log::info("PayPal 支付成功", [
                        '订单号' => $orderSN,
                        'PayPal订单ID' => $token
                    ]);
                }
            }

        } catch (ApiException $e) {
            Log::error("PayPal 支付失败", [
                '订单号' => $orderSN,
                'PayPal订单ID' => $token,
                '错误' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error("PayPal 处理错误", [
                '订单号' => $orderSN,
                '错误' => $e->getMessage()
            ]);
        }

        return redirect(url('detail-order-sn', ['orderSN' => $orderSN]));
    }

    /**
     * 异步通知
     * PayPal Webhooks 回调
     */
    public function notifyUrl(Request $request)
    {
        // 获取回调结果
        $json_data = $this->get_JsonData();

        if (!empty($json_data)) {
            Log::debug("PayPal notify info:\r\n" . json_encode($json_data));

            // 处理 webhook 事件
            $eventType = $json_data['event_type'] ?? '';

            if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
                // 处理支付完成事件
                $resource = $json_data['resource'] ?? [];
                $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? '';

                if ($orderId) {
                    Log::info("PayPal Webhook: 支付完成", ['订单ID' => $orderId]);
                }
            }
        } else {
            Log::debug("PayPal notify fail: 参数为空");
        }

        // PayPal webhooks 需要返回 200 状态码
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * 获取 JSON 数据
     */
    private function get_JsonData()
    {
        $json = file_get_contents('php://input');
        if ($json) {
            $json = str_replace("'", '', $json);
            $json = json_decode($json, true);
        }
        return $json;
    }
}
