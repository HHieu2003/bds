<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bat_dong_san', function (Blueprint $table) {
            $table->id();

            $table->foreignId('du_an_id')
                ->nullable()
                ->constrained('du_an')
                ->nullOnDelete();

            $table->foreignId('nhan_vien_phu_trach_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();
            $table->foreignId('chu_nha_id')
                ->nullable()
                ->constrained('chu_nha')->nullOnDelete();
            // =============================================
            // DINH DANH & SEO
            // =============================================
            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->string('ma_bat_dong_san')->unique();

            // =============================================
            // PHAN LOAI
            // =============================================
            // can_ho | nha_pho | biet_thu | dat_nen | shophouse
            $table->string('loai_hinh')->default('can_ho');
            // ban | thue
            $table->string('nhu_cau')->default('ban');

            // =============================================
            // THONG TIN CHUNG
            // =============================================
            $table->string('ma_can')->nullable();
            $table->string('toa')->nullable();
            $table->string('tang')->nullable();

            $table->string('huong_cua')->nullable();
            $table->string('huong_ban_cong')->nullable();

            $table->decimal('dien_tich', 10, 2);

            $table->string('so_phong_ngu', 20)->nullable()->default(null);

            // co_ban | full | cao_cap | nguyen_ban
            $table->string('noi_that')->nullable();

            $table->longText('mo_ta')->nullable();
            $table->longText('ghi_chu_noi_bo')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->json('album_anh')->nullable();
            $table->json('album_video')->nullable()->comment('Danh sach duong dan video upload');

            // =============================================
            // THONG TIN BAN
            // (NULL khi nhu_cau = 'thue')
            // =============================================
            $table->decimal('gia', 15, 2)->nullable();
            $table->decimal('phi_moi_gioi', 15, 2)->nullable();
            $table->decimal('phi_sang_ten', 15, 2)->nullable();
            // so_hong | so_do | hop_dong | chua_co
            $table->string('phap_ly')->nullable();

            // =============================================
            // THONG TIN THUE
            // (NULL khi nhu_cau = 'ban')
            // =============================================
            $table->decimal('gia_thue', 15, 2)->nullable();
            // vao_luon | thang_X | sau_ngay_X
            $table->string('thoi_gian_vao_thue')->nullable();
            // tien_mat | chuyen_khoan | 3_coc_1 | 3_coc_10tr
            $table->string('hinh_thuc_thanh_toan')->nullable();

            // =============================================
            // TRANG THAI & HIEN THI
            // =============================================
            $table->boolean('noi_bat')->default(false);
            $table->boolean('gui_mail_canh_bao_gia')->default(true);
            $table->boolean('hien_thi')->default(true);
            // con_hang | dat_coc | da_ban | dang_thue | da_thue | tam_an
            $table->string('trang_thai')->default('con_hang');
            $table->unsignedBigInteger('luot_xem')->default(0);
            $table->unsignedInteger('thu_tu_hien_thi')->default(0);

            // =============================================
            // SEO
            // =============================================
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            // =============================================
            // THOI GIAN
            // created_at = ngay tao
            // updated_at = ngay cap nhat (tu dong, khong can them cot)
            // =============================================
            $table->timestamp('thoi_diem_dang')->nullable();
            $table->timestamps(); // created_at + updated_at
            $table->softDeletes();

            // =============================================
            // INDEX
            // =============================================
            $table->index(['du_an_id', 'trang_thai']);
            $table->index(['nhan_vien_phu_trach_id']);
            $table->index(['loai_hinh', 'nhu_cau', 'trang_thai']);
            $table->index(['gia', 'dien_tich']);
            $table->index(['gia_thue', 'dien_tich']);
            $table->index(['noi_bat', 'hien_thi', 'thu_tu_hien_thi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bat_dong_san');
    }
};
