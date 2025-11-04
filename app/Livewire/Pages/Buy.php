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
        $goods = $goodsService->detail($id);

        // 商品不存在，重定向到首页
        if (empty($goods)) {
            session()->flash('error', '该商品不存在或已下架');
            $this->redirect('/', navigate: true);

            return;
        }

        // 商品未上架，重定向到首页
        if ($goods->is_open != Goods::STATUS_OPEN) {
            session()->flash('error', '该商品暂未上架，敬请期待！');
            $this->redirect('/', navigate: true);

            return;
        }

        // 设置商品属性
        $this->product = $goods;

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
        // Livewire 表单验证
        $this->validate();

        \DB::beginTransaction();
        try {
            // 创建伪 Request 对象用于服务层验证
            $request = new \Illuminate\Http\Request([
                'gid' => $this->product->id,
                'email' => $this->email,
                'by_amount' => $this->quantity,
                'search_pwd' => $this->searchPassword ?? '',
                'coupon_code' => $this->couponCode ?? '',
                'payway' => $this->selectedPaymentId,
                'img_verify_code' => $this->imgVerifyCode ?? '',
            ] + $this->customFields);

            // 添加 IP 到 Request
            $request->server->set('REMOTE_ADDR', request()->ip());

            // 按照原控制器逻辑进行验证
            $orderService->validatorCreateOrder($request);
            $goods = $orderService->validatorGoods($request);
            $orderService->validatorLoopCarmis($request);

            // 设置商品
            $orderProcessService->setGoods($goods);

            // 优惠码验证
            $coupon = $orderService->validatorCoupon($request);
            $orderProcessService->setCoupon($coupon);

            // 代充框验证
            $otherIpt = $orderService->validatorChargeInput($goods, $request);
            $orderProcessService->setOtherIpt($otherIpt);

            // 设置订单数据
            $orderProcessService->setBuyAmount($this->quantity);
            $orderProcessService->setPayID($this->selectedPaymentId);
            $orderProcessService->setEmail($this->email);
            $orderProcessService->setBuyIP(request()->ip());
            $orderProcessService->setSearchPwd($this->searchPassword ?? '');

            // 创建订单
            $order = $orderProcessService->createOrder();

            \DB::commit();

            // 设置订单 Cookie
            $this->queueOrderCookie($order->order_sn);

            // 重定向到支付页
            return $this->redirect(route('bill', ['order' => $order->order_sn]));
        } catch (RuleValidationException $e) {
            \DB::rollBack();
            // 显示验证错误
            $this->addError('submit', $e->getMessage());
        } catch (\Exception $e) {
            \DB::rollBack();
            // 显示其他错误
            $this->addError('submit', $e->getMessage());
        }
    }

    /**
     * 设置订单 Cookie
     */
    private function queueOrderCookie(string $orderSN): void
    {
        $cookies = request()->cookie('dujiaoka_orders');
        if (empty($cookies)) {
            cookie()->queue('dujiaoka_orders', json_encode([$orderSN]), 525600); // 1 year
        } else {
            $cookies = json_decode($cookies, true);
            if (! in_array($orderSN, $cookies)) {
                array_push($cookies, $orderSN);
            }
            cookie()->queue('dujiaoka_orders', json_encode($cookies), 525600);
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
