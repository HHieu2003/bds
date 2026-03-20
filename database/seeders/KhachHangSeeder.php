<?php

namespace Database\Seeders;

use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Database\Seeder;

class KhachHangSeeder extends Seeder
{
    public function run(): void
    {
        $saleIds = NhanVien::where('vai_tro', 'sale')->pluck('id')->toArray();

        $khachHang = [
            ['ho_ten' => 'Nguyễn Văn An',    'so_dien_thoai' => '0971111111', 'email' => 'an.nv@gmail.com',    'muc_do_tiem_nang' => 'nong'],
            ['ho_ten' => 'Trần Thị Bình',    'so_dien_thoai' => '0972222222', 'email' => 'binh.tt@gmail.com',  'muc_do_tiem_nang' => 'nong'],
            ['ho_ten' => 'Lê Minh Cường',    'so_dien_thoai' => '0973333333', 'email' => 'cuong.lm@gmail.com', 'muc_do_tiem_nang' => 'am'],
            ['ho_ten' => 'Phạm Thu Hà',      'so_dien_thoai' => '0974444444', 'email' => 'ha.pt@gmail.com',    'muc_do_tiem_nang' => 'am'],
            ['ho_ten' => 'Hoàng Văn Đức',    'so_dien_thoai' => '0975555555', 'email' => 'duc.hv@gmail.com',   'muc_do_tiem_nang' => 'lanh'],
            ['ho_ten' => 'Vũ Thị Hoa',       'so_dien_thoai' => '0976666666', 'email' => 'hoa.vt@gmail.com',   'muc_do_tiem_nang' => 'nong'],
            ['ho_ten' => 'Đặng Quốc Hùng',   'so_dien_thoai' => '0977777777', 'email' => 'hung.dq@gmail.com',  'muc_do_tiem_nang' => 'am'],
            ['ho_ten' => 'Ngô Thị Lan',      'so_dien_thoai' => '0978888888', 'email' => 'lan.nt@gmail.com',   'muc_do_tiem_nang' => 'lanh'],
            ['ho_ten' => 'Bùi Văn Long',     'so_dien_thoai' => '0979999999', 'email' => 'long.bv@gmail.com',  'muc_do_tiem_nang' => 'nong'],
            ['ho_ten' => 'Đinh Thị Mai',     'so_dien_thoai' => '0981111111', 'email' => 'mai.dt@gmail.com',   'muc_do_tiem_nang' => 'am'],
            ['ho_ten' => 'Hà Văn Nam',       'so_dien_thoai' => '0982222222', 'email' => null,                 'muc_do_tiem_nang' => 'lanh'],
            ['ho_ten' => 'Kiều Thị Oanh',    'so_dien_thoai' => '0983333333', 'email' => null,                 'muc_do_tiem_nang' => 'am'],
        ];

        foreach ($khachHang as $i => $kh) {
            KhachHang::create([
                ...$kh,
                'nguon_khach_hang'       => ['website', 'chat', 'lien_he', 'sale'][$i % 4],
                'nhan_vien_phu_trach_id' => $saleIds[$i % count($saleIds)],
                'kich_hoat'              => true,
                'lien_he_cuoi_at'        => now()->subDays(rand(0, 30)),
            ]);
        }

        $this->command->info('✅ KhachHang: ' . count($khachHang) . ' bản ghi');
    }
}
