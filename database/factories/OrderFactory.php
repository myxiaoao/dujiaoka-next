<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Factories;

use App\Models\Goods;
use App\Models\Order;
use App\Models\Pay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buyAmount = fake()->numberBetween(1, 10);
        $goodsPrice = fake()->randomFloat(2, 10, 500);
        $totalPrice = $goodsPrice * $buyAmount;
        $couponDiscount = fake()->optional(0.3)->randomFloat(2, 5, 50) ?? 0;
        $wholesaleDiscount = fake()->optional(0.2)->randomFloat(2, 1, 20) ?? 0;
        $actualPrice = max(1, $totalPrice - $couponDiscount - $wholesaleDiscount);

        return [
            'order_sn' => date('YmdHis').fake()->numerify('######'),
            'title' => fake()->randomElement([
                'Steam 账号购买',
                'Netflix 会员',
                'ChatGPT Plus',
                'Office 365',
                '腾讯视频VIP',
                '云服务器',
            ]),
            'type' => fake()->randomElement([Order::AUTOMATIC_DELIVERY, Order::MANUAL_PROCESSING]),
            'goods_id' => Goods::factory(),
            'goods_price' => $goodsPrice,
            'buy_amount' => $buyAmount,
            'total_price' => $totalPrice,
            'coupon_id' => 0,  // 0 表示未使用优惠券，实际关联在 Seeder 中处理
            'coupon_discount_price' => $couponDiscount,
            'wholesale_discount_price' => $wholesaleDiscount,
            'actual_price' => $actualPrice,
            'email' => fake()->email(),
            'pay_id' => Pay::inRandomOrder()->first()?->id ?? 1,
            'trade_no' => fake()->numerify('####################'),
            'status' => fake()->randomElement([
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_FAILURE,
                Order::STATUS_ABNORMAL,
            ]),
            'info' => fake()->optional(0.5)->paragraph(),
            'buy_ip' => fake()->ipv4(),
            'search_pwd' => fake()->numerify('######'),
        ];
    }

    /**
     * 待支付状态
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PENDING,
            'trade_no' => '',  // 待支付订单没有交易流水号
        ]);
    }

    /**
     * 处理中状态
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PROCESSING,
        ]);
    }

    /**
     * 已完成状态
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_COMPLETED,
            'info' => fake()->paragraph(),
        ]);
    }

    /**
     * 失败状态
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_FAILURE,
        ]);
    }

    /**
     * 异常状态
     */
    public function abnormal(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_ABNORMAL,
        ]);
    }
}
