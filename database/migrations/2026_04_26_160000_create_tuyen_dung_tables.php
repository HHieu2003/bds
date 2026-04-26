<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Bảng Tin Tuyển Dụng ──
        Schema::create('tin_tuyen_dung', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->string('phong_ban')->nullable();          // Phòng kinh doanh, Marketing...
            $table->string('hinh_thuc');                       // toan_thoi_gian, ban_thoi_gian, thuc_tap
            $table->string('dia_diem')->nullable();            // Hà Nội, HCM...
            $table->integer('so_luong')->default(1);
            $table->string('muc_luong')->nullable();           // "6 - 10 Triệu" hoặc "Thỏa thuận"
            $table->string('tag')->nullable();                 // "Hot - Tuyển Gấp", "Khối Văn Phòng"
            $table->string('tag_color')->default('danger');    // danger, primary, success, warning...
            $table->text('mo_ta_ngan')->nullable();
            $table->longText('mo_ta_cong_viec')->nullable();
            $table->longText('yeu_cau')->nullable();
            $table->longText('quyen_loi')->nullable();
            $table->date('han_nop')->nullable();
            $table->boolean('hien_thi')->default(true);
            $table->boolean('noi_bat')->default(false);
            $table->integer('thu_tu')->default(0);
            $table->unsignedBigInteger('nhan_vien_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nhan_vien_id')->references('id')->on('nhan_vien')->nullOnDelete();
        });

        // ── Bảng Đơn Ứng Tuyển ──
        Schema::create('don_ung_tuyen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tin_tuyen_dung_id');
            $table->string('ho_ten');
            $table->string('email');
            $table->string('so_dien_thoai');
            $table->integer('nam_sinh')->nullable();
            $table->string('link_cv')->nullable();             // Link Google Drive, TopCV...
            $table->string('file_cv')->nullable();             // File upload lên R2
            $table->text('gioi_thieu')->nullable();
            $table->string('trang_thai')->default('moi');      // moi, dang_xem_xet, hen_phong_van, trung_tuyen, tu_choi
            $table->text('ghi_chu_admin')->nullable();
            $table->unsignedBigInteger('nhan_vien_xu_ly_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tin_tuyen_dung_id')->references('id')->on('tin_tuyen_dung')->cascadeOnDelete();
            $table->foreign('nhan_vien_xu_ly_id')->references('id')->on('nhan_vien')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_ung_tuyen');
        Schema::dropIfExists('tin_tuyen_dung');
    }
};
