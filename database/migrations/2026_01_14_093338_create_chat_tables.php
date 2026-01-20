<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // ID định danh cho trình duyệt
            $table->string('user_name')->nullable();
            $table->string('user_phone'); // Bắt buộc nhập sđt
            $table->boolean('is_verified')->default(false); // Trạng thái xác minh
            $table->string('verification_code')->nullable(); // Mã OTP
            $table->timestamp('expires_at')->nullable(); // Thời hạn cho phiên chưa xác minh
            $table->string('context_url')->nullable(); // Khách đang xem trang nào
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_session_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Null = Khách, Có ID = Sale/Admin
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
