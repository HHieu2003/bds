<?php

namespace Database\Seeders;

use App\Models\BatDongSan;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\YeuCauLienHe;
use Illuminate\Database\Seeder;

class YeuCauLienHeSeeder extends Seeder
{
    public function run(): void
    {
        $khachHang = KhachHang::all();
        $bdsIds    = BatDongSan::pluck('id')->toArray();
        $saleIds   = NhanVien::where('vai_tro', 'sale')->pluck('id')->toArray();
        $trangThai = ['moi', 'da_lien_he', 'dang_tu_van', 'da_chot'];
        $nguon     = ['website', 'hotline', 'chat', 'form_bds'];

        foreach ($khachHang->take(8) as $i => $kh) {
            YeuCauLienHe::create([
                'khach_hang_id'          => $kh->id,
                'bat_dong_san_id'        => $bdsIds[$i % count($bdsIds)],
                'nhan_vien_phu_trach_id' => $saleIds[$i % count($saleIds)],
                'ho_ten'                 => $kh->ho_ten,
                'so_dien_thoai'          => $kh->so_dien_thoai,
                'email'                  => $kh->email,
                'noi_dung'               => 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.',
                'nguon_lien_he'          => $nguon[$i % count($nguon)],
                'muc_do_quan_tam'        => ['cao', 'trung_binh', 'thap'][$i % 3],
                'trang_thai'             => $trangThai[$i % count($trangThai)],
                'thoi_diem_lien_he'      => now()->subHours(rand(1, 720)),
            ]);
        }

        $this->command->info('✅ YeuCauLienHe: 8 bản ghi');
    }
}
