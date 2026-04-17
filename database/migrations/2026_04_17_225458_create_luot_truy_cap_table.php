<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('luot_truy_cap', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('trang', 100)->nullable()->index(); // 'home','bds','du-an','tim-kiem','lien-he',...
            $table->unsignedBigInteger('bat_dong_san_id')->nullable()->index();
            $table->unsignedBigInteger('du_an_id')->nullable()->index();
            $table->unsignedBigInteger('khu_vuc_id')->nullable()->index();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['session_id', 'created_at']);
            $table->index(['trang', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luot_truy_cap');
    }
};
