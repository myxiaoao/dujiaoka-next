<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Factories;

use App\Models\Carmis;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carmis>
 */
class CarmisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carmiTypes = [
            // 账号密码格式
            fn () => '账号: '.fake()->userName()."\n密码: ".fake()->password(8, 16),
            // 激活码格式
            fn () => strtoupper(fake()->bothify('????-####-????-####')),
            // 兑换码格式
            fn () => strtoupper(fake()->bothify('??########??')),
            // 邮箱密码格式
            fn () => fake()->email()."\n".fake()->password(8, 16),
            // 卡号密码格式
            fn () => '卡号: '.fake()->numerify('################')."\n密码: ".fake()->numerify('######'),
        ];

        return [
            'goods_id' => Goods::factory()->automatic(),
            'carmi' => fake()->randomElement($carmiTypes)(),
            'status' => fake()->randomElement([Carmis::STATUS_UNSOLD, Carmis::STATUS_SOLD]),
            'is_loop' => fake()->boolean(10), // 10% 循环使用
        ];
    }

    /**
     * 未售出状态
     */
    public function unsold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Carmis::STATUS_UNSOLD,
        ]);
    }

    /**
     * 已售出状态
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Carmis::STATUS_SOLD,
        ]);
    }

    /**
     * 循环使用
     */
    public function loop(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_loop' => true,
        ]);
    }
}
