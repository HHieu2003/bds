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
        Schema::create('lich_hen', function (Blueprint $table) {
            $table->id();

            // Liên kết khách hàng (Thêm mới)
            $table->foreignId('khach_hang_id')->nullable()->constrained('khach_hang')->onDelete('set null');

            // Thông tin khách hàng đặt lịch (Vẫn giữ để backup nếu khách không đăng nhập)
            $table->string('ten_khach_hang');
            $table->string('sdt_khach_hang');
            $table->string('email_khach_hang')->nullable();

            // Xem căn nào?
            $table->foreignId('bat_dong_san_id')->constrained('bat_dong_san')->onDelete('cascade');

            // Thời gian hẹn
            $table->dateTime('thoi_gian_hen');

            // Nhân viên nào phụ trách dẫn đi? (Gán bởi Admin hoặc tự nhận)
            $table->foreignId('nhan_vien_id')->nullable()->constrained('users');

            // Trạng thái lịch
            $table->string('trang_thai')->default('moi_dat'); // moi_dat, da_xac_nhan, hoan_thanh, huy

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_hen');
    }
};
