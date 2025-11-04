# 系统配置管理指南

本文档说明 dujiaoka-next 系统配置的管理方式、存储机制和使用方法。

## 配置架构

### 存储方式
系统配置采用**缓存存储**机制：
- **存储位置**: Laravel Cache (database/redis/file)
- **缓存键**: `system-setting`
- **过期策略**: 永久存储 (`Cache::forever()`)
- **自动恢复**: 缓存过期时自动从默认配置恢复

### 为什么使用缓存？
1. **性能优化**: 避免每次请求读取数据库
2. **灵活性**: 支持多种缓存驱动（database, redis, file）
3. **实时更新**: 修改配置立即生效
4. **兼容性**: 与原 dujiaoka 系统保持一致

---

## 配置文件

### 默认配置文件
**位置**: `config/dujiaoka_settings.php`

```php
<?php

return [
    // 基础设置
    'title' => '独角数卡',
    'language' => 'zh_CN',
    'order_expire_time' => 5,

    // 订单推送设置
    'is_open_telegram_push' => false,
    'telegram_bot_token' => null,

    // 邮件设置
    'driver' => 'smtp',
    'host' => null,
    'port' => 587,

    // 极验设置
    'is_open_geetest' => false,
    'geetest_id' => null,

    // ... 更多配置
];
```

### 作用
- 定义系统默认配置
- 缓存过期时自动恢复
- Seeder 初始化数据源

---

## 配置初始化

### 方式 1: 数据库 Seeder（推荐）

```bash
# 运行所有 Seeder（包含系统配置）
php artisan migrate --seed

# 单独运行系统配置 Seeder
php artisan db:seed --class=SystemSettingSeeder
```

**Seeder 逻辑**:
```php
// database/seeders/SystemSettingSeeder.php
public function run(): void
{
    // 检查是否已存在配置
    if (Cache::has('system-setting')) {
        $this->command->info('System settings already exist. Skipping...');
        return;
    }

    // 从配置文件加载默认值
    $defaultSettings = config('dujiaoka_settings');

    // 存储到缓存
    Cache::forever('system-setting', $defaultSettings);
}
```

### 方式 2: 自动恢复机制

系统在以下情况自动恢复配置：

**1. 访问系统设置页面时**:
```php
// app/Filament/Pages/SystemSetting.php
public function mount(): void
{
    $settings = Cache::get('system-setting');

    if (empty($settings)) {
        $settings = config('dujiaoka_settings');
        Cache::forever('system-setting', $settings);
    }

    $this->data = $settings;
}
```

**2. 调用配置获取函数时**:
```php
// app/Helpers/functions.php
function dujiaoka_config_get(string $key, $default = null)
{
    $sysConfig = Cache::get('system-setting');

    // 自动恢复
    if (empty($sysConfig)) {
        $sysConfig = config('dujiaoka_settings');
        Cache::forever('system-setting', $sysConfig);
    }

    return $sysConfig[$key] ?? $default;
}
```

---

## 配置分类

### 1. 基础设置

| 配置项 | 键名 | 类型 | 默认值 | 说明 |
|--------|------|------|--------|------|
| 网站标题 | `title` | string | "独角数卡" | 显示在浏览器标签 |
| 图片 Logo | `img_logo` | string | null | 网站 Logo 图片 |
| 文字 Logo | `text_logo` | string | "独角数卡" | 纯文本 Logo |
| SEO 关键词 | `keywords` | string | "独角数卡,自动发卡,数字商品" | SEO 优化 |
| SEO 描述 | `description` | string | "独角数卡 - 开源式自动化售货系统" | SEO 优化 |
| 网站语言 | `language` | string | "zh_CN" | zh_CN, zh_TW, en |
| 管理员邮箱 | `manage_email` | string | null | 接收通知邮件 |
| 订单过期时间 | `order_expire_time` | int | 5 | 分钟数 |
| 开启防红跳转 | `is_open_anti_red` | bool | false | 微信防红 |
| 开启图形验证码 | `is_open_img_code` | bool | false | 购买页验证码 |
| 开启查询密码 | `is_open_search_pwd` | bool | false | 订单查询密码 |
| 开启谷歌翻译 | `is_open_google_translate` | bool | false | 网站翻译功能 |
| 网站公告 | `notice` | string | "<p>欢迎使用独角数卡系统！</p>" | HTML 格式 |
| 底部信息 | `footer` | string | "Powered by Dujiaoka" | 页脚文字 |

### 2. 订单推送设置

| 配置项 | 键名 | 类型 | 默认值 | 说明 |
|--------|------|------|--------|------|
| Server 酱推送 | `is_open_server_jiang` | bool | false | 开关 |
| Server 酱 Token | `server_jiang_token` | string | null | API Token |
| Telegram 推送 | `is_open_telegram_push` | bool | false | 开关 |
| Telegram Bot Token | `telegram_bot_token` | string | null | Bot Token |
| Telegram User ID | `telegram_userid` | string | null | 接收用户 ID |
| Bark 推送 | `is_open_bark_push` | bool | false | 开关 |
| Bark 推送地址 | `is_open_bark_push_url` | bool | false | 是否自定义服务器 |
| Bark 服务器 | `bark_server` | string | null | 自定义服务器地址 |
| Bark Token | `bark_token` | string | null | Bark Token |
| 企业微信推送 | `is_open_qywxbot_push` | bool | false | 开关 |
| 企业微信 Key | `qywxbot_key` | string | null | 机器人 Key |

### 3. 邮件设置

| 配置项 | 键名 | 类型 | 默认值 | 说明 |
|--------|------|------|--------|------|
| 邮件驱动 | `driver` | string | "smtp" | smtp, sendmail, mailgun 等 |
| SMTP 主机 | `host` | string | null | smtp.example.com |
| SMTP 端口 | `port` | int | 587 | 25, 465, 587 |
| SMTP 用户名 | `username` | string | null | 邮箱账号 |
| SMTP 密码 | `password` | string | null | 邮箱密码/授权码 |
| 加密方式 | `encryption` | string | "tls" | tls, ssl, null |
| 发件人地址 | `from_address` | string | null | noreply@example.com |
| 发件人名称 | `from_name` | string | "独角发卡" | 邮件显示名称 |

### 4. 极验设置

| 配置项 | 键名 | 类型 | 默认值 | 说明 |
|--------|------|------|--------|------|
| 极验 ID | `geetest_id` | string | null | 极验 ID |
| 极验 Key | `geetest_key` | string | null | 极验密钥 |
| 开启极验 | `is_open_geetest` | bool | false | 验证码开关 |

---

## 配置管理

### 后台管理

**访问路径**: `/admin` → 系统设置

**操作说明**:
1. 登录 Filament 后台
2. 点击左侧菜单 "系统设置"
3. 四个 Tab 分别管理不同配置分类：
   - 基础设置
   - 订单推送
   - 邮件设置
   - 极验设置
4. 修改配置后点击 "保存"
5. 配置立即生效（更新缓存）

**实现逻辑**:
```php
// app/Filament/Pages/SystemSetting.php
public function save(): void
{
    // 保存到缓存
    Cache::put('system-setting', $this->data);

    // 提示消息
    Notification::make()
        ->title('保存成功')
        ->success()
        ->send();
}
```

### 代码调用

#### 获取配置值

```php
// 方式 1: 辅助函数（推荐）
$title = dujiaoka_config_get('title');
$orderExpireTime = dujiaoka_config_get('order_expire_time', 5);

// 方式 2: 直接读取缓存
$settings = Cache::get('system-setting', []);
$title = $settings['title'] ?? '独角数卡';
```

#### Blade 模板中使用

```blade
{{-- 获取配置 --}}
{{ dujiaoka_config_get('title') }}

{{-- 带默认值 --}}
{{ dujiaoka_config_get('notice', '暂无公告') }}

{{-- 判断开关 --}}
@if(dujiaoka_config_get('is_open_telegram_push'))
    <p>Telegram 推送已开启</p>
@endif
```

---

## 缓存管理

### 清除配置缓存

```bash
# 清除系统配置缓存
php artisan cache:forget system-setting

# 清除所有缓存
php artisan cache:clear

# 清除优化缓存（包含配置缓存）
php artisan optimize:clear
```

### 配置缓存驱动

**编辑 `.env` 文件**:

```env
# 数据库缓存（默认）
CACHE_STORE=database

# Redis 缓存（推荐生产环境）
CACHE_STORE=redis

# 文件缓存
CACHE_STORE=file
```

**性能对比**:
| 驱动 | 读取速度 | 并发性能 | 持久化 | 推荐场景 |
|------|---------|---------|--------|---------|
| database | ⭐⭐ | ⭐⭐ | ✅ | 小型站点 |
| redis | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ✅ | 生产环境 |
| file | ⭐⭐⭐ | ⭐⭐ | ✅ | 开发环境 |

---

## 配置迁移

### 从原系统升级

**自动迁移**:
```bash
php artisan dujiaoka:upgrade ...
```

升级命令会自动：
1. 读取原系统缓存配置（如果存在）
2. 保留用户自定义配置
3. 补充新增配置项的默认值

**手动迁移**:
1. 导出原系统配置到 JSON
2. 对照新系统配置项逐一设置
3. 保存到后台系统设置

---

## 配置备份

### 导出配置

```bash
# 导出当前配置到 JSON 文件
php artisan tinker --execute="
    \$settings = Cache::get('system-setting');
    file_put_contents('system-settings-backup.json', json_encode(\$settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo 'Backup saved to system-settings-backup.json';
"
```

### 导入配置

```bash
# 从 JSON 文件导入配置
php artisan tinker --execute="
    \$settings = json_decode(file_get_contents('system-settings-backup.json'), true);
    Cache::forever('system-setting', \$settings);
    echo 'Settings imported successfully';
"
```

---

## 常见问题

### Q: 配置修改后不生效？
A:
1. 检查缓存驱动是否正确配置
2. 清除缓存: `php artisan cache:clear`
3. 重启 Queue Worker: `php artisan queue:restart`

### Q: 配置丢失怎么办？
A:
- 系统会自动从 `config/dujiaoka_settings.php` 恢复默认配置
- 如果需要恢复特定配置，运行: `php artisan db:seed --class=SystemSettingSeeder`

### Q: 如何重置所有配置？
A:
```bash
# 1. 清除缓存
php artisan cache:forget system-setting

# 2. 重新初始化
php artisan db:seed --class=SystemSettingSeeder
```

### Q: 缓存过期了怎么办？
A: 系统已实现自动恢复机制，无需手动处理。

### Q: 如何添加自定义配置项？
A:
1. 在 `config/dujiaoka_settings.php` 添加新配置项
2. 在 `app/Filament/Pages/SystemSetting.php` 添加表单字段
3. 清除缓存重新初始化

---

## 配置安全

### 敏感信息保护

- **SMTP 密码**: 存储在缓存中，避免明文存储在代码
- **API Token**: 后台输入，缓存存储
- **访问控制**: 仅管理员可访问系统设置页面

### 最佳实践

1. **定期备份**: 导出配置 JSON 文件备份
2. **测试环境**: 修改配置前在测试环境验证
3. **文档记录**: 记录重要配置修改历史
4. **权限控制**: 限制系统设置访问权限

---

## 开发指南

### 添加新配置项

**1. 更新默认配置文件**:
```php
// config/dujiaoka_settings.php
return [
    // ...
    'new_config_item' => 'default_value',
];
```

**2. 更新系统设置页面表单**:
```php
// app/Filament/Pages/SystemSetting.php
TextInput::make('new_config_item')
    ->label('新配置项')
    ->default('default_value'),
```

**3. 清除缓存重新初始化**:
```bash
php artisan cache:forget system-setting
php artisan db:seed --class=SystemSettingSeeder
```

**4. 在代码中使用**:
```php
$value = dujiaoka_config_get('new_config_item');
```

---

## 相关文档

- [数据库初始化说明](DATABASE_SEEDING.md) - Seeder 详解
- [Filament 后台文档](https://filamentphp.com) - 表单组件使用
- [Laravel 缓存文档](https://laravel.com/docs/cache) - 缓存驱动配置
