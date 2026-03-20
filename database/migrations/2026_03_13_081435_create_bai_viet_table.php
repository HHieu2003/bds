<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bai_viet', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nhan_vien_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->text('mo_ta_ngan')->nullable();
            $table->longText('noi_dung');

            $table->string('hinh_anh')->nullable();
            $table->json('album_anh')->nullable();

            // tin_tuc | phong_thuy | tuyen_dung | kien_thuc
            $table->string('loai_bai_viet')->default('tin_tuc');

            $table->boolean('noi_bat')->default(false);
            $table->boolean('hien_thi')->default(true);
            $table->unsignedBigInteger('luot_xem')->default(0);
            $table->unsignedInteger('thu_tu_hien_thi')->default(0);

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            $table->timestamp('thoi_diem_dang')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['loai_bai_viet', 'hien_thi', 'noi_bat']);
            $table->index(['thoi_diem_dang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_viet');
    }
};
