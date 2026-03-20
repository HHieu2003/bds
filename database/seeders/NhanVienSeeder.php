<?php

namespace Database\Seeders;

use App\Models\NhanVien;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NhanVienSeeder extends Seeder
{
    public function run(): void
    {
        $nhanVien = [
            [
                'ho_ten'        => 'Nguyễn Thành Công',
                'email'         => 'admin@thanhcongland.com',
                'password'      => Hash::make('password'),
                'vai_tro'       => 'admin',
                'so_dien_thoai' => '0901234567',
                'anh_dai_dien'  => null,
                'dia_chi'       => '123 Đường Thành Công, Hà Đông, Hà Nội',
                'kich_hoat'     => true,
            ],
            [
                'ho_ten'        => 'Trần Văn Nguồn',
                'email'         => 'nguon1@thanhcongland.com',
                'password'      => Hash::make('password'),
                'vai_tro'       => 'nguon',
                'so_dien_thoai' => '0912345678',
                'anh_dai_dien'  => null,
                'dia_chi'       => 'Hà Đông, Hà Nội',
                'kich_hoat'     => true,
            ],
            [
                'ho_ten'        => 'Lê Thị Hương',
                'email'         => 'nguon2@thanhcongland.com',
                'password'      => Hash::make('password'),
                'vai_tro'       => 'nguon',
                'so_dien_thoai' => '0923456789',
                'anh_dai_dien'  => null,
                'dia_chi'       => 'Nam Từ Liêm, Hà Nội',
                'kich_hoat'     => true,
            ],
            [
                'ho_ten'        => 'Phạm Minh Sale',
                'email'         => 'sale1@thanhcongland.com',
                'password'      => Hash::make('password'),
                'vai_tro'       => 'sale',
                'so_dien_thoai' => '0934567890',
                'anh_dai_dien'  => null,
                'dia_chi'       => 'Cầu Giấy, Hà Nội',
                'kich_hoat'     => true,
            ],
            [
                'ho_ten'        => 'Hoàng Thị Sale',
                'email'         => 'sale2@thanhcongland.com',
                'password'      => Hash::make('password'),
                'vai_tro'       => 'sale',
                'so_dien_thoai' => '0945678901',
                'anh_dai_dien'  => null,
                'dia_chi'       => 'Thanh Xuân, Hà Nội',
                'kich_hoat'     => true,
            ],
            [
                'ho_ten'        => 'Vũ Đức Sale',
                'email'         => 'sale3@thanhcongland.com',
                'password'      => Hash::make('password'),
                'vai_tro'       => 'sale',
                'so_dien_thoai' => '0956789012',
                'anh_dai_dien'  => null,
                'dia_chi'       => 'Hoàng Mai, Hà Nội',
                'kich_hoat'     => true,
            ],
        ];

        foreach ($nhanVien as $nv) {
            NhanVien::create($nv);
        }

        $this->command->info('✅ NhanVien: ' . count($nhanVien) . ' bản ghi');
    }
}
