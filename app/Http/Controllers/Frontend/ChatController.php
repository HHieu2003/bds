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

        // 1. Tìm xem khách hàng có phiên chat nào đang mở không
        $phienChat = PhienChat::where('khach_hang_id', $khachHang?->id)
            ->whereIn('trang_thai', ['dang_cho', 'dang_chat'])
            ->first();

        // 2. Nếu chưa có, tạo phiên chat mới
        if (!$phienChat) {
            $phienChat = PhienChat::create([
                'khach_hang_id' => $khachHang?->id,
                'trang_thai'    => 'dang_cho',
                'kenh'          => 'website',
                'session_id'    => session()->getId(),
            ]);

            // Gửi tin nhắn tự động từ hệ thống
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'nguoi_gui'     => 'hethong',
                'loai_tin_nhan' => 'vanban',
                'noi_dung'      => 'Xin chào! Tôi là trợ lý tư vấn của Thành Công Land. Tôi có thể giúp gì cho bạn?',
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

        $khachHang = Auth::guard('customer')->user();

        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $request->phien_chat_id,
            'khach_hang_id' => $khachHang?->id,
            'nguoi_gui'     => 'khachhang',
            'loai_tin_nhan' => 'vanban',
            'noi_dung'      => $request->noi_dung,
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
            ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'created_at']);

        return response()->json([
            'success'   => true,
            'tin_nhans' => $tinNhans,
        ]);
    }
}
