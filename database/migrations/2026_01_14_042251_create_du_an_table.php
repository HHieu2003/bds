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

            // Thông tin dự án
            $table->string('ten_du_an');          // VD: Vinhomes Smart City
            $table->string('dia_chi')->nullable(); // VD: Tây Mỗ, Nam Từ Liêm...
            $table->string('chu_dau_tu')->nullable();
            $table->string('don_vi_thi_cong')->nullable();
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh')->nullable(); // Lưu đường dẫn ảnh
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('du_an');
    }
};
