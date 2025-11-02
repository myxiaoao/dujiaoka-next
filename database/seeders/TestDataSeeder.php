<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Seeders;

use App\Models\Carmis;
use App\Models\Coupon;
use App\Models\Goods;
use App\Models\GoodsGroup;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * 测试数据标记
     */
    private const TEST_DATA_MARKER = 'test_data';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 开始生成测试数据...');

        // 禁用模型事件，避免触发邮件等通知
        Order::unsetEventDispatcher();

        // 清空之前的测试数据
        $this->clearTestData();

        DB::transaction(function () {
            // 1. 创建商品分类 (8个)
            $this->command->info('📁 生成商品分类...');
            $groups = GoodsGroup::factory()
                ->count(8)
                ->create();

            $this->command->info("✅ 创建了 {$groups->count()} 个商品分类");

            // 2. 创建商品 (30个, 关联到分类)
            $this->command->info('🛍️  生成商品...');

            // 20个自动发货商品
            $automaticGoods = Goods::factory()
                ->count(20)
                ->automatic()
                ->create([
                    'group_id' => fn () => $groups->random()->id,
                ]);

            // 5个热门商品
            Goods::factory()
                ->count(5)
                ->popular()
                ->create([
                    'group_id' => fn () => $groups->random()->id,
                ]);

            // 5个低库存商品
            Goods::factory()
                ->count(5)
                ->lowStock()
                ->create([
                    'group_id' => fn () => $groups->random()->id,
                ]);

            $totalGoods = Goods::count();
            $this->command->info("✅ 创建了 {$totalGoods} 个商品");

            // 3. 为自动发货商品创建卡密 (每个商品10-50个卡密)
            $this->command->info('🎫 生成卡密库存...');
            $totalCarmis = 0;

            foreach ($automaticGoods as $goods) {
                $carmiCount = fake()->numberBetween(10, 50);
                Carmis::factory()
                    ->count($carmiCount)
                    ->create([
                        'goods_id' => $goods->id,
                        'status' => Carmis::STATUS_UNSOLD,
                    ]);

                // 创建少量已售出的卡密
                Carmis::factory()
                    ->count(fake()->numberBetween(0, 5))
                    ->sold()
                    ->create([
                        'goods_id' => $goods->id,
                    ]);

                $totalCarmis += $carmiCount;
            }

            $this->command->info("✅ 创建了 {$totalCarmis} 个卡密");

            // 4. 创建优惠券 (20个)
            $this->command->info('🎟️  生成优惠券...');

            // 15个通用优惠券
            $coupons = Coupon::factory()
                ->count(15)
                ->unused()
                ->create();

            // 5个已使用的优惠券
            Coupon::factory()
                ->count(5)
                ->used()
                ->create();

            // 为部分优惠券关联商品
            $allGoods = Goods::all();
            foreach ($coupons->random(8) as $coupon) {
                $coupon->goods()->attach(
                    $allGoods->random(fake()->numberBetween(1, 5))->pluck('id')
                );
            }

            $this->command->info("✅ 创建了 {$coupons->count()} 个优惠券");

            // 5. 创建订单 (100个, 涵盖各种状态)
            $this->command->info('📦 生成订单...');

            // 20个待支付订单
            Order::factory()
                ->count(20)
                ->pending()
                ->create([
                    'goods_id' => fn () => $allGoods->random()->id,
                ]);

            // 50个已完成订单
            $completedOrders = Order::factory()
                ->count(50)
                ->completed()
                ->create([
                    'goods_id' => fn () => $allGoods->random()->id,
                ]);

            // 为部分已完成订单关联优惠券
            foreach ($completedOrders->random(15) as $order) {
                $coupon = $coupons->random();
                $order->update([
                    'coupon_id' => $coupon->id,
                ]);
            }

            // 15个处理中订单
            Order::factory()
                ->count(15)
                ->processing()
                ->create([
                    'goods_id' => fn () => $allGoods->random()->id,
                ]);

            // 10个失败订单
            Order::factory()
                ->count(10)
                ->failed()
                ->create([
                    'goods_id' => fn () => $allGoods->random()->id,
                ]);

            // 5个异常订单
            Order::factory()
                ->count(5)
                ->abnormal()
                ->create([
                    'goods_id' => fn () => $allGoods->random()->id,
                ]);

            $totalOrders = Order::count();
            $this->command->info("✅ 创建了 {$totalOrders} 个订单");
        });

        $this->command->info('');
        $this->command->info('🎉 测试数据生成完成！');
        $this->command->info('');
        $this->command->info('📊 数据统计：');
        $this->command->table(
            ['类型', '数量'],
            [
                ['商品分类', GoodsGroup::count()],
                ['商品', Goods::count()],
                ['卡密', Carmis::count()],
                ['优惠券', Coupon::count()],
                ['订单', Order::count()],
            ]
        );
        $this->command->info('');
        $this->command->info('💡 提示：使用 php artisan test-data:clear 清空测试数据');
    }

    /**
     * 清空测试数据
     */
    private function clearTestData(): void
    {
        $this->command->warn('🗑️  清理旧的测试数据...');

        // 使用 truncate 清空表并自动重置自增 ID
        // 注意：这里假设我们清空所有非系统数据
        // 保留邮件模板和支付方式
        DB::table('coupons_goods')->truncate();
        DB::table('coupons')->truncate();
        DB::table('orders')->truncate();
        DB::table('carmis')->truncate();
        DB::table('goods')->truncate();
        DB::table('goods_group')->truncate();

        $this->command->info('✅ 旧数据已清理');
        $this->command->info('✅ 数据库自增 ID 已重置');
    }
}
