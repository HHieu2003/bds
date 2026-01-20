<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Vô hiệu hóa kiểm tra khóa ngoại để tránh lỗi khi truncate
        Schema::disableForeignKeyConstraints();

        // Xóa dữ liệu cũ
        DB::table('users')->truncate();
        DB::table('cau_hinh')->truncate();
        DB::table('bai_viet')->truncate();
        // Các bảng khác sẽ được xóa trong DuAnSeeder nếu cần

        Schema::enableForeignKeyConstraints();

        // 1. Tạo Users (Admin, Sale, Đầu chủ)
        DB::table('users')->insert([
            [
                'name' => 'Admin Quản Trị',
                'email' => 'admin@thanhcongland.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'phone' => '0909000111',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nguyễn Văn Sale',
                'email' => 'sale@thanhcongland.com',
                'password' => Hash::make('123456'),
                'role' => 'sale', // Nhân viên tư vấn
                'phone' => '0912345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Văn Nguồn',
                'email' => 'nguon@thanhcongland.com',
                'password' => Hash::make('123456'),
                'role' => 'dau_chu', // Nhân viên nguồn hàng
                'phone' => '0988777666',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'admin', // admin
                'phone' => '0988777666',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Tạo Cấu hình hệ thống (Settings)
        DB::table('cau_hinh')->insert([
            ['key' => 'tieu_de_web', 'value' => 'Thành Công Land - Bất Động Sản Uy Tín', 'mo_ta' => 'Tiêu đề hiển thị trên tab trình duyệt'],
            ['key' => 'hotline', 'value' => '0988.888.888', 'mo_ta' => 'Số hotline hiển thị trên header/footer'],
            ['key' => 'email_lien_he', 'value' => 'contact@thanhcongland.com', 'mo_ta' => 'Email nhận thông báo'],
            ['key' => 'dia_chi_cty', 'value' => 'Tầng 5, Tòa nhà ABC, Phố Duy Tân, Hà Nội', 'mo_ta' => 'Địa chỉ công ty'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/thanhcongland', 'mo_ta' => 'Link Fanpage'],
        ]);

        // 3. Gọi Seeder Dự án & Bất động sản
        $this->call([
            DuAnSeeder::class,
        ]);

        // 4. Tạo vài Bài viết mẫu (Tin tức)
        DB::table('bai_viet')->insert([
            [
                'tieu_de' => 'Thị trường Bất động sản cuối năm 2024: Cơ hội nào cho nhà đầu tư?',
                'slug' => 'thi-truong-bds-cuoi-nam-2024',
                'mo_ta_ngan' => 'Phân tích chuyên sâu về xu hướng dòng tiền và các phân khúc tiềm năng.',
                'noi_dung' => '<p>Nội dung chi tiết bài viết...</p>',
                'loai_bai_viet' => 'tin_tuc',
                'hien_thi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tieu_de' => 'Những lưu ý phong thủy khi chọn mua chung cư',
                'slug' => 'luu-y-phong-thuy-chung-cu',
                'mo_ta_ngan' => 'Hướng dẫn chọn hướng nhà, tầng lầu hợp mệnh gia chủ.',
                'noi_dung' => '<p>Nội dung chi tiết bài viết...</p>',
                'loai_bai_viet' => 'phong_thuy',
                'hien_thi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
