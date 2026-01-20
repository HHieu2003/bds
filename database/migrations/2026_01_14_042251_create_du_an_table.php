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
            $table->string('ten_du_an');
            $table->string('slug')->unique(); // Quan trọng cho SEO: du-an/vinhomes-ocean-park
            $table->string('dia_chi');
            $table->string('chu_dau_tu')->nullable();
            $table->text('mo_ta_ngan')->nullable(); // Hiển thị ở danh sách
            $table->longText('noi_dung_chi_tiet')->nullable(); // Hiển thị ở trang chi tiết
            $table->string('hinh_anh_dai_dien')->nullable(); // Ảnh bìa
            $table->json('album_anh')->nullable(); // Lưu nhiều ảnh dưới dạng JSON
            $table->string('dien_tich_tong_the')->nullable();
            $table->string('map_url')->nullable(); // Link Google Maps iframe
            $table->string('trang_thai')->default('dang_mo_ban'); // sap_mo_ban, dang_mo_ban, da_ban_het
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('du_an');
    }
};
