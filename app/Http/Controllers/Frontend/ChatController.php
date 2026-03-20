<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Khởi tạo khung chat hoặc lấy lịch sử tin nhắn
     */
    public function khoiTao(Request $request)
    {
        $khachHang = Auth::guard('customer')->user();

        // 1. Tìm phiên chat cũ đang MỞ của khách hàng này
        $phienChat = PhienChat::where('khach_hang_id', $khachHang?->id)
            ->whereIn('trang_thai', ['dang_cho', 'dang_chat'])
            ->first();

        // 2. Nếu chưa có phiên chat nào đang mở thì mới tạo mới
        if (!$phienChat) {
            $phienChat = PhienChat::create([
                'khach_hang_id' => $khachHang?->id,
                'trang_thai'    => 'dang_cho',
                'kenh'          => 'website',
                'session_id'    => session()->getId(),
            ]);

            // Tin nhắn chào mừng tự động
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'nguoi_gui'     => 'hethong',
                'loai_tin_nhan' => 'vanban',
                'noi_dung'      => 'Xin chào! Tôi là trợ lý tư vấn BĐS của Thành Công Land. Tôi có thể giúp gì cho bạn?',
            ]);
        }

        return response()->json([
            'success'       => true,
            'phien_chat_id' => $phienChat->id,
            'message'       => 'Phiên chat đã được tải.',
        ]);
    }

    /**
     * Khách hàng gửi tin nhắn mới
     */
    public function gui(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $request->validate(['noi_dung' => 'required|string']);

        $phienChat = PhienChat::find($request->phien_chat_id);
        if (!$phienChat) {
            return response()->json(['success' => false, 'message' => 'Phiên chat không tồn tại']);
        }

        // Lưu tin nhắn
        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $phienChat->id,
            'nguoi_gui_type' => 'khach_hang', // Phân biệt ai gửi
            'nguoi_gui_id' => Auth::guard('customer')->id(),
            'noi_dung' => $request->noi_dung,
            'da_doc' => 0
        ]);

        return response()->json([
            'success' => true,
            'tin_nhan' => $tinNhan
        ]);
    }
}
