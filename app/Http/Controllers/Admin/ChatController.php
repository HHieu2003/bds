<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Giao diện danh sách các phiên chat
     */
    public function index()
    {
        $phienChats = $this->getDanhSachPhienChat();
        return view('admin.chat.index', compact('phienChats'));
    }

    /**
     * Mở một phiên chat cụ thể để trò chuyện
     */
    public function show($id, Request $request)
    {
        $activeChat = PhienChat::with(['khachHang', 'tinNhan' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        // Đánh dấu toàn bộ tin nhắn của khách trong phiên này là "Đã đọc"
        TinNhanChat::where('phien_chat_id', $id)
            ->where('nguoi_gui', 'khachhang')
            ->where('da_doc', 0)
            ->update(['da_doc' => 1]);

        // Nếu phiên chat đang chờ, gán cho nhân viên hiện tại tiếp nhận luôn
        // Nếu phiên chat đang chờ, gán cho nhân viên hiện tại tiếp nhận luôn
        if ($activeChat->trang_thai === 'dang_cho') {
            $activeChat->update([
                'trang_thai' => 'dang_chat',
                'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id() // <-- Đã sửa tên cột ở đây
            ]);
        }

        // Nếu là request gọi ngầm (AJAX) từ Javascript để lấy tin nhắn mới
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'tin_nhans' => $activeChat->tinNhan
            ]);
        }

        // Nếu là request bình thường thì trả về giao diện
        $phienChats = $this->getDanhSachPhienChat();
        return view('admin.chat.index', compact('phienChats', 'activeChat'));
    }

    /**
     * Nhân viên gửi tin nhắn trả lời
     */
    public function traLoi(Request $request, $id)
    {
        $request->validate(['noi_dung' => 'required|string']);

        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $id,
            'nguoi_gui' => 'nhanvien',
            'loai_tin_nhan' => 'vanban',
            'noi_dung' => $request->noi_dung,
            'da_doc' => 0
        ]);

        // Cập nhật thời gian update của phiên chat để nó nhảy lên đầu danh sách
        PhienChat::where('id', $id)->update(['updated_at' => now()]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    /**
     * Hàm dùng chung: Lấy danh sách phiên chat (Ưu tiên người đang chờ lên đầu)
     */
    private function getDanhSachPhienChat()
    {
        return PhienChat::with('khachHang')
            // Đếm số tin nhắn chưa đọc của mỗi phiên
            ->withCount(['tinNhan as chua_doc' => function ($q) {
                $q->where('nguoi_gui', 'khachhang')->where('da_doc', 0);
            }])
            // Ưu tiên sắp xếp: Đang chờ -> Đang chat -> Đã đóng
            ->orderByRaw("FIELD(trang_thai, 'dang_cho', 'dang_chat', 'da_dong')")
            ->orderBy('updated_at', 'desc')
            ->get();
    }
}
