<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            // Role: 'admin' (Quản trị), 'dau_chu' (Nhân viên nguồn hàng), 'sale' (Nhân viên tư vấn)
            $table->string('role')->default('sale');
            $table->string('phone')->nullable(); // Số điện thoại nhân viên để hiển thị liên hệ
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
