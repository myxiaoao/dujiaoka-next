<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 检查是否已经存在配置
        if (Cache::has('system-setting')) {
            $this->command->info('System settings already exist in cache. Skipping...');

            return;
        }

        // 从配置文件加载默认系统配置
        $defaultSettings = config('dujiaoka_settings');

        // 存储到缓存
        Cache::forever('system-setting', $defaultSettings);

        $this->command->info('System settings initialized successfully!');
        $this->command->info('Default template: '.$defaultSettings['template']);
        $this->command->info('Default language: '.$defaultSettings['language']);
        $this->command->info('Order expire time: '.$defaultSettings['order_expire_time'].' minutes');
    }
}
