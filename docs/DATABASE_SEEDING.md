# 数据库初始化说明

本文档说明独角数卡 Laravel 12 版本的数据库初始化（Seeding）功能。

## 概述

系统提供了两个 Seeder 来初始化必要的基础数据：
- **EmailTemplateSeeder** - 初始化 5 个邮件模板
- **PaySeeder** - 初始化 34 个支付网关配置

## 使用方法

### 新系统安装时

运行迁移时自动初始化数据（推荐）：
```bash
php artisan migrate --seed
```

### 单独运行 Seeders

初始化所有数据：
```bash
php artisan db:seed
```

仅初始化邮件模板：
```bash
php artisan db:seed --class=EmailTemplateSeeder
```

仅初始化支付网关：
```bash
php artisan db:seed --class=PaySeeder
```

## EmailTemplateSeeder

### 功能说明

初始化 5 个预定义的邮件模板，用于系统的各种邮件通知场景。

### 初始化的模板

| ID | Token | 模板名称 | 用途 |
|----|-------|---------|------|
| 2 | `card_send_user_email` | 发货邮件模板 | 订单完成后发送卡密给用户 |
| 3 | `manual_send_manage_mail` | 人工处理订单通知 | 需要人工处理时通知管理员 |
| 4 | `failed_order` | 订单异常通知 | 订单处理失败时的通知 |
| 5 | `completed_order` | 订单完成通知 | 订单完成的通知 |
| 6 | `pending_order` | 待处理订单通知 | 订单待处理时的通知 |

### 模板特性

- 现代化 HTML 邮件设计
- 响应式布局，适配各种邮件客户端
- 渐变色背景，视觉效果美观
- 支持变量替换：
  - `{webname}` - 网站名称
  - `{title}` - 邮件标题
  - `{order_id}` - 订单号
  - `{order_title}` - 商品名称
  - `{order_price}` - 订单金额
  - `{ord_info}` - 订单详情/卡密信息
  - `{buy_amount}` - 购买数量
  - `{completed_at}` - 完成时间
  - `{created_at}` - 创建时间

### 幂等性

使用 `updateOrCreate()` 方法，基于 `tpl_token` 字段：
- 可以安全地重复运行
- 不会产生重复数据
- 会更新已存在的模板

## PaySeeder

### 功能说明

初始化 34 个支付网关配置，覆盖主流的国内外支付方式和加密货币支付。

### 支付网关列表

#### 传统支付（1-20）

**支付宝（2个）**
1. 支付宝当面付 (`zfbf2f`) - 扫码支付
2. 支付宝 PC (`aliweb`) - PC 网页支付

**码支付（3个）**
3. 码支付 QQ (`mqq`)
4. 码支付支付宝 (`mzfb`)
5. 码支付微信 (`mwx`)

**Paysapi（2个）**
6. Paysapi 支付宝 (`pszfb`)
7. Paysapi 微信 (`pswx`)

**微信支付（1个）**
8. 微信扫码 (`wescan`)

**Payjs（1个）**
11. Payjs 微信扫码 (`payjswescan`)

**易支付（3个）**
14. 易支付-支付宝 (`alipay`)
15. 易支付-微信 (`wxpay`)
16. 易支付-QQ 钱包 (`qqpay`)

**PayPal（1个）**
17. PayPal (`paypal`)

**V免签（2个）**
19. V 免签支付宝 (`vzfb`)
20. V 免签微信 (`vwx`)

#### 国际支付和加密货币（21-34）

**Stripe（1个）**
21. Stripe[微信支付宝] (`stripe`)

**Coinbase（1个）**
22. Coinbase[加密货币] (`coinbase`) - 默认关闭

**Epusdt（1个）**
23. Epusdt[trc20] (`epusdt`) - 默认关闭

**TokenPay 系列（11个）**
24. TRX (`tokenpay-trx`)
25. USDT-TRC20 (`tokenpay-usdt-trc`)
26. ETH (`tokenpay-eth`)
27. USDT-ERC20 (`tokenpay-usdt-eth`)
28. USDC-ERC20 (`tokenpay-usdc-eth`)
29. BNB (`tokenpay-bnb`)
30. USDT-BSC (`tokenpay-usdt-bsc`)
31. USDC-BSC (`tokenpay-usdc-bsc`)
32. MATIC (`tokenpay-matic`)
33. USDT-Polygon (`tokenpay-usdt-polygon`)
34. USDC-Polygon (`tokenpay-usdc-polygon`)

### 支付方式分类

| 分类 | 说明 | 数量 |
|------|------|------|
| **跳转支付** (`pay_method=1`) | 跳转到第三方页面完成支付 | 32个 |
| **扫码支付** (`pay_method=2`) | 显示二维码扫码支付 | 2个 |

| 支付场景 | 说明 | 数量 |
|---------|------|------|
| **PC端** (`pay_client=1`) | 仅支持电脑端 | 20个 |
| **手机端** (`pay_client=2`) | 仅支持移动端 | 0个 |
| **全平台** (`pay_client=3`) | PC和移动端都支持 | 14个 |

### 配置说明

所有支付网关初始化时使用**占位符配置**，需要管理员在 Filament 后台进行实际配置：

- `merchant_id` - 商户号/API 标识
- `merchant_key` - API 密钥/公钥（某些网关不需要）
- `merchant_pem` - 私钥/密钥/API 地址

### 启用状态

- **默认启用** (`is_open=1`): 大部分支付网关（32个）
- **默认关闭** (`is_open=0`): Coinbase、Epusdt（2个）
- **特殊状态** (`is_open=2`): 易支付-支付宝（1个）

### 幂等性

使用 `updateOrCreate()` 方法，基于 `pay_check` 字段：
- 可以安全地重复运行
- 不会产生重复数据
- 会更新已存在的配置

## 老系统升级

如果您是从 Laravel 6 版本升级而来：

### 自动处理

升级命令 `php artisan dujiaoka:upgrade` 会自动迁移：
- ✅ `emailtpls` 表的数据（包括自定义模板）
- ✅ `pays` 表的数据（包括已配置的支付网关）

### 手动处理

如果您希望使用新的默认模板，可以：

1. 备份现有数据：
```bash
# 备份邮件模板
php artisan tinker
>>> DB::table('emailtpls')->get()->toJson();

# 备份支付配置
>>> DB::table('pays')->get()->toJson();
```

2. 运行 Seeders 重置为默认配置：
```bash
php artisan db:seed --class=EmailTemplateSeeder
php artisan db:seed --class=PaySeeder
```

## 后续配置

### 配置邮件模板

1. 登录 Filament 后台: `/admin`
2. 进入 **邮件模板** 管理
3. 编辑模板内容，自定义变量替换

### 配置支付网关

1. 登录 Filament 后台: `/admin`
2. 进入 **支付方式** 管理
3. 选择需要使用的支付网关
4. 填入真实的商户信息和密钥
5. 启用该支付网关

## 技术细节

### DatabaseSeeder 配置

`database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    // Seed email templates and payment gateways
    $this->call([
        EmailTemplateSeeder::class,
        PaySeeder::class,
    ]);

    // ... other seeders
}
```

### Seeder 源码位置

- EmailTemplateSeeder: `database/seeders/EmailTemplateSeeder.php`
- PaySeeder: `database/seeders/PaySeeder.php`

### 数据表结构

**emailtpls 表:**
- `id` - 主键
- `tpl_token` - 模板标识（唯一）
- `tpl_name` - 模板名称
- `tpl_content` - 模板内容（HTML）

**pays 表:**
- `id` - 主键
- `pay_name` - 支付名称
- `pay_check` - 支付标识（唯一）
- `pay_method` - 支付方式（1跳转/2扫码）
- `pay_client` - 支付场景（1PC/2手机/3全部）
- `merchant_id` - 商户号
- `merchant_key` - 商户密钥
- `merchant_pem` - 商户私钥/密钥
- `pay_handleroute` - 支付处理路由
- `is_open` - 是否启用

## 常见问题

### Q: Seeder 会覆盖我已有的配置吗？

A: 不会。Seeder 使用 `updateOrCreate()` 方法：
- 如果记录不存在，会创建新记录
- 如果记录已存在（基于 `tpl_token` 或 `pay_check`），会更新该记录
- 建议在首次安装时运行，后续谨慎使用

### Q: 我可以只初始化部分数据吗？

A: 可以。使用 `--class` 参数指定特定的 Seeder：
```bash
php artisan db:seed --class=EmailTemplateSeeder  # 只初始化邮件模板
php artisan db:seed --class=PaySeeder           # 只初始化支付网关
```

### Q: 如何恢复默认配置？

A: 重新运行对应的 Seeder 即可恢复默认配置。但建议先备份当前数据。

### Q: 我添加了自定义邮件模板，运行 Seeder 会被删除吗？

A: 不会。Seeder 只会更新它管理的 5 个模板（ID: 2-6），不会影响其他模板。

### Q: 我可以禁用某些支付网关吗？

A: 可以。在 Filament 后台的 **支付方式** 管理中，将 `is_open` 设置为 `0` 即可禁用。

## 相关文档

- [主 README](../README.md)
- [升级指南](UPGRADE_GUIDE.md)
- [部署指南](DEPLOYMENT_GUIDE.md)
- [CLAUDE 开发指南](../CLAUDE.md)
