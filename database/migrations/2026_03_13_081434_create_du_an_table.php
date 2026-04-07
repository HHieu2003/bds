<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('du_an', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khu_vuc_id')
                ->nullable()
                ->constrained('khu_vuc')
                ->nullOnDelete();

            $table->string('ten_du_an');
            $table->string('slug')->unique();

            // 1 cot dia chi duy nhat de hien thi
            $table->string('dia_chi')->nullable();

            $table->string('chu_dau_tu')->nullable();
            $table->string('don_vi_thi_cong')->nullable();

            $table->text('mo_ta_ngan')->nullable();
            $table->longText('noi_dung_chi_tiet')->nullable();

            $table->string('hinh_anh_dai_dien')->nullable();
            $table->text('map_url')->nullable();

            $table->boolean('noi_bat')->default(false);
            $table->boolean('hien_thi')->default(true);
            $table->unsignedInteger('thu_tu_hien_thi')->default(0);

            // sap_mo_ban | dang_mo_ban | da_ban_het
            $table->string('trang_thai')->default('dang_mo_ban');

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            $table->timestamp('thoi_diem_dang')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['khu_vuc_id', 'trang_thai']);
            $table->index(['hien_thi', 'noi_bat', 'thu_tu_hien_thi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('du_an');
    }
};
