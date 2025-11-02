<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Factories;

use App\Models\Goods;
use App\Models\GoodsGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goods>
 */
class GoodsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $order = 1000;

        $retailPrice = fake()->randomFloat(2, 10, 500);
        $actualPrice = fake()->randomFloat(2, $retailPrice * 0.5, $retailPrice * 0.95);

        return [
            'gd_name' => fake()->randomElement([
                'Steam 账号',
                'Netflix 会员月卡',
                'ChatGPT Plus 月卡',
                'Microsoft Office 365',
                '腾讯视频 VIP',
                '爱奇艺会员',
                '阿里云服务器',
                'Photoshop 激活码',
                '王者荣耀点券',
                '原神月卡',
                'Minecraft 账号',
                'Spotify Premium',
                'GitHub Copilot',
                'JetBrains 全家桶',
                'Adobe Creative Cloud',
            ]).fake()->numberBetween(1, 99),
            'gd_description' => fake()->sentence(10),
            'gd_keywords' => implode(',', fake()->words(5)),
            'group_id' => GoodsGroup::factory(),
            'type' => fake()->randomElement([Goods::AUTOMATIC_DELIVERY, Goods::MANUAL_PROCESSING]),
            'picture' => null,
            'retail_price' => $retailPrice,
            'actual_price' => $actualPrice,
            'in_stock' => fake()->numberBetween(0, 1000),
            'sales_volume' => fake()->numberBetween(0, 500),
            'buy_limit_num' => fake()->numberBetween(0, 10),
            'buy_prompt' => fake()->optional(0.3)->paragraph(),
            'description' => fake()->optional(0.7)->paragraphs(3, true),
            'wholesale_price_cnf' => fake()->optional(0.2)->randomElement([
                json_encode(['10' => 9.5, '50' => 9.0, '100' => 8.5]),
                json_encode(['5' => 9.8, '20' => 9.5]),
            ]),
            'other_ipu_cnf' => fake()->optional(0.3)->randomElement([
                json_encode(['qq' => 'QQ号码', 'phone' => '手机号']),
                json_encode(['email' => '邮箱地址']),
            ]),
            'api_hook' => fake()->optional(0.1)->url(),
            'ord' => $order--,
            'is_open' => fake()->boolean(85),
        ];
    }

    /**
     * 自动发货类型
     */
    public function automatic(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Goods::AUTOMATIC_DELIVERY,
        ]);
    }

    /**
     * 人工处理类型
     */
    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Goods::MANUAL_PROCESSING,
        ]);
    }

    /**
     * 低库存
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'in_stock' => fake()->numberBetween(0, 10),
        ]);
    }

    /**
     * 高销量
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'sales_volume' => fake()->numberBetween(500, 2000),
        ]);
    }
}
