{{-- 首页 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="space-y-6">
    {{-- 公告栏 (如果有) - 最上面，全宽度 --}}
    @php
        $notice = dujiaoka_config_get('notice');
    @endphp
    @if($notice)
    <div class="-mx-6 px-6">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <flux:icon.information-circle class="size-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" />
                <div class="flex-1">
                    <flux:heading size="sm" class="text-yellow-900 dark:text-yellow-100 mb-1">公告</flux:heading>
                    <div class="text-sm text-yellow-800 dark:text-yellow-200 prose prose-sm max-w-none">
                        {!! $notice !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 分类导航和搜索框 - 一行显示 --}}
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4 pb-4 border-b border-zinc-200 dark:border-zinc-700">
        {{-- 左侧：分类 Tab 导航 --}}
        @if(!$search && $allGroups->count() > 1)
        <div class="flex-shrink-0">
            <div class="inline-flex flex-wrap gap-2 p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                {{-- 全部分类 --}}
                <button
                    type="button"
                    wire:click="selectGroup(null)"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors disabled:opacity-50
                        {{ $selectedGroup === null
                            ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm'
                            : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                >
                    全部
                </button>

                {{-- 各个分组 --}}
                @foreach($allGroups as $group)
                <button
                    type="button"
                    wire:click="selectGroup({{ $group->id }})"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors disabled:opacity-50
                        {{ $selectedGroup === $group->id
                            ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm'
                            : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                >
                    {{ $group->gp_name }}
                    <span class="ml-1 text-xs opacity-70">({{ $group->goods->count() }})</span>
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 右侧：搜索框 --}}
        <div class="flex-1 lg:max-w-xs ml-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent"
                    placeholder="搜索商品..." />
            </div>
        </div>
    </div>

    {{-- Loading 状态 --}}
    <div wire:loading class="text-center py-3">
        <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg shadow-sm">
            <svg class="animate-spin h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm text-blue-800 dark:text-blue-200 font-medium">加载中...</span>
        </div>
    </div>

    {{-- 商品分组列表 --}}
    @forelse($goodsGroups as $group)
    <section wire:key="group-{{ $group->id }}-{{ $selectedGroup ?? 'all' }}">
        {{-- 分类标题 --}}
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $group->gp_name }}</h2>
            <span class="px-2 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 rounded">
                {{ $group->goods->count() }} 个商品
            </span>
        </div>

        {{-- 商品网格 --}}
        @if($group->goods->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($group->goods as $product)
            <x-product-card :product="$product" />
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
