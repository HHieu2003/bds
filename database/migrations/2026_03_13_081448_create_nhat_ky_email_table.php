<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhat_ky_email', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->foreignId('nhan_vien_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // dat_lich_hen | ky_gui | canh_bao_gia | xac_thuc | chao_mung
            $table->string('loai_email');
            $table->string('email_nguoi_nhan');
            $table->string('tieu_de');
            $table->longText('noi_dung')->nullable();

            // thanh_cong | that_bai | dang_cho
            $table->string('trang_thai')->default('thanh_cong');
            $table->text('loi')->nullable();

            // Doi tuong lien quan: lich_hen, ky_gui, bat_dong_san...
            $table->string('doi_tuong_lien_quan')->nullable();
            $table->unsignedBigInteger('doi_tuong_id')->nullable();

            $table->timestamp('thoi_diem_gui')->nullable();
            $table->timestamps();

            $table->index(['loai_email', 'trang_thai']);
            $table->index(['khach_hang_id', 'thoi_diem_gui']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhat_ky_email');
    }
};
