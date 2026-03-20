<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_tim_kiem', function (Blueprint $table) {
            $table->id();

            // Doi cascadeOnDelete → nullOnDelete
            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete(); // ← sua lai


            $table->string('session_id')->nullable();
            $table->string('tu_khoa')->nullable();

            // Luu toan bo bo loc duoi dang JSON: loai_hinh, gia_tu, gia_den, khu_vuc...
            $table->json('bo_loc')->nullable();
            $table->string('sap_xep_theo')->nullable();
            $table->unsignedInteger('so_ket_qua')->default(0);

            $table->timestamp('thoi_diem_tim_kiem')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->index(['khach_hang_id', 'thoi_diem_tim_kiem']);
            $table->index(['session_id', 'thoi_diem_tim_kiem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_tim_kiem');
    }
};
