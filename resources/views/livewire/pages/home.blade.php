{{-- 首页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="space-y-8">
    {{-- 搜索栏 --}}
    <div class="max-w-2xl mx-auto">
        <flux:input.group>
            <flux:input.group.prefix>
                <flux:icon.magnifying-glass variant="micro" />
            </flux:input.group.prefix>
            <flux:input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="搜索商品..." />
        </flux:input.group>
    </div>

    {{-- 公告 (如果有) --}}
    @php
        $notice = dujiaoka_config_get('notice');
    @endphp
    @if($notice)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">公告</h3>
                <p class="text-sm text-blue-800 dark:text-blue-200">{!! nl2br(e($notice)) !!}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- 商品分组列表 --}}
    @forelse($goodsGroups as $group)
    <section>
        {{-- 分类标题 --}}
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $group->gp_name }}</h2>
            <span class="px-2 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 rounded">
                {{ $group->goods->count() }} 个商品
            </span>
        </div>

        {{-- 商品网格 --}}
        @if($group->goods->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($group->goods as $product)
            <livewire:components.product-card :product="$product" :key="'product-'.$product->id" />
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
            <svg class="w-12 h-12 mx-auto text-zinc-400 dark:text-zinc-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p class="text-zinc-500 dark:text-zinc-400">该分类暂无商品</p>
        </div>
        @endif
    </section>
    @empty
    {{-- 无商品或搜索无结果 --}}
    <div class="text-center py-20 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
        @if($search)
        <svg class="w-16 h-16 mx-auto text-zinc-400 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">未找到相关商品</h3>
        <p class="text-zinc-500 dark:text-zinc-400 mb-6">搜索 "{{ $search }}" 没有找到任何商品</p>
        <flux:button
            wire:click="$set('search', '')"
            variant="ghost">
            清除搜索
        </flux:button>
        @else
        <svg class="w-16 h-16 mx-auto text-zinc-400 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">暂无商品</h3>
        <p class="text-zinc-500 dark:text-zinc-400">请稍后再来查看</p>
        @endif
    </div>
    @endforelse
</div>
