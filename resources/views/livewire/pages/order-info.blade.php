{{-- 订单详情页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="max-w-4xl mx-auto space-y-6">
    {{-- 页面标题 --}}
    <div class="flex items-center justify-between">
        <flux:heading size="2xl">订单详情</flux:heading>
        <flux:badge :color="$this->getStatusBadgeColor()" size="lg">
            {{ $this->getStatusText() }}
        </flux:badge>
    </div>

    {{-- 订单基本信息 --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:heading size="lg" class="mb-4">订单信息</flux:heading>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">订单号</flux:text>
                <flux:text class="font-mono">{{ $order->order_sn }}</flux:text>
            </div>

            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">下单时间</flux:text>
                <flux:text>{{ $order->created_at->format('Y-m-d H:i:s') }}</flux:text>
            </div>

            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">商品名称</flux:text>
                <flux:text>{{ $order->goods->gd_name }}</flux:text>
            </div>

            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">购买数量</flux:text>
                <flux:text>{{ $order->buy_amount }} 件</flux:text>
            </div>

            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">订单金额</flux:text>
                <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">
                    ¥{{ number_format($order->actual_price, 2) }}
                </flux:heading>
            </div>

            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">支付方式</flux:text>
                <flux:text>{{ $order->pay->pay_name ?? '未知' }}</flux:text>
            </div>

            @if($order->email)
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">邮箱地址</flux:text>
                <flux:text>{{ $order->email }}</flux:text>
            </div>
            @endif
        </div>
    </div>

    {{-- 卡密信息（仅自动发货且已完成） --}}
    @if($order->type == \App\Models\Goods::AUTOMATIC_DELIVERY && $order->status == \App\Models\Order::STATUS_COMPLETED)
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">卡密信息</flux:heading>
            <flux:badge color="green">
                <flux:icon.check-circle variant="micro" />
                已发货
            </flux:badge>
        </div>

        {{-- 卡密内容显示 --}}
        @if($order->info)
        <div class="space-y-4" x-data="{ copied: false }">
            <div>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">卡密内容</flux:text>
                <textarea
                    readonly
                    rows="6"
                    class="w-full px-4 py-3 font-mono text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 resize-none"
                >{{ $order->info }}</textarea>
            </div>

            {{-- 复制按钮 --}}
            <div class="flex items-center gap-3">
                <flux:button
                    type="button"
                    variant="primary"
                    icon="clipboard-document"
                    @click="
                        navigator.clipboard.writeText('{{ addslashes($order->info) }}').then(() => {
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        }).catch(() => {
                            alert('复制失败，请手动选择复制');
                        });
                    "
                >
                    复制卡密
                </flux:button>

                {{-- 复制成功提示 --}}
                <div x-show="copied" x-transition class="flex items-center gap-2 text-green-600 dark:text-green-400">
                    <flux:icon.check-circle variant="micro" class="size-5" />
                    <flux:text class="text-sm font-medium">复制成功</flux:text>
                </div>
            </div>
        </div>
        @endif

        {{-- 邮箱提示 --}}
        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <flux:icon.information-circle class="size-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                <div class="flex-1">
                    <flux:text class="text-sm text-blue-800 dark:text-blue-200">
                        卡密已同时发送至您的邮箱 <span class="font-mono">{{ $order->email }}</span>，请妥善保管。
                    </flux:text>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 待支付提示 --}}
    @if($order->status == \App\Models\Order::STATUS_WAIT_PAY)
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-6">
        <div class="flex items-start gap-3">
            <flux:icon.clock class="size-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" />
            <div class="flex-1">
                <flux:heading size="sm" class="text-amber-900 dark:text-amber-100 mb-1">等待支付</flux:heading>
                <flux:text class="text-sm text-amber-800 dark:text-amber-200 mb-3">
                    订单已创建，请尽快完成支付。订单将在30分钟后自动过期。
                </flux:text>
                <flux:button href="/bill/{{ $order->order_sn }}" variant="primary" size="sm" icon="credit-card">
                    去支付
                </flux:button>
            </div>
        </div>
    </div>
    @endif

    {{-- 失败/过期提示 --}}
    @if(in_array($order->status, [\App\Models\Order::STATUS_FAILURE, \App\Models\Order::STATUS_EXPIRED, \App\Models\Order::STATUS_ABNORMAL]))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
        <div class="flex items-start gap-3">
            <flux:icon.x-circle class="size-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
            <div class="flex-1">
                <flux:heading size="sm" class="text-red-900 dark:text-red-100 mb-1">订单{{ $this->getStatusText() }}</flux:heading>
                <flux:text class="text-sm text-red-800 dark:text-red-200">
                    @if($order->status == \App\Models\Order::STATUS_EXPIRED)
                        订单已超时未支付，已自动取消。
                    @elseif($order->status == \App\Models\Order::STATUS_FAILURE)
                        订单支付失败或已取消。
                    @else
                        订单状态异常，请联系客服。
                    @endif
                </flux:text>
            </div>
        </div>
    </div>
    @endif

    {{-- 操作按钮 --}}
    <div class="flex gap-4">
        <flux:button href="/" variant="ghost" icon="home">
            返回首页
        </flux:button>
        <flux:button href="{{ route('search-order') }}" variant="ghost" icon="magnifying-glass">
            查询其他订单
        </flux:button>
    </div>
</div>
