# 独角数卡升级指南 (Laravel 6 → Laravel 12)

## 升级命令使用说明

### 快速开始

升级命令提供了完全自动化的数据迁移流程，确保您的老系统数据安全迁移到新系统。

### 基础用法

#### 1. 交互式升级（推荐）

```bash
php artisan dujiaoka:upgrade
```

命令会交互式地询问您：
- 老数据库主机地址
- 老数据库端口
- 老数据库名称
- 老数据库用户名
- 老数据库密码
- 老系统文件路径

#### 2. 命令行参数方式

```bash
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --port=3306 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --old-path=/var/www/dujiaoka
```

#### 3. 仅验证连接（Dry Run）

在正式迁移前，建议先验证数据库连接：

```bash
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --dry-run
```

#### 4. 跳过文件复制

如果您已经手动复制了文件，或者想单独处理文件：

```bash
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --skip-files
```

## 命令参数说明

| 参数 | 说明 | 默认值 | 必填 |
|------|------|--------|------|
| `--host` | 老数据库主机地址 | 交互式询问 | 否 |
| `--port` | 老数据库端口 | 3306 | 否 |
| `--database` | 老数据库名称 | 交互式询问 | 否 |
| `--username` | 老数据库用户名 | 交互式询问 | 否 |
| `--password` | 老数据库密码 | 交互式询问 | 否 |
| `--old-path` | 老系统文件路径 | 交互式询问 | 否 |
| `--skip-files` | 跳过文件复制 | false | 否 |
| `--dry-run` | 仅验证连接，不执行迁移 | false | 否 |

## 升级流程（7个步骤）

### 步骤 1/7: 配置老数据库连接
- 收集老数据库连接信息
- 显示配置摘要供确认

### 步骤 2/7: 验证数据库连接
- 测试老数据库连接
- 测试新数据库连接
- 检查必需的表是否存在

### 步骤 3/7: 数据统计
- 显示老数据库中的记录统计
- 确认是否继续迁移

### 步骤 4/7: 备份当前新数据库
- 自动使用 mysqldump 备份新数据库
- 备份文件保存在 `storage/app/backups/` 目录
- 文件名格式: `backup_YYYY-MM-DD_HHMMSS.sql`

### 步骤 5/7: 开始数据迁移
- 使用事务确保数据一致性
- 分批复制数据（每批 500 条）
- 迁移以下表：
  - goods_group（商品分类）
  - goods（商品）
  - carmis（卡密）
  - coupons（优惠券）
  - coupons_goods（优惠券-商品关联）
  - emailtpls（邮件模板）
  - pays（支付网关）
  - orders（订单）

### 步骤 6/7: 复制文件资产
- 复制 `storage/app` 目录
- 复制 `public/uploads` 目录

### 步骤 7/7: 验证数据完整性
- 对比老库和新库的记录数量
- 确保数据迁移无遗漏

## 安全保障

### ✓ 老数据库完全安全
- **只读操作**：命令只从老数据库读取数据，不会修改任何内容
- **独立数据库**：新系统使用独立的数据库
- **随时回滚**：老系统保持完好，可随时切换回去

### ✓ 自动备份
- 迁移前自动备份新数据库
- 备份文件永久保存在 `storage/app/backups/`

### ✓ 事务保护
- 每个表的迁移都使用数据库事务
- 如果某个表迁移失败，会自动回滚

### ✓ 数据验证
- 迁移完成后自动验证记录数量
- 确保数据完整性

## 完整升级示例

```bash
# 1. 首先进行连接验证（推荐）
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --dry-run

# 2. 验证通过后，执行正式迁移
php artisan dujiaoka:upgrade \
    --host=127.0.0.1 \
    --database=dujiaoka \
    --username=root \
    --password=your_password \
    --old-path=/var/www/dujiaoka

# 3. 迁移完成后，创建 Filament 管理员
php artisan make:filament-user

# 4. 清理缓存
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# 5. 访问后台
# http://your-domain.com/admin
```

## 迁移后的表结构

所有表结构与老系统保持 100% 一致，包括：

| 表名 | 说明 | 重要字段 |
|------|------|---------|
| goods_group | 商品分类 | gp_name, is_open, ord |
| goods | 商品 | gd_name, actual_price, in_stock, sales_volume |
| carmis | 卡密库存 | carmi, status, is_loop |
| coupons | 优惠券 | coupon, type, discount, used |
| orders | 订单 | order_sn, actual_price, status, email |
| pays | 支付网关 | pay_name, pay_handleroute, merchant_id |
| emailtpls | 邮件模板 | tpl_token, tpl_content |

## 注意事项

### 数据库准备

**重要**：升级命令需要新数据库已经运行了迁移：

```bash
# 在运行升级命令前，先运行迁移
php artisan migrate
```

### 文件权限

确保新系统的 storage 目录有写权限：

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 环境配置

确保新系统的 `.env` 文件配置正确：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dujiaoka_new  # 使用新的数据库名
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 故障排除

### 问题 1: 连接老数据库失败

**错误**：`✗ 老数据库连接失败`

**解决方案**：
- 检查老数据库是否正在运行
- 验证主机地址、端口、用户名、密码是否正确
- 确认数据库用户有远程连接权限（如果不在同一台服务器）

### 问题 2: 缺少表

**错误**：`✗ 老数据库中缺少以下表`

**解决方案**：
- 确认老系统数据库是否完整
- 检查是否使用了正确的数据库名

### 问题 3: 备份失败

**错误**：`⚠️ 备份失败`

**解决方案**：
- 检查 `storage/app/backups` 目录是否有写权限
- 确认 mysqldump 命令是否可用：`which mysqldump`
- 可以选择继续（不推荐）或手动备份后再执行

### 问题 4: 数据验证失败

**错误**：`✗ 数据验证失败：数量不匹配`

**解决方案**：
- 检查迁移过程中是否有错误日志
- 可能是迁移过程中数据库有新数据写入
- 建议暂停老系统写入，重新执行迁移

## 回滚方案

如果升级后发现问题，可以立即回滚：

### 方法 1: 切换 Nginx 配置（推荐）

```bash
# 修改 Nginx 配置指向老系统
vim /etc/nginx/sites-available/dujiaoka
# 找到 root 配置，改回老路径
# root /var/www/dujiaoka/public;

# 重载 Nginx
systemctl reload nginx
```

**回滚时间**: < 2 分钟

### 方法 2: 恢复备份

```bash
# 从备份恢复新数据库（如果需要）
mysql -u root -p dujiaoka_new < storage/app/backups/backup_YYYY-MM-DD_HHMMSS.sql
```

## 支持与帮助

- GitHub Issues: https://github.com/your-repo/issues
- 文档: https://docs.dujiaoka.com

---

**升级命令设计原则**：零风险、可回滚、全自动、有保障
