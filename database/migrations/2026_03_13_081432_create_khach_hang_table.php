<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten')->nullable();
            $table->string('so_dien_thoai')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();

            // website | chat | lien_he | ky_gui | sale
            $table->string('nguon_khach_hang')->default('website');

            // lanh | am | nong
            $table->string('muc_do_tiem_nang')->default('am');

            $table->foreignId('nhan_vien_phu_trach_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            $table->text('ghi_chu_noi_bo')->nullable();
            $table->boolean('kich_hoat')->default(true);
            $table->timestamp('sdt_xac_thuc_at')->nullable();
            $table->timestamp('email_xac_thuc_at')->nullable();
            $table->timestamp('lien_he_cuoi_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['muc_do_tiem_nang', 'nhan_vien_phu_trach_id']);
            $table->index(['nguon_khach_hang', 'kich_hoat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khach_hang');
    }
};
