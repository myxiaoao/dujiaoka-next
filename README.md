# 独角数卡 Laravel 12+ 版本

> 从 Laravel 6 + dcat-admin 升级到 Laravel 12 + Filament 4 的现代化版本

## 🚀 快速开始

### 新系统安装

```bash
# 1. 安装依赖
composer install

# 2. 配置环境
cp .env.example .env
php artisan key:generate

# 3. 配置数据库（编辑 .env）
# 4. 运行迁移并初始化数据
php artisan migrate --seed

# 注：--seed 会自动初始化：
# - 5个邮件模板
# - 34个支付网关配置
# 或者单独运行: php artisan db:seed

# 5. 创建管理员
php artisan make:filament-user

# 6. 启动服务
php artisan serve

# 访问后台: http://localhost:8000/admin
```

### 开发环境测试数据

开发时可以生成测试数据用于调试和演示：

```bash
# 生成测试数据（商品、订单、卡密、优惠券等）
php artisan db:seed --class=TestDataSeeder

# 清空测试数据（上线前使用）
php artisan test-data:clear

# 强制清空（不需要确认）
php artisan test-data:clear --force
```

详细说明见 [测试数据文档](docs/TEST_DATA.md)

### 从老系统升级

```bash
# 自动化升级（推荐）
php artisan dujiaoka:upgrade

# 详细文档
# - docs/UPGRADE_GUIDE.md
# - docs/UPGRADE_QUICKSTART.md
# - docs/MIGRATION_SUMMARY.md
```

## 📋 主要功能

- ✅ 商品管理（分类、商品、库存、批发价）
- ✅ 订单管理（自动/人工发货）
- ✅ 卡密管理（导入、循环使用）
- ✅ 优惠券系统
- ✅ 34种支付网关（支付宝、微信、PayPal、Stripe、加密货币等）
- ✅ 邮件通知系统（5种邮件模板）
- ✅ 现代化后台（Filament 4）
- ✅ 数据统计 Dashboard

## 📚 文档

- [完整升级指南](docs/UPGRADE_GUIDE.md)
- [快速升级](docs/UPGRADE_QUICKSTART.md)
- [迁移总结](docs/MIGRATION_SUMMARY.md)
- [数据库初始化说明](docs/DATABASE_SEEDING.md) ⭐ 新增
- [测试数据生成指南](docs/TEST_DATA.md) ⭐ 新增
- [部署指南](docs/DEPLOYMENT_GUIDE.md)
- [测试清单](docs/TEST_CHECKLIST.md)
- [更多文档](docs/)

## 🛠 技术栈

- PHP >= 8.2
- Laravel 12+
- Filament 4+
- MySQL 5.7+

## 📝 许可证

继承原独角数卡项目许可证
