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
        Schema::create('goods_group', function (Blueprint $table) {
            $table->id();
            $table->string('gp_name', 200)->comment('分类名称');
            $table->boolean('is_open')->default(true)->comment('是否启用，1是 0否');
            $table->integer('ord')->default(1)->comment('排序权重 越大越靠前');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_group');
    }
};
