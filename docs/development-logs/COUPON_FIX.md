# 优惠券模块错误修复报告

**修复日期**: 2025-11-01
**错误类型**: Undefined constant
**影响范围**: 优惠券列表页面

---

## 🐛 错误描述

### 错误信息
```
Undefined constant App\Models\Coupon::TYPE_FIXED_AMOUNT
```

### 错误位置
- **文件**: `app/Filament/Resources/Coupons/Tables/CouponsTable.php:82`
- **页面**: `/admin/coupons`

### 根本原因

代码中使用了不存在的常量 `TYPE_FIXED_AMOUNT` 和 `TYPE_PERCENTAGE`，这些常量在 Coupon 模型中并未定义。

经过检查老系统的数据库结构发现：
- ✅ 老系统的 `coupons` 表**没有** `type` 字段
- ✅ 老系统只有 `discount`（优惠金额）字段
- ✅ 老系统使用 `is_use` 字段表示使用状态

---

## ✅ 修复方案

### 1. 数据库结构对齐

**老系统 coupons 表结构**:
```sql
CREATE TABLE `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `is_use` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否已经使用 1未使用 2已使用',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用 1是 0否',
  `coupon` varchar(150) COMMENT '优惠码',
  `ret` int NOT NULL DEFAULT '0' COMMENT '剩余使用次数',
  ...
)
```

**关键字段**:
- `discount` - 优惠金额（decimal）
- `is_use` - 使用状态（1=未使用, 2=已使用）
- `is_open` - 启用状态（1=启用, 0=禁用）
- `ret` - 剩余使用次数（0=无限制）

---

## 📝 修改的文件

### 1. Coupon 模型 ✅

**文件**: `app/Models/Coupon.php`

**修改内容**:
- ❌ 移除 `TYPE_ONE_TIME` 常量
- ❌ 移除 `TYPE_REPEAT` 常量
- ❌ 移除 `TYPE_FIXED_AMOUNT` 常量
- ❌ 移除 `TYPE_PERCENTAGE` 常量
- ✅ 保留 `STATUS_UNUSED = 1` 常量
- ✅ 保留 `STATUS_USE = 2` 常量
- ✅ 更新 `$fillable` 字段列表

**修改后**:
```php
protected $fillable = [
    'coupon',
    'discount',
    'is_use',
    'ret',
    'is_open',
];

const STATUS_UNUSED = 1;
const STATUS_USE = 2;
```

---

### 2. CouponsTable 表格配置 ✅

**文件**: `app/Filament/Resources/Coupons/Tables/CouponsTable.php`

**修改内容**:

#### 移除的字段
- ❌ `type` 列（类型）
- ❌ `used` 列（已使用次数）

#### 新增/修改的字段
- ✅ `discount` - 显示为货币格式（CNY）
- ✅ `is_use` - 使用状态徽章（未使用/已使用）
- ✅ `ret` - 剩余次数（0显示为"无限"）

**修改后的列配置**:
```php
TextColumn::make('coupon')
    ->label('优惠券码')
    ->searchable()
    ->copyable(),

TextColumn::make('discount')
    ->label('优惠金额')
    ->money('CNY')
    ->sortable(),

TextColumn::make('is_use')
    ->label('使用状态')
    ->badge()
    ->color(fn (int $state): string => match ($state) {
        Coupon::STATUS_UNUSED => 'success',
        Coupon::STATUS_USE => 'danger',
        default => 'gray',
    })
    ->formatStateUsing(fn (int $state): string => match ($state) {
        Coupon::STATUS_UNUSED => '未使用',
        Coupon::STATUS_USE => '已使用',
        default => '未知',
    }),

TextColumn::make('ret')
    ->label('剩余次数')
    ->sortable()
    ->badge()
    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger')
    ->formatStateUsing(fn (int $state): string => $state == 0 ? '无限' : (string) $state),
```

#### 筛选器更新
```php
SelectFilter::make('is_use')
    ->label('使用状态')
    ->options([
        Coupon::STATUS_UNUSED => '未使用',
        Coupon::STATUS_USE => '已使用',
    ]),

SelectFilter::make('is_open')
    ->label('启用状态')
    ->options([
        1 => '启用',
        0 => '禁用',
    ]),
```

---

### 3. CouponForm 表单配置 ✅

**文件**: `app/Filament/Resources/Coupons/Schemas/CouponForm.php`

**修改内容**:

#### 移除的字段
- ❌ `type` - 优惠券类型（Radio 组件）
- ❌ `used` - 已使用次数

#### 保留/新增的字段
- ✅ `coupon` - 优惠券码（TextInput, 最大150字符）
- ✅ `discount` - 优惠金额（TextInput, 货币格式）
- ✅ `ret` - 剩余使用次数（TextInput, 数字）
- ✅ `is_open` - 是否启用（Toggle）
- ✅ `goods` - 关联商品（Select, 多选）

**修改后的表单**:
```php
Section::make('优惠券基本信息')
    ->schema([
        TextInput::make('coupon')
            ->label('优惠券码')
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(150)
            ->helperText('优惠券的唯一标识码'),

        TextInput::make('discount')
            ->label('优惠金额')
            ->numeric()
            ->required()
            ->prefix('¥')
            ->step(0.01)
            ->helperText('减免的金额'),

        TextInput::make('ret')
            ->label('剩余使用次数')
            ->numeric()
            ->default(0)
            ->helperText('0表示无限制使用'),

        Toggle::make('is_open')
            ->label('是否启用')
            ->default(true)
            ->inline(false),
    ])
    ->columns(2),

Section::make('关联商品')
    ->schema([
        Select::make('goods')
            ->label('适用商品')
            ->relationship('goods', 'gd_name')
            ->multiple()
            ->preload()
            ->searchable()
            ->helperText('不选择则适用于所有商品'),
    ]),
```

---

## 🎯 修复效果

### 修复前
- ❌ 访问 `/admin/coupons` 页面报 500 错误
- ❌ Undefined constant 错误
- ❌ 无法查看优惠券列表

### 修复后
- ✅ 页面正常加载
- ✅ 可以查看优惠券列表
- ✅ 可以创建新优惠券
- ✅ 可以编辑现有优惠券
- ✅ 字段符合老系统数据库结构
- ✅ 显示格式更加直观（货币格式、状态徽章等）

---

## 📊 字段对比

| 字段名 | 老系统 | 新系统（修复前） | 新系统（修复后） | 说明 |
|--------|--------|-----------------|-----------------|------|
| coupon | ✅ | ✅ | ✅ | 优惠券码 |
| discount | ✅ | ✅ | ✅ | 优惠金额 |
| is_use | ✅ | ❌ | ✅ | 使用状态 |
| is_open | ✅ | ✅ | ✅ | 启用状态 |
| ret | ✅ | ✅ | ✅ | 剩余次数 |
| type | ❌ | ✅ | ❌ | **不存在**（已移除） |
| used | ❌ | ✅ | ❌ | **不存在**（已移除） |

---

## ✅ 验证清单

- [x] 优惠券列表页面正常显示
- [x] 可以查看优惠券详情
- [x] 可以创建新优惠券
- [x] 可以编辑优惠券
- [x] 筛选器正常工作
- [x] 搜索功能正常
- [x] 排序功能正常
- [x] 批量删除功能正常
- [x] 软删除功能正常
- [x] 关联商品功能正常
- [x] 代码通过 Laravel Pint 格式化
- [x] 缓存已清除

---

## 🔍 其他发现的问题

### 1. 数据库迁移文件缺少 `pv` 字段

虽然在 Model 的原始 `$fillable` 中有 `pv` 字段，但数据库迁移文件和老系统都没有这个字段。

**处理**: 已从 Model 中移除

---

## 💡 改进建议

### 短期
1. ✅ 已修复：对齐老系统数据库结构
2. ✅ 已实现：使用货币格式显示优惠金额
3. ✅ 已实现：使用徽章显示状态

### 中期
1. 考虑添加优惠券使用历史记录
2. 添加优惠券批量生成功能
3. 添加优惠券有效期字段

### 长期
1. 支持百分比折扣（需要修改数据库）
2. 支持满减条件（需要新增字段）
3. 优惠券使用统计和报表

---

## 📚 相关文档

- [Filament 中文化配置](FILAMENT_LOCALIZATION.md)
- [数据库迁移文档](../database/migrations/)
- [Coupon 模型](../app/Models/Coupon.php)

---

## 🔄 更新历史

| 日期 | 问题 | 修复 | 状态 |
|------|------|------|------|
| 2025-11-01 | Undefined constant 错误 | 移除不存在的常量，对齐数据库结构 | ✅ 完成 |

---

**修复完成日期**: 2025-11-01
**修复人员**: AI Assistant
**状态**: ✅ 完成并验证
**代码格式**: ✅ 通过 Laravel Pint
