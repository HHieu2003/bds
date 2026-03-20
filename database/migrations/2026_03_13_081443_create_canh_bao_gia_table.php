<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canh_bao_gia', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->cascadeOnDelete();

            $table->foreignId('bat_dong_san_id')
                ->constrained('bat_dong_san')
                ->cascadeOnDelete();

            $table->string('email');
            $table->decimal('gia_mong_muon', 15, 2)->nullable();

            // Snapshot gia tai thoi diem dang ky de so sanh khi gui thong bao
            $table->decimal('gia_luc_dang_ky', 15, 2)->nullable();
            $table->boolean('da_gui_thong_bao')->default(false);
            $table->timestamp('thoi_diem_gui')->nullable();
            $table->timestamps();

            $table->index(['bat_dong_san_id', 'da_gui_thong_bao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canh_bao_gia');
    }
};
