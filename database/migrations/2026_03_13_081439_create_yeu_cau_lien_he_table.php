<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_cau_lien_he', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->foreignId('bat_dong_san_id')
                ->nullable()
                ->constrained('bat_dong_san')
                ->nullOnDelete();

            $table->foreignId('nhan_vien_phu_trach_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // Snapshot thong tin tai thoi diem gui (khach vang lai chua co tai khoan)
            $table->string('ho_ten');
            $table->string('so_dien_thoai');
            $table->string('email')->nullable();
            $table->text('noi_dung')->nullable();

            // website | hotline | chat | form_bds
            $table->string('nguon_lien_he')->default('website');
            $table->string('muc_do_quan_tam')->nullable();

            // moi | da_lien_he | dang_tu_van | da_chot | dong
            $table->string('trang_thai')->default('moi');
            $table->text('ghi_chu_admin')->nullable();

            $table->timestamp('thoi_diem_lien_he')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['trang_thai', 'nhan_vien_phu_trach_id']);
            $table->index(['bat_dong_san_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_cau_lien_he');
    }
};
