<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm composite indexes cho các hotpath query thường xuyên nhất.
     * Giảm thời gian quét bảng cho trang Home, BDS List, BDS Detail.
     */
    public function up(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            // Index cho homepage & listing: WHERE hien_thi=1 AND nhu_cau=? AND trang_thai=?
            $table->index(['hien_thi', 'nhu_cau', 'trang_thai', 'created_at'], 'idx_bds_listing_filter');

            // Index cho featured: WHERE hien_thi=1 AND noi_bat=1 AND trang_thai=?
            $table->index(['hien_thi', 'trang_thai', 'noi_bat', 'thu_tu_hien_thi'], 'idx_bds_featured');

            // Index cho slug lookup: WHERE slug=? AND hien_thi=1
            $table->index(['slug', 'hien_thi'], 'idx_bds_slug_hienthi');
        });
    }

    public function down(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            $table->dropIndex('idx_bds_listing_filter');
            $table->dropIndex('idx_bds_featured');
            $table->dropIndex('idx_bds_slug_hienthi');
        });
    }
};
