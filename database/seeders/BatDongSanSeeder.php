<?php

namespace Database\Seeders;

use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\NhanVien;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BatDongSanSeeder extends Seeder
{
    public function run(): void
    {
        $duAnIds  = DuAn::pluck('id')->toArray();
        $nvIds    = NhanVien::whereIn('vai_tro', ['nguon', 'admin'])->pluck('id')->toArray();

        $loaiHinh = ['can_ho', 'nha_pho', 'biet_thu', 'dat_nen', 'shophouse'];
        $noiThat  = ['co_ban', 'full', 'cao_cap', 'nguyen_ban'];
        $phapLy   = ['so_hong', 'so_do', 'hop_dong'];
        $huong    = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];

        // --------- BĐS BÁN ---------
        $bdsBan = [
            ['tieu_de' => 'Căn hộ 2PN Vinhomes Smart City view hồ', 'loai' => 'can_ho', 'dt' => 65.5, 'pn' => 2, 'gia' => 2800000000, 'ma_can' => 'VS-A1-1201'],
            ['tieu_de' => 'Căn hộ 3PN Vinhomes Smart City tầng cao', 'loai' => 'can_ho', 'dt' => 89.2, 'pn' => 3, 'gia' => 4200000000, 'ma_can' => 'VS-B2-2501'],
            ['tieu_de' => 'Nhà phố Hà Đông 4 tầng mặt tiền đường lớn', 'loai' => 'nha_pho', 'dt' => 82.0, 'pn' => 4, 'gia' => 7500000000, 'ma_can' => null],
            ['tieu_de' => 'Biệt thự đơn lập Thanh Hà Cienco 5', 'loai' => 'biet_thu', 'dt' => 200.0, 'pn' => 5, 'gia' => 15000000000, 'ma_can' => 'TH-BT-001'],
            ['tieu_de' => 'Căn hộ 1PN+1 Mipec Rubik 360 giá tốt', 'loai' => 'can_ho', 'dt' => 52.3, 'pn' => 1, 'gia' => 2100000000, 'ma_can' => 'MR-T5-0803'],
            ['tieu_de' => 'Đất nền liền kề Thanh Hà 80m² sổ đỏ', 'loai' => 'dat_nen', 'dt' => 80.0, 'pn' => 0, 'gia' => 3200000000, 'ma_can' => null],
            ['tieu_de' => 'Shophouse An Bình City tầng 1 kinh doanh', 'loai' => 'shophouse', 'dt' => 95.0, 'pn' => 0, 'gia' => 6800000000, 'ma_can' => 'AB-SH-012'],
            ['tieu_de' => 'Căn hộ 2PN An Bình City full nội thất', 'loai' => 'can_ho', 'dt' => 70.8, 'pn' => 2, 'gia' => 2500000000, 'ma_can' => 'AB-CT1-1502'],
            ['tieu_de' => 'Nhà phố Cầu Giấy 5 tầng gần Keangnam', 'loai' => 'nha_pho', 'dt' => 55.0, 'pn' => 5, 'gia' => 12000000000, 'ma_can' => null],
            ['tieu_de' => 'Căn hộ 3PN Eurowindow Twin Parks', 'loai' => 'can_ho', 'dt' => 93.5, 'pn' => 3, 'gia' => 3500000000, 'ma_can' => 'ET-T2-1801'],
        ];

        // --------- BĐS THUÊ ---------
        $bdsThue = [
            ['tieu_de' => 'Cho thuê căn hộ 2PN Vinhomes Smart City đầy đủ nội thất', 'loai' => 'can_ho', 'dt' => 65.5, 'pn' => 2, 'gia_thue' => 12000000],
            ['tieu_de' => 'Cho thuê căn hộ 1PN Mipec Rubik 360 tầng cao', 'loai' => 'can_ho', 'dt' => 50.0, 'pn' => 1, 'gia_thue' => 9000000],
            ['tieu_de' => 'Cho thuê nhà phố Hà Đông 4 tầng làm văn phòng', 'loai' => 'nha_pho', 'dt' => 80.0, 'pn' => 0, 'gia_thue' => 25000000],
            ['tieu_de' => 'Cho thuê shophouse An Bình City kinh doanh', 'loai' => 'shophouse', 'dt' => 90.0, 'pn' => 0, 'gia_thue' => 35000000],
            ['tieu_de' => 'Cho thuê căn hộ 3PN Vinhomes Smart City cao cấp', 'loai' => 'can_ho', 'dt' => 89.0, 'pn' => 3, 'gia_thue' => 18000000],
            ['tieu_de' => 'Cho thuê nhà nguyên căn Thanh Xuân 3 tầng', 'loai' => 'nha_pho', 'dt' => 68.0, 'pn' => 3, 'gia_thue' => 20000000],
        ];

        $count = 0;

        // Thêm BĐS bán
        foreach ($bdsBan as $i => $bds) {
            BatDongSan::create([
                'du_an_id'               => $duAnIds[$i % count($duAnIds)],
                'nhan_vien_phu_trach_id' => $nvIds[$i % count($nvIds)],
                'tieu_de'                => $bds['tieu_de'],
                'slug'                   => Str::slug($bds['tieu_de']) . '-' . ($i + 1),
                'ma_bat_dong_san'        => 'BDS-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'loai_hinh'              => $bds['loai'],
                'nhu_cau'                => 'ban',
                'ma_can'                 => $bds['ma_can'],
                'toa'                    => $bds['loai'] === 'can_ho' ? 'Tòa ' . chr(65 + ($i % 4)) : null,
                'tang'                   => $bds['loai'] === 'can_ho' ? 'Tầng ' . rand(5, 30) : null,
                'huong_cua'              => $huong[array_rand($huong)],
                'dien_tich'              => $bds['dt'],
                'so_phong_ngu'           => $bds['pn'],
                'noi_that'               => $noiThat[array_rand($noiThat)],
                'mo_ta'                  => '<p>' . $bds['tieu_de'] . '. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>',
                'hinh_anh'               => null,
                'gia'                    => $bds['gia'],
                'phi_moi_gioi'           => round($bds['gia'] * 0.01),
                'phap_ly'                => $phapLy[array_rand($phapLy)],
                'noi_bat'                => $i < 4,
                'hien_thi'               => true,
                'trang_thai'             => $i === 3 ? 'dat_coc' : 'con_hang',
                'luot_xem'               => rand(50, 500),
                'thu_tu_hien_thi'        => $i + 1,
                'seo_title'              => $bds['tieu_de'] . ' - Thành Công Land',
                'seo_description'        => 'Chi tiết ' . $bds['tieu_de'],
                'thoi_diem_dang'         => now()->subDays(rand(1, 60)),
            ]);
            $count++;
        }

        // Thêm BĐS thuê
        foreach ($bdsThue as $i => $bds) {
            BatDongSan::create([
                'du_an_id'               => $duAnIds[$i % count($duAnIds)],
                'nhan_vien_phu_trach_id' => $nvIds[$i % count($nvIds)],
                'tieu_de'                => $bds['tieu_de'],
                'slug'                   => Str::slug($bds['tieu_de']) . '-th-' . ($i + 1),
                'ma_bat_dong_san'        => 'BDS-TH-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'loai_hinh'              => $bds['loai'],
                'nhu_cau'                => 'thue',
                'dien_tich'              => $bds['dt'],
                'so_phong_ngu'           => $bds['pn'],
                'noi_that'               => 'full',
                'mo_ta'                  => '<p>' . $bds['tieu_de'] . '. Đầy đủ tiện nghi, an ninh 24/7. Liên hệ ngay để xem nhà.</p>',
                'gia_thue'               => $bds['gia_thue'],
                'thoi_gian_vao_thue'     => 'vao_luon',
                'hinh_thuc_thanh_toan'   => '3_coc_1',
                'noi_bat'                => $i < 2,
                'hien_thi'               => true,
                'trang_thai'             => 'con_hang',
                'luot_xem'               => rand(30, 300),
                'thu_tu_hien_thi'        => $i + 11,
                'seo_title'              => $bds['tieu_de'] . ' - Thành Công Land',
                'seo_description'        => 'Chi tiết ' . $bds['tieu_de'],
                'thoi_diem_dang'         => now()->subDays(rand(1, 45)),
            ]);
            $count++;
        }

        $this->command->info('✅ BatDongSan: ' . $count . ' bản ghi');
    }
}
