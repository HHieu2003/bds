<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten')->nullable();

            // Cho phép nullable để linh hoạt (khách có thể chỉ nhập Email hoặc chỉ nhập SĐT)
            $table->string('so_dien_thoai')->nullable()->unique();
            $table->string('email')->nullable()->unique();

            $table->string('password')->nullable(); // Null nếu dùng login nhanh
            $table->boolean('is_active')->default(true);

            // Hai cột xác thực tích hợp sẵn
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khach_hang');
    }
};
