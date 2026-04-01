<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tin_nhan_noi_bo', function (Blueprint $table) {
            $table->id();

            // Nguoi gui (nhan vien nao cung co the gui)
            $table->foreignId('nguoi_gui_id')
                ->constrained('nhan_vien')
                ->cascadeOnDelete();

            // Nguoi nhan
            $table->foreignId('nguoi_nhan_id')
                ->constrained('nhan_vien')
                ->cascadeOnDelete();

            // Lien ket voi lich hen neu nhan tin lien quan den lich hen cu the
            $table->foreignId('lich_hen_id')
                ->nullable()
                ->constrained('lich_hen')
                ->nullOnDelete();

            $table->foreignId('bat_dong_san_id')
                ->nullable()
                ->constrained('bat_dong_san')
                ->nullOnDelete();

            $table->foreignId('du_an_id')
                ->nullable()
                ->constrained('du_an')
                ->nullOnDelete();

            $table->foreignId('khach_hang_lq_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            // van_ban | hinh_anh | dinh_kem
            $table->string('loai_tin_nhan')->default('van_ban');
            $table->text('noi_dung')->nullable();
            $table->string('tep_dinh_kem')->nullable();

            $table->boolean('da_doc')->default(false);
            $table->timestamp('da_doc_at')->nullable();

            $table->timestamps();

            $table->index(['nguoi_gui_id', 'nguoi_nhan_id', 'created_at']);
            $table->index(['nguoi_nhan_id', 'da_doc']);
            $table->index(['lich_hen_id']);
            $table->index(['bat_dong_san_id']);
            $table->index(['du_an_id']);
            $table->index(['khach_hang_lq_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_nhan_noi_bo');
    }
};
