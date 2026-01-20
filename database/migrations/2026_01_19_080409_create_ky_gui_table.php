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
        Schema::create('ky_gui', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten_chu_nha');
            $table->string('so_dien_thoai');
            $table->string('email')->nullable();

            // Thông tin BĐS muốn bán/cho thuê
            $table->string('loai_hinh'); // chung_cu, nha_dat...
            $table->string('dia_chi');
            $table->decimal('dien_tich', 8, 2);
            $table->decimal('gia_mong_muon', 15, 2);
            $table->string('hinh_anh_tham_khao')->nullable();
            $table->text('ghi_chu')->nullable();

            // Quy trình xử lý của Nhân viên nguồn hàng
            $table->string('trang_thai')->default('cho_duyet'); // cho_duyet, da_lien_he, da_nhan, tu_choi
            $table->text('phan_hoi_cua_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ky_gui');
    }
};
