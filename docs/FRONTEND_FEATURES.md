# 前端功能文档

本文档详细说明 dujiaoka-next 前端的页面、功能和用户体验。

## 技术架构

### 核心技术栈
- **Livewire 3**: 全栈组件，无需编写 JavaScript 即可实现动态交互
- **Tailwind CSS 4**: 原子化 CSS 框架
- **Flux UI**: Livewire 官方免费组件库
- **Alpine.js**: 轻量级 JavaScript 框架（Livewire 内置）
- **Vite**: 现代化前端构建工具

### 架构特点
- **无刷新交互**: Livewire 实现 SPA 般的用户体验
- **SEO 友好**: 服务端渲染，搜索引擎可索引
- **响应式设计**: 移动端、平板、桌面端全适配
- **暗色模式**: 完整的 Dark Mode 支持

## 前端页面

### 1. 首页 (Home)

**路由**: `GET /`
**组件**: `App\Livewire\Pages\Home`
**模板**: `resources/views/livewire/pages/home.blade.php`

#### 功能特性

##### 商品搜索
- **实时搜索**: wire:model.live.debounce.300ms
- **搜索防抖**: 300ms 延迟，减少服务器请求
- **搜索范围**: 商品名称 + 商品描述
- **友好提示**: 搜索无结果时显示清除按钮

```blade
<flux:input wire:model.live.debounce.300ms="search"
    type="search"
    placeholder="搜索商品..." />
```

##### 分类导航
- **"全部" 分类**: 默认选中，显示所有商品
- **分组 Tab**: 显示分组名称 + 商品数量
- **实时切换**: Livewire 即时过滤，无需刷新
- **搜索时隐藏**: 避免干扰用户搜索

```php
// 选择分组
public ?int $selectedGroup = null;

public function selectGroup(?int $groupId): void
{
    $this->selectedGroup = $groupId;
}
```

##### 公告显示
- **富文本内容**: 支持 HTML 格式
- **蓝色提示框**: 带图标的信息提示样式
- **配置控制**: `dujiaoka_config_get('notice')`

##### 商品卡片
- **商品信息**: 图片、名称、价格、库存
- **库存状态**: 彩色 Badge 徽章
  - 绿色: 库存 > 100
  - 黄色: 库存 10-100
  - 红色: 库存 1-9
  - 灰色: 缺货
- **Livewire 组件**: `ProductCard` 独立组件

##### 无商品提示
- **暂无商品**: 空状态友好提示
- **搜索无结果**: 带清除按钮的提示

---

### 2. 商品购买页 (Buy)

**路由**: `GET /buy/{id}`
**组件**: `App\Livewire\Pages\Buy`
**模板**: `resources/views/livewire/pages/buy.blade.php`

#### 功能特性

##### 商品详情
- **商品图片**: 支持默认占位图
- **商品名称**: 大标题显示
- **价格显示**:
  - 售价（actual_price）
  - 原价（retail_price，删除线样式）
  - 批发价阶梯（如果有）

##### 批发价阶梯
```
购买数量 ≥ 10: ¥8.00/件
购买数量 ≥ 50: ¥7.50/件
购买数量 ≥ 100: ¥7.00/件
```

##### 购买表单
- **购买数量**:
  - 数字输入框
  - 最小值: 1
  - 最大值: min(库存, 10000)
  - Livewire 实时验证

- **邮箱地址**:
  - Email 验证
  - 必填字段
  - 用于接收卡密和通知

- **查询密码** (可选):
  - 系统配置控制是否显示
  - 用于后续订单查询

- **优惠码** (可选):
  - 文本输入
  - 支持优惠券折扣

- **图形验证码** (可选):
  - 系统配置控制
  - 防止机器人刷单

- **自定义表单字段**:
  - 商品可配置额外字段
  - 如：充值账号、区服选择等

##### 支付方式选择
- **单选按钮**: Radio button
- **支付图标**: 各支付方式 Logo
- **支付名称**: 支付宝、微信、PayPal 等

##### 商品描述
- **富文本内容**: HTML 格式
- **购买须知**: 重要提示
- **SEO 优化**: Meta 标签

---

### 3. 订单查询页 (SearchOrder)

**路由**: `GET /search-order`
**组件**: `App\Livewire\Pages\SearchOrder`
**模板**: `resources/views/livewire/pages/search-order.blade.php`

#### 功能特性

##### 查询方式

**按订单号查询**:
```blade
<flux:input wire:model="orderSn"
    placeholder="请输入订单号" />
```

**按邮箱查询**:
```blade
<flux:input type="email" wire:model="email" />
<flux:input type="password" wire:model="searchPassword"
    placeholder="查询密码" />
```

**按浏览器缓存查询**:
- 自动查询本地订单记录
- 无需输入任何信息

##### 查询逻辑
```php
public function searchByOrderSn(): void
{
    $order = Order::where('order_sn', $this->orderSn)->first();
    if ($order) {
        $this->redirect(route('order-info', $order));
    }
}
```

##### 友好提示
- **未找到订单**: 红色提示框
- **多订单结果**: 跳转第一个订单
- **SEO noindex**: 防止搜索引擎索引

---

### 4. 订单详情页 (OrderInfo)

**路由**: `GET /order/{order}`
**组件**: `App\Livewire\Pages\OrderInfo`
**模板**: `resources/views/livewire/pages/order-info.blade.php`

#### 功能特性

##### 订单状态徽章
```php
public function getStatusBadgeColor(): string
{
    return match ($this->order->status) {
        Order::STATUS_WAIT_PAY => 'amber',
        Order::STATUS_COMPLETED => 'green',
        Order::STATUS_FAILURE => 'red',
        // ...
    };
}
```

颜色映射:
- 🟡 Amber: 待支付
- 🔵 Blue: 处理中
- 🟢 Green: 已完成
- 🔴 Red: 失败/异常
- ⚫ Zinc: 已过期

##### 订单信息展示
- **订单号**: 等宽字体显示
- **下单时间**: Y-m-d H:i:s 格式
- **商品名称**: 关联商品
- **购买数量**: X 件
- **订单金额**: ¥X.XX 大字号蓝色显示
- **支付方式**: 支付网关名称
- **邮箱地址**: (如果有)

##### 卡密显示 (核心功能) ⭐

**显示条件**:
- 订单类型 = 自动发货
- 订单状态 = 已完成

**卡密内容**:
```blade
<textarea readonly rows="6" class="font-mono ...">
    {{ $order->info }}
</textarea>
```

**一键复制**:
```blade
<flux:button @click="
    navigator.clipboard.writeText('{{ addslashes($order->info) }}')
        .then(() => { copied = true; setTimeout(() => copied = false, 2000); })
">
    复制卡密
</flux:button>
```

特性:
- 使用原生 Clipboard API（无需 clipboard.js）
- Alpine.js 状态管理
- 2秒成功提示动画
- 复制失败提示用户手动选择

##### 订单状态提示

**待支付**:
- 🟡 黄色提示框
- 倒计时提示（30分钟过期）
- "去支付" 按钮

**已完成**:
- 🟢 绿色徽章
- 卡密显示区域
- 邮箱提示

**失败/过期**:
- 🔴 红色提示框
- 失败原因说明
- 返回首页按钮

##### 操作按钮
- **返回首页**: icon="home"
- **查询其他订单**: icon="magnifying-glass"

---

### 5. 结算页 (Bill)

**路由**: `GET /bill/{order}`
**组件**: `App\Livewire\Pages\Bill`
**模板**: `resources/views/livewire/pages/bill.blade.php`

#### 功能特性

##### 订单信息
- **订单号**: 大字号显示
- **商品名称**: 关联商品
- **订单金额**: ¥X.XX

##### 支付方式
- **选择支付**: 支付网关列表
- **切换支付**: 可能支持更换支付方式

##### 订单状态检查
- **自动检查**: 已支付自动跳转订单详情
- **支付跳转**: 跳转支付网关

---

### 6. 二维码支付页 (QrPay)

**路由**: `GET /qrpay/{order}`
**组件**: `App\Livewire\Pages\QrPay`
**模板**: `resources/views/livewire/pages/qr-pay.blade.php`

#### 功能特性

##### 二维码生成
- **二维码图片**: 支付链接生成
- **订单金额**: 大字号显示
- **有效期提示**: 倒计时

##### 订单状态轮询
- **Livewire 轮询**: wire:poll
- **自动跳转**: 支付成功后跳转订单详情
- **过期处理**: 订单过期提示

---

### 7. 错误页 (Error)

**路由**: `GET /error`
**组件**: `App\Livewire\Pages\Error`
**模板**: `resources/views/livewire/pages/error.blade.php`

#### 功能特性

- **错误提示**: 友好的错误信息
- **返回首页**: 按钮
- **SEO noindex**: 防止搜索引擎索引

---

## UI 组件

### Flux UI 组件使用

#### 表单组件
```blade
<!-- 输入框 -->
<flux:input wire:model="fieldName" placeholder="提示文字" />

<!-- 选择框 -->
<flux:select wire:model="fieldName" :options="$options" />

<!-- 按钮 -->
<flux:button variant="primary" icon="check">提交</flux:button>

<!-- 文本 -->
<flux:text class="text-sm">文字内容</flux:text>

<!-- 标题 -->
<flux:heading size="lg">标题文字</flux:heading>

<!-- 徽章 -->
<flux:badge color="green">已完成</flux:badge>
```

#### 图标组件
```blade
<flux:icon.magnifying-glass variant="micro" />
<flux:icon.check-circle class="size-5" />
<flux:icon.information-circle />
```

### 响应式设计

```blade
<!-- Grid 布局 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- 移动端 1列，平板 2列，桌面 3列 -->
</div>

<!-- Flex 布局 -->
<div class="flex flex-col md:flex-row gap-4">
    <!-- 移动端垂直，桌面端水平 -->
</div>
```

### 暗色模式

```blade
<!-- 背景色 -->
<div class="bg-white dark:bg-zinc-900">

<!-- 文字颜色 -->
<p class="text-zinc-900 dark:text-white">

<!-- 边框颜色 -->
<div class="border-zinc-200 dark:border-zinc-700">
```

---

## 多语言支持

### 已支持语言
- **zh_CN**: 中文简体（默认）
- **zh_TW**: 中文繁体
- **en**: English

### 语言文件位置
```
resources/lang/
├── zh_CN/
│   ├── auth.php
│   ├── pagination.php
│   └── validation.php
├── zh_TW/
│   └── (相同文件)
└── en/
    └── (相同文件)
```

### 切换语言
在系统设置中配置 `language` 字段：
- `zh_CN`
- `zh_TW`
- `en`

---

## SEO 优化

### SEO 服务
**服务类**: `App\Service\SeoService`

功能:
- 动态生成 Meta 标签
- 设置 noindex (订单查询、错误页)
- 首页 SEO 优化
- 商品页 SEO 优化

### 使用示例
```php
public function render(SeoService $seoService)
{
    $seoData = $seoService->getHomeSeoData();
    // $seoData['title'], $seoData['description'], $seoData['keywords']

    return view('livewire.pages.home')->with($seoData);
}
```

---

## 性能优化

### Livewire 优化
- **懒加载**: wire:init
- **防抖**: debounce.300ms
- **节流**: throttle
- **延迟加载**: wire:loading

### 前端资源
```bash
# 开发环境 (热更新)
npm run dev

# 生产环境 (压缩优化)
npm run build
```

### 缓存优化
- **系统配置**: Cache::forever('system-setting')
- **自动恢复**: 缓存过期自动重新加载

---

## 用户体验亮点

### 1. 即时反馈
- 搜索实时更新（300ms 防抖）
- 分类切换无刷新
- 表单验证即时提示

### 2. 友好提示
- 空状态提示
- 错误提示
- 成功提示动画

### 3. 一键操作
- 卡密一键复制
- 清除搜索
- 快速导航

### 4. 移动端优化
- 响应式布局
- 触摸友好
- 适配小屏幕

### 5. 暗色模式
- 完整的 Dark Mode
- 自动适配系统主题
- 护眼舒适

---

## 对比原系统改进

| 功能 | 原系统 | Next 版本 | 改进 |
|------|--------|-----------|------|
| **页面刷新** | 传统表单提交 | Livewire 无刷新 | ✅ SPA 体验 |
| **搜索** | 表单提交 | 实时搜索（防抖） | ✅ 即时响应 |
| **卡密复制** | clipboard.js | 原生 Clipboard API | ✅ 减少依赖 |
| **分类导航** | Bootstrap Tab | Livewire 状态管理 | ✅ 性能优化 |
| **暗色模式** | ❌ 不支持 | ✅ 完整支持 | ✅ 用户体验 |
| **响应式** | Bootstrap Grid | Tailwind Responsive | ✅ 更灵活 |
| **主题** | 3个主题切换 | 单一现代主题 | ✅ 统一体验 |

---

## 常见问题

### Q: 如何禁用暗色模式？
A: 暗色模式基于用户系统设置自动切换，无需禁用。

### Q: 如何自定义主题颜色？
A: 修改 Tailwind 配置文件 `tailwind.config.js`。

### Q: 前端资源编译失败？
A: 检查 Node.js 版本 >= 18，运行 `npm install` 重新安装依赖。

### Q: Livewire 组件不更新？
A: 清除缓存 `php artisan optimize:clear`，刷新浏览器。

---

## 开发指南

### 创建新页面
```bash
# 创建 Livewire 组件
php artisan make:livewire Pages/YourPage

# 添加路由
# routes/common/web.php
Route::get('/your-page', YourPage::class)->name('your-page');
```

### 创建新组件
```bash
# 创建 Livewire 组件
php artisan make:livewire Components/YourComponent
```

### 调试技巧
```blade
<!-- Livewire 调试 -->
@dump($variable)

<!-- Alpine.js 调试 -->
<div x-data="{ debug: true }" x-show="debug">
    调试信息
</div>
```

---

## 相关文档

- [Livewire 官方文档](https://livewire.laravel.com)
- [Tailwind CSS 文档](https://tailwindcss.com)
- [Flux UI 文档](https://fluxui.dev)
- [Alpine.js 文档](https://alpinejs.dev)
