{{-- 购买页 - 使用 Flux 免费组件 + Tailwind 卡片，展示表单验证、SEO、支付方式选择 --}}
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:grid-rows-1">
        {{-- 左侧：商品详情 --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            {{-- 商品标题（仅移动端显示） --}}
            <div class="lg:hidden">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-3">
                    {{ $product->gd_name }}
                </h1>

                <div class="flex flex-wrap gap-2">
                    @if($product->type == \App\Models\Goods::AUTOMATIC_DELIVERY)
                    <flux:badge color="blue">自动发货</flux:badge>
                    @else
                    <flux:badge color="amber">人工发货</flux:badge>
                    @endif

                    <flux:badge color="green">库存: {{ $product->in_stock }}</flux:badge>

                    @if($product->buy_limit_num > 0)
                    <flux:badge color="zinc">限购: {{ $product->buy_limit_num }}</flux:badge>
                    @endif
                </div>
            </div>

            {{-- 商品描述 --}}
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 flex-1 flex flex-col">
                <flux:heading size="lg" class="mb-4">商品详情</flux:heading>

                {{-- 批发价提示 --}}
                @if(!empty($formattedProduct->wholesale_price_cnf) && is_array($formattedProduct->wholesale_price_cnf))
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4">
                    <flux:heading size="sm" class="mb-2">批发优惠</flux:heading>
                    <div class="space-y-1 text-sm text-amber-800 dark:text-amber-200">
                        @foreach($formattedProduct->wholesale_price_cnf as $ws)
                        <p>购买 {{ $ws['number'] }} 件及以上，{{ $ws['price'] }} 元/件</p>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="prose prose-zinc dark:prose-invert max-w-none flex-1">
                    {!! $formattedProduct->description !!}
                </div>
            </div>
        </div>

        {{-- 右侧：购买表单 --}}
        <div class="lg:col-span-1 flex flex-col">
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 flex-1">
                <form wire:submit="submitOrder" class="space-y-4">
                    {{-- 商品标题和标签（桌面端显示） --}}
                    <div class="hidden lg:block pb-4 border-b border-zinc-200 dark:border-zinc-700 mb-4">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">
                            {{ $product->gd_name }}
                        </h2>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($product->type == \App\Models\Goods::AUTOMATIC_DELIVERY)
                            <flux:badge color="blue">自动发货</flux:badge>
                            @else
                            <flux:badge color="amber">人工发货</flux:badge>
                            @endif

                            <flux:badge color="green">库存: {{ $product->in_stock }}</flux:badge>

                            @if($product->buy_limit_num > 0)
                            <flux:badge color="zinc">限购: {{ $product->buy_limit_num }}</flux:badge>
                            @endif
                        </div>
                    </div>

                    {{-- 价格显示 --}}
                    <div class="pb-4 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-baseline gap-3">
                            <span class="text-4xl font-bold text-red-600 dark:text-red-400">
                                ¥{{ number_format($product->actual_price, 2) }}
                            </span>
                            @if($product->retail_price > $product->actual_price)
                            <span class="text-lg text-zinc-400 dark:text-zinc-500 line-through">
                                ¥{{ number_format($product->retail_price, 2) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- 电子邮箱 --}}
                    <flux:field>
                        <flux:label>电子邮箱</flux:label>
                        <flux:input
                            wire:model.blur="email"
                            type="email"
                            placeholder="接收卡密或通知"
                            required />
                        <flux:error name="email" />
                    </flux:field>

                    {{-- 购买数量 --}}
                    <flux:field>
                        <flux:label>购买数量</flux:label>
                        <flux:input
                            wire:model.live="quantity"
                            type="number"
                            min="1"
                            max="999"
                            required />
                        <flux:error name="quantity" />
                    </flux:field>

                    {{-- 查询密码 (可选) --}}
                    @if(dujiaoka_config_get('is_open_search_pwd') == \App\Models\Goods::STATUS_OPEN)
                    <flux:field>
                        <flux:label>
                            查询密码
                            <flux:badge size="sm" color="zinc">可选</flux:badge>
                        </flux:label>
                        <flux:input
                            wire:model="searchPassword"
                            type="text"
                            placeholder="用于查询订单" />
                        <flux:description>设置后可用于查询订单状态</flux:description>
                    </flux:field>
                    @endif

                    {{-- 优惠码 (可选) --}}
                    @if(isset($formattedProduct->open_coupon))
                    <flux:field>
                        <flux:label>
                            优惠码
                            <flux:badge size="sm" color="zinc">可选</flux:badge>
                        </flux:label>
                        <flux:input
                            wire:model.blur="couponCode"
                            type="text"
                            placeholder="您有优惠码吗？" />
                        <flux:error name="couponCode" />
                    </flux:field>
                    @endif

                    {{-- 图形验证码 (如启用) --}}
                    @if(dujiaoka_config_get('is_open_img_code') == \App\Models\Goods::STATUS_OPEN)
                    <flux:field>
                        <flux:label>验证码</flux:label>
                        <div class="flex gap-2">
                            <flux:input
                                wire:model="imgVerifyCode"
                                type="text"
                                placeholder="输入验证码"
                                class="flex-1"
                                required />
                            <img
                                src="{{ captcha_src('buy') . time() }}"
                                @click="$el.src = '{{ captcha_src('buy') }}' + Math.random()"
                                class="h-10 cursor-pointer border border-zinc-200 dark:border-zinc-700 rounded"
                                alt="验证码">
                        </div>
                        <flux:error name="imgVerifyCode" />
                    </flux:field>
                    @endif

                    {{-- 支付方式 --}}
                    @if(!empty($paymentMethods))
                    <flux:field>
                        <flux:label>支付方式</flux:label>
                        <flux:radio.group wire:model="selectedPaymentId">
                            @foreach($paymentMethods as $method)
                            <flux:radio
                                value="{{ $method['id'] }}"
                                label="{{ $method['pay_name'] }}" />
                            @endforeach
                        </flux:radio.group>
                        <flux:error name="selectedPaymentId" />
                    </flux:field>
                    @endif

                    {{-- 提交按钮 --}}
                    <div class="pt-4">
                        <flux:button
                            type="submit"
                            variant="primary"
                            class="w-full h-12 text-lg"
                            icon="shopping-cart"
                            :disabled="$product->in_stock <= 0">
                            {{ $product->in_stock > 0 ? '立即下单' : '暂时缺货' }}
                        </flux:button>
                    </div>

                    {{-- 错误提示 --}}
                    @error('submit')
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-start gap-2">
                            <flux:icon.exclamation-circle class="size-5 text-red-600 dark:text-red-400 flex-shrink-0" />
                            <flux:text class="text-red-800 dark:text-red-200">{{ $message }}</flux:text>
                        </div>
                    </div>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</div>
