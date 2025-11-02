<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->numberBetween(1, 4);
        $coupon = match ($type) {
            1 => strtoupper(fake()->bothify('??##??##')),
            2 => 'VIP'.fake()->numerify('######'),
            3 => strtoupper(fake()->lexify('??????')),
            4 => fake()->bothify('DISCOUNT####'),
        };

        return [
            'coupon' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'discount' => fake()->randomFloat(2, 5, 100),
            'is_use' => Coupon::STATUS_UNUSED,
            'ret' => fake()->numberBetween(0, 100), // 0 = 无限制
            'is_open' => fake()->boolean(85),
        ];
    }

    /**
     * 未使用状态
     */
    public function unused(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_use' => Coupon::STATUS_UNUSED,
        ]);
    }

    /**
     * 已使用状态
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_use' => Coupon::STATUS_USE,
            'ret' => 0,
        ]);
    }

    /**
     * 无限次使用
     */
    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'ret' => 0,
        ]);
    }

    /**
     * 大额优惠券
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount' => fake()->randomFloat(2, 50, 200),
        ]);
    }
}
