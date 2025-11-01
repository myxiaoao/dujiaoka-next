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
        Schema::create('coupons_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('goods_id')->comment('商品id');
            $table->unsignedInteger('coupons_id')->comment('优惠码id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons_goods');
    }
};
