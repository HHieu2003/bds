<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tin_nhan_chat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('phien_chat_id')
                ->constrained('phien_chat')
                ->cascadeOnDelete();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->foreignId('nhan_vien_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // khach_hang | nhan_vien | he_thong
            $table->string('nguoi_gui')->default('khach_hang');

            // van_ban | hinh_anh | dinh_kem | bat_dong_san
            $table->string('loai_tin_nhan')->default('van_ban');

            $table->text('noi_dung')->nullable();
            $table->string('tep_dinh_kem')->nullable();
            $table->boolean('da_doc')->default(false);
            $table->timestamp('da_doc_at')->nullable();

            $table->timestamps();

            $table->index(['phien_chat_id', 'created_at']);
            $table->index(['nhan_vien_id', 'da_doc']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_nhan_chat');
    }
};
