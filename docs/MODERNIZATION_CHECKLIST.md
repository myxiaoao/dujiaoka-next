# 独角数卡 PHP 8.2+ 和 Laravel 12 现代化迁移清单

> **生成日期**: 2025-11-02
> **PHP 版本**: 8.2+
> **Laravel 版本**: 12
> **预估总工作量**: 18-28 小时（3-4 个工作日）

---

## 📋 目录

- [第一阶段：快速见效（1天）](#第一阶段快速见效1天)
- [第二阶段：核心架构升级（2天）](#第二阶段核心架构升级2天)
- [第三阶段：代码现代化（1天）](#第三阶段代码现代化1天)
- [测试检查清单](#测试检查清单)
- [回滚计划](#回滚计划)

---

## 🎯 总览统计

| 阶段 | 影响文件 | 新增文件 | 修改行数 | 工作量 | 风险 |
|------|---------|---------|---------|--------|------|
| 第一阶段 | 15 个 | 0 个 | ~200 行 | 8-11h | 低 |
| 第二阶段 | 20 个 | 8 个 Enum | ~500 行 | 6-9h | 中 |
| 第三阶段 | 12 个 | 0 个 | ~300 行 | 4-7h | 低 |
| **合计** | **47 个** | **8 个** | **~1000 行** | **18-28h** | **中低** |

---

## 第一阶段：快速见效（1天）

**目标**: 低风险高价值，立即提升代码质量
**预估工作量**: 8-11 小时
**风险等级**: 🟢 低

### 1.1 添加模型 casts() 方法（0.5h）⭐⭐⭐⭐⭐

#### 影响文件：5 个模型

| # | 文件路径 | 修改类型 | 优先级 |
|---|---------|---------|--------|
| 1 | `app/Models/Order.php` | 添加 casts() 方法 | 高 |
| 2 | `app/Models/Goods.php` | 添加 casts() 方法 | 高 |
| 3 | `app/Models/Carmis.php` | 添加 casts() 方法 | 高 |
| 4 | `app/Models/Coupon.php` | 添加 casts() 方法 | 中 |
| 5 | `app/Models/Pay.php` | 添加 casts() 方法 | 中 |

#### 详细修改内容

##### 1. `app/Models/Order.php`

**位置**: 在 `$dispatchesEvents` 之后添加

```php
// 添加以下方法
protected function casts(): array
{
    return [
        'goods_price' => 'decimal:2',
        'coupon_discount_price' => 'decimal:2',
        'wholesale_discount_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'actual_price' => 'decimal:2',
        'buy_amount' => 'integer',
        'goods_id' => 'integer',
        'coupon_id' => 'integer',
        'pay_id' => 'integer',
        'status' => 'integer',
        'type' => 'integer',
        'coupon_ret_back' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**检查点**:
- [ ] 方法添加成功
- [ ] 运行测试通过
- [ ] Filament 表单正常显示

---

##### 2. `app/Models/Goods.php`

**位置**: 在 `$dispatchesEvents` 之后添加

```php
// 添加以下方法
protected function casts(): array
{
    return [
        'is_open' => 'boolean',
        'is_open_wholesale' => 'boolean',
        'actual_price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'in_stock' => 'integer',
        'buy_limit_num' => 'integer',
        'sales_volume' => 'integer',
        'ord' => 'integer',
        'group_id' => 'integer',
        'type' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**检查点**:
- [ ] 方法添加成功
- [ ] 布尔值字段正常工作
- [ ] 价格字段精度正确

---

##### 3. `app/Models/Carmis.php`

**位置**: 在类的末尾添加

```php
// 添加以下方法
protected function casts(): array
{
    return [
        'status' => 'integer',
        'is_loop' => 'boolean',
        'goods_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**检查点**:
- [ ] 方法添加成功
- [ ] `is_loop` 布尔值正确

---

##### 4. `app/Models/Coupon.php`

**位置**: 在类的末尾添加

```php
// 添加以下方法
protected function casts(): array
{
    return [
        'is_use' => 'integer',
        'is_open' => 'boolean',
        'discount' => 'decimal:2',
        'ret' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**检查点**:
- [ ] 方法添加成功
- [ ] 折扣精度正确

---

##### 5. `app/Models/Pay.php`

**位置**: 在类的末尾添加

```php
// 添加以下方法
protected function casts(): array
{
    return [
        'pay_method' => 'integer',
        'pay_client' => 'integer',
        'is_open' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**检查点**:
- [ ] 方法添加成功
- [ ] 支付方式显示正常

---

### 1.2 构造器属性提升（2-3h）⭐⭐⭐⭐

#### 影响文件：10 个类

| # | 文件路径 | 当前代码行数 | 优化后行数 | 节省 |
|---|---------|------------|-----------|------|
| 1 | `app/Http/Controllers/PayController.php` | 已完成 ✅ | - | - |
| 2 | `app/Http/Controllers/Home/OrderController.php` | 已完成 ✅ | - | - |
| 3 | `app/Http/Controllers/Home/HomeController.php` | 已完成 ✅ | - | - |
| 4 | `app/Service/OrderService.php` | ~15 行 | ~5 行 | 10 行 |
| 5 | `app/Service/OrderProcessService.php` | ~30 行 | ~10 行 | 20 行 |
| 6 | `app/Jobs/MailSend.php` | ~15 行 | ~5 行 | 10 行 |
| 7 | `app/Jobs/OrderExpired.php` | ~10 行 | ~4 行 | 6 行 |
| 8 | `app/Jobs/TelegramPush.php` | ~12 行 | ~5 行 | 7 行 |
| 9 | `app/Jobs/BarkPush.php` | ~10 行 | ~4 行 | 6 行 |
| 10 | `app/Jobs/ServerJiang.php` | ~10 行 | ~4 行 | 6 行 |

#### 详细修改内容

##### 4. `app/Service/OrderService.php`

**当前代码** (第 22-28 行):
```php
private $goodsService;
private $couponService;

public function __construct()
{
    $this->goodsService = app('Service\GoodsService');
    $this->couponService = app('Service\CouponService');
}
```

**修改为**:
```php
public function __construct(
    private readonly GoodsService $goodsService,
    private readonly CouponService $couponService,
) {}
```

**额外修改**:
- 在文件顶部添加导入:
  ```php
  use App\Service\GoodsService;
  use App\Service\CouponService;
  ```

**检查点**:
- [ ] 删除旧的属性声明
- [ ] 添加新的构造器
- [ ] 添加 use 语句
- [ ] 运行测试通过

---

##### 5. `app/Service/OrderProcessService.php`

**当前代码** (第 56-84 行):
```php
private $couponService;
private $orderService;
private $carmisService;
private $emailtplService;
private $goodsService;

private $orderSN;
private $goodsID;
private $email;
// ... 更多属性

public function __construct()
{
    $this->couponService = app('Service\CouponService');
    $this->orderService = app('Service\OrderService');
    $this->carmisService = app('Service\CarmisService');
    $this->emailtplService = app('Service\EmailtplService');
    $this->goodsService = app('Service\GoodsService');
}
```

**修改为**:
```php
// 服务依赖通过构造器注入
public function __construct(
    private readonly CouponService $couponService,
    private readonly OrderService $orderService,
    private readonly CarmisService $carmisService,
    private readonly EmailtplService $emailtplService,
    private readonly GoodsService $goodsService,
) {}

// 保留这些属性（通过 setter 设置）
private string $orderSN;
private int|string $goodsID;
private string $email;
// ... 其他通过 setter 设置的属性
```

**额外修改**:
- 在文件顶部添加导入:
  ```php
  use App\Service\CouponService;
  use App\Service\OrderService;
  use App\Service\CarmisService;
  use App\Service\EmailtplService;
  use App\Service\GoodsService;
  ```

**检查点**:
- [ ] 只注入服务依赖
- [ ] 保留 setter 方法
- [ ] 保留通过 setter 设置的属性
- [ ] 运行测试通过

---

##### 6. `app/Jobs/MailSend.php`

**当前代码** (第 21-33 行):
```php
private $to;
private $content;
private $title;

public function __construct(string $to, string $title, string $content)
{
    $this->to = $to;
    $this->title = $title;
    $this->content = $content;
}
```

**修改为**:
```php
public function __construct(
    private readonly string $to,
    private readonly string $title,
    private readonly string $content,
) {}
```

**检查点**:
- [ ] 删除旧的属性声明
- [ ] 添加新的构造器
- [ ] 测试队列任务序列化正常
- [ ] 测试邮件发送正常

---

##### 7. `app/Jobs/OrderExpired.php`

**当前代码** (第 19-26 行):
```php
private $orderSN;

public function __construct(string $orderSN)
{
    $this->orderSN = $orderSN;
}
```

**修改为**:
```php
public function __construct(
    private readonly string $orderSN,
) {}
```

**检查点**:
- [ ] 删除旧的属性声明
- [ ] 添加新的构造器
- [ ] 测试订单过期任务正常

---

##### 8. `app/Jobs/TelegramPush.php`

**当前代码** (第 19-27 行):
```php
private $order;
private $goodsService;

public function __construct(Order $order)
{
    $this->order = $order;
    $this->goodsService = app('Service\GoodsService');
}
```

**修改为**:
```php
public function __construct(
    private readonly Order $order,
) {}

// 修改 handle 方法，使用方法注入
public function handle(GoodsService $goodsService): void
{
    // 将 $this->goodsService 改为 $goodsService
}
```

**额外修改**:
- 在文件顶部添加导入:
  ```php
  use App\Service\GoodsService;
  ```

**检查点**:
- [ ] 删除 `$goodsService` 属性
- [ ] 使用方法注入
- [ ] 更新 handle 方法中的引用
- [ ] 测试 Telegram 推送正常

---

##### 9-10. 其他 Job 类

按照相同的模式修改以下文件：
- `app/Jobs/BarkPush.php`
- `app/Jobs/ServerJiang.php`
- `app/Jobs/WorkWeiXinPush.php`
- `app/Jobs/ApiHook.php`
- `app/Jobs/CouponBack.php`

**通用修改模式**:
```php
// Before
private $order;
public function __construct(Order $order) {
    $this->order = $order;
}

// After
public function __construct(
    private readonly Order $order,
) {}
```

---

### 1.3 添加 readonly 属性（1-2h）⭐⭐⭐

#### 影响文件：3 个 Event 类

| # | 文件路径 | 修改类型 |
|---|---------|---------|
| 1 | `app/Events/OrderUpdated.php` | 属性改为 readonly |
| 2 | `app/Events/GoodsDeleted.php` | 属性改为 readonly |
| 3 | `app/Events/GoodsGroupDeleted.php` | 属性改为 readonly |

#### 详细修改内容

##### 1. `app/Events/OrderUpdated.php`

**当前代码** (第 20-30 行):
```php
public $order;

public function __construct(Order $order)
{
    $this->order = $order;
}
```

**修改为**:
```php
public function __construct(
    public readonly Order $order,
) {}
```

**检查点**:
- [ ] 删除旧的属性声明
- [ ] 添加新的构造器
- [ ] 测试事件触发正常
- [ ] 测试监听器接收正常

---

##### 2-3. 其他 Event 类

按照相同的模式修改：
- `app/Events/GoodsDeleted.php` - `$goods` 属性
- `app/Events/GoodsGroupDeleted.php` - `$goodsGroup` 属性

---

### 1.4 完善类型声明（4-6h）⭐⭐⭐⭐

#### 影响文件：20+ 个文件

##### A. Service 类方法类型声明

**文件**: `app/Service/OrderProcessService.php`

需要添加类型声明的方法（第 86-140 行）:

```php
// Before
public function setBuyIP($buyIP): void
public function setSearchPwd($searchPwd): void
public function setGoods(Goods $goods)
public function setPay(Pay $pay)
public function setCoupon(?Coupon $coupon): void  // ✅ 已正确
public function setBuyAmount($buyAmount): void
public function setTotalPrice($totalPrice): void
public function setActualPrice($actualPrice): void

// After
public function setBuyIP(string $buyIP): void
public function setSearchPwd(string $searchPwd): void
public function setGoods(Goods $goods): void
public function setPay(Pay $pay): void
public function setCoupon(?Coupon $coupon): void
public function setBuyAmount(int $buyAmount): void
public function setTotalPrice(string $totalPrice): void
public function setActualPrice(string $actualPrice): void
```

**检查点**:
- [ ] 所有 setter 方法都有参数类型
- [ ] 所有 setter 方法都有返回类型 `:void`
- [ ] 运行测试通过

---

##### B. 辅助函数类型声明

**文件**: `app/Helpers/functions.php`

需要添加类型声明的函数：

```php
// Before (第 93-104 行)
function dujiaoka_config_get(string $key, $default = null)

// After
function dujiaoka_config_get(string $key, mixed $default = null): mixed

// Before (第 169-178 行)
function picture_ulr($file, $getHost = false)

// After
function picture_ulr(?string $file, bool $getHost = false): string

// Before (第 181-203 行)
function replace_mail_tpl($mailtpl = [], $data = [])

// After
function replace_mail_tpl(array $mailtpl = [], array $data = []): array|false

// Before (第 206-212 行)
function format_wholesale_price($str)

// After
function format_wholesale_price(string $str): array

// Before (第 215-238 行)
function format_charge_input($str)

// After
function format_charge_input(string $str): array

// Before (第 241-246 行)
function site_url()

// After
function site_url(): string
```

**检查点**:
- [ ] 所有函数都有参数类型
- [ ] 所有函数都有返回类型
- [ ] 运行测试通过

---

### 第一阶段完成检查

完成第一阶段后，执行以下检查：

```bash
# 1. 运行代码格式化
vendor/bin/pint

# 2. 运行所有测试
php artisan test

# 3. 手动测试关键功能
# - 创建订单
# - 支付流程
# - 邮件发送
# - 后台管理
```

**检查清单**:
- [ ] Pint 格式检查通过
- [ ] 所有测试通过
- [ ] 订单创建正常
- [ ] 支付流程正常
- [ ] 邮件发送正常
- [ ] 后台管理正常
- [ ] 队列任务正常

---

## 第二阶段：核心架构升级（2天）

**目标**: Enum 重构和依赖注入优化
**预估工作量**: 6-9 小时
**风险等级**: 🟡 中

### 2.1 创建 Enum 类（2-3h）⭐⭐⭐⭐⭐

#### 新增文件：8 个 Enum 类

| # | 文件路径 | 值的数量 | 优先级 |
|---|---------|---------|--------|
| 1 | `app/Enums/OrderStatus.php` | 7 个 | 高 |
| 2 | `app/Enums/CouponBackStatus.php` | 2 个 | 高 |
| 3 | `app/Enums/CarmisStatus.php` | 2 个 | 高 |
| 4 | `app/Enums/CouponStatus.php` | 2 个 | 中 |
| 5 | `app/Enums/PayMethod.php` | 2 个 | 中 |
| 6 | `app/Enums/PayClient.php` | 3 个 | 中 |
| 7 | `app/Enums/CommonStatus.php` | 2 个 | 低 |
| 8 | `app/Enums/GoodsType.php` | 2 个 | 中 |

#### 详细实现

##### 1. 创建 Enum 基础 Trait

**新建文件**: `app/Enums/Concerns/HasOptions.php`

```php
<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Enums\Concerns;

trait HasOptions
{
    public static function options(): array
    {
        return array_map(
            fn(self $case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }

    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    public static function labels(): array
    {
        return array_map(fn(self $case) => $case->label(), self::cases());
    }
}
```

---

##### 2. `app/Enums/OrderStatus.php`

**新建文件**:

```php
<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Enums;

use App\Enums\Concerns\HasOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: int implements HasLabel, HasColor, HasIcon
{
    use HasOptions;

    case WaitPay = 1;
    case Pending = 2;
    case Processing = 3;
    case Completed = 4;
    case Failure = 5;
    case Expired = -1;
    case Abnormal = 6;

    public function label(): string
    {
        return match($this) {
            self::WaitPay => admin_trans('order.fields.status_wait_pay'),
            self::Pending => admin_trans('order.fields.status_pending'),
            self::Processing => admin_trans('order.fields.status_processing'),
            self::Completed => admin_trans('order.fields.status_completed'),
            self::Failure => admin_trans('order.fields.status_failure'),
            self::Expired => admin_trans('order.fields.status_expired'),
            self::Abnormal => admin_trans('order.fields.status_abnormal'),
        };
    }

    public function getLabel(): ?string
    {
        return $this->label();
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::WaitPay => 'warning',
            self::Pending => 'info',
            self::Processing => 'primary',
            self::Completed => 'success',
            self::Failure, self::Abnormal => 'danger',
            self::Expired => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::WaitPay => 'heroicon-o-clock',
            self::Pending => 'heroicon-o-pause',
            self::Processing => 'heroicon-o-arrow-path',
            self::Completed => 'heroicon-o-check-circle',
            self::Failure => 'heroicon-o-x-circle',
            self::Abnormal => 'heroicon-o-exclamation-triangle',
            self::Expired => 'heroicon-o-calendar-x-mark',
        };
    }

    // 业务逻辑方法
    public function isPaid(): bool
    {
        return $this->value > self::WaitPay->value && $this->value !== self::Expired->value;
    }

    public function canBePaid(): bool
    {
        return $this === self::WaitPay;
    }

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }

    public function isFailed(): bool
    {
        return in_array($this, [self::Failure, self::Abnormal, self::Expired]);
    }
}
```

---

##### 3-8. 其他 Enum 类

创建以下文件（简化版，只列出结构）:

**`app/Enums/CouponBackStatus.php`**:
```php
enum CouponBackStatus: int implements HasLabel
{
    use HasOptions;

    case Wait = 0;
    case Completed = 1;

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
}
```

**`app/Enums/CarmisStatus.php`**:
```php
enum CarmisStatus: int implements HasLabel, HasColor
{
    use HasOptions;

    case Sold = 1;
    case Unsold = 2;

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
    public function getColor(): string|array|null { ... }
}
```

**`app/Enums/CouponStatus.php`**:
```php
enum CouponStatus: int implements HasLabel
{
    use HasOptions;

    case Unused = 0;
    case Used = 1;

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
}
```

**`app/Enums/PayMethod.php`**:
```php
enum PayMethod: int implements HasLabel
{
    use HasOptions;

    case YuE = 1;      // 余额支付
    case Online = 2;   // 在线支付

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
}
```

**`app/Enums/PayClient.php`**:
```php
enum PayClient: int implements HasLabel
{
    use HasOptions;

    case PC = 1;
    case Mobile = 2;
    case Any = 3;

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
}
```

**`app/Enums/CommonStatus.php`**:
```php
enum CommonStatus: int implements HasLabel
{
    use HasOptions;

    case Off = 0;
    case On = 1;

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
}
```

**`app/Enums/GoodsType.php`**:
```php
enum GoodsType: int implements HasLabel
{
    use HasOptions;

    case AutoDelivery = 1;      // 自动发货
    case ManualProcessing = 2;  // 人工处理

    public function label(): string { ... }
    public function getLabel(): ?string { ... }
}
```

**创建检查**:
- [ ] 创建 `app/Enums/Concerns/HasOptions.php`
- [ ] 创建 8 个 Enum 类
- [ ] 所有 Enum 都实现了 Filament 接口
- [ ] 所有 Enum 都有 `label()` 方法
- [ ] 运行 `vendor/bin/pint` 格式化

---

### 2.2 更新模型使用 Enum（1-2h）⭐⭐⭐⭐⭐

#### 影响文件：6 个模型

| # | 文件路径 | 修改内容 |
|---|---------|---------|
| 1 | `app/Models/Order.php` | 更新 casts，保留常量 |
| 2 | `app/Models/Carmis.php` | 更新 casts |
| 3 | `app/Models/Coupon.php` | 更新 casts |
| 4 | `app/Models/Pay.php` | 更新 casts |
| 5 | `app/Models/BaseModel.php` | 保留常量（暂不删除） |
| 6 | `app/Models/Goods.php` | 更新 casts |

#### 详细修改内容

##### 1. `app/Models/Order.php`

**添加导入**:
```php
use App\Enums\OrderStatus;
use App\Enums\CouponBackStatus;
use App\Enums\GoodsType;
```

**更新 casts() 方法**:
```php
protected function casts(): array
{
    return [
        'status' => OrderStatus::class,           // 修改：使用 Enum
        'coupon_ret_back' => CouponBackStatus::class, // 修改：使用 Enum
        'type' => GoodsType::class,               // 修改：使用 Enum
        'goods_price' => 'decimal:2',
        'coupon_discount_price' => 'decimal:2',
        'wholesale_discount_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'actual_price' => 'decimal:2',
        'buy_amount' => 'integer',
        'goods_id' => 'integer',
        'coupon_id' => 'integer',
        'pay_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**更新 getStatusMap() 方法**:
```php
// 更新现有方法以支持向后兼容
public static function getStatusMap()
{
    return OrderStatus::options();
}
```

**保留常量（向后兼容）**:
```php
// 保留以下常量（添加注释标记为已废弃）
/**
 * @deprecated 使用 OrderStatus Enum 代替
 */
const STATUS_WAIT_PAY = 1;
// ... 其他常量
```

**检查点**:
- [ ] 添加 Enum 导入
- [ ] 更新 casts() 方法
- [ ] 更新 getStatusMap() 方法
- [ ] 常量标记为 @deprecated
- [ ] 运行测试通过

---

##### 2-6. 其他模型

按照相同的模式更新：

**`app/Models/Carmis.php`**:
```php
use App\Enums\CarmisStatus;

protected function casts(): array
{
    return [
        'status' => CarmisStatus::class,  // 修改
        'is_loop' => 'boolean',
        // ...
    ];
}
```

**`app/Models/Coupon.php`**:
```php
use App\Enums\CouponStatus;

protected function casts(): array
{
    return [
        'is_use' => CouponStatus::class,  // 修改
        'is_open' => 'boolean',
        // ...
    ];
}
```

**`app/Models/Pay.php`**:
```php
use App\Enums\PayMethod;
use App\Enums\PayClient;

protected function casts(): array
{
    return [
        'pay_method' => PayMethod::class,  // 修改
        'pay_client' => PayClient::class,  // 修改
        'is_open' => 'boolean',
        // ...
    ];
}
```

**`app/Models/Goods.php`**:
```php
use App\Enums\GoodsType;

protected function casts(): array
{
    return [
        'type' => GoodsType::class,  // 修改
        'is_open' => 'boolean',
        // ...
    ];
}
```

---

### 2.3 更新使用 Enum 的代码（2-3h）⭐⭐⭐⭐

#### 影响文件：约 15-20 个文件

这是最耗时的部分，需要逐个更新使用常量的地方。

##### A. 监听器更新

**文件**: `app/Listeners/OrderUpdated.php`

**当前代码** (第 48-63 行):
```php
if ($event->order->type == Order::MANUAL_PROCESSING) {
    switch ($event->order->status) {
        case Order::STATUS_PENDING:
            // ...
            break;
        case Order::STATUS_COMPLETED:
            // ...
            break;
        case Order::STATUS_FAILURE:
            // ...
            break;
    }
}
```

**修改为**:
```php
use App\Enums\GoodsType;
use App\Enums\OrderStatus;

if ($event->order->type === GoodsType::ManualProcessing) {
    $templateToken = match($event->order->status) {
        OrderStatus::Pending => 'pending_order',
        OrderStatus::Completed => 'completed_order',
        OrderStatus::Failure => 'failed_order',
        default => null,
    };

    if ($templateToken !== null) {
        $mailtpl = Emailtpl::query()
            ->where('tpl_token', $templateToken)
            ->first()
            ?->toArray();

        if ($mailtpl) {
            // 发送邮件逻辑
            self::sendMailToOrderStatus($mailtpl, $order, $to);
        }
    }
}
```

**检查点**:
- [ ] 添加 Enum 导入
- [ ] 替换常量为 Enum
- [ ] 使用 match 表达式
- [ ] 测试邮件通知正常

---

##### B. 控制器更新

**文件**: `app/Http/Controllers/PayController.php`

**当前代码** (第 76-82 行):
```php
if ($this->order->status == Order::STATUS_EXPIRED) {
    throw new RuleValidationException(__('dujiaoka.prompt.order_is_expired'));
}
if ($this->order->status > Order::STATUS_WAIT_PAY) {
    throw new RuleValidationException(__('dujiaoka.prompt.order_already_paid'));
}
```

**修改为**:
```php
use App\Enums\OrderStatus;

if ($this->order->status === OrderStatus::Expired) {
    throw new RuleValidationException(__('dujiaoka.prompt.order_is_expired'));
}
if ($this->order->status->isPaid()) {  // 使用 Enum 方法
    throw new RuleValidationException(__('dujiaoka.prompt.order_already_paid'));
}
```

---

##### C. 服务类更新

需要更新的服务类文件：
- `app/Service/OrderService.php`
- `app/Service/OrderProcessService.php`
- `app/Service/CarmisService.php`

**示例**: `app/Service/OrderProcessService.php`

查找所有使用常量的地方，例如：
```php
// Before
if ($order->status === Order::STATUS_WAIT_PAY) { ... }

// After
use App\Enums\OrderStatus;
if ($order->status === OrderStatus::WaitPay) { ... }
```

---

##### D. Job 类更新

**文件**: `app/Jobs/OrderExpired.php`

```php
// Before
if ($order && $order->status == Order::STATUS_WAIT_PAY) {
    // ...
}

// After
use App\Enums\OrderStatus;
if ($order && $order->status === OrderStatus::WaitPay) {
    // ...
}
```

---

### 2.4 依赖注入优化（1-2h）⭐⭐⭐

这部分在第一阶段已经完成大部分，只需检查剩余的服务类。

**检查文件**:
- [ ] `app/Service/GoodsService.php` - 检查是否有 `app()` 调用
- [ ] `app/Service/PayService.php` - 检查是否有 `app()` 调用
- [ ] `app/Service/CarmisService.php` - 检查是否有 `app()` 调用
- [ ] `app/Service/EmailtplService.php` - 检查是否有 `app()` 调用
- [ ] `app/Service/CouponService.php` - 检查是否有 `app()` 调用

---

### 第二阶段完成检查

```bash
# 1. 格式化代码
vendor/bin/pint

# 2. 运行所有测试
php artisan test

# 3. 测试 Filament 后台
# - 查看订单列表（状态显示）
# - 编辑订单（状态选择）
# - 查看商品列表
# - 编辑商品

# 4. 测试业务流程
# - 创建订单
# - 订单状态变更
# - 邮件通知
```

**检查清单**:
- [ ] 所有 Enum 类创建成功
- [ ] 模型 casts 更新完成
- [ ] Filament 后台显示正常
- [ ] 订单状态显示正确颜色和图标
- [ ] 订单创建流程正常
- [ ] 邮件通知正常
- [ ] 所有测试通过

---

## 第三阶段：代码现代化（1天）

**目标**: 使用 match 表达式和命名参数
**预估工作量**: 4-7 小时
**风险等级**: 🟢 低

### 3.1 Match 表达式替代 Switch（3-4h）⭐⭐⭐

#### 影响文件：9 个支付控制器

| # | 文件路径 | switch 数量 |
|---|---------|------------|
| 1 | `app/Http/Controllers/Pay/AlipayController.php` | 1 个（3 case） |
| 2 | `app/Http/Controllers/Pay/WepayController.php` | 1 个 |
| 3 | `app/Http/Controllers/Pay/PayjsController.php` | 1 个 |
| 4 | `app/Http/Controllers/Pay/VpayController.php` | 1 个 |
| 5 | `app/Http/Controllers/Pay/MapayController.php` | 1 个 |
| 6 | `app/Http/Controllers/Pay/PaysapiController.php` | 1 个 |
| 7 | `app/Http/Controllers/Pay/YipayController.php` | 1 个 |
| 8 | `app/Http/Controllers/Pay/StripeController.php` | 复杂 HTML |
| 9 | `app/Listeners/OrderUpdated.php` | 已在第二阶段完成 ✅ |

#### 重构策略

由于支付控制器的 switch 语句包含复杂的逻辑（try-catch、视图渲染等），建议采用**提取方法**的方式，而不是简单的 match 表达式。

##### 示例：`app/Http/Controllers/Pay/AlipayController.php`

**当前代码** (第 41-74 行):
```php
switch ($payway) {
    case 'zfbf2f':
    case 'alipayscan':
        try {
            $result = Pay::alipay($config)->scan($order)->toArray();
            // ... 10 行代码
            return $this->render('static_pages/qrpay', $result, __('dujiaoka.scan_qrcode_to_pay'));
        } catch (\Exception $e) {
            return $this->err(__('dujiaoka.prompt.abnormal_payment_channel').$e->getMessage());
        }
    case 'aliweb':
        try {
            $result = Pay::alipay($config)->web($order);
            return $result;
        } catch (\Exception $e) {
            return $this->err(__('dujiaoka.prompt.abnormal_payment_channel').$e->getMessage());
        }
    case 'aliwap':
        // ...
}
```

**重构为**:
```php
// gateway() 方法简化
public function gateway(string $payway, string $orderSN)
{
    try {
        $this->loadGateWay($orderSN, $payway);
        $config = $this->buildConfig();
        $order = $this->buildOrder();

        return match($payway) {
            'zfbf2f', 'alipayscan' => $this->handleScanPayment($config, $order),
            'aliweb' => $this->handleWebPayment($config, $order),
            'aliwap' => $this->handleWapPayment($config, $order),
            default => throw new RuleValidationException(__('dujiaoka.prompt.unsupported_payment_method')),
        };
    } catch (RuleValidationException $exception) {
        return $this->err($exception->getMessage());
    }
}

// 提取的私有方法
private function buildConfig(): array
{
    return [
        'app_id' => $this->payGateway->merchant_id,
        'ali_public_key' => $this->payGateway->merchant_key,
        'private_key' => $this->payGateway->merchant_pem,
        'notify_url' => url($this->payGateway->pay_handleroute.'/notify_url'),
        'return_url' => url('detail-order-sn', ['orderSN' => $this->order->order_sn]),
        'http' => [
            'timeout' => 10.0,
            'connect_timeout' => 10.0,
        ],
    ];
}

private function buildOrder(): array
{
    return [
        'out_trade_no' => $this->order->order_sn,
        'total_amount' => (float) $this->order->actual_price,
        'subject' => $this->order->order_sn,
    ];
}

private function handleScanPayment(array $config, array $order)
{
    try {
        $result = Pay::alipay($config)->scan($order)->toArray();
        $result['payname'] = $this->order->order_sn;
        $result['actual_price'] = (float) $this->order->actual_price;
        $result['orderid'] = $this->order->order_sn;
        $result['jump_payuri'] = $result['qr_code'];

        return $this->render('static_pages/qrpay', $result, __('dujiaoka.scan_qrcode_to_pay'));
    } catch (\Exception $e) {
        return $this->err(__('dujiaoka.prompt.abnormal_payment_channel').$e->getMessage());
    }
}

private function handleWebPayment(array $config, array $order)
{
    try {
        return Pay::alipay($config)->web($order);
    } catch (\Exception $e) {
        return $this->err(__('dujiaoka.prompt.abnormal_payment_channel').$e->getMessage());
    }
}

private function handleWapPayment(array $config, array $order)
{
    try {
        return Pay::alipay($config)->wap($order);
    } catch (\Exception $e) {
        return $this->err(__('dujiaoka.prompt.abnormal_payment_channel').$e->getMessage());
    }
}
```

**优势**:
- ✅ 主方法更简洁（使用 match）
- ✅ 每个支付方式独立方法（易测试）
- ✅ 配置构建提取为方法（可复用）
- ✅ 更符合单一职责原则

**检查点**:
- [ ] 提取 `buildConfig()` 方法
- [ ] 提取 `buildOrder()` 方法
- [ ] 提取各支付方式处理方法
- [ ] 主方法使用 match
- [ ] 测试支付流程正常

---

**其他支付控制器**:
按照相同的模式重构其他支付控制器。每个控制器的具体实现可能略有不同，但核心思路相同：
1. 提取配置构建方法
2. 提取订单数据构建方法
3. 每个支付方式一个独立方法
4. 主方法使用 match 调度

---

### 3.2 命名参数优化（1-2h）⭐⭐

#### 适用场景

查找并优化以下类型的方法调用：

##### A. 布尔参数

**文件**: 多处使用 `picture_ulr()` 的地方

```php
// Before
$url = picture_ulr($file, true);

// After
$url = picture_ulr(file: $file, getHost: true);
```

---

##### B. bccomp 调用

**文件**: `app/Http/Controllers/PayController.php` 等

```php
// Before
$bccomp = bccomp((string) $this->order->actual_price, '0.00', 2);

// After
$bccomp = bccomp(
    num1: (string) $this->order->actual_price,
    num2: '0.00',
    scale: 2
);
```

---

##### C. Job dispatch 调用

```php
// Before
OrderExpired::dispatch($order->order_sn)->delay(Carbon::now()->addMinutes($expiredOrderDate));

// After
OrderExpired::dispatch(orderSN: $order->order_sn)
    ->delay(Carbon::now()->addMinutes($expiredOrderDate));
```

---

### 3.3 Null 安全操作符（0.5-1h）⭐⭐

查找可以使用 `?->` 的地方：

**文件**: `app/Listeners/OrderUpdated.php` 已在第二阶段更新

其他潜在位置：
```php
// Before
if ($user) {
    return $user->name;
}

// After
return $user?->name;

// Before
$result = Emailtpl::query()->where('tpl_token', 'pending_order')->first();
if ($result) {
    $data = $result->toArray();
}

// After
$data = Emailtpl::query()
    ->where('tpl_token', 'pending_order')
    ->first()
    ?->toArray();
```

---

### 第三阶段完成检查

```bash
# 1. 格式化代码
vendor/bin/pint

# 2. 运行所有测试
php artisan test

# 3. 重点测试支付流程
# - 支付宝扫码支付
# - 支付宝网页支付
# - 支付宝手机支付
# - 微信支付
# - 其他支付网关

# 4. 代码审查
# - 检查 match 表达式逻辑
# - 检查方法提取是否正确
# - 检查命名参数可读性
```

**检查清单**:
- [ ] 所有支付控制器重构完成
- [ ] Match 表达式正确
- [ ] 方法提取合理
- [ ] 支付流程测试通过
- [ ] 命名参数提升可读性
- [ ] 代码格式化通过
- [ ] 所有测试通过

---

## 测试检查清单

### 自动化测试

```bash
# 运行所有测试
php artisan test

# 运行特定测试
php artisan test --filter=OrderTest
php artisan test --filter=PaymentTest

# 代码格式检查
vendor/bin/pint --test

# 代码格式修复
vendor/bin/pint
```

### 手动测试清单

#### 订单流程
- [ ] 创建订单（正常商品）
- [ ] 创建订单（批发商品）
- [ ] 创建订单（使用优惠券）
- [ ] 订单支付（支付宝）
- [ ] 订单支付（微信）
- [ ] 订单过期
- [ ] 订单完成
- [ ] 订单失败

#### 邮件通知
- [ ] 待处理订单邮件
- [ ] 订单完成邮件
- [ ] 订单失败邮件
- [ ] 发货邮件

#### 后台管理
- [ ] 订单列表显示
- [ ] 订单状态筛选
- [ ] 订单状态显示（颜色、图标）
- [ ] 订单编辑
- [ ] 商品列表显示
- [ ] 商品编辑
- [ ] 卡密管理
- [ ] 优惠券管理

#### 支付网关
- [ ] 支付宝扫码支付
- [ ] 支付宝网页支付
- [ ] 支付宝手机支付
- [ ] 微信扫码支付
- [ ] PayPal 支付
- [ ] Stripe 支付

---

## 回滚计划

### Git 分支策略

```bash
# 创建功能分支
git checkout -b feature/modernization-phase1
git checkout -b feature/modernization-phase2
git checkout -b feature/modernization-phase3

# 合并到主分支
git checkout main
git merge feature/modernization-phase1
```

### 回滚步骤

如果某个阶段出现问题：

```bash
# 1. 检查当前状态
git status

# 2. 撤销未提交的更改
git restore .

# 3. 回滚到上一个提交
git reset --hard HEAD~1

# 4. 如果已经推送，创建回滚提交
git revert HEAD
```

### 数据库备份

在每个阶段开始前：

```bash
# 备份数据库
php artisan db:backup

# 或使用 mysqldump
mysqldump -u root -p dujiaoka > backup_$(date +%Y%m%d_%H%M%S).sql
```

---

## 风险提示

### 高风险操作

1. **Enum 迁移** - 可能影响现有数据
   - 缓解：保留常量，渐进式迁移
   - 测试：完整的集成测试

2. **支付控制器重构** - 可能影响支付流程
   - 缓解：重点测试所有支付网关
   - 测试：真实支付环境测试

3. **类型声明** - 可能导致运行时错误
   - 缓解：完整的类型覆盖
   - 测试：边界条件测试

### 中风险操作

1. **构造器属性提升** - 可能影响依赖注入
   - 缓解：逐个类测试
   - 测试：单元测试和集成测试

2. **Match 表达式** - 行为可能与 switch 不同
   - 缓解：保持逻辑一致
   - 测试：对比测试

### 低风险操作

1. **模型 casts** - Laravel 标准功能
2. **Readonly 属性** - PHP 8.1 稳定特性
3. **命名参数** - 仅影响可读性

---

## 完成标准

### 代码质量指标

- [ ] Pint 格式检查通过（0 错误）
- [ ] PHPStan/Larastan 静态分析通过（Level 5+）
- [ ] 测试覆盖率 > 80%
- [ ] 所有测试通过（0 失败）

### 功能验收标准

- [ ] 所有现有功能正常
- [ ] Filament 后台正常显示
- [ ] 订单创建流程正常
- [ ] 支付流程正常（至少测试 3 个支付网关）
- [ ] 邮件通知正常
- [ ] 队列任务正常

### 文档更新

- [ ] 更新 `CLAUDE.md` - 添加 Enum 使用说明
- [ ] 更新 `README.md` - 如需要
- [ ] 创建 `docs/ENUMS.md` - Enum 使用文档
- [ ] 更新 `CHANGELOG.md` - 记录现代化升级

---

## 后续优化建议

完成三个阶段后，还可以考虑：

1. **提取支付网关基类** - 减少重复代码
2. **优化服务层架构** - 使用接口和抽象类
3. **添加更多业务 Enum** - 如通知类型等
4. **性能优化** - 使用缓存、延迟加载
5. **安全加固** - 添加更多验证和授权

---

## 支持和帮助

如果在迁移过程中遇到问题：

1. 查看 Laravel 12 文档
2. 查看 Filament 4 文档
3. 查看 PHP 8.2 文档
4. Git 提交历史和 diff
5. 运行测试查看具体错误

---

**最后更新**: 2025-11-02
**维护者**: AI Assistant
**状态**: 待执行

---

## 快速开始

从第一阶段开始：

```bash
# 1. 创建功能分支
git checkout -b feature/modernization-phase1

# 2. 开始修改第一个文件
# app/Models/Order.php - 添加 casts() 方法

# 3. 测试
php artisan test

# 4. 提交
git add .
git commit -m "feat(models): 添加 Order 模型 casts() 方法"

# 5. 继续下一个文件...
```

祝迁移顺利！🚀
