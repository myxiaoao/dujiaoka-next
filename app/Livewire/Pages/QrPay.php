<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Models\Order;
use App\Service\PayService;
use App\Service\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class QrPay extends Component
{
    public Order $orderData;

    public string $qrcodeUrl = '';

    public bool $paymentCompleted = false;

    public function mount(string $order, PayService $payService): void
    {
        // 通过订单号查找订单
        $this->orderData = Order::with(['goods', 'pay'])->where('order_sn', $order)->firstOrFail();

        // 检查订单状态
        if ($this->orderData->status == Order::STATUS_EXPIRED) {
            session()->flash('error_message', '订单已过期');
            $this->redirect(route('search-order'));

            return;
        }

        if ($this->orderData->status == Order::STATUS_COMPLETED) {
            // 订单已支付，跳转到订单详情
            $this->redirect(route('order-info', ['order' => $this->orderData->id]));

            return;
        }

        // 生成支付二维码
        try {
            $paymentData = $payService->pay($this->orderData);
            $this->qrcodeUrl = $paymentData['qrcode'] ?? '';
        } catch (\Exception $e) {
            session()->flash('error_message', '生成支付二维码失败：'.$e->getMessage());
            $this->redirect(route('bill', ['order' => $this->orderData->order_sn]));
        }
    }

    public function checkPaymentStatus(): void
    {
        // 刷新订单状态
        $this->orderData->refresh();

        // 检查订单是否已完成
        if ($this->orderData->status == Order::STATUS_COMPLETED) {
            $this->paymentCompleted = true;
            // 延迟跳转，让用户看到成功提示
            $this->dispatch('payment-completed');
        }

        // 检查订单是否过期
        if ($this->orderData->status == Order::STATUS_EXPIRED) {
            session()->flash('error', '订单已过期');
            $this->redirect(route('search-order'));
        }
    }

    public function render(SeoService $seoService)
    {
        $seoData = $seoService->getDefaultSeoData('扫码支付', true);

        return view('livewire.pages.qr-pay')->with($seoData);
    }
}
