<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            NhanVienSeeder::class,      // 1. Nhân viên trước
            KhachHangSeeder::class,     // 2. Khách hàng
            KhuVucSeeder::class,        // 3. Khu vực
            DuAnSeeder::class,          // 4. Dự án (cần khu_vuc)
            BatDongSanSeeder::class,    // 5. BĐS (cần du_an, nhan_vien)
            BaiVietSeeder::class,       // 6. Bài viết
            YeuCauLienHeSeeder::class,  // 7. Yêu cầu liên hệ
            LichHenSeeder::class,       // 8. Lịch hẹn
            KyGuiSeeder::class,         // 9. Ký gửi
            PhienChatSeeder::class,     // 10. Phiên chat
        ]);
    }
}
