# Livewire 迁移指南

> **重要说明**：本项目使用 Livewire 3 + Flux 免费组件 + Tailwind CSS 构建 UI。
>
> **Flux 组件使用策略**：
> - ✅ **免费组件**：Button、Input、Field、Label、Error、Badge、Heading、Text、Icon、Select、Radio 等 - **已使用**
> - ❌ **Pro 组件**（需付费）：Card、Table、Calendar、Charts、Accordion 等 - **使用 Tailwind 替代**

## ✅ 已完成的工作

### 1. 核心基础设施
- ✅ 安装 Livewire v3.6.4 和 Flux v2.6.1
- ✅ 配置前端资产（Tailwind 4 + Vite）
- ✅ 创建 SEO Service（app/Service/SeoService.php）
- ✅ 创建主布局（resources/views/components/layouts/app.blade.php）- 使用 Tailwind 导航

### 2. 示例组件（使用 Flux 免费组件 + Tailwind 卡片）
- ✅ ProductCard 组件 - 使用 flux:heading、flux:text、flux:badge、flux:button
- ✅ Home 页面组件 - 使用 flux:input.group 搜索框、flux:button
- ✅ Buy 页面组件 - 使用 flux:field、flux:label、flux:input、flux:error、flux:button、flux:radio.group

### 3. 路由配置
- ✅ 重写 routes/common/web.php 为 Livewire 3 路由（使用 Route::get()）
- ✅ 保留必要的后端 API 路由

### 4. 代码清理
- ✅ 删除旧主题目录（hyper/luna/unicorn/install）
- ✅ 删除旧静态资产（public/assets/）
- ✅ 运行代码格式化（Laravel Pint）

### 5. UI 组件
- ✅ 使用 Flux 免费组件（Button、Input、Field、Label、Error、Badge、Heading、Text、Icon）
- ✅ Pro 组件使用 Tailwind 替代（Card 使用 `<div class="bg-white dark:bg-zinc-900 rounded-lg border..."`）
- ✅ @fluxAppearance 和 @fluxScripts 用于暗黑模式和脚本

## 📋 需要你完成的工作

### 第一步：创建剩余的 Livewire 页面组件

按照 `Home.php` 和 `Buy.php` 的模式创建以下页面（**使用 Flux 免费组件 + Tailwind 卡片**）：

#### 1. 订单查询页 (SearchOrder)
```bash
php artisan make:livewire Pages/SearchOrder
```

**需要实现**：
- 三种查询方式：订单号、邮箱、浏览器记录
- 表单验证（参考 Buy.php 中的 flux:field、flux:input、flux:error）
- SEO 配置（noindex）

**参考文件**：
- 示例组件：`app/Livewire/Pages/Buy.php`（表单验证和 Flux 组件用法）
- 路由配置：在 `routes/common/web.php` 中取消注释

#### 2. 订单详情页 (OrderInfo)
```bash
php artisan make:livewire Pages/OrderInfo
```

**需要实现**：
- 显示订单信息
- 显示卡密（如果是自动发货）
- 订单状态追踪
- SEO 配置（noindex, nofollow）

**参考文件**：
- 示例组件：`app/Livewire/Pages/Buy.php`（Tailwind 卡片和 flux:badge）
- 示例组件：`resources/views/livewire/components/product-card.blade.php`（flux:heading、flux:text、flux:badge）

#### 3. 支付页 (Bill)
```bash
php artisan make:livewire Pages/Bill
```

**需要实现**：
- 显示订单信息和支付金额
- 支付方式切换
- 跳转到支付网关
- SEO 配置（noindex）

**参考文件**：
- 示例组件：`app/Livewire/Pages/Buy.php`（flux:radio.group 用法）

#### 4. 二维码支付页 (QrPay)
```bash
php artisan make:livewire Pages/QrPay
```

**需要实现**：
- 显示支付二维码
- 支付状态轮询（使用 `wire:poll.5s="checkPaymentStatus"`）
- 支付成功后自动跳转
- SEO 配置（noindex）

**关键代码**：
```php
// 在组件中添加轮询方法
public function checkPaymentStatus()
{
    // 检查订单支付状态
    $order = Order::find($this->orderId);

    if ($order->status === Order::STATUS_COMPLETED) {
        return redirect()->route('order-info', $order->id);
    }
}
```

**视图中使用**：
```blade
<div wire:poll.5s="checkPaymentStatus">
    {{-- 二维码和支付信息 --}}
</div>
```

#### 5. 错误页 (Error)
创建简单的 Blade 视图：
```bash
mkdir -p resources/views/pages
touch resources/views/pages/error.blade.php
```

**内容示例（Flux 免费组件 + Tailwind 卡片）**：
```blade
<x-layouts.app>
    <div class="max-w-2xl mx-auto text-center py-20">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12">
            <flux:icon.exclamation-triangle class="size-20 mx-auto text-red-500 mb-6" />
            <flux:heading size="2xl" class="mb-4">
                {{ $message ?? '发生错误' }}
            </flux:heading>
            <flux:button href="/" variant="primary">
                返回首页
            </flux:button>
        </div>
    </div>
</x-layouts.app>
```

### 第二步：启用路由

在 `routes/common/web.php` 中取消注释以下路由：

**注意**：Livewire 3 使用 `Route::get()` 而不是 `Route::livewire()`

```php
Route::get('/search-order', \App\Livewire\Pages\SearchOrder::class)->name('search-order');
Route::get('/order/{order}', \App\Livewire\Pages\OrderInfo::class)->name('order-info');
Route::get('/bill/{order}', \App\Livewire\Pages\Bill::class)->name('bill');
Route::get('/qrpay/{order}', \App\Livewire\Pages\QrPay::class)->name('qrpay');
Route::view('/error', 'pages.error')->name('error');
```

### 第三步：SEO 优化

#### 1. 创建默认 OG 图片
在 `public/` 目录下放置一张默认的 Open Graph 图片：
- 文件名：`default-og-image.jpg`
- 尺寸：1200x630px
- 这张图片会在没有商品图片时作为社交分享的默认图片

#### 2. 安装 Sitemap 生成器（可选）
```bash
composer require spatie/laravel-sitemap
```

创建 Sitemap 生成命令：
```bash
php artisan make:command GenerateSitemap
```

**命令内容**：
```php
<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Console\Commands;

use App\Models\Goods;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = '生成网站 Sitemap';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // 添加首页
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'));

        // 添加所有商品页
        Goods::where('is_open', Goods::STATUS_OPEN)->get()->each(function (Goods $goods) use ($sitemap) {
            $sitemap->add(
                Url::create('/buy/' . $goods->id)
                    ->setLastModificationDate($goods->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
            );
        });

        // 写入文件
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap 生成成功！');
    }
}
```

### 第四步：测试

#### 1. 功能测试
```bash
php artisan serve
```

访问以下页面测试：
- [ ] 首页：http://localhost:8000
- [ ] 购买页：http://localhost:8000/buy/1（替换 1 为实际商品 ID）
- [ ] 订单查询：http://localhost:8000/search-order
- [ ] 搜索功能
- [ ] 表单验证
- [ ] 支付流程

#### 2. SEO 测试
使用以下工具验证 SEO：
- [ ] Google Rich Results Test: https://search.google.com/test/rich-results
- [ ] Facebook Sharing Debugger: https://developers.facebook.com/tools/debug/
- [ ] Twitter Card Validator: https://cards-dev.twitter.com/validator

#### 3. 性能测试
```bash
npm run build  # 构建生产资产
```

使用 Lighthouse 测试性能和 SEO 评分。

### 第五步：生产部署

#### 1. 构建资产
```bash
npm run build
```

#### 2. 清除缓存
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 3. 生成 Sitemap（如果安装了）
```bash
php artisan sitemap:generate
```

## 📚 参考资料

### 已创建的示例文件

#### 1. SEO Service
**文件**：`app/Service/SeoService.php`

**提供的方法**：
- `getGoodsSeoData(Goods $goods)` - 获取商品页 SEO 数据
- `getHomeSeoData()` - 获取首页 SEO 数据
- `getDefaultSeoData(string $pageTitle, bool $noindex)` - 获取默认 SEO 数据
- `getProductSchema(Goods $goods)` - 生成 Product Schema
- `getOrganizationSchema()` - 生成 Organization Schema
- `getBreadcrumbSchema(Goods $goods)` - 生成面包屑 Schema

**使用示例**：
```php
public function render(SeoService $seoService)
{
    $seoData = $seoService->getGoodsSeoData($this->product);

    return view('livewire.pages.buy')->with($seoData);
}
```

#### 2. 主布局
**文件**：`resources/views/components/layouts/app.blade.php`

**SEO 功能**：
- 动态 title、description、keywords
- Open Graph 标签（Facebook/LinkedIn）
- Twitter Card 标签
- Canonical 链接
- JSON-LD 结构化数据
- Noindex 支持

**使用示例**：
```php
#[Layout('components.layouts.app')]
class MyPage extends Component
{
    public function render()
    {
        return view('livewire.pages.my-page')->with([
            'title' => '页面标题',
            'description' => '页面描述',
            'keywords' => '关键词1, 关键词2',
            'noindex' => false, // 设置为 true 则不索引
        ]);
    }
}
```

#### 3. ProductCard 组件
**文件**：`app/Livewire/Components/ProductCard.php`

**使用示例**：
```blade
<livewire:components.product-card :product="$product" :key="'product-'.$product->id" />
```

#### 4. Livewire 页面组件模式

**组件类示例**（Buy.php）：
```php
<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Buy extends Component
{
    // 公共属性
    public Goods $product;
    public string $email = '';

    // 挂载方法
    public function mount(int $id, GoodsService $goodsService): void
    {
        $this->product = $goodsService->detail($id);
    }

    // 验证规则
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    // 动作方法
    public function submitOrder()
    {
        $this->validate();
        // 处理逻辑
    }

    // 渲染方法
    public function render(SeoService $seoService)
    {
        $seoData = $seoService->getGoodsSeoData($this->product);

        return view('livewire.pages.buy')->with($seoData);
    }
}
```

**视图示例（Flux 免费组件 + Tailwind 卡片）**：
```blade
<div class="max-w-7xl mx-auto">
    {{-- 使用 Tailwind 卡片（Card 是 Pro 组件） --}}
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
        <flux:heading size="2xl" class="mb-4">{{ $product->gd_name }}</flux:heading>

        <form wire:submit="submitOrder" class="space-y-4">
            {{-- 使用 Flux 表单组件 --}}
            <flux:field>
                <flux:label>电子邮箱</flux:label>
                <flux:input wire:model.blur="email" type="email" />
                <flux:error name="email" />
            </flux:field>

            <flux:button type="submit" variant="primary">
                提交
            </flux:button>
        </form>
    </div>
</div>
```

## 🎨 UI 组件指南

### Flux 免费组件 + Tailwind 组合策略

本项目采用混合策略：**Flux 免费组件 + Tailwind CSS**

#### ✅ 使用 Flux 免费组件

**按钮 (Button)**
```blade
<flux:button variant="primary">主按钮</flux:button>
<flux:button variant="ghost">次要按钮</flux:button>
<flux:button href="/buy/1">链接按钮</flux:button>
```

**输入框 (Input)**
```blade
<flux:input wire:model="email" type="email" placeholder="请输入邮箱" />

{{-- 带图标的输入框组 --}}
<flux:input.group>
    <flux:input.group.prefix>
        <flux:icon.magnifying-glass variant="micro" />
    </flux:input.group.prefix>
    <flux:input placeholder="搜索..." />
</flux:input.group>
```

**表单字段 (Field + Label + Error)**
```blade
<flux:field>
    <flux:label>电子邮箱</flux:label>
    <flux:input wire:model="email" type="email" />
    <flux:error name="email" />
    <flux:description>可选的描述文本</flux:description>
</flux:field>
```

**徽章 (Badge)**
```blade
<flux:badge color="blue">自动发货</flux:badge>
<flux:badge color="green" size="sm">库存: 100</flux:badge>
<flux:badge color="red">缺货</flux:badge>
```

**标题和文本 (Heading + Text)**
```blade
<flux:heading size="2xl">商品标题</flux:heading>
<flux:heading size="lg">小标题</flux:heading>
<flux:text>普通文本</flux:text>
<flux:text class="text-sm">小文本</flux:text>
```

**图标 (Icon)**
```blade
<flux:icon.shopping-cart variant="micro" />
<flux:icon.magnifying-glass class="size-5" />
<flux:icon.exclamation-circle class="size-5 text-red-500" />
```

**单选按钮组 (Radio)**
```blade
<flux:radio.group wire:model="paymentId" variant="segmented">
    <flux:radio value="1" label="支付宝" />
    <flux:radio value="2" label="微信支付" />
</flux:radio.group>
```

#### ❌ 使用 Tailwind 替代 Pro 组件

**卡片 (Card - Pro 组件)**
```blade
<div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
    <!-- 卡片内容 -->
</div>
```

**自定义布局容器**
```blade
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 网格布局 -->
    </div>
</div>
```

### 参考示例文件
- `resources/views/livewire/pages/buy.blade.php` - Flux 表单组件完整示例
- `resources/views/livewire/components/product-card.blade.php` - Flux + Tailwind 混合使用
- `resources/views/livewire/pages/home.blade.php` - 搜索框、按钮示例

### Flux 资源
- 官方文档：https://fluxui.dev
- Icon 列表：https://heroicons.com/
- `@fluxAppearance` - 暗黑模式支持
- `@fluxScripts` - 必要的脚本

## 🔍 常见问题

### Q1: 如何添加暗黑模式支持？
A: 暗黑模式已在所有示例文件中实现，只需在 Tailwind 类中使用 `dark:` 前缀：
```blade
<div class="bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
    <input class="bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700">
</div>
```
参考所有已创建的视图文件，它们都已包含完整的暗黑模式样式。

### Q2: 如何处理文件上传？
A: 使用 Livewire 的 `WithFileUploads` trait：
```php
use Livewire\WithFileUploads;

class MyComponent extends Component
{
    use WithFileUploads;

    public $photo;

    public function save()
    {
        $this->validate(['photo' => 'image|max:1024']);
        $this->photo->store('photos');
    }
}
```

### Q2: 为什么不使用所有 Flux 组件？
A: Flux 有免费组件和 Pro 组件（需付费）。本项目使用免费组件（Button、Input、Field、Label、Error、Badge、Heading、Text、Icon、Radio）+ Tailwind 替代 Pro 组件（Card、Table、Calendar 等）。

### Q3: 如何实现实时验证？
A: 使用 `wire:model.blur` 或 `wire:model.live` 配合 `flux:error`：
```blade
<flux:field>
    <flux:label>邮箱</flux:label>
    <flux:input wire:model.blur="email" type="email" />  {{-- 失焦时验证 --}}
    <flux:error name="email" />
</flux:field>

<flux:input wire:model.live="search" placeholder="实时搜索" />  {{-- 实时验证 --}}
```

### Q4: 如何优化性能？
A:
1. 使用 `wire:key` 在循环中
2. 使用懒加载：`wire:init="loadData"`
3. 使用 debounce：`wire:model.live.debounce.500ms`
4. 合理使用 `wire:poll`，避免过于频繁

## 📝 代码风格

所有 PHP 文件必须包含：
```php
<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
```

使用 Laravel Pint 自动格式化：
```bash
vendor/bin/pint
```

## 🎉 完成标志

当你完成以下所有任务后，迁移即告完成：
- [ ] 创建所有 6 个页面组件
- [ ] 启用所有路由
- [ ] 创建默认 OG 图片
- [ ] 所有页面功能正常
- [ ] SEO 测试通过
- [ ] Lighthouse 性能评分 > 90
- [ ] 代码格式化无错误

祝你顺利完成迁移！如有问题，请参考已创建的示例文件。
