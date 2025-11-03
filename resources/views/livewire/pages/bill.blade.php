{{-- 支付页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="max-w-3xl mx-auto space-y-6">
    {{-- 页面标题 --}}
    <div class="text-center mb-8">
        <flux:heading size="2xl" class="mb-2">订单支付</flux:heading>
        <flux:text>请选择支付方式并完成支付</flux:text>
    </div>

    {{-- 订单信息 --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:heading size="lg" class="mb-4">订单信息</flux:heading>

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">订单号</flux:text>
                <flux:text class="font-mono">{{ $order->order_sn }}</flux:text>
            </div>

            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">商品名称</flux:text>
                <flux:text>{{ $order->goods->gd_name }}</flux:text>
            </div>

            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">购买数量</flux:text>
                <flux:text>{{ $order->buy_amount }} 件</flux:text>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">应付金额</flux:heading>
                <flux:heading size="2xl" class="text-blue-600 dark:text-blue-400">
                    ¥{{ number_format($order->actual_price, 2) }}
                </flux:heading>
            </div>
        </div>
    </div>

    {{-- 支付方式选择 --}}
    @if($paymentMethods->count() > 1)
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:field>
            <flux:label>选择支付方式</flux:label>
            <flux:radio.group wire:model="selectedPaymentId" variant="segmented">
                @foreach($paymentMethods as $method)
                <flux:radio
                    value="{{ $method['id'] }}"
                    label="{{ $method['pay_name'] }}" />
                @endforeach
            </flux:radio.group>
            <flux:error name="payment" />
        </flux:field>
    </div>
    @endif

    {{-- 支付提示 --}}
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <flux:icon.clock class="size-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" />
            <div class="flex-1">
                <flux:heading size="sm" class="text-amber-900 dark:text-amber-100 mb-1">支付提示</flux:heading>
                <flux:text class="text-sm text-amber-800 dark:text-amber-200">
                    请在30分钟内完成支付，超时订单将自动取消。支付完成后，系统将自动发货至您的邮箱。
                </flux:text>
            </div>
        </div>
    </div>

    {{-- 操作按钮 --}}
    <div class="flex flex-col gap-3">
        <flux:button
            wire:click="proceedToPayment"
            variant="primary"
            class="w-full"
            size="lg">
            <flux:icon.credit-card variant="micro" />
            确认支付
        </flux:button>

        <div class="flex gap-3">
            <flux:button href="/" variant="ghost" class="flex-1">
                <flux:icon.home variant="micro" />
                返回首页
            </flux:button>
            <flux:button href="{{ route('search-order') }}" variant="ghost" class="flex-1">
                <flux:icon.magnifying-glass variant="micro" />
                查询订单
            </flux:button>
        </div>
    </div>
</div>
