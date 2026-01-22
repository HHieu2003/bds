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

            // Liên kết khách hàng (Thêm mới - Quan trọng nhất)
            $table->foreignId('khach_hang_id')->nullable()->constrained('khach_hang')->onDelete('cascade');

            // Vẫn giữ user_id nếu Sale muốn lưu tin, hoặc session_id cho khách vãng lai chưa xác thực
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('session_id')->nullable();

            // Cột sđt này có thể bỏ nếu đã dùng khach_hang_id, 
            // nhưng nên giữ lại để lưu sđt cho trường hợp khách chưa tạo tài khoản hoàn chỉnh
            $table->string('so_dien_thoai')->nullable();

            $table->timestamps();
        });

        // Bảng đăng ký nhận thông báo giá (Price Alerts)
        Schema::create('nhan_bao_gia', function (Blueprint $table) {
            $table->id();

            // Nếu có khách hàng thì link luôn
            $table->foreignId('khach_hang_id')->nullable()->constrained('khach_hang')->onDelete('cascade');

            $table->string('email');
            $table->foreignId('bat_dong_san_id')->constrained('bat_dong_san')->onDelete('cascade');
            $table->decimal('gia_mong_muon', 15, 2)->nullable();
            $table->boolean('da_gui_thong_bao')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_bao_gia'); // Drop bảng này trước vì nó phụ thuộc
        Schema::dropIfExists('yeu_thich');
    }
};
