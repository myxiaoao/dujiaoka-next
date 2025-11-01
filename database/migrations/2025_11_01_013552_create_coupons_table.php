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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->decimal('discount', 10, 2)->default(0)->comment('优惠金额');
            $table->tinyInteger('is_use')->default(1)->comment('是否已经使用 1未使用 2已使用');
            $table->boolean('is_open')->default(true)->comment('是否启用 1是 0否');
            $table->string('coupon', 150)->comment('优惠码');
            $table->integer('ret')->default(0)->comment('剩余使用次数');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('coupon', 'idx_coupon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
