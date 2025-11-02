<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Console\Commands;

use App\Models\Carmis;
use App\Models\Coupon;
use App\Models\Goods;
use App\Models\GoodsGroup;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTestDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-data:clear
                            {--force : 强制清空数据，不需要确认}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清空所有测试数据（保留邮件模板和支付方式）';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // 显示警告信息
        $this->warn('⚠️  警告：此操作将清空以下数据：');
        $this->warn('   - 商品分类');
        $this->warn('   - 商品');
        $this->warn('   - 卡密');
        $this->warn('   - 优惠券');
        $this->warn('   - 订单');
        $this->newLine();
        $this->info('✅ 保留的数据：');
        $this->info('   - 邮件模板');
        $this->info('   - 支付方式');
        $this->info('   - 用户账号');
        $this->newLine();

        // 显示当前数据量
        $this->info('📊 当前数据统计：');
        $this->table(
            ['类型', '数量'],
            [
                ['商品分类', GoodsGroup::count()],
                ['商品', Goods::count()],
                ['卡密', Carmis::count()],
                ['优惠券', Coupon::count()],
                ['订单', Order::count()],
            ]
        );
        $this->newLine();

        // 确认删除
        if (! $this->option('force')) {
            if (! $this->confirm('确定要清空这些数据吗？')) {
                $this->info('❌ 操作已取消');

                return Command::FAILURE;
            }
        }

        // 执行删除
        $this->info('🗑️  正在清空数据...');

        try {
            // 使用 truncate 清空表并自动重置自增 ID
            // 注意：truncate 会导致隐式提交，不能在事务中使用，但会自动重置 AUTO_INCREMENT
            $this->info('  • 清空优惠券-商品关联...');
            DB::table('coupons_goods')->truncate();

            $this->info('  • 清空优惠券...');
            DB::table('coupons')->truncate();

            $this->info('  • 清空订单...');
            DB::table('orders')->truncate();

            $this->info('  • 清空卡密...');
            DB::table('carmis')->truncate();

            $this->info('  • 清空商品...');
            DB::table('goods')->truncate();

            $this->info('  • 清空商品分类...');
            DB::table('goods_group')->truncate();

            $this->newLine();
            $this->info('✅ 数据清空完成！');
            $this->info('✅ 数据库自增 ID 已重置');
            $this->newLine();
            $this->info('💡 提示：使用 php artisan db:seed --class=TestDataSeeder 重新生成测试数据');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ 清空数据失败：'.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
