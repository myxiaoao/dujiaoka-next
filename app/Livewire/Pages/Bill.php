<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Models\Order;
use App\Models\Pay;
use App\Service\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Bill extends Component
{
    public Order $order;

    public int $selectedPaymentId;

    public function mount(string $order): void
    {
        // 通过订单号查找订单
        $this->order = Order::with(['goods', 'pay'])->where('order_sn', $order)->firstOrFail();

        // 检查订单状态
        if ($this->order->status == Order::STATUS_EXPIRED) {
            session()->flash('error', '订单已过期');
            $this->redirect(route('search-order'));

            return;
        }

        if ($this->order->status != Order::STATUS_WAIT_PAY) {
            // 订单已支付，跳转到订单详情
            $this->redirect(route('order-info', ['order' => $this->order->id]));

            return;
        }

        // 默认选择当前订单的支付方式
        $this->selectedPaymentId = $this->order->pay_id;
    }

    public function getPaymentMethods()
    {
        return Pay::query()
            ->where('is_open', Pay::STATUS_OPEN)
            ->orderBy('ord', 'desc')
            ->get()
            ->map(function ($pay) {
                return [
                    'id' => $pay->id,
                    'pay_name' => $pay->pay_name,
                    'pay_check' => $pay->pay_check,
                ];
            });
    }

    public function proceedToPayment()
    {
        // 如果支付方式改变，更新订单
        if ($this->selectedPaymentId != $this->order->pay_id) {
            $this->order->update(['pay_id' => $this->selectedPaymentId]);
            $this->order->refresh();
        }

        // 根据支付方式类型决定跳转
        $pay = Pay::find($this->selectedPaymentId);

        if (! $pay) {
            $this->addError('payment', '支付方式不存在');

            return;
        }

        // 如果是扫码支付，跳转到二维码页面
        if ($pay->pay_check == Pay::PAY_CHECK_QRCODE) {
            return redirect()->route('qrpay', ['order' => $this->order->order_sn]);
        }

        // 否则跳转到支付网关（这里简化处理，实际应该调用支付服务）
        return redirect("/payment/{$pay->pay_handleroute}/{$this->order->order_sn}");
    }

    public function render(SeoService $seoService)
    {
        $seoData = $seoService->getDefaultSeoData('订单支付', true);

        return view('livewire.pages.bill', [
            'paymentMethods' => $this->getPaymentMethods(),
        ])->with($seoData);
    }
}
