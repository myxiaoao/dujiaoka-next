# 独角数卡文档中心

本目录包含独角数卡 Laravel 12 版本的完整文档。

## 📖 核心文档

### 用户指南
- **[完整升级指南](UPGRADE_GUIDE.md)** - 从 Laravel 6 升级到 Laravel 12 的详细步骤
- **[部署指南](DEPLOYMENT_GUIDE.md)** - 生产环境部署说明
- **[前端功能文档](FRONTEND_FEATURES.md)** ⭐ 新增 - 前端页面、功能和用户体验
- **[配置管理指南](CONFIGURATION.md)** ⭐ 新增 - 系统配置管理和缓存机制

### 开发指南
- **[数据库初始化说明](DATABASE_SEEDING.md)** - 邮件模板、支付网关、系统配置初始化
- **[测试数据指南](TEST_DATA.md)** - 开发环境测试数据生成和清理

## 🎨 前端技术栈

- **Livewire 3**: 全栈组件，无刷新交互
- **Tailwind CSS 4**: 原子化 CSS 框架
- **Flux UI**: Livewire 官方免费组件库
- **Alpine.js**: 轻量级 JS 框架（内置）
- **暗色模式**: 完整 Dark Mode 支持
- **多语言**: 中文简体、繁体、English

详细说明见 [前端功能文档](FRONTEND_FEATURES.md)

## 🔧 后端技术栈

- **Laravel 12**: 最新框架特性
- **Filament 4**: 现代化管理面板
- **PHP 8.2+**: 严格类型、构造函数属性提升
- **MySQL 5.7+**: 数据库
- **Laravel Pint**: 代码格式化

## 📦 核心功能

### 前台功能
- ✅ 商品浏览（分类导航、实时搜索）
- ✅ 商品购买（批发价阶梯、优惠码）
- ✅ 订单查询（订单号、邮箱、浏览器缓存）
- ✅ 订单详情（卡密显示、一键复制）
- ✅ 二维码支付（实时状态检查）
- ✅ 多语言支持（zh_CN, zh_TW, en）

### 后台功能
- ✅ 商品管理（分类、商品、库存、批发价）
- ✅ 订单管理（自动/人工发货、状态管理）
- ✅ 卡密管理（导入/导出、循环使用）
- ✅ 优惠券系统（商品关联、使用限制）
- ✅ 支付网关（34种支付方式）
- ✅ 邮件通知（5种邮件模板）
- ✅ 系统配置（缓存自动恢复）
- ✅ 数据统计（Dashboard 可视化）

## 🔧 开发日志

项目从 Laravel 6 + dcat-admin 迁移到 Laravel 12 + Filament 4 的详细开发过程文档：

- **[开发日志目录](development-logs/)** - 包含迁移过程、技术报告、功能清单等详细文档
  - **迁移相关**: `MIGRATION_SUMMARY.md`, `LARAVEL12_COMPATIBILITY_REPORT.md`, `UPGRADE_QUICKSTART.md`
  - **功能开发**: `FILAMENT_LOCALIZATION.md`, `FILAMENT_OPTIMIZATION.md`, `COUPON_FIX.md`
  - **项目管理**: `COMPLETION_REPORT.md`, `TEST_CHECKLIST.md`, `ADMIN_FEATURE_CHECKLIST.md`
  - **文件清单**: `FILES_SUMMARY.md`, `MODERNIZATION_FILES.md`

## 🚀 快速开始

### 新系统安装
```bash
# 1. 安装依赖
composer install && npm install

# 2. 配置环境
cp .env.example .env
php artisan key:generate

# 3. 配置数据库（编辑 .env）

# 4. 运行迁移并初始化
php artisan migrate --seed

# 5. 创建管理员
php artisan make:filament-user

# 6. 启动服务
composer dev
```

### 从老系统升级
```bash
php artisan dujiaoka:upgrade \
  --host=localhost \
  --database=old_db \
  --username=root \
  --password=pass \
  --old-path=/path/to/old
```

详见 [完整升级指南](UPGRADE_GUIDE.md)

## 📚 文档索引

### 按主题浏览

#### 🎯 前端相关
- [前端功能文档](FRONTEND_FEATURES.md) - 页面、组件、用户体验
- [配置管理指南](CONFIGURATION.md) - 系统配置（包含前端语言、模板等）

#### 🔧 后端相关
- [数据库初始化说明](DATABASE_SEEDING.md) - Seeders 使用
- [测试数据指南](TEST_DATA.md) - 开发测试数据
- [部署指南](DEPLOYMENT_GUIDE.md) - 生产环境部署

#### 🚀 升级迁移
- [完整升级指南](UPGRADE_GUIDE.md) - 主要升级文档
- [development-logs/UPGRADE_QUICKSTART.md](development-logs/UPGRADE_QUICKSTART.md) - 快速升级
- [development-logs/MIGRATION_SUMMARY.md](development-logs/MIGRATION_SUMMARY.md) - 迁移总结

#### 📋 功能清单
- [development-logs/ADMIN_FEATURE_CHECKLIST.md](development-logs/ADMIN_FEATURE_CHECKLIST.md) - 后台功能
- [development-logs/COMPLETION_REPORT.md](development-logs/COMPLETION_REPORT.md) - 完成报告

## 🔗 相关链接

- **[返回主 README](../README.md)** - 项目主页
- **[CLAUDE.md](../CLAUDE.md)** - AI 辅助开发指南
- **[AGENTS.md](../AGENTS.md)** - Laravel Boost 指南
- **[CHANGELOG.md](../CHANGELOG.md)** - 更新日志

## 💡 常见问题

### 如何开始？
1. 阅读 [主 README](../README.md) 了解项目概况
2. 新安装：按照快速开始步骤操作
3. 从老系统升级：阅读 [完整升级指南](UPGRADE_GUIDE.md)

### 如何了解前端功能？
阅读 [前端功能文档](FRONTEND_FEATURES.md)，包含：
- 7个前端页面详细说明
- Livewire 组件使用
- UI 组件库（Flux UI）
- 响应式设计和暗色模式

### 如何管理系统配置？
阅读 [配置管理指南](CONFIGURATION.md)，包含：
- 配置存储机制（缓存）
- 配置分类（基础、推送、邮件、极验）
- 后台管理界面
- 配置备份和恢复

### 如何初始化数据？
阅读 [数据库初始化说明](DATABASE_SEEDING.md)，包含：
- EmailTemplateSeeder（5个邮件模板）
- PaySeeder（34个支付网关）
- SystemSettingSeeder（系统配置）
- TestDataSeeder（测试数据）

### 如何部署到生产环境？
阅读 [部署指南](DEPLOYMENT_GUIDE.md)，包含：
- 服务器环境要求
- 安装步骤
- 性能优化
- 安全配置

## 🤝 贡献文档

欢迎改进和补充文档！提交 Pull Request 时请：
1. 确保 Markdown 格式正确
2. 添加清晰的示例代码
3. 更新相关索引链接

## 📄 许可证

文档继承原 dujiaoka 项目许可证
