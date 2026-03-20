<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_thich', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->cascadeOnDelete();

            $table->foreignId('bat_dong_san_id')
                ->constrained('bat_dong_san')
                ->cascadeOnDelete();

            // Cho khach vang lai chua dang nhap
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->unique(
                ['khach_hang_id', 'bat_dong_san_id'],
                'uniq_yeu_thich_khach_bds'
            );
            $table->unique(
                ['session_id', 'bat_dong_san_id'],
                'uniq_yeu_thich_session_bds'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_thich');
    }
};
