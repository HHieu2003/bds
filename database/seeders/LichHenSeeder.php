<?php

namespace Database\Seeders;

use App\Models\BatDongSan;
use App\Models\KhachHang;
use App\Models\LichHen;
use App\Models\NhanVien;
use Illuminate\Database\Seeder;

class LichHenSeeder extends Seeder
{
    public function run(): void
    {
        $khachHang  = KhachHang::all();
        $bdsIds     = BatDongSan::pluck('id')->toArray();
        $saleIds    = NhanVien::where('vai_tro', 'sale')->pluck('id')->toArray();
        $nguonIds   = NhanVien::where('vai_tro', 'nguon')->pluck('id')->toArray();

        $lichHen = [
            ['ngay' => now()->addDays(1)->setTime(9, 0),  'tt' => 'da_xac_nhan'],
            ['ngay' => now()->addDays(1)->setTime(14, 0), 'tt' => 'da_xac_nhan'],
            ['ngay' => now()->addDays(2)->setTime(10, 0), 'tt' => 'cho_xac_nhan'],
            ['ngay' => now()->addDays(3)->setTime(15, 0), 'tt' => 'cho_xac_nhan'],
            ['ngay' => now()->subDays(2)->setTime(9, 0),  'tt' => 'hoan_thanh'],
            ['ngay' => now()->subDays(5)->setTime(14, 0), 'tt' => 'hoan_thanh'],
            ['ngay' => now()->subDays(1)->setTime(10, 0), 'tt' => 'huy'],
            ['ngay' => now()->addDays(5)->setTime(9, 30), 'tt' => 'moi_dat'],
        ];

        foreach ($lichHen as $i => $lh) {
            $kh = $khachHang[$i % $khachHang->count()];
            LichHen::create([
                'khach_hang_id'           => $kh->id,
                'bat_dong_san_id'         => $bdsIds[$i % count($bdsIds)],
                'nhan_vien_sale_id'       => $saleIds[$i % count($saleIds)],
                'nhan_vien_nguon_hang_id' => $nguonIds[$i % count($nguonIds)],
                'ten_khach_hang'          => $kh->ho_ten,
                'sdt_khach_hang'          => $kh->so_dien_thoai,
                'email_khach_hang'        => $kh->email,
                'thoi_gian_hen'           => $lh['ngay'],
                'dia_diem_hen'            => 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông',
                'ghi_chu_sale'            => 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.',
                'nguon_dat_lich'          => ['website', 'phone', 'sale', 'chat'][$i % 4],
                'trang_thai'              => $lh['tt'],
                'xac_nhan_at'             => in_array($lh['tt'], ['da_xac_nhan', 'hoan_thanh']) ? now() : null,
                'hoan_thanh_at'           => $lh['tt'] === 'hoan_thanh' ? now() : null,
                'huy_at'                  => $lh['tt'] === 'huy' ? now() : null,
            ]);
        }

        $this->command->info('✅ LichHen: ' . count($lichHen) . ' bản ghi');
    }
}
