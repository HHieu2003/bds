<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dung UUID theo chuan Laravel Notifications
        Schema::create('thong_bao', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Loai thong bao: lich_hen_moi | yeu_cau_lien_he | ky_gui_moi | tin_nhan_chat
            $table->string('loai');

            // nhan_vien | khach_hang
            $table->string('doi_tuong_nhan');
            $table->unsignedBigInteger('doi_tuong_nhan_id');

            $table->string('tieu_de')->nullable();
            $table->text('noi_dung')->nullable();

            // Du lieu kem theo: { "lich_hen_id": 5, "bat_dong_san_id": 12 }
            $table->json('du_lieu')->nullable();
            $table->string('lien_ket')->nullable();

            $table->timestamp('da_doc_at')->nullable();
            $table->timestamps();

            $table->index(['doi_tuong_nhan', 'doi_tuong_nhan_id', 'da_doc_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thong_bao');
    }
};
