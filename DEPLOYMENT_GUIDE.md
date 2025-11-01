# 部署指南

## 生产环境部署步骤

### 1. 服务器要求

**最低配置**:
- CPU: 2核
- 内存: 2GB
- 硬盘: 20GB SSD
- 带宽: 5Mbps

**推荐配置**:
- CPU: 4核+
- 内存: 4GB+
- 硬盘: 50GB SSD
- 带宽: 10Mbps+

**软件环境**:
- OS: Ubuntu 20.04+ / CentOS 8+
- PHP: 8.2+
- MySQL: 5.7+ / MariaDB: 10.3+
- Nginx: 1.18+
- Redis: 6.0+ (可选，用于队列和缓存)
- Supervisor (用于队列管理)

### 2. 安装PHP和扩展

```bash
# Ubuntu
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-redis

# CentOS
sudo yum install -y php82 php82-php-fpm php82-php-mysqlnd php82-php-mbstring \
    php82-php-xml php82-php-curl php82-php-zip php82-php-gd php82-php-redis
```

### 3. 安装Composer

```bash
cd /tmp
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 4. 部署应用

```bash
# 创建部署目录
sudo mkdir -p /var/www
cd /var/www

# 克隆项目（或上传代码）
git clone <repository-url> dujiaoka-next
cd dujiaoka-next

# 安装依赖
composer install --no-dev --optimize-autoloader

# 设置权限
sudo chown -R www-data:www-data /var/www/dujiaoka-next
sudo chmod -R 755 /var/www/dujiaoka-next
sudo chmod -R 775 storage bootstrap/cache
```

### 5. 配置环境变量

```bash
# 复制环境文件
cp .env.example .env

# 生成应用密钥
php artisan key:generate

# 编辑配置
vim .env
```

**必须配置的项**:
```env
APP_NAME="独角数卡"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dujiaoka_new
DB_USERNAME=dujiaoka
DB_PASSWORD=strong_password_here

CACHE_STORE=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. 数据库设置

```bash
# 登录MySQL
mysql -u root -p

# 创建数据库和用户
CREATE DATABASE dujiaoka_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dujiaoka'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON dujiaoka_new.* TO 'dujiaoka'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# 运行迁移
php artisan migrate

# 创建管理员
php artisan make:filament-user
```

### 7. Nginx配置

```bash
sudo vim /etc/nginx/sites-available/dujiaoka
```

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/dujiaoka-next/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # 静态文件缓存
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# 启用站点
sudo ln -s /etc/nginx/sites-available/dujiaoka /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 8. SSL证书（Let's Encrypt）

```bash
# 安装certbot
sudo apt install certbot python3-certbot-nginx

# 获取证书
sudo certbot --nginx -d your-domain.com

# 自动续期
sudo certbot renew --dry-run
```

### 9. 配置队列处理 (Supervisor)

```bash
sudo vim /etc/supervisor/conf.d/dujiaoka-worker.conf
```

```ini
[program:dujiaoka-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dujiaoka-next/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/dujiaoka-next/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# 重新加载配置
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start dujiaoka-worker:*

# 检查状态
sudo supervisorctl status
```

### 10. 配置定时任务

```bash
sudo crontab -e -u www-data
```

添加：
```cron
* * * * * cd /var/www/dujiaoka-next && php artisan schedule:run >> /dev/null 2>&1
```

### 11. 优化配置

```bash
# 缓存配置
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 优化自动加载
composer dump-autoload --optimize

# 生产环境优化
php artisan optimize
```

### 12. 安全加固

```bash
# 禁用不必要的PHP函数
sudo vim /etc/php/8.2/fpm/php.ini
```

添加：
```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
```

```bash
# 限制文件上传大小
upload_max_filesize = 10M
post_max_size = 10M
```

```bash
# 重启PHP-FPM
sudo systemctl restart php8.2-fpm
```

## 监控和维护

### 日志监控

```bash
# 应用日志
tail -f storage/logs/laravel.log

# Nginx日志
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# 队列日志
tail -f storage/logs/worker.log
```

### 数据库备份

```bash
# 创建备份脚本
sudo vim /usr/local/bin/backup-dujiaoka.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/dujiaoka"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# 备份数据库
mysqldump -u dujiaoka -p'password' dujiaoka_new | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# 备份文件
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/dujiaoka-next/storage/app

# 删除30天前的备份
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed: $DATE"
```

```bash
# 设置权限
sudo chmod +x /usr/local/bin/backup-dujiaoka.sh

# 添加到crontab（每天凌晨2点）
sudo crontab -e
```

```cron
0 2 * * * /usr/local/bin/backup-dujiaoka.sh >> /var/log/dujiaoka-backup.log 2>&1
```

## 故障排除

### 500错误
```bash
# 检查日志
tail -50 storage/logs/laravel.log

# 检查权限
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 清理缓存
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 队列不工作
```bash
# 检查Supervisor状态
sudo supervisorctl status

# 重启队列
sudo supervisorctl restart dujiaoka-worker:*

# 手动测试
php artisan queue:work --once
```

### 数据库连接失败
```bash
# 检查MySQL状态
sudo systemctl status mysql

# 测试连接
mysql -u dujiaoka -p dujiaoka_new

# 检查Laravel配置
php artisan config:clear
php artisan migrate:status
```

## 性能优化

### OPcache配置

```bash
sudo vim /etc/php/8.2/fpm/conf.d/10-opcache.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.validate_timestamps=0
```

### Redis优化

```bash
sudo vim /etc/redis/redis.conf
```

```conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### Nginx优化

```nginx
# Nginx配置中添加
client_max_body_size 20M;
client_body_buffer_size 128k;
fastcgi_buffer_size 128k;
fastcgi_buffers 4 256k;
fastcgi_busy_buffers_size 256k;
```

---

**部署完成后**，访问 `https://your-domain.com/admin` 进入后台管理！
