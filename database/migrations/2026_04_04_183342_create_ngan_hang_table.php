<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Chạy migration để tạo bảng.
     */
    public function up(): void
    {
        Schema::create('ngan_hang', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ngan_hang'); // VD: Vietcombank, Techcombank
            $table->string('logo')->nullable(); // Đường dẫn ảnh logo ngân hàng

            // Các thông số quan trọng để tính toán
            $table->decimal('lai_suat_uu_dai', 5, 2); // Lãi suất năm đầu (VD: 6.50 %)
            $table->integer('thoi_gian_uu_dai')->default(12); // Số tháng được hưởng ưu đãi (VD: 12 tháng)
            $table->decimal('lai_suat_tha_noi', 5, 2)->nullable(); // Lãi suất sau ưu đãi (VD: 10.50 %)

            // Giới hạn cho vay của ngân hàng đó
            $table->integer('ty_le_vay_toi_da')->default(70); // % tối đa cho vay trên giá trị BĐS (VD: 70%)
            $table->integer('thoi_gian_vay_toi_da')->default(25); // Số năm tối đa cho vay (VD: 25 năm)

            $table->boolean('trang_thai')->default(1); // 1: Hiển thị, 0: Ẩn
            $table->timestamps();
        });
    }

    /**
     * Rollback migration (xóa bảng nếu cần).
     */
    public function down(): void
    {
        Schema::dropIfExists('ngan_hang');
    }
};
