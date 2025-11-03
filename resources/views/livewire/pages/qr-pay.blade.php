{{-- 二维码支付页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="max-w-2xl mx-auto space-y-6" wire:poll.5s="checkPaymentStatus">
    {{-- 页面标题 --}}
    <div class="text-center mb-8">
        <flux:heading size="2xl" class="mb-2">扫码支付</flux:heading>
        <flux:text>请使用{{ $orderData->pay->pay_name }}扫描二维码完成支付</flux:text>
    </div>

    {{-- 支付成功提示 --}}
    @if($paymentCompleted)
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6"
         x-data="{ redirect: false }"
         x-init="setTimeout(() => { redirect = true; $wire.redirect('{{ route('order-info', ['order' => $orderData->id]) }}') }, 2000)">
        <div class="flex items-center gap-3 justify-center">
            <flux:icon.check-circle class="size-8 text-green-600 dark:text-green-400" />
            <div class="text-center">
                <flux:heading size="lg" class="text-green-900 dark:text-green-100 mb-1">支付成功</flux:heading>
                <flux:text class="text-sm text-green-800 dark:text-green-200">
                    正在跳转到订单详情页...
                </flux:text>
            </div>
        </div>
    </div>
    @endif

    {{-- 二维码显示 --}}
    @if(!$paymentCompleted)
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-8">
        <div class="text-center space-y-6">
            {{-- 订单金额 --}}
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">支付金额</flux:text>
                <flux:heading size="3xl" class="text-blue-600 dark:text-blue-400">
                    ¥{{ number_format($orderData->actual_price, 2) }}
                </flux:heading>
            </div>

            {{-- 二维码 --}}
            @if($qrcodeUrl)
            <div class="flex justify-center">
                <div class="bg-white p-4 rounded-lg border-2 border-zinc-200">
                    <img src="{{ $qrcodeUrl }}" alt="支付二维码" class="w-64 h-64">
                </div>
            </div>
            @else
            <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                <flux:icon.exclamation-circle class="size-12 mx-auto mb-2" />
                <flux:text>无法生成支付二维码</flux:text>
            </div>
            @endif

            {{-- 支付状态提示 --}}
            <div class="flex items-center justify-center gap-2 text-zinc-500 dark:text-zinc-400">
                <div class="animate-spin">
                    <flux:icon.arrow-path variant="micro" />
                </div>
                <flux:text class="text-sm">等待支付确认...</flux:text>
            </div>
        </div>
    </div>

    {{-- 订单信息 --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:heading size="lg" class="mb-4">订单信息</flux:heading>

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">订单号</flux:text>
                <flux:text class="font-mono">{{ $orderData->order_sn }}</flux:text>
            </div>

            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">商品名称</flux:text>
                <flux:text>{{ $orderData->goods->gd_name }}</flux:text>
            </div>

            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">购买数量</flux:text>
                <flux:text>{{ $orderData->buy_amount }} 件</flux:text>
            </div>

            <div class="flex justify-between items-center">
                <flux:text class="text-zinc-500 dark:text-zinc-400">支付方式</flux:text>
                <flux:text>{{ $orderData->pay->pay_name }}</flux:text>
            </div>
        </div>
    </div>

    {{-- 支付提示 --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <flux:icon.information-circle class="size-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
            <div class="flex-1">
                <flux:heading size="sm" class="text-blue-900 dark:text-blue-100 mb-1">支付说明</flux:heading>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                    <li>• 请使用{{ $orderData->pay->pay_name }}扫描上方二维码</li>
                    <li>• 支付成功后将自动跳转到订单详情页</li>
                    <li>• 订单将在30分钟后自动过期</li>
                    <li>• 如遇问题，请联系客服</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- 操作按钮 --}}
    <div class="flex gap-3">
        <flux:button href="{{ route('bill', ['order' => $orderData->order_sn]) }}" variant="ghost" class="flex-1" icon="arrow-left">
            返回支付页
        </flux:button>
        <flux:button href="{{ route('search-order') }}" variant="ghost" class="flex-1" icon="magnifying-glass">
            查询订单
        </flux:button>
    </div>
</div>
