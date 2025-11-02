# Filament 后台中文化配置

**更新日期**: 2025-11-01
**影响范围**: Filament 后台界面、导航菜单、图标

---

## 📋 更新内容概览

本次更新将 Filament 后台完全中文化，并根据业务特点优化了图标和菜单顺序。

### ✅ 完成的更新

1. **7个资源菜单中文化**
2. **业务图标优化**
3. **菜单顺序优化**
4. **品牌名称配置**
5. **语言包发布**
6. **Storage 链接创建**

---

## 🎯 资源菜单中文化

### 1. 商品分类 (GoodsGroupResource)

**文件**: `app/Filament/Resources/GoodsGroups/GoodsGroupResource.php`

```php
protected static ?string $navigationLabel = '商品分类';
protected static ?string $modelLabel = '商品分类';
protected static ?string $pluralModelLabel = '商品分类';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;
protected static ?int $navigationSort = 1;
```

**图标**: 📁 文件夹图标（OutlinedFolderOpen）
**排序**: 1（第一位）
**说明**: 用于管理商品的分类目录

---

### 2. 商品管理 (GoodsResource)

**文件**: `app/Filament/Resources/Goods/GoodsResource.php`

```php
protected static ?string $navigationLabel = '商品管理';
protected static ?string $modelLabel = '商品';
protected static ?string $pluralModelLabel = '商品';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
protected static ?int $navigationSort = 2;
```

**图标**: 🛍️ 购物袋图标（OutlinedShoppingBag）
**排序**: 2
**说明**: 商品的增删改查管理

---

### 3. 卡密管理 (CarmisResource)

**文件**: `app/Filament/Resources/Carmis/CarmisResource.php`

```php
protected static ?string $navigationLabel = '卡密管理';
protected static ?string $modelLabel = '卡密';
protected static ?string $pluralModelLabel = '卡密';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;
protected static ?int $navigationSort = 3;
```

**图标**: 🎫 票据图标（OutlinedTicket）
**排序**: 3
**说明**: 管理卡密库存，适合数字商品的卡密（激活码）

---

### 4. 订单管理 (OrderResource)

**文件**: `app/Filament/Resources/Orders/OrderResource.php`

```php
protected static ?string $navigationLabel = '订单管理';
protected static ?string $modelLabel = '订单';
protected static ?string $pluralModelLabel = '订单';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;
protected static ?int $navigationSort = 4;
```

**图标**: 🛒 购物车图标（OutlinedShoppingCart）
**排序**: 4
**说明**: 订单的全生命周期管理

---

### 5. 优惠券 (CouponResource)

**文件**: `app/Filament/Resources/Coupons/CouponResource.php`

```php
protected static ?string $navigationLabel = '优惠券';
protected static ?string $modelLabel = '优惠券';
protected static ?string $pluralModelLabel = '优惠券';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;
protected static ?int $navigationSort = 5;
```

**图标**: 🎁 礼物图标（OutlinedGift）
**排序**: 5
**说明**: 优惠券的创建、分配和使用管理

---

### 6. 支付方式 (PayResource)

**文件**: `app/Filament/Resources/Pays/PayResource.php`

```php
protected static ?string $navigationLabel = '支付方式';
protected static ?string $modelLabel = '支付方式';
protected static ?string $pluralModelLabel = '支付方式';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
protected static ?int $navigationSort = 6;
```

**图标**: 💳 信用卡图标（OutlinedCreditCard）
**排序**: 6
**说明**: 34个支付网关的配置管理

---

### 7. 邮件模板 (EmailtplResource)

**文件**: `app/Filament/Resources/Emailtpls/EmailtplResource.php`

```php
protected static ?string $navigationLabel = '邮件模板';
protected static ?string $modelLabel = '邮件模板';
protected static ?string $pluralModelLabel = '邮件模板';
protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
protected static ?int $navigationSort = 7;
```

**图标**: ✉️ 信封图标（OutlinedEnvelope）
**排序**: 7
**说明**: 邮件通知模板的编辑和管理

---

## 🎨 图标选择说明

### 图标设计原则

1. **语义化**: 图标与功能直接关联
2. **一致性**: 全部使用 Heroicon Outlined 风格
3. **辨识度**: 图标之间差异明显，易于区分

### 图标映射表

| 功能 | 图标名称 | 图标 | 业务含义 |
|------|---------|------|---------|
| 商品分类 | FolderOpen | 📁 | 分类目录结构 |
| 商品管理 | ShoppingBag | 🛍️ | 商品购物 |
| 卡密管理 | Ticket | 🎫 | 数字商品票据 |
| 订单管理 | ShoppingCart | 🛒 | 购物车/订单 |
| 优惠券 | Gift | 🎁 | 优惠礼物 |
| 支付方式 | CreditCard | 💳 | 支付卡片 |
| 邮件模板 | Envelope | ✉️ | 邮件信封 |

---

## 🏷️ 品牌配置

**文件**: `app/Providers/Filament/AdminPanelProvider.php`

```php
->brandName('独角数卡管理系统')
```

**显示位置**: Filament 后台左上角
**效果**: 替换默认的 "Dashboard" 文字

---

## 🌏 语言包配置

### 语言环境设置

**文件**: `config/app.php` / `.env`

```env
APP_LOCALE=zh_CN
APP_FALLBACK_LOCALE=en
```

### Filament 语言包

已发布 Filament 官方中文语言包：

```bash
php artisan vendor:publish --tag=filament-translations
```

**位置**: `resources/lang/vendor/filament/zh_CN/`

**包含的翻译**:
- 通用 UI 文本
- 表单验证消息
- 表格操作文本
- 导航菜单文本
- 等等...

---

## 📊 菜单顺序设计

菜单按照业务流程顺序排列：

1. **商品分类** (1) - 先设置分类
2. **商品管理** (2) - 再添加商品
3. **卡密管理** (3) - 为商品添加库存
4. **订单管理** (4) - 核心业务流程
5. **优惠券** (5) - 营销功能
6. **支付方式** (6) - 系统配置
7. **邮件模板** (7) - 通知配置

**排序规则**:
- 核心业务在前
- 配置项在后
- 符合用户使用习惯

---

## 🔧 其他配置

### Storage 链接

```bash
php artisan storage:link
```

**作用**: 创建 `public/storage` → `storage/app/public` 软链接
**用途**: 访问上传的商品图片、文件等

### 缓存清理

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**说明**: 更新配置后需要清除缓存使其生效

---

## ✅ 验证清单

完成配置后，请检查以下项目：

- [ ] 后台左上角显示"独角数卡管理系统"
- [ ] 左侧导航菜单全部为中文
- [ ] 7个资源菜单都有对应的图标
- [ ] 菜单顺序符合业务逻辑
- [ ] 表格、表单等 UI 元素显示中文
- [ ] 验证错误消息显示中文
- [ ] 图片上传和显示正常

---

## 📝 页面错误排查

### 常见问题

#### 1. 页面报 500 错误

**检查步骤**:
```bash
# 查看错误日志
tail -f storage/logs/laravel.log

# 清除缓存
php artisan config:clear
php artisan cache:clear
```

#### 2. 图片不显示

**解决方案**:
```bash
# 创建 storage 链接
php artisan storage:link

# 检查权限
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 3. 语言未生效

**检查配置**:
```bash
# 确认 .env 配置
grep APP_LOCALE .env

# 重新发布语言包
php artisan vendor:publish --tag=filament-translations --force
```

#### 4. 菜单图标不显示

**原因**: Heroicon 名称错误

**解决**: 参考 [Heroicons](https://heroicons.com/) 使用正确的图标名称

---

## 🎯 自定义扩展

### 添加新资源

创建新资源时，记得添加中文配置：

```php
class NewResource extends Resource
{
    protected static ?string $navigationLabel = '中文标签';
    protected static ?string $modelLabel = '模型名称';
    protected static ?string $pluralModelLabel = '复数名称';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;
    protected static ?int $navigationSort = 10;
}
```

### 自定义品牌名称

修改 `app/Providers/Filament/AdminPanelProvider.php`:

```php
->brandName('你的品牌名称')
```

### 修改主题颜色

```php
->colors([
    'primary' => Color::Amber,  // 可以改为其他颜色
])
```

可用颜色: Amber, Blue, Cyan, Emerald, Fuchsia, Gray, Green, Indigo, Lime, Orange, Pink, Purple, Red, Rose, Sky, Slate, Stone, Teal, Violet, Yellow, Zinc

---

## 📚 相关资源

- [Filament 官方文档](https://filamentphp.com/docs)
- [Heroicons 图标库](https://heroicons.com/)
- [Laravel 本地化文档](https://laravel.com/docs/12.x/localization)
- [项目主 README](../README.md)
- [CLAUDE 开发指南](../CLAUDE.md)

---

## 🔄 更新历史

| 日期 | 更新内容 | 影响范围 |
|------|---------|---------|
| 2025-11-01 | 初始中文化配置 | 7个资源、品牌名称、语言包 |

---

**配置完成日期**: 2025-11-01
**配置人员**: AI Assistant
**状态**: ✅ 完成
