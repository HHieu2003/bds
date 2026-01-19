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
        // 1. Bảng phiên chat (Lưu SĐT khách)
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique(); // Số điện thoại là định danh duy nhất
            $table->foreignId('du_an_id')->nullable()->constrained('du_an')->onDelete('set null');
            $table->foreignId('bat_dong_san_id')->nullable()->constrained('bat_dong_san')->onDelete('set null');
            // ---------------------
            $table->boolean('is_read')->default(false); // Admin đã đọc chưa?
            $table->timestamps();
        });

        // 2. Bảng tin nhắn
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained('chat_sessions')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_admin')->default(false); // True: Admin nhắn, False: Khách nhắn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_tables');
    }
};
