<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ky_gui', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->foreignId('nhan_vien_phu_trach_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // =============================================
            // THONG TIN CHU NHA (snapshot)
            // =============================================
            $table->string('ho_ten_chu_nha');
            $table->string('so_dien_thoai');
            $table->string('email')->nullable();

            // =============================================
            // PHAN LOAI
            // =============================================
            // can_ho | nha_pho | biet_thu | dat_nen | shophouse
            $table->string('loai_hinh');
            // ban | thue
            $table->string('nhu_cau')->default('ban');

            // =============================================
            // THONG TIN BDS KY GUI
            // =============================================
            // 1 cot dia chi duy nhat (da chot o bat_dong_san)
            $table->string('dia_chi')->nullable();

            $table->decimal('dien_tich', 10, 2);
            $table->string('huong_nha')->nullable();
            $table->unsignedSmallInteger('so_phong_ngu')->default(0);
            $table->unsignedSmallInteger('so_phong_tam')->default(0);
            // co_ban | full | cao_cap | nguyen_ban
            $table->string('noi_that')->nullable();

            // =============================================
            // GIA BAN
            // (NULL khi nhu_cau = 'thue')
            // =============================================
            $table->decimal('gia_ban_mong_muon', 15, 2)->nullable();
            // so_hong | so_do | hop_dong | chua_co
            $table->string('phap_ly')->nullable();

            // =============================================
            // GIA THUE
            // (NULL khi nhu_cau = 'ban')
            // =============================================
            $table->decimal('gia_thue_mong_muon', 15, 2)->nullable();
            // tien_mat | chuyen_khoan | 3_coc_1 | theo_quy | theo_nam
            $table->string('hinh_thuc_thanh_toan')->nullable();

            // =============================================
            // HINH ANH THAM KHAO
            // =============================================
            $table->json('hinh_anh_tham_khao')->nullable();
            $table->text('ghi_chu')->nullable();

            // =============================================
            // QUY TRINH XU LY NOI BO
            // =============================================
            // website | phone | sale | zalo | walk_in
            $table->string('nguon_ky_gui')->default('website');

            // cho_duyet | da_lien_he | da_nhan | tu_choi
            $table->string('trang_thai')->default('cho_duyet');
            $table->text('phan_hoi_cua_admin')->nullable();
            $table->timestamp('thoi_diem_xu_ly')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['nhu_cau', 'trang_thai']);
            $table->index(['nhan_vien_phu_trach_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ky_gui');
    }
};
