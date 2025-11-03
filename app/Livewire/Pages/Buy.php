<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Exceptions\RuleValidationException;
use App\Models\Goods;
use App\Service\GoodsService;
use App\Service\OrderProcessService;
use App\Service\OrderService;
use App\Service\PayService;
use App\Service\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Buy extends Component
{
    public Goods $product;

    public string $email = '';

    public int $quantity = 1;

    public string $searchPassword = '';

    public string $couponCode = '';

    public string $imgVerifyCode = '';

    public int $selectedPaymentId = 0;

    public array $paymentMethods = [];

    public array $customFields = [];

    public function mount(
        int $id,
        GoodsService $goodsService,
        PayService $payService
    ): void {
        // 加载商品
        $this->product = $goodsService->detail($id);

        // 验证商品状态
        $goodsService->validatorGoodsStatus($this->product);

        // 加载支付方式
        $this->paymentMethods = $payService->pays();

        if (! empty($this->paymentMethods)) {
            $this->selectedPaymentId = $this->paymentMethods[0]['id'];
        }
    }

    public function rules(): array
    {
        $rules = [
            'email' => 'required|email',
            'quantity' => 'required|integer|min:1',
        ];

        // 如果启用了查询密码
        if (dujiaoka_config_get('is_open_search_pwd') == Goods::STATUS_OPEN) {
            $rules['searchPassword'] = 'nullable|string|max:255';
        }

        // 如果启用了图形验证码
        if (dujiaoka_config_get('is_open_img_code') == Goods::STATUS_OPEN) {
            $rules['imgVerifyCode'] = 'required|string';
        }

        return $rules;
    }

    public function submitOrder(
        OrderService $orderService,
        OrderProcessService $orderProcessService
    ) {
        // 验证表单
        $this->validate();

        try {
            // 准备订单数据
            $orderData = [
                'gid' => $this->product->id,
                'email' => $this->email,
                'by_amount' => $this->quantity,
                'search_pwd' => $this->searchPassword,
                'coupon_code' => $this->couponCode,
                'payway' => $this->selectedPaymentId,
                'img_verify_code' => $this->imgVerifyCode,
            ];

            // 添加自定义字段
            foreach ($this->customFields as $field => $value) {
                $orderData[$field] = $value;
            }

            // 验证订单
            $validatedData = $orderService->validation($orderData);

            // 创建订单
            $order = $orderProcessService->createOrder($validatedData);

            // 重定向到支付页
            return redirect()->route('bill', $order->id);
        } catch (RuleValidationException $e) {
            // 显示验证错误
            $this->addError('submit', $e->getMessage());
        }
    }

    public function updatedQuantity(): void
    {
        // 数量改变时可以重新计算价格（批发价）
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }
    }

    public function render(SeoService $seoService, GoodsService $goodsService)
    {
        // 格式化商品数据
        $formattedProduct = $goodsService->format($this->product);

        // 获取 SEO 数据
        $seoData = $seoService->getGoodsSeoData($this->product);

        return view('livewire.pages.buy', [
            'formattedProduct' => $formattedProduct,
        ])->with($seoData);
    }
}
