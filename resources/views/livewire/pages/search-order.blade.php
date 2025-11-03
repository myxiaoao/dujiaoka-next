{{-- 订单查询页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="max-w-2xl mx-auto">
    {{-- 页面标题 --}}
    <div class="text-center mb-8">
        <flux:heading size="4xl" class="mb-3">订单查询</flux:heading>
        <flux:text class="text-base">通过以下方式查询您的订单</flux:text>
    </div>

    {{-- 查询方式选择 --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
        <flux:field>
            <flux:label>查询方式</flux:label>
            <flux:radio.group wire:model.live="searchType" variant="segmented">
                <flux:radio value="order_sn" label="订单号查询" />
                <flux:radio value="email" label="邮箱查询" />
                <flux:radio value="browser" label="浏览器缓存" />
            </flux:radio.group>
            <flux:error name="searchType" />
        </flux:field>
    </div>

    {{-- 查询表单 --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <form wire:submit="searchOrder" class="space-y-4">
            {{-- 订单号查询 --}}
            @if($searchType === 'order_sn')
            <flux:field>
                <flux:label>订单号</flux:label>
                <flux:input
                    wire:model="orderSn"
                    type="text"
                    placeholder="请输入订单号"
                    required />
                <flux:error name="orderSn" />
                <flux:description>请输入您下单时获得的订单号</flux:description>
            </flux:field>
            @endif

            {{-- 邮箱查询 --}}
            @if($searchType === 'email')
            <flux:field>
                <flux:label>电子邮箱</flux:label>
                <flux:input
                    wire:model="email"
                    type="email"
                    placeholder="请输入下单时使用的邮箱"
                    required />
                <flux:error name="email" />
            </flux:field>

            @if(dujiaoka_config_get('is_open_search_pwd') == \App\Models\Goods::STATUS_OPEN)
            <flux:field>
                <flux:label>查询密码</flux:label>
                <flux:input
                    wire:model="searchPassword"
                    type="text"
                    placeholder="请输入下单时设置的查询密码"
                    required />
                <flux:error name="searchPassword" />
                <flux:description>这是您下单时设置的查询密码</flux:description>
            </flux:field>
            @endif
            @endif

            {{-- 浏览器缓存查询提示 --}}
            @if($searchType === 'browser')
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <flux:icon.information-circle class="size-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                    <div class="flex-1">
                        <flux:heading size="sm" class="text-blue-900 dark:text-blue-100 mb-1">浏览器缓存查询</flux:heading>
                        <flux:text class="text-sm text-blue-800 dark:text-blue-200">
                            将查询您在本浏览器下单的所有订单记录。如果您清除了浏览器缓存，将无法通过此方式查询。
                        </flux:text>
                    </div>
                </div>
            </div>
            @endif

            {{-- 提交按钮 --}}
            <flux:button type="submit" variant="primary" class="w-full mt-6" icon="magnifying-glass">
                查询订单
            </flux:button>
        </form>
    </div>

    {{-- 帮助信息 --}}
    <div class="mt-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
        <flux:heading size="sm" class="mb-2">查询帮助</flux:heading>
        <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
            <li>• 订单号查询：使用下单后获得的订单号进行查询</li>
            <li>• 邮箱查询：使用下单时填写的邮箱地址查询</li>
            <li>• 浏览器缓存：自动查询您在当前浏览器的所有订单</li>
        </ul>
    </div>
</div>
