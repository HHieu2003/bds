<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('email')->unique();
            $table->string('password');
            // admin | nguon_hang | sale
            $table->string('vai_tro')->default('sale');
            $table->string('so_dien_thoai')->nullable()->unique();
            $table->string('anh_dai_dien')->nullable();
            $table->string('dia_chi')->nullable();
            $table->boolean('kich_hoat')->default(true);
            $table->timestamp('dang_nhap_cuoi_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vai_tro', 'kich_hoat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhan_vien');
    }
};
