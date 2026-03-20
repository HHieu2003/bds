<?php

namespace Database\Seeders;

use App\Models\BaiViet;
use App\Models\NhanVien;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BaiVietSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = NhanVien::where('vai_tro', 'admin')->first()->id;

        $baiViet = [
            [
                'tieu_de'       => 'Thị trường bất động sản Hà Nội quý 1/2026: Xu hướng và cơ hội',
                'loai'          => 'tin_tuc',
                'mo_ta_ngan'    => 'Phân tích toàn diện thị trường BĐS Hà Nội đầu năm 2026, những phân khúc đang tăng mạnh.',
                'noi_bat'       => true,
            ],
            [
                'tieu_de'       => 'Kinh nghiệm mua căn hộ lần đầu: 10 điều cần biết',
                'loai'          => 'kien_thuc',
                'mo_ta_ngan'    => 'Hướng dẫn chi tiết cho người mua căn hộ lần đầu, tránh các bẫy phổ biến.',
                'noi_bat'       => true,
            ],
            [
                'tieu_de'       => 'Phong thủy nhà ở: Hướng cửa chính ảnh hưởng thế nào đến tài vận',
                'loai'          => 'phong_thuy',
                'mo_ta_ngan'    => 'Bí quyết chọn hướng nhà theo phong thủy, mang lại may mắn và thịnh vượng.',
                'noi_bat'       => false,
            ],
            [
                'tieu_de'       => 'Vinhomes Smart City: Khu đô thị đáng sống nhất Hà Nội 2026',
                'loai'          => 'tin_tuc',
                'mo_ta_ngan'    => 'Tổng quan về Vinhomes Smart City sau 5 năm hoạt động, cộng đồng cư dân đông đúc.',
                'noi_bat'       => true,
            ],
            [
                'tieu_de'       => 'Thủ tục sang tên sổ đỏ năm 2026: Hướng dẫn từng bước',
                'loai'          => 'kien_thuc',
                'mo_ta_ngan'    => 'Hồ sơ, thời gian, chi phí sang tên sổ đỏ theo quy định mới nhất.',
                'noi_bat'       => false,
            ],
            [
                'tieu_de'       => 'Top 5 khu vực đầu tư BĐS sinh lời cao tại Hà Nội',
                'loai'          => 'kien_thuc',
                'mo_ta_ngan'    => 'Phân tích chi tiết 5 khu vực có tiềm năng tăng giá tốt nhất Hà Nội.',
                'noi_bat'       => false,
            ],
            [
                'tieu_de'       => 'Thành Công Land tuyển dụng nhân viên kinh doanh BĐS 2026',
                'loai'          => 'tuyen_dung',
                'mo_ta_ngan'    => 'Thành Công Land đang tuyển dụng nhân viên sale BĐS, thu nhập hấp dẫn, không giới hạn.',
                'noi_bat'       => false,
            ],
        ];

        foreach ($baiViet as $i => $bv) {
            BaiViet::create([
                'nhan_vien_id'   => $adminId,
                'tieu_de'        => $bv['tieu_de'],
                'slug'           => Str::slug($bv['tieu_de']) . '-' . ($i + 1),
                'mo_ta_ngan'     => $bv['mo_ta_ngan'],
                'noi_dung'       => '<h2>' . $bv['tieu_de'] . '</h2><p>' . $bv['mo_ta_ngan'] . '</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>',
                'loai_bai_viet'  => $bv['loai'],
                'noi_bat'        => $bv['noi_bat'],
                'hien_thi'       => true,
                'luot_xem'       => rand(100, 2000),
                'thu_tu_hien_thi' => $i + 1,
                'seo_title'      => $bv['tieu_de'] . ' | Thành Công Land',
                'seo_description' => $bv['mo_ta_ngan'],
                'thoi_diem_dang' => now()->subDays(rand(1, 90)),
            ]);
        }

        $this->command->info('✅ BaiViet: ' . count($baiViet) . ' bản ghi');
    }
}
