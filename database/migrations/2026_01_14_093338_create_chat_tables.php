<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng Phiên Chat (Hội thoại)
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();

            // Liên kết với bảng Khách Hàng (Quan trọng nhất)
            $table->foreignId('khach_hang_id')->nullable()->constrained('khach_hang')->onDelete('set null');

            // Các thông tin định danh phụ (Backup)
            $table->string('session_id')->unique(); // Session ID của Laravel
            $table->string('user_name')->nullable();
            $table->string('user_phone')->nullable();

            $table->boolean('is_verified')->default(false); // Đã xác thực SĐT hay chưa
            $table->string('context_url')->nullable(); // Khách đang xem trang nào khi chat

            $table->timestamps();
        });

        // 2. Bảng Tin Nhắn
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_session_id');

            // Nếu là Admin/Sale trả lời thì lưu ID user, nếu là khách thì để Null
            $table->unsignedBigInteger('user_id')->nullable();

            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('chat_session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
