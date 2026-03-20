<?php

namespace Database\Seeders;

use App\Models\KhuVuc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KhuVucSeeder extends Seeder
{
    public function run(): void
    {
        // Cấp 1: Tỉnh/Thành
        $haNoi = KhuVuc::create([
            'cap_khu_vuc'     => 'tinh_thanh',
            'ten_khu_vuc'     => 'Hà Nội',
            'slug'            => 'ha-noi',
            'ma_hanh_chinh'   => '01',
            'hien_thi'        => true,
            'thu_tu_hien_thi' => 1,
            'seo_title'       => 'Bất động sản Hà Nội',
            'seo_description' => 'Mua bán cho thuê bất động sản Hà Nội uy tín',
        ]);

        // Cấp 2: Quận/Huyện
        $quanHuyen = [
            ['ten' => 'Hà Đông',    'slug' => 'ha-dong',    'ma' => '268', 'thu_tu' => 1],
            ['ten' => 'Cầu Giấy',   'slug' => 'cau-giay',   'ma' => '007', 'thu_tu' => 2],
            ['ten' => 'Nam Từ Liêm', 'slug' => 'nam-tu-liem', 'ma' => '020', 'thu_tu' => 3],
            ['ten' => 'Thanh Xuân', 'slug' => 'thanh-xuan',  'ma' => '009', 'thu_tu' => 4],
            ['ten' => 'Hoàng Mai',  'slug' => 'hoang-mai',   'ma' => '021', 'thu_tu' => 5],
            ['ten' => 'Long Biên',  'slug' => 'long-bien',   'ma' => '022', 'thu_tu' => 6],
        ];

        foreach ($quanHuyen as $q) {
            KhuVuc::create([
                'cap_khu_vuc'      => 'quan_huyen',
                'khu_vuc_cha_id'   => $haNoi->id,
                'ten_khu_vuc'      => $q['ten'],
                'slug'             => $q['slug'],
                'ma_hanh_chinh'    => $q['ma'],
                'hien_thi'         => true,
                'thu_tu_hien_thi'  => $q['thu_tu'],
                'seo_title'        => 'Bất động sản ' . $q['ten'],
                'seo_description'  => 'Mua bán, cho thuê BĐS tại ' . $q['ten'] . ', Hà Nội',
            ]);
        }

        $this->command->info('✅ KhuVuc: ' . (1 + count($quanHuyen)) . ' bản ghi');
    }
}
