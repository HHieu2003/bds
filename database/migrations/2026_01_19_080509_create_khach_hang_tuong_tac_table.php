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
        // Bảng lưu danh sách yêu thích (Favorites)
        Schema::create('yeu_thich', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bat_dong_san_id')->constrained('bat_dong_san')->onDelete('cascade');

            // Nếu khách đã đăng nhập
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Nếu khách vãng lai (lưu theo session hoặc cookie)
            $table->string('session_id')->nullable();

            $table->timestamps();
        });

        // Bảng đăng ký nhận thông báo giá (Price Alerts)
        Schema::create('nhan_bao_gia', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('bat_dong_san_id')->constrained('bat_dong_san')->onDelete('cascade');
            $table->decimal('gia_mong_muon', 15, 2)->nullable(); // Khi giá giảm xuống mức này thì báo
            $table->boolean('da_gui_thong_bao')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hang_tuong_tac');
    }
};
