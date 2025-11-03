<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Models\Order;
use App\Service\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class OrderInfo extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['goods', 'pay']);
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->order->status) {
            Order::STATUS_WAIT_PAY => 'amber',
            Order::STATUS_PENDING => 'blue',
            Order::STATUS_PROCESSING => 'blue',
            Order::STATUS_COMPLETED => 'green',
            Order::STATUS_FAILURE => 'red',
            Order::STATUS_EXPIRED => 'zinc',
            Order::STATUS_ABNORMAL => 'red',
            default => 'zinc',
        };
    }

    public function getStatusText(): string
    {
        return match ($this->order->status) {
            Order::STATUS_WAIT_PAY => '待支付',
            Order::STATUS_PENDING => '待处理',
            Order::STATUS_PROCESSING => '处理中',
            Order::STATUS_COMPLETED => '已完成',
            Order::STATUS_FAILURE => '失败',
            Order::STATUS_EXPIRED => '已过期',
            Order::STATUS_ABNORMAL => '异常',
            default => '未知',
        };
    }

    public function render(SeoService $seoService)
    {
        $seoData = $seoService->getDefaultSeoData('订单详情', true);
        $seoData['noindex'] = true;

        return view('livewire.pages.order-info')->with($seoData);
    }
}
