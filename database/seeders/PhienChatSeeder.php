<?php

namespace Database\Seeders;

use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhienChatSeeder extends Seeder
{
    public function run(): void
    {
        $khachHang = KhachHang::all();
        $saleIds   = NhanVien::where('vai_tro', 'sale')->pluck('id')->toArray();

        $cuocHoiThoai = [
            [
                'khach' => 'Xin chào, tôi muốn tìm căn hộ 2 phòng ngủ tại Vinhomes Smart City.',
                'nv'    => 'Chào bạn! Hiện tại chúng tôi có một số căn hộ 2PN tại Vinhomes Smart City. Bạn có thể cho biết mức ngân sách dự kiến không ạ?',
            ],
            [
                'khach' => 'Cho hỏi căn hộ BDS-0001 còn trống không?',
                'nv'    => 'Dạ căn hộ đó hiện vẫn còn ạ! Bạn có muốn đặt lịch xem nhà không?',
            ],
            [
                'khach' => 'Tôi muốn thuê nhà tháng 4, giá khoảng 10-15 triệu.',
                'nv'    => 'Dạ, với mức giá đó chúng tôi có một số căn hộ 2PN rất phù hợp. Để tôi gửi thông tin chi tiết cho bạn nhé!',
            ],
        ];

        foreach ($khachHang->take(3) as $i => $kh) {
            // Tạo phiên chat
            $phienChat = PhienChat::create([
                'khach_hang_id'          => $kh->id,
                'nhan_vien_phu_trach_id' => $saleIds[$i % count($saleIds)],
                'session_id'             => Str::uuid(),
                'url_ngu_canh'           => '/bat-dong-san',
                'trang_thai'             => $i === 2 ? 'da_dong' : 'dang_mo',
                'tin_nhan_cuoi_at'       => now()->subMinutes(rand(5, 120)),
            ]);

            $hoiThoai = $cuocHoiThoai[$i];

            // Tin nhắn khách
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'khach_hang_id' => $kh->id,
                'nguoi_gui'     => 'khach_hang',
                'loai_tin_nhan' => 'van_ban',
                'noi_dung'      => $hoiThoai['khach'],
                'da_doc'        => true,
                'da_doc_at'     => now()->subMinutes(rand(10, 60)),
                'created_at'    => now()->subMinutes(rand(30, 120)),
            ]);

            // Tin nhắn nhân viên
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'nhan_vien_id'  => $saleIds[$i % count($saleIds)],
                'nguoi_gui'     => 'nhan_vien',
                'loai_tin_nhan' => 'van_ban',
                'noi_dung'      => $hoiThoai['nv'],
                'da_doc'        => true,
                'da_doc_at'     => now()->subMinutes(rand(5, 30)),
                'created_at'    => now()->subMinutes(rand(5, 29)),
            ]);
        }

        // Thêm 1 phiên chat khách vãng lai
        $phienVangLai = PhienChat::create([
            'khach_hang_id'          => null,
            'nhan_vien_phu_trach_id' => $saleIds[0],
            'session_id'             => Str::uuid(),
            'ten_khach_vang_lai'     => 'Khách Vãng Lai',
            'sdt_khach_vang_lai'     => '0999888777',
            'da_xac_thuc_sdt'        => false,
            'url_ngu_canh'           => '/bat-dong-san/can-ho-2pn-vinhomes-1',
            'trang_thai'             => 'dang_mo',
            'tin_nhan_cuoi_at'       => now()->subMinutes(5),
        ]);

        TinNhanChat::create([
            'phien_chat_id' => $phienVangLai->id,
            'nguoi_gui'     => 'khach_hang',
            'loai_tin_nhan' => 'van_ban',
            'noi_dung'      => 'Cho hỏi giá căn hộ này bao nhiêu vậy ạ?',
            'da_doc'        => false,
        ]);

        $this->command->info('✅ PhienChat + TinNhanChat: 4 phiên, 7 tin nhắn');
    }
}
