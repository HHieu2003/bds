<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dang_ky_nhan_tin', function (Blueprint $table) {
            $table->id();

            // 1. Thông tin người nhận
            $table->foreignId('khach_hang_id')->nullable()->constrained('khach_hang')->cascadeOnDelete();
            $table->string('email');

            // 2. Tiêu chí đăng ký (Tất cả đều cho phép null để khách tùy chọn)
            $table->string('nhu_cau', 50)->nullable(); // VD: 'ban' (Mua) hoặc 'thue' (Thuê)
            $table->foreignId('du_an_id')->nullable()->constrained('du_an')->cascadeOnDelete();
            $table->foreignId('bat_dong_san_id')->nullable()->constrained('bat_dong_san')->cascadeOnDelete();
            $table->string('so_phong_ngu', 50)->nullable(); // VD: 'studio', '1', '2', '3'

            // 3. Khoảng giá quan tâm
            $table->decimal('muc_gia_tu', 15, 2)->nullable();
            $table->decimal('muc_gia_den', 15, 2)->nullable();

            // 4. Trạng thái
            $table->boolean('trang_thai')->default(true); // 1: Đang nhận tin, 0: Đã hủy đăng ký
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dang_ky_nhan_tin');
    }
};
