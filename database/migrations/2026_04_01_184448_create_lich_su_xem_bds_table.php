<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lich_su_xem_bds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bat_dong_san_id')->constrained('bat_dong_san')->onDelete('cascade');
            $table->unsignedBigInteger('khach_hang_id')->nullable(); // null = khách vãng lai
            $table->string('session_id', 100)->nullable();           // track khách chưa đăng nhập
            $table->string('loai_hinh', 50)->nullable();             // chung_cu, nha_pho, ...
            $table->string('nhu_cau', 20)->nullable();               // ban / thue
            $table->unsignedBigInteger('du_an_id')->nullable();      // thuộc dự án nào
            $table->unsignedBigInteger('khu_vuc_id')->nullable();    // thuộc khu vực nào
            $table->decimal('gia_tu', 15, 2)->nullable();            // khoảng giá
            $table->decimal('gia_den', 15, 2)->nullable();
            $table->integer('thoi_gian_xem')->default(0);            // giây ở lại trang
            $table->timestamps();

            $table->index(['khach_hang_id', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index('bat_dong_san_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_xem_bds');
    }
};
