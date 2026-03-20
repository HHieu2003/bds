<?php

namespace Database\Seeders;

use App\Models\DuAn;
use App\Models\KhuVuc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DuAnSeeder extends Seeder
{
    public function run(): void
    {
        $khuVuc = KhuVuc::where('cap_khu_vuc', 'quan_huyen')->get()->keyBy('slug');

        $duAn = [
            [
                'ten'        => 'Vinhomes Smart City',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Đại lộ Thăng Long, Nam Từ Liêm, Hà Nội',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
            ],
            [
                'ten'        => 'Mipec Rubik 360',
                'slug_kv'    => 'cau-giay',
                'dia_chi'    => 'Xuân Thủy, Cầu Giấy, Hà Nội',
                'chu_dt'     => 'Mipec',
                'thi_cong'   => 'Hòa Bình',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
            ],
            [
                'ten'        => 'An Bình City',
                'slug_kv'    => 'ha-dong',
                'dia_chi'    => 'Phạm Văn Đồng, Bắc Từ Liêm, Hà Nội',
                'chu_dt'     => 'GeleximcoVietnam',
                'thi_cong'   => 'Delta',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
            ],
            [
                'ten'        => 'Thanh Hà Cienco 5',
                'slug_kv'    => 'ha-dong',
                'dia_chi'    => 'Cienco 5, Mỗ Lao, Hà Đông, Hà Nội',
                'chu_dt'     => 'Cienco 5',
                'thi_cong'   => 'Cienco 5',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
            ],
            [
                'ten'        => 'Eurowindow Twin Parks',
                'slug_kv'    => 'long-bien',
                'dia_chi'    => 'Trâu Quỳ, Long Biên, Hà Nội',
                'chu_dt'     => 'Eurowindow',
                'thi_cong'   => 'Eurowindow',
                'trang_thai' => 'sap_mo_ban',
                'noi_bat'    => false,
            ],
        ];

        foreach ($duAn as $i => $da) {
            DuAn::create([
                'khu_vuc_id'          => $khuVuc[$da['slug_kv']]->id ?? null,
                'ten_du_an'           => $da['ten'],
                'slug'                => Str::slug($da['ten']) . '-' . ($i + 1),
                'dia_chi'             => $da['dia_chi'],
                'chu_dau_tu'          => $da['chu_dt'],
                'don_vi_thi_cong'     => $da['thi_cong'],
                'mo_ta_ngan'          => 'Dự án ' . $da['ten'] . ' - khu đô thị hiện đại, tiện nghi đầy đủ.',
                'noi_dung_chi_tiet'   => '<p>Dự án <strong>' . $da['ten'] . '</strong> tọa lạc tại ' . $da['dia_chi'] . '. Với thiết kế hiện đại, hệ thống tiện ích đẳng cấp, đây là lựa chọn lý tưởng cho gia đình bạn.</p>',
                'hien_thi'            => true,
                'noi_bat'             => $da['noi_bat'],
                'trang_thai'          => $da['trang_thai'],
                'thu_tu_hien_thi'     => $i + 1,
                'seo_title'           => $da['ten'] . ' - Thành Công Land',
                'seo_description'     => 'Thông tin chi tiết dự án ' . $da['ten'],
                'thoi_diem_dang'      => now()->subDays(rand(10, 180)),
            ]);
        }

        $this->command->info('✅ DuAn: ' . count($duAn) . ' bản ghi');
    }
}
