<?php

namespace Database\Seeders;

use App\Models\KhachHang;
use App\Models\KyGui;
use App\Models\NhanVien;
use Illuminate\Database\Seeder;

class KyGuiSeeder extends Seeder
{
    public function run(): void
    {
        $khachHang = KhachHang::all();
        $nvIds     = NhanVien::whereIn('vai_tro', ['nguon', 'admin'])->pluck('id')->toArray();

        $kyGui = [
            [
                'ho_ten'    => 'Nguyễn Văn Chính',
                'sdt'       => '0961111111',
                'loai'      => 'can_ho',
                'nhu_cau'   => 'ban',
                'dia_chi'   => 'Tầng 12 Toà A, Vinhomes Smart City',
                'dt'        => 68.5,
                'gia'       => 2900000000,
                'trang_thai' => 'cho_duyet',
            ],
            [
                'ho_ten'    => 'Trần Thị Dung',
                'sdt'       => '0962222222',
                'loai'      => 'nha_pho',
                'nhu_cau'   => 'ban',
                'dia_chi'   => '25 Ngõ 45, Hà Đông, Hà Nội',
                'dt'        => 52.0,
                'gia'       => 6500000000,
                'trang_thai' => 'da_lien_he',
            ],
            [
                'ho_ten'    => 'Lê Văn Tú',
                'sdt'       => '0963333333',
                'loai'      => 'can_ho',
                'nhu_cau'   => 'thue',
                'dia_chi'   => 'Tầng 8 Toà B2, Mipec Rubik 360',
                'dt'        => 55.0,
                'gia'       => null,
                'trang_thai' => 'da_nhan',
            ],
            [
                'ho_ten'    => 'Phạm Thị Kim',
                'sdt'       => '0964444444',
                'loai'      => 'dat_nen',
                'nhu_cau'   => 'ban',
                'dia_chi'   => 'Lô TT-05, Khu đô thị Thanh Hà',
                'dt'        => 90.0,
                'gia'       => 4200000000,
                'trang_thai' => 'cho_duyet',
            ],
        ];

        foreach ($kyGui as $i => $kg) {
            $kh = $khachHang->count() > $i ? $khachHang[$i] : null;
            KyGui::create([
                'khach_hang_id'          => $kh?->id,
                'nhan_vien_phu_trach_id' => $kg['trang_thai'] !== 'cho_duyet' ? $nvIds[$i % count($nvIds)] : null,
                'ho_ten_chu_nha'         => $kg['ho_ten'],
                'so_dien_thoai'          => $kg['sdt'],
                'email'                  => 'kygui' . ($i + 1) . '@gmail.com',
                'loai_hinh'              => $kg['loai'],
                'nhu_cau'                => $kg['nhu_cau'],
                'dia_chi'                => $kg['dia_chi'],
                'dien_tich'              => $kg['dt'],
                'so_phong_ngu'           => in_array($kg['loai'], ['can_ho', 'nha_pho']) ? rand(2, 4) : 0,
                'gia_ban_mong_muon'      => $kg['nhu_cau'] === 'ban' ? $kg['gia'] : null,
                'gia_thue_mong_muon'     => $kg['nhu_cau'] === 'thue' ? 10000000 + ($i * 2000000) : null,
                'phap_ly'                => 'so_hong',
                'ghi_chu'                => 'Bán gấp, cần tiền gấp. Giá có thể thương lượng.',
                'nguon_ky_gui'           => ['website', 'phone', 'zalo'][$i % 3],
                'trang_thai'             => $kg['trang_thai'],
                'thoi_diem_xu_ly'        => $kg['trang_thai'] !== 'cho_duyet' ? now()->subDays(rand(1, 10)) : null,
            ]);
        }

        $this->command->info('✅ KyGui: ' . count($kyGui) . ' bản ghi');
    }
}
