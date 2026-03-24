<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ══════════════════════════════
    // KHỞI TẠO PHIÊN CHAT (POST)
    // ══════════════════════════════
    public function khoiTao(Request $request)
    {
        $khachHang = Auth::guard('customer')->user();
        $sessionId = session()->getId();

        // 1. Tìm xem khách có phiên chat nào đang mở không
        if ($khachHang) {
            $phienChat = PhienChat::where('khach_hang_id', $khachHang->id)
                ->whereIn('trang_thai', ['dang_cho', 'dang_chat'])
                ->first();
        } else {
            // Dành cho trường hợp sau này bạn muốn cho khách vãng lai chat
            $phienChat = PhienChat::where('session_id', $sessionId)
                ->whereIn('trang_thai', ['dang_cho', 'dang_chat'])
                ->first();
        }

        // 2. Nếu chưa có, tạo phiên chat mới
        if (!$phienChat) {
            $phienChat = PhienChat::create([
                'khach_hang_id' => $khachHang?->id,
                'session_id'    => $sessionId,  // Đã thêm lại để sửa lỗi 1364

                'trang_thai'    => 'dang_cho',
            ]);

            // Gửi tin nhắn tự động từ hệ thống
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'nguoi_gui'     => 'hethong',
                'loai_tin_nhan' => 'vanban',    // Bổ sung loại tin nhắn
                'noi_dung'      => 'Xin chào! Tôi là trợ lý tư vấn của Thành Công Land. Tôi có thể giúp gì cho bạn?',
                'da_doc'        => 0
            ]);
        }

        return response()->json([
            'success'       => true,
            'phien_chat_id' => $phienChat->id,
            'message'       => 'Khởi tạo thành công',
        ]);
    }

    // ══════════════════════════════
    // GỬI TIN NHẮN (POST)
    // ══════════════════════════════
    public function guiTinNhan(Request $request)
    {
        $request->validate([
            'phien_chat_id' => ['required', 'exists:phien_chat,id'],
            'noi_dung'      => ['required', 'string', 'max:2000'],
        ]);

        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $request->phien_chat_id,
            'nguoi_gui'     => 'khachhang',
            'loai_tin_nhan' => 'vanban',    // Bổ sung loại tin nhắn
            'noi_dung'      => $request->noi_dung,
            'da_doc'        => 0
        ]);

        return response()->json([
            'success'  => true,
            'tin_nhan' => $tinNhan,
        ]);
    }

    // ══════════════════════════════
    // LẤY LỊCH SỬ CHAT (GET)
    // ══════════════════════════════
    public function lichSu(int $phienChatId)
    {
        $tinNhans = TinNhanChat::where('phien_chat_id', $phienChatId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success'   => true,
            'tin_nhans' => $tinNhans,
        ]);
    }
}
