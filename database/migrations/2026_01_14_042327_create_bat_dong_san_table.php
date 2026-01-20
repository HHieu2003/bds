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

            // --- CÁC CỘT CHUẨN SEO ---
            $table->string('tieu_de');
            $table->string('slug')->unique();

            // --- KHÓA NGOẠI ---
            $table->foreignId('du_an_id')->nullable()->constrained('du_an')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // --- THÔNG TIN QUẢN LÝ ---
            $table->string('toa')->nullable(); // Để nullable để tránh lỗi nếu không nhập
            $table->string('ma_can');
            $table->date('ngay_dang')->nullable();

            // --- THÔNG SỐ KỸ THUẬT ---
            $table->string('huong_nha')->nullable(); // Dùng thống nhất tên là huong_nha
            $table->double('dien_tich');

            // Sửa lại thành integer để sau này dễ lọc (VD: tìm nhà > 2 phòng ngủ)
            $table->integer('so_phong_ngu')->default(0);
            $table->integer('so_phong_tam')->default(0);

            $table->string('noi_that')->nullable();

            // --- GIÁ & THANH TOÁN ---
            // QUAN TRỌNG: (15, 2) để lưu được giá 3.5 tỷ
            $table->decimal('gia', 15, 2);

            // --- TRẠNG THÁI & HIỂN THỊ ---
            $table->text('mo_ta')->nullable();
            $table->text('tien_ich')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->json('album_anh')->nullable(); // Đổi tên hinh_anh (json) thành album_anh cho rõ nghĩa

            // --- PHÂN LOẠI ---
            $table->string('loai_hinh')->default('can_ho'); // can_ho, dat_nen...
            $table->string('nhu_cau')->default('ban'); // ban, thue

            $table->boolean('is_hot')->default(false);
            $table->string('trang_thai')->default('con_hang');
            $table->integer('luot_xem')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bat_dong_san');
    }
};
