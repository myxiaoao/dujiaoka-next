{{-- 商品卡片组件 - 使用 Flux 免费组件 + Tailwind 卡片 --}}
<div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-lg transition-shadow duration-300">
    {{-- 商品图片 --}}
    @if($product->picture)
    <div class="aspect-video w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
        <img
            src="{{ picture_url($product->picture) }}"
            alt="{{ $product->gd_name }}"
            class="h-full w-full object-cover"
            loading="lazy">
    </div>
    @endif

    {{-- 商品信息 --}}
    <div class="p-4 space-y-3">
        {{-- 商品名称 --}}
        <flux:heading size="lg" class="line-clamp-2">
            {{ $product->gd_name }}
        </flux:heading>

        {{-- 商品描述 --}}
        @if($product->gd_description)
        <flux:text class="line-clamp-2">
            {{ Str::limit(strip_tags($product->gd_description), 100) }}
        </flux:text>
        @endif

        {{-- 价格和库存 --}}
        <div class="flex items-center justify-between">
            <div class="flex items-baseline gap-2">
                <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">
                    ¥{{ number_format($product->actual_price, 2) }}
                </flux:heading>

                @if($product->retail_price > $product->actual_price)
                <flux:text class="text-sm line-through">
                    ¥{{ number_format($product->retail_price, 2) }}
                </flux:text>
                @endif
            </div>

            {{-- 库存状态 --}}
            @if($product->in_stock > 0)
            <flux:badge color="green" size="sm">
                库存: {{ $product->in_stock }}
            </flux:badge>
            @else
            <flux:badge color="red" size="sm">
                缺货
            </flux:badge>
            @endif
        </div>

        {{-- 购买按钮 --}}
        <flux:button
            href="{{ route('buy', $product->id) }}"
            variant="primary"
            class="w-full"
            :disabled="$product->in_stock <= 0">
            {{ $product->in_stock > 0 ? '立即购买' : '暂时缺货' }}
        </flux:button>
    </div>
</div>
