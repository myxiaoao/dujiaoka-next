<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id')->comment('所属分类id');
            $table->string('gd_name', 200)->comment('商品名称');
            $table->string('gd_description', 200)->comment('商品描述');
            $table->string('gd_keywords', 200)->comment('商品关键字');
            $table->text('picture')->nullable()->comment('商品图片');
            $table->decimal('retail_price', 10, 2)->default(0)->comment('零售价');
            $table->decimal('actual_price', 10, 2)->default(0)->comment('实际售价');
            $table->integer('in_stock')->default(0)->comment('库存');
            $table->integer('sales_volume')->default(0)->comment('销量');
            $table->integer('ord')->default(1)->comment('排序权重 越大越靠前');
            $table->integer('buy_limit_num')->default(0)->comment('限制单次购买最大数量，0为不限制');
            $table->text('buy_prompt')->nullable()->comment('购买提示');
            $table->text('description')->nullable()->comment('商品描述');
            $table->tinyInteger('type')->default(1)->comment('商品类型 1自动发货 2人工处理');
            $table->text('wholesale_price_cnf')->nullable()->comment('批发价配置');
            $table->text('other_ipu_cnf')->nullable()->comment('其他输入框配置');
            $table->text('api_hook')->nullable()->comment('回调事件');
            $table->boolean('is_open')->default(true)->comment('是否启用，1是 0否');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
