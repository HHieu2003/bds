<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ghi_chu_khach_hang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->constrained('khach_hang')
                ->cascadeOnDelete();

            $table->foreignId('nhan_vien_id')
                ->constrained('nhan_vien')
                ->cascadeOnDelete();

            $table->foreignId('bat_dong_san_id')
                ->nullable()
                ->constrained('bat_dong_san')
                ->nullOnDelete();

            $table->foreignId('lich_hen_id')
                ->nullable()
                ->constrained('lich_hen')
                ->nullOnDelete();

            $table->text('noi_dung');

            // goi_dien | zalo | email | gap_truc_tiep | chat
            $table->string('kenh_tuong_tac')->nullable();

            // quan_tam | can_goi_lai | da_chot | tu_choi
            $table->string('ket_qua')->nullable();

            // Dat lich nhac nhan vien follow up
            $table->timestamp('nhac_lai_at')->nullable();
            $table->timestamps();

            $table->index(['khach_hang_id', 'created_at']);
            $table->index(['nhan_vien_id', 'nhac_lai_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ghi_chu_khach_hang');
    }
};
