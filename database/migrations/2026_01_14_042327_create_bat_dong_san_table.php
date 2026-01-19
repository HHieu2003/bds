<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bat_dong_san', function (Blueprint $table) {
            $table->id();

            // --- CÁC CỘT CHUẨN SEO (THÊM MỚI VÀO ĐÂY LUÔN) ---
            $table->string('tieu_de');          // Tiêu đề bài đăng (Quan trọng cho SEO)
            $table->string('slug')->unique();   // Đường dẫn đẹp (VD: ban-can-ho-a10)

            // --- KHÓA NGOẠI ---
            $table->foreignId('du_an_id')->constrained('du_an')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // --- THÔNG TIN QUẢN LÝ ---
            $table->string('toa'); // Ví dụ: GS1, S2.01, TK1...
            $table->string('ma_can');
            $table->date('ngay_goi')->nullable();
            $table->date('ngay_dang')->nullable();

            // --- THÔNG SỐ KỸ THUẬT ---
            $table->string('huong_cua')->nullable();
            $table->string('huong_ban_cong')->nullable();
            $table->double('dien_tich');
            $table->string('phong_ngu');
            $table->string('noi_that')->nullable();

            // --- GIÁ & THANH TOÁN ---
            $table->decimal('gia', 15, 0);
            $table->string('hinh_thuc_thanh_toan')->nullable();

            // --- TRẠNG THÁI & HIỂN THỊ ---
            $table->string('thoi_gian_vao')->nullable();
            $table->text('mo_ta')->nullable();
            $table->json('hinh_anh')->nullable();

            // --- PHÂN LOẠI ---
            $table->string('loai_hinh')->default('thue');
            $table->string('trang_thai')->default('con_hang');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bat_dong_san');
    }
};
