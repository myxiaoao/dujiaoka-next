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
        Schema::create('emailtpls', function (Blueprint $table) {
            $table->id();
            $table->string('tpl_name', 150)->comment('邮件标题');
            $table->text('tpl_content')->comment('邮件内容');
            $table->string('tpl_token', 50)->comment('邮件标识');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('tpl_token', 'mail_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emailtpls');
    }
};
