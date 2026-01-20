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
        Schema::create('bai_viet', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->string('slug')->unique(); // duong-dan-bai-viet
            $table->text('mo_ta_ngan')->nullable(); // Sapo
            $table->longText('noi_dung'); // Nội dung bài viết (dùng CKEditor)
            $table->string('hinh_anh')->nullable();

            // Phân loại: tin_tuc, phong_thuy, tuyen_dung, kien_thuc
            $table->string('loai_bai_viet')->default('tin_tuc');

            $table->boolean('hien_thi')->default(true);
            $table->integer('luot_xem')->default(0);

            // Người viết bài
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_viet');
    }
};
