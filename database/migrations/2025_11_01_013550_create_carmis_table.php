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
        Schema::create('carmis', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('goods_id')->comment('所属商品');
            $table->tinyInteger('status')->default(1)->comment('状态 1未售出 2已售出');
            $table->boolean('is_loop')->default(false)->comment('循环卡密 1是 0否');
            $table->text('carmi')->comment('卡密');
            $table->timestamps();
            $table->softDeletes();

            $table->index('goods_id', 'idx_goods_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carmis');
    }
};
