<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tạo bảng Chủ Nhà
        Schema::create('chu_nha', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 20)->unique();
            $table->string('email', 100)->nullable();
            $table->string('cccd', 20)->nullable();
            $table->string('dia_chi')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->foreignId('nhan_vien_phu_trach_id')->nullable()->constrained('nhan_vien')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        // Khi rollback sẽ gỡ bỏ khóa ngoại và xóa bảng
        Schema::table('bat_dong_san', function (Blueprint $table) {
            $table->dropForeign(['chu_nha_id']);
            $table->dropColumn('chu_nha_id');
        });
        Schema::dropIfExists('chu_nha');
    }
};
