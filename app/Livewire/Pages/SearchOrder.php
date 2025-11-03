<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Models\Goods;
use App\Service\OrderService;
use App\Service\SeoService;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class SearchOrder extends Component
{
    public string $searchType = 'order_sn'; // order_sn, email, browser

    public string $orderSn = '';

    public string $email = '';

    public string $searchPassword = '';

    public function rules(): array
    {
        $rules = [];

        if ($this->searchType === 'order_sn') {
            $rules['orderSn'] = 'required|string';
        } elseif ($this->searchType === 'email') {
            $rules['email'] = 'required|email';
            // 如果系统开启了查询密码功能
            if (dujiaoka_config_get('is_open_search_pwd') == Goods::STATUS_OPEN) {
                $rules['searchPassword'] = 'required|string';
            }
        }

        return $rules;
    }

    public function validationAttributes(): array
    {
        return [
            'orderSn' => '订单号',
            'email' => '邮箱',
            'searchPassword' => '查询密码',
        ];
    }

    public function searchOrder(OrderService $orderService)
    {
        if ($this->searchType === 'browser') {
            return $this->searchByBrowser($orderService);
        }

        $this->validate();

        if ($this->searchType === 'order_sn') {
            return $this->searchByOrderSn($orderService);
        }

        if ($this->searchType === 'email') {
            return $this->searchByEmail($orderService);
        }
    }

    private function searchByOrderSn(OrderService $orderService)
    {
        $order = $orderService->detailOrderSN($this->orderSn);

        if (! $order) {
            $this->addError('orderSn', '订单不存在');

            return;
        }

        return $this->redirect(route('order-info', ['order' => $order->id]));
    }

    private function searchByEmail(OrderService $orderService)
    {
        $orders = $orderService->withEmailAndPassword($this->email, $this->searchPassword);

        if (! $orders || $orders->isEmpty()) {
            $this->addError('email', '未找到相关订单');

            return;
        }

        // 如果只有一个订单，直接跳转
        if ($orders->count() === 1) {
            return $this->redirect(route('order-info', ['order' => $orders->first()->id]));
        }

        // 多个订单，跳转到第一个订单
        return $this->redirect(route('order-info', ['order' => $orders->first()->id]));
    }

    private function searchByBrowser(OrderService $orderService)
    {
        $cookies = Cookie::get('dujiaoka_orders');

        if (empty($cookies)) {
            $this->addError('searchType', '浏览器缓存中未找到订单记录');

            return;
        }

        $orderSNS = json_decode($cookies, true);
        $orders = $orderService->byOrderSNS($orderSNS);

        if (! $orders || $orders->isEmpty()) {
            $this->addError('searchType', '未找到相关订单');

            return;
        }

        // 跳转到最近的订单
        return $this->redirect(route('order-info', ['order' => $orders->first()->id]));
    }

    public function render(SeoService $seoService)
    {
        $seoData = $seoService->getDefaultSeoData('订单查询', true);

        return view('livewire.pages.search-order')->with($seoData);
    }
}
