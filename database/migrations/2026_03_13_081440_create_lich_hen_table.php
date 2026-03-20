<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_hen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->foreignId('bat_dong_san_id')
                ->constrained('bat_dong_san')
                ->cascadeOnDelete();

            // Sale: nguoi tao lich hen nay
            $table->foreignId('nhan_vien_sale_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // Nguon hang: nguoi nhan & xac nhan lich
            $table->foreignId('nhan_vien_nguon_hang_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // Snapshot thong tin khach (de bao ve du lieu khi khach xoa tai khoan)
            $table->string('ten_khach_hang');
            $table->string('sdt_khach_hang');
            $table->string('email_khach_hang')->nullable();

            $table->dateTime('thoi_gian_hen');
            $table->string('dia_diem_hen')->nullable();
            $table->text('ghi_chu_sale')->nullable();
            $table->text('ghi_chu_nguon_hang')->nullable();

            // website | phone | chat | sale
            $table->string('nguon_dat_lich')->default('sale');

            // moi_dat | cho_xac_nhan | da_xac_nhan | hoan_thanh | huy | tu_choi
            $table->string('trang_thai')->default('moi_dat');

            $table->text('ly_do_tu_choi')->nullable();

            $table->timestamp('xac_nhan_at')->nullable();
            $table->timestamp('tu_choi_at')->nullable();
            $table->timestamp('hoan_thanh_at')->nullable();
            $table->timestamp('huy_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['thoi_gian_hen', 'trang_thai']);
            $table->index(['nhan_vien_sale_id', 'trang_thai']);
            $table->index(['nhan_vien_nguon_hang_id', 'trang_thai']);
            $table->index(['khach_hang_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_hen');
    }
};
