{{-- 错误提示页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="max-w-2xl mx-auto">
    {{-- 错误信息卡片 --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-8 mb-6">
        <div class="text-center space-y-6">
            {{-- 错误图标 --}}
            <div class="flex justify-center">
                @if($type === 'warning')
                    <flux:icon.exclamation-triangle class="size-20 {{ $this->getIconClass() }}" />
                @elseif($type === 'info')
                    <flux:icon.information-circle class="size-20 {{ $this->getIconClass() }}" />
                @else
                    <flux:icon.x-circle class="size-20 {{ $this->getIconClass() }}" />
                @endif
            </div>

            {{-- 错误标题 --}}
            <div>
                <flux:heading size="2xl" class="mb-2">{{ $title }}</flux:heading>
            </div>

            {{-- 错误消息 --}}
            <div class="rounded-lg border {{ $this->getBackgroundClass() }} p-6">
                <flux:text class="text-lg {{ $this->getDescriptionClass() }}">
                    {{ $message }}
                </flux:text>
            </div>
        </div>
    </div>

    {{-- 帮助信息 --}}
    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
        <flux:heading size="sm" class="mb-3">您可以尝试</flux:heading>
        <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
            <li class="flex items-start gap-2">
                <flux:icon.arrow-right variant="micro" class="size-4 mt-0.5 flex-shrink-0" />
                <span>返回首页重新选择商品</span>
            </li>
            <li class="flex items-start gap-2">
                <flux:icon.arrow-right variant="micro" class="size-4 mt-0.5 flex-shrink-0" />
                <span>查询您的订单状态</span>
            </li>
            <li class="flex items-start gap-2">
                <flux:icon.arrow-right variant="micro" class="size-4 mt-0.5 flex-shrink-0" />
                <span>刷新页面重试</span>
            </li>
            <li class="flex items-start gap-2">
                <flux:icon.arrow-right variant="micro" class="size-4 mt-0.5 flex-shrink-0" />
                <span>如果问题持续存在，请联系客服</span>
            </li>
        </ul>
    </div>

    {{-- 操作按钮 --}}
    <div class="flex flex-col gap-3">
        <flux:button href="/" variant="primary" class="w-full" size="lg">
            <flux:icon.home variant="micro" />
            返回首页
        </flux:button>

        <div class="flex gap-3">
            <flux:button href="{{ route('search-order') }}" variant="ghost" class="flex-1">
                <flux:icon.magnifying-glass variant="micro" />
                查询订单
            </flux:button>
            <flux:button onclick="window.location.reload()" variant="ghost" class="flex-1">
                <flux:icon.arrow-path variant="micro" />
                刷新页面
            </flux:button>
        </div>
    </div>
</div>
