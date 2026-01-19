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
        Schema::create('lien_he', function (Blueprint $table) {
            $table->id();

            // Thông tin khách hàng
            $table->string('so_dien_thoai'); // Quan trọng nhất
            $table->text('loi_nhan')->nullable();

            // Khách quan tâm căn nào?
            $table->foreignId('bat_dong_san_id')->constrained('bat_dong_san')->onDelete('cascade');

            // Trạng thái xử lý của nhân viên
            $table->string('trang_thai')->default('chua_xu_ly'); // 'chua_xu_ly', 'da_goi', 'ket_thuc'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_he');
    }
};
