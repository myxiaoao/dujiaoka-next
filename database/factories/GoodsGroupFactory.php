<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoodsGroup>
 */
class GoodsGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $order = 100;

        $categories = [
            '游戏账号', '游戏道具', '游戏点卡', '游戏代练',
            '软件激活码', '会员卡密', '视频会员', '音乐会员',
            '云服务器', '域名注册', 'CDN服务', '对象存储',
            '在线课程', '电子书', '教程资源', '设计素材',
        ];

        return [
            'gp_name' => fake()->unique()->randomElement($categories),
            'is_open' => fake()->boolean(80), // 80% 启用
            'ord' => $order--,
        ];
    }
}
