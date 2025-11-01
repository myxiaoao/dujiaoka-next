# 更新日志

本文档记录独角数卡 Laravel 12 版本的重要更新和变更。

## [未发布] - 2025-11-01

### 新增功能

#### Filament 后台中文化
- ✨ 7个资源菜单完全中文化（商品分类、商品管理、卡密管理、订单管理、优惠券、支付方式、邮件模板）
- 🎨 根据业务优化图标选择（使用 Heroicon Outlined 系列）
- 📊 优化菜单排序（按业务流程排列）
- 🏷️ 配置品牌名称"独角数卡管理系统"
- 🌏 发布 Filament 中文语言包
- 🔗 创建 Storage 软链接

#### 数据库初始化系统
- ✨ 添加 `EmailTemplateSeeder` - 初始化 5 个邮件模板
  - 发货邮件模板
  - 人工处理订单通知
  - 订单异常通知
  - 订单完成通知
  - 待处理订单通知

- ✨ 添加 `PaySeeder` - 初始化 34 个支付网关配置
  - 支付宝（2个）
  - 码支付（3个）
  - Paysapi（2个）
  - 微信支付（1个）
  - Payjs（1个）
  - 易支付（3个）
  - PayPal（1个）
  - V免签（2个）
  - Stripe（1个）
  - Coinbase（1个）
  - Epusdt（1个）
  - TokenPay（11个加密货币）

#### 文档更新
- 📝 新增 `docs/DATABASE_SEEDING.md` - 完整的数据库初始化文档
- 📝 更新 `README.md` - 添加数据初始化步骤说明
- 📝 更新 `CLAUDE.md` - 添加 Seeder 使用指南
- 📝 更新 `docs/UPGRADE_GUIDE.md` - 添加新安装用户指引
- 📝 更新 `docs/README.md` - 添加新文档索引

### 改进

#### 代码质量
- 🔧 更新验证规则接口 - `Rule` → `ValidationRule` (Laravel 12)
  - `app/Rules/SearchPwd.php`
  - `app/Rules/VerifyImg.php`

- 🔧 现代化路由语法 - 字符串语法 → 数组语法
  - `routes/common/web.php` - 12个路由
  - `routes/common/pay.php` - 34个路由

- 🔧 完善模型安全性 - 添加 `$fillable` 属性
  - `app/Models/Goods.php`
  - `app/Models/GoodsGroup.php`
  - `app/Models/Order.php`
  - `app/Models/Carmis.php`
  - `app/Models/Coupon.php`
  - `app/Models/Pay.php`
  - `app/Models/Emailtpl.php`
  - `app/Models/BaseModel.php`

### 兼容性

- ✅ Laravel 12 完全兼容
- ✅ PHP 8.2+ 类型声明支持
- ✅ Filament 4 最佳实践

### 技术报告

- 📊 生成 `docs/LARAVEL12_COMPATIBILITY_REPORT.md`
- 📊 生成 `docs/FINAL_COMPATIBILITY_CHECK.md`

---

## [1.0.0] - 初始版本

### 主要功能

#### 核心系统
- ✅ Laravel 6 → Laravel 12 完整迁移
- ✅ dcat-admin 2.x → Filament 4 后台重构
- ✅ PHP 8.2+ 支持
- ✅ 数据库结构 100% 兼容

#### 功能模块
- ✅ 商品管理（分类、商品、库存、批发价）
- ✅ 订单管理（自动/人工发货）
- ✅ 卡密管理（导入、循环使用）
- ✅ 优惠券系统
- ✅ 支付网关（初始 12 个控制器）
- ✅ 邮件通知系统
- ✅ 数据统计 Dashboard

#### Filament 后台资源
- ✅ GoodsGroupResource - 商品分类管理
- ✅ GoodsResource - 商品管理
- ✅ CarmisResource - 卡密管理
- ✅ CouponResource - 优惠券管理
- ✅ OrderResource - 订单管理
- ✅ PayResource - 支付方式管理
- ✅ EmailtplResource - 邮件模板管理

#### 升级工具
- 🔧 `php artisan dujiaoka:upgrade` - 自动化升级命令
  - 数据库连接验证
  - 自动数据备份
  - 7个表数据迁移
  - 文件资产复制
  - 完整性验证

#### 服务层
- OrderService - 订单验证和创建
- OrderProcessService - 订单生命周期管理
- GoodsService - 商品库存管理
- CarmisService - 卡密处理
- CouponService - 优惠券验证
- PayService - 支付网关集成

#### 队列任务
- MailSend - 邮件发送
- OrderExpired - 订单过期处理
- TelegramPush - Telegram 通知
- ServerJiang - Server酱通知
- BarkPush - Bark 推送
- WorkWeiXinPush - 企业微信推送
- ApiHook - Webhook 回调
- CouponBack - 优惠券退回

#### 中间件
- DujiaoBoot - 系统初始化
- InstallCheck - 安装检查
- PayGateWay - 支付网关验证
- DujiaoSystem - 系统级检查

#### PayPal 集成
- 🔄 升级到 `paypal/paypal-server-sdk:1.1.0`
- ✅ PaypalPayController 完整实现
- ✅ 订单创建、支付、回调流程

### 文档
- 📝 完整升级指南
- 📝 快速升级指南
- 📝 迁移总结
- 📝 部署指南
- 📝 测试清单
- 📝 迁移验证报告
- 📝 兼容性报告

---

## 版本说明

### 版本号规范
本项目遵循 [语义化版本](https://semver.org/lang/zh-CN/) 规范：

- **主版本号**：不兼容的 API 修改
- **次版本号**：向下兼容的功能性新增
- **修订号**：向下兼容的问题修正

### 标签说明
- ✨ 新功能
- 🔧 改进/优化
- 🐛 Bug 修复
- 📝 文档更新
- 🔒 安全修复
- ⚡ 性能优化
- 🎨 UI/样式更新
- ♻️ 代码重构
- 🗑️ 废弃功能
- 🔥 移除功能
- ✅ 测试相关
- 📊 数据/报告
