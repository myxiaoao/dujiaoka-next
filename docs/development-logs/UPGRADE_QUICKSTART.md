# 快速升级指南

## 前置准备

1. **确保新系统已安装依赖**
```bash
composer install
```

2. **配置新系统 .env 文件**
```env
DB_DATABASE=dujiaoka_new  # 使用新的数据库名
```

3. **运行数据库迁移**
```bash
php artisan migrate
```

## 升级步骤

### 方式一：交互式升级（推荐新手）

```bash
php artisan dujiaoka:upgrade
```

按照提示输入老系统信息即可。

### 方式二：命令行升级（推荐脚本化）

```bash
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --old-path=/var/www/dujiaoka
```

### 方式三：先测试连接

```bash
# 第一步：验证连接
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --dry-run

# 第二步：正式升级
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --old-path=/var/www/dujiaoka
```

## 升级后操作

```bash
# 1. 创建管理员账号
php artisan make:filament-user

# 2. 清理缓存
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# 3. 设置文件权限
chmod -R 775 storage bootstrap/cache

# 4. 访问后台测试
# http://your-domain.com/admin
```

## 安全说明

- ✅ 老数据库不会被修改（只读操作）
- ✅ 自动备份到 storage/app/backups/
- ✅ 使用事务保证数据一致性
- ✅ 迁移失败自动回滚
- ✅ 老系统可以继续运行

## 常见问题

**Q: 升级需要停止老系统吗？**
A: 不需要。老系统可以继续运行，升级过程只读取数据。

**Q: 升级失败怎么办？**
A: 每个表使用事务保护，失败会自动回滚。老数据库不受影响。

**Q: 可以多次运行升级命令吗？**
A: 可以。每次运行会清空新库表并重新导入。

**Q: 备份文件在哪里？**
A: storage/app/backups/backup_YYYY-MM-DD_HHMMSS.sql

**Q: 如何回滚到老系统？**
A: 只需修改 Nginx 配置指向老系统目录即可，2分钟内完成。

## 完整示例

```bash
# === 在新系统目录执行 ===
cd /var/www/dujiaoka-next

# 1. 安装依赖
composer install

# 2. 配置 .env
cp .env.example .env
# 编辑 .env，设置新数据库名：dujiaoka_new

# 3. 生成密钥
php artisan key:generate

# 4. 运行迁移
php artisan migrate

# 5. 执行升级（交互式）
php artisan dujiaoka:upgrade

# 6. 创建管理员
php artisan make:filament-user

# 7. 清理缓存
php artisan optimize

# 8. 测试访问
# http://localhost:8000/admin
php artisan serve
```

## 需要帮助？

查看详细文档：`UPGRADE_GUIDE.md`
