# 现代化迁移文件清单（简化版）

> 快速参考：所有需要修改的文件列表

---

## 📊 统计总览

- **影响文件总数**: 47 个
- **新增文件**: 9 个（8 个 Enum + 1 个 Trait）
- **修改文件**: 38 个
- **预估总行数**: ~1000 行

---

## 🗂️ 按文件类型分类

### 新增文件（9 个）

```
app/Enums/
├── Concerns/
│   └── HasOptions.php                    # 新建
├── OrderStatus.php                       # 新建
├── CouponBackStatus.php                  # 新建
├── CarmisStatus.php                      # 新建
├── CouponStatus.php                      # 新建
├── PayMethod.php                         # 新建
├── PayClient.php                         # 新建
├── CommonStatus.php                      # 新建
└── GoodsType.php                         # 新建
```

### 模型文件（6 个）

```
app/Models/
├── Order.php                             # 修改：添加 casts()，使用 Enum
├── Goods.php                             # 修改：添加 casts()，使用 Enum
├── Carmis.php                            # 修改：添加 casts()，使用 Enum
├── Coupon.php                            # 修改：添加 casts()，使用 Enum
├── Pay.php                               # 修改：添加 casts()，使用 Enum
└── BaseModel.php                         # 修改：常量标记 @deprecated
```

### 服务文件（5 个）

```
app/Service/
├── OrderService.php                      # 修改：构造器属性提升，使用 Enum
├── OrderProcessService.php               # 修改：构造器属性提升，类型声明，使用 Enum
├── GoodsService.php                      # 检查：依赖注入
├── CarmisService.php                     # 修改：使用 Enum
└── CouponService.php                     # 检查：依赖注入
```

### 控制器文件（12 个）

```
app/Http/Controllers/
├── PayController.php                     # 已完成 ✅（构造器属性提升）
│                                         # 修改：使用 Enum
├── Home/
│   ├── OrderController.php               # 已完成 ✅（构造器属性提升）
│   └── HomeController.php                # 已完成 ✅（构造器属性提升）
└── Pay/
    ├── AlipayController.php              # 修改：match 表达式，提取方法
    ├── WepayController.php               # 修改：match 表达式，提取方法
    ├── PayjsController.php               # 修改：match 表达式，提取方法
    ├── VpayController.php                # 修改：match 表达式，提取方法
    ├── MapayController.php               # 修改：match 表达式，提取方法
    ├── PaysapiController.php             # 修改：match 表达式，提取方法
    ├── YipayController.php               # 修改：match 表达式，提取方法
    ├── StripeController.php              # 修改：match 表达式，提取方法
    └── CoinbaseController.php            # 修改：match 表达式，提取方法
```

### Job 文件（8 个）

```
app/Jobs/
├── MailSend.php                          # 修改：构造器属性提升，readonly
├── OrderExpired.php                      # 修改：构造器属性提升，readonly，使用 Enum
├── TelegramPush.php                      # 修改：构造器属性提升，readonly，方法注入
├── BarkPush.php                          # 修改：构造器属性提升，readonly
├── ServerJiang.php                       # 修改：构造器属性提升，readonly
├── WorkWeiXinPush.php                    # 修改：构造器属性提升，readonly
├── ApiHook.php                           # 修改：构造器属性提升，readonly
└── CouponBack.php                        # 修改：构造器属性提升，readonly，使用 Enum
```

### 事件文件（3 个）

```
app/Events/
├── OrderUpdated.php                      # 修改：构造器属性提升，readonly
├── GoodsDeleted.php                      # 修改：构造器属性提升，readonly
└── GoodsGroupDeleted.php                 # 修改：构造器属性提升，readonly
```

### 监听器文件（3 个）

```
app/Listeners/
├── OrderUpdated.php                      # 修改：使用 Enum，match 表达式，null 安全操作符
├── GoodsDeleted.php                      # 检查：可能需要类型声明
└── GoodsGroupDeleted.php                 # 检查：可能需要类型声明
```

### 辅助函数文件（1 个）

```
app/Helpers/
└── functions.php                         # 修改：完善类型声明（6 个函数）
```

---

## 📋 按阶段分类

### 第一阶段：快速见效（15 个文件）

**优先级**：⭐⭐⭐⭐⭐

#### 添加 casts() 方法（5 个）
```
✓ app/Models/Order.php
✓ app/Models/Goods.php
✓ app/Models/Carmis.php
✓ app/Models/Coupon.php
✓ app/Models/Pay.php
```

#### 构造器属性提升（7 个）
```
✓ app/Service/OrderService.php
✓ app/Service/OrderProcessService.php
✓ app/Jobs/MailSend.php
✓ app/Jobs/OrderExpired.php
✓ app/Jobs/TelegramPush.php
✓ app/Jobs/BarkPush.php
✓ app/Jobs/ServerJiang.php
✓ app/Jobs/WorkWeiXinPush.php (新增)
✓ app/Jobs/ApiHook.php (新增)
✓ app/Jobs/CouponBack.php (新增)
```

#### 添加 readonly（3 个）
```
✓ app/Events/OrderUpdated.php
✓ app/Events/GoodsDeleted.php
✓ app/Events/GoodsGroupDeleted.php
```

#### 类型声明（1 个）
```
✓ app/Helpers/functions.php
✓ app/Service/OrderProcessService.php (setter 方法)
```

---

### 第二阶段：核心架构（20 个文件）

**优先级**：⭐⭐⭐⭐

#### 创建 Enum（9 个新文件）
```
✓ app/Enums/Concerns/HasOptions.php
✓ app/Enums/OrderStatus.php
✓ app/Enums/CouponBackStatus.php
✓ app/Enums/CarmisStatus.php
✓ app/Enums/CouponStatus.php
✓ app/Enums/PayMethod.php
✓ app/Enums/PayClient.php
✓ app/Enums/CommonStatus.php
✓ app/Enums/GoodsType.php
```

#### 更新模型使用 Enum（6 个）
```
✓ app/Models/Order.php
✓ app/Models/Goods.php
✓ app/Models/Carmis.php
✓ app/Models/Coupon.php
✓ app/Models/Pay.php
✓ app/Models/BaseModel.php
```

#### 更新使用 Enum 的代码（约 15 个）
```
✓ app/Listeners/OrderUpdated.php
✓ app/Http/Controllers/PayController.php
✓ app/Service/OrderService.php
✓ app/Service/OrderProcessService.php
✓ app/Service/CarmisService.php
✓ app/Jobs/OrderExpired.php
✓ app/Jobs/CouponBack.php
... (其他使用常量的文件)
```

---

### 第三阶段：代码现代化（12 个文件）

**优先级**：⭐⭐⭐

#### Match 表达式（9 个）
```
✓ app/Http/Controllers/Pay/AlipayController.php
✓ app/Http/Controllers/Pay/WepayController.php
✓ app/Http/Controllers/Pay/PayjsController.php
✓ app/Http/Controllers/Pay/VpayController.php
✓ app/Http/Controllers/Pay/MapayController.php
✓ app/Http/Controllers/Pay/PaysapiController.php
✓ app/Http/Controllers/Pay/YipayController.php
✓ app/Http/Controllers/Pay/StripeController.php
✓ app/Http/Controllers/Pay/CoinbaseController.php
```

#### 命名参数（多个文件）
```
✓ app/Http/Controllers/PayController.php
✓ 所有调用 picture_ulr() 的文件
✓ 所有调用 bccomp() 的文件
```

---

## 🎯 修改优先级矩阵

| 文件 | 阶段 | 修改类型 | 工作量 | 优先级 | 风险 |
|------|------|---------|--------|--------|------|
| `app/Models/*.php` (5个) | 1 | 添加 casts | 0.5h | ⭐⭐⭐⭐⭐ | 低 |
| `app/Service/*.php` (2个) | 1 | 构造器属性 | 1h | ⭐⭐⭐⭐ | 低 |
| `app/Jobs/*.php` (8个) | 1 | 构造器属性 | 1.5h | ⭐⭐⭐⭐ | 低 |
| `app/Events/*.php` (3个) | 1 | readonly | 0.5h | ⭐⭐⭐ | 低 |
| `app/Helpers/functions.php` | 1 | 类型声明 | 1h | ⭐⭐⭐⭐ | 低 |
| `app/Enums/*.php` (9个新建) | 2 | 创建 Enum | 2-3h | ⭐⭐⭐⭐⭐ | 中 |
| `app/Models/*.php` (6个) | 2 | 使用 Enum | 1h | ⭐⭐⭐⭐⭐ | 中 |
| 使用常量的文件 (~15个) | 2 | 替换常量 | 2-3h | ⭐⭐⭐⭐ | 中 |
| `app/Http/Controllers/Pay/*.php` (9个) | 3 | match + 提取方法 | 3-4h | ⭐⭐⭐ | 低 |
| 多个文件 | 3 | 命名参数 | 1h | ⭐⭐ | 低 |

---

## 🔍 文件查找命令

### 查找所有使用常量的文件

```bash
# Order 常量
grep -r "Order::STATUS_" app/ --include="*.php"

# Carmis 常量
grep -r "Carmis::SOLD" app/ --include="*.php"

# BaseModel 常量
grep -r "BaseModel::" app/ --include="*.php"
```

### 查找所有 switch 语句

```bash
grep -r "switch\s*(" app/ --include="*.php"
```

### 查找所有 app() 调用

```bash
grep -r "app('Service" app/ --include="*.php"
```

### 查找所有缺少类型声明的函数

```bash
# 查找没有返回类型的函数
grep -rP "function\s+\w+\([^)]*\)\s*{" app/ --include="*.php"
```

---

## ✅ 检查清单模板

### 第一阶段检查

```markdown
## 第一阶段完成检查

- [ ] 5 个模型添加 casts() 方法
- [ ] 10 个类使用构造器属性提升
- [ ] 3 个 Event 添加 readonly
- [ ] 辅助函数添加类型声明
- [ ] 运行 `vendor/bin/pint` 通过
- [ ] 运行 `php artisan test` 通过
- [ ] Filament 后台显示正常
- [ ] 订单创建流程正常
```

### 第二阶段检查

```markdown
## 第二阶段完成检查

- [ ] 创建 9 个 Enum 类
- [ ] 6 个模型更新 casts 使用 Enum
- [ ] ~15 个文件替换常量为 Enum
- [ ] 运行 `vendor/bin/pint` 通过
- [ ] 运行 `php artisan test` 通过
- [ ] Filament 状态显示正确（颜色、图标）
- [ ] 订单状态变更正常
- [ ] 邮件通知正常
```

### 第三阶段检查

```markdown
## 第三阶段完成检查

- [ ] 9 个支付控制器使用 match
- [ ] 支付控制器提取方法完成
- [ ] 命名参数优化完成
- [ ] 运行 `vendor/bin/pint` 通过
- [ ] 运行 `php artisan test` 通过
- [ ] 支付宝支付正常
- [ ] 微信支付正常
- [ ] 其他支付网关正常
```

---

## 📝 代码片段参考

### Enum 基础结构

```php
<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Enums;

use App\Enums\Concerns\HasOptions;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum YourEnum: int implements HasLabel, HasColor
{
    use HasOptions;

    case Value1 = 1;
    case Value2 = 2;

    public function label(): string
    {
        return match($this) {
            self::Value1 => __('label.value1'),
            self::Value2 => __('label.value2'),
        };
    }

    public function getLabel(): ?string
    {
        return $this->label();
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Value1 => 'success',
            self::Value2 => 'warning',
        };
    }
}
```

### 构造器属性提升

```php
// Before
private $property;
public function __construct(Type $property) {
    $this->property = $property;
}

// After
public function __construct(
    private readonly Type $property,
) {}
```

### Model casts() 方法

```php
protected function casts(): array
{
    return [
        'status' => YourEnum::class,
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'count' => 'integer',
        'created_at' => 'datetime',
    ];
}
```

### Match 表达式

```php
// Before
switch ($value) {
    case 'a':
        return doA();
    case 'b':
        return doB();
    default:
        throw new Exception();
}

// After
return match($value) {
    'a' => doA(),
    'b' => doB(),
    default => throw new Exception(),
};
```

---

## 🚀 快速开始

```bash
# 1. 创建分支
git checkout -b feature/modernization-phase1

# 2. 开始第一个文件
# 编辑 app/Models/Order.php

# 3. 运行测试
php artisan test

# 4. 格式化
vendor/bin/pint

# 5. 提交
git add app/Models/Order.php
git commit -m "feat(models): add Order casts() method"
```

---

**生成日期**: 2025-11-02
**总文件数**: 47 个（新增 9 个，修改 38 个）
**预估工作量**: 18-28 小时

详细说明请查看：`docs/MODERNIZATION_CHECKLIST.md`
