# 独角数卡 Laravel 12 版本

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
# 4. 运行迁移
php artisan migrate

# 5. 创建管理员
php artisan make:filament-user

# 6. 启动服务
php artisan serve

# 访问后台: http://localhost:8000/admin
```

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
- ✅ 12种支付网关（支付宝、微信、PayPal等）
- ✅ 邮件通知
- ✅ 现代化后台（Filament 4）
- ✅ 数据统计 Dashboard

## 📚 文档

- [完整升级指南](docs/UPGRADE_GUIDE.md)
- [快速升级](docs/UPGRADE_QUICKSTART.md)
- [迁移总结](docs/MIGRATION_SUMMARY.md)
- [部署指南](docs/DEPLOYMENT_GUIDE.md)
- [测试清单](docs/TEST_CHECKLIST.md)
- [更多文档](docs/)

## 🛠 技术栈

- PHP >= 8.2
- Laravel 12
- Filament 4
- MySQL 5.7+

## 📝 许可证

继承原独角数卡项目许可证

---

**版本**: Laravel 12.x | **管理面板**: Filament 4.x
