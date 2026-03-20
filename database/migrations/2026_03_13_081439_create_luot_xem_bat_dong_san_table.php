<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('luot_xem_bat_dong_san', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bat_dong_san_id')
                ->constrained('bat_dong_san')
                ->cascadeOnDelete();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->string('session_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // direct | google | facebook | zalo | referral
            $table->string('nguon_truy_cap')->nullable();
            $table->string('url_trang_truoc')->nullable();

            // desktop | mobile | tablet
            $table->string('thiet_bi')->nullable();

            $table->timestamp('thoi_diem_xem')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->index(['bat_dong_san_id', 'thoi_diem_xem']);
            $table->index(['khach_hang_id', 'thoi_diem_xem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luot_xem_bat_dong_san');
    }
};
