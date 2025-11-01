<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_sn', 150)->comment('订单号');
            $table->unsignedInteger('goods_id')->comment('关联商品id');
            $table->unsignedInteger('coupon_id')->default(0)->comment('关联优惠码id');
            $table->string('title', 200)->comment('订单名称');
            $table->tinyInteger('type')->default(1)->comment('1自动发货 2人工处理');
            $table->decimal('goods_price', 10, 2)->default(0)->comment('商品单价');
            $table->integer('buy_amount')->default(1)->comment('购买数量');
            $table->decimal('coupon_discount_price', 10, 2)->default(0)->comment('优惠码优惠价格');
            $table->decimal('wholesale_discount_price', 10, 2)->default(0)->comment('批发价优惠');
            $table->decimal('total_price', 10, 2)->default(0)->comment('订单总价');
            $table->decimal('actual_price', 10, 2)->default(0)->comment('实际支付价格');
            $table->string('search_pwd', 200)->default('')->comment('查询密码');
            $table->string('email', 200)->comment('下单邮箱');
            $table->text('info')->nullable()->comment('订单详情');
            $table->unsignedInteger('pay_id')->nullable()->comment('支付通道id');
            $table->string('buy_ip', 50)->comment('购买者下单IP地址');
            $table->string('trade_no', 200)->default('')->comment('第三方支付订单号');
            $table->tinyInteger('status')->default(1)->comment('1待支付 2待处理 3处理中 4已完成 5处理失败 6异常 -1过期');
            $table->boolean('coupon_ret_back')->default(false)->comment('优惠码使用次数是否已经回退 0否 1是');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('order_sn', 'idx_order_sn');
            $table->index('goods_id', 'idx_goods_id');
            $table->index('email', 'idex_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
