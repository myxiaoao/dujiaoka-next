<?php

declare(strict_types=1);

/**
 * This file is part of dujiaoka next server projects.
 */
return [
    // 基础设置
    'title' => '独角数卡',
    'img_logo' => null,
    'text_logo' => '独角数卡',
    'keywords' => '独角数卡,自动发卡,数字商品',
    'description' => '独角数卡 - 开源式自动化售货系统',
    'template' => 'unicorn',
    'language' => 'zh_CN',
    'manage_email' => null,
    'order_expire_time' => 5,
    'is_open_anti_red' => false,
    'is_open_img_code' => false,
    'is_open_search_pwd' => false,
    'is_open_google_translate' => false,
    'notice' => '<p>欢迎使用独角数卡系统！</p>',
    'footer' => 'Powered by Dujiaoka',

    // 订单推送设置
    'is_open_server_jiang' => false,
    'server_jiang_token' => null,
    'is_open_telegram_push' => false,
    'telegram_bot_token' => null,
    'telegram_userid' => null,
    'is_open_bark_push' => false,
    'is_open_bark_push_url' => false,
    'bark_server' => null,
    'bark_token' => null,
    'is_open_qywxbot_push' => false,
    'qywxbot_key' => null,

    // 邮件设置
    'driver' => 'smtp',
    'host' => null,
    'port' => 587,
    'username' => null,
    'password' => null,
    'encryption' => 'tls',
    'from_address' => null,
    'from_name' => '独角发卡',

    // 极验设置
    'geetest_id' => null,
    'geetest_key' => null,
    'is_open_geetest' => false,
];
