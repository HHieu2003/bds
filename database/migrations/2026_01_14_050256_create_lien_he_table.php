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
        Schema::create('lien_he', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('email');
            $table->string('so_dien_thoai');
            $table->text('noi_dung');

            // Quan tâm đến BĐS nào? (Optional)
            $table->foreignId('bat_dong_san_id')->nullable()->constrained('bat_dong_san')->onDelete('set null');

            $table->string('trang_thai')->default('moi'); // moi, da_lien_he, da_chot
            $table->text('ghi_chu_admin')->nullable(); // Admin note lại kết quả tư vấn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_he');
    }
};
