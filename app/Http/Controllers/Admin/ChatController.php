<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private const HISTORY_LIMIT = 100;

    // ── DANH SÁCH PHIÊN CHAT ────────────────────────────────
    public function index()
    {
        if (request()->boolean('check_unread')) {
            $tongChuaDoc = TinNhanChat::where('nguoi_gui', 'khach_hang')
                ->where('da_doc', 0)
                ->count();

            return response()->json([
                'success' => true,
                'tong_chua_doc' => $tongChuaDoc,
            ]);
        }

        $phienChats = $this->danhSach();
        $activeChat = $phienChats->first();
        $currentMessages = collect();

        if ($activeChat) {
            $currentMessages = TinNhanChat::where('phien_chat_id', $activeChat->id)
                ->latest('id')
                ->limit(self::HISTORY_LIMIT)
                ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'tep_dinh_kem', 'da_doc', 'created_at'])
                ->reverse()
                ->values();
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'phien_chats' => $phienChats,
                'active_chat' => $activeChat,
                'messages' => $currentMessages,
            ]);
        }

        return view('admin.chat.index', compact('phienChats', 'activeChat', 'currentMessages'));
    }

    // ── CHI TIẾT PHIÊN CHAT ─────────────────────────────────
    public function show($id)
    {
        $phienChats = $this->danhSach();
        $activeChat = PhienChat::with(['khachHang', 'nhanVienPhuTrach', 'tinNhanCuoi'])->findOrFail($id);

        TinNhanChat::where('phien_chat_id', $id)
            ->where('nguoi_gui', 'khach_hang')
            ->where('da_doc', 0)
            ->update(['da_doc' => 1, 'da_doc_at' => now()]);

        $currentMessages = TinNhanChat::where('phien_chat_id', $id)
            ->latest('id')
            ->limit(self::HISTORY_LIMIT)
            ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'tep_dinh_kem', 'da_doc', 'created_at'])
            ->reverse()
            ->values();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'active_chat' => $activeChat,
                'messages' => $currentMessages,
            ]);
        }

        return view('admin.chat.index', compact('phienChats', 'activeChat', 'currentMessages'));
    }

    // ── TIẾP NHẬN PHIÊN CHAT ────────────────────────────────
    public function tiepNhan($id)
    {
        $phien = PhienChat::findOrFail($id);
        $phien->update([
            'trang_thai' => 'dang_chat',
            'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id(),
        ]);

        return response()->json(['success' => true]);
    }

    // ── ĐÓNG PHIÊN CHAT ─────────────────────────────────────
    public function dongPhien($id)
    {
        $phien = PhienChat::findOrFail($id);
        $phien->update(['trang_thai' => 'da_dong']);

        return response()->json(['success' => true]);
    }

    // ── GỬI TIN NHẮN (NHÂN VIÊN -> KHÁCH) ───────────────────
    public function traLoi(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => 'nullable|string',
            'tep_tin' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:20480',
        ]);

        if (!$request->noi_dung && !$request->hasFile('tep_tin')) {
            return response()->json(['success' => false, 'message' => 'Nội dung trống']);
        }

        $phien = PhienChat::findOrFail($id);

        if ($phien->trang_thai === 'da_dong') {
            return response()->json(['success' => false, 'message' => 'Phiên đã đóng']);
        }

        if ($phien->trang_thai === 'dang_bot') {
            $phien->update([
                'trang_thai' => 'dang_chat',
                'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id(),
            ]);
        }

        $tepDinhKem = null;
        $loaiTinNhan = 'van_ban';

        if ($request->hasFile('tep_tin')) {
            $file = $request->file('tep_tin');
            $tepDinhKem = $file->store('chat_media', 'r2');
            $loaiTinNhan = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'hinh_anh';
        }

        $msg = TinNhanChat::create([
            'phien_chat_id' => $id,
            'nguoi_gui' => 'nhan_vien',
            'loai_tin_nhan' => $loaiTinNhan,
            'noi_dung' => $request->noi_dung,
            'tep_dinh_kem' => $tepDinhKem,
            'da_doc' => true,
        ]);

        return response()->json(['success' => true, 'tin_nhan' => $msg]);
    }

    // ── LONG POLLING PHÍA ADMIN ──────────────────────────────
    public function longPoll(Request $request, $id)
    {
        $sauId = (int) $request->input('sau_id', 0);
        $phien = PhienChat::findOrFail($id);

        $tinNhans = TinNhanChat::where('phien_chat_id', $id)
            ->where('id', '>', $sauId)
            ->orderBy('id', 'asc')
            ->limit(50)
            ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'tep_dinh_kem', 'da_doc', 'created_at']);

        if ($tinNhans->isNotEmpty()) {
            TinNhanChat::where('phien_chat_id', $id)
                ->where('nguoi_gui', 'khach_hang')
                ->where('da_doc', 0)
                ->update(['da_doc' => 1, 'da_doc_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'trang_thai' => $phien->trang_thai,
            'tin_nhans' => $tinNhans,
        ]);
    }

    // ── XÓA PHIÊN CHAT (QUYỀN ADMIN) ──────────────────────────────
    public function destroy($id)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        // Chỉ admin mới có quyền xóa
        if ($nhanVien->vai_tro !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Từ chối: Chỉ Admin mới có quyền xóa phiên chat.']);
        }

        $phien = PhienChat::find($id);
        if ($phien) {
            // Xóa sạch các tin nhắn thuộc phiên này để tránh dữ liệu mồ côi
            TinNhanChat::where('phien_chat_id', $id)->delete();
            $phien->delete();
        }

        // BẮT BUỘC TRẢ VỀ JSON để tránh lỗi redirect 302 method DELETE
        return response()->json(['success' => true]);
    }

    // ── HELPER ──────────────────────────────────────────────
    private function danhSach()
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $query = PhienChat::with(['khachHang', 'nhanVienPhuTrach', 'tinNhanCuoi'])
            ->withCount(['tinNhans as so_chua_doc' => function ($q) {
                $q->where('nguoi_gui', 'khach_hang')->where('da_doc', 0);
            }]);

        // PHÂN QUYỀN: Nếu là Sale, chỉ hiển thị Chat của Sale đó VÀ Chat đang chờ (chưa ai nhận)
        if ($nhanVien && ($nhanVien->vai_tro === 'sale' || (method_exists($nhanVien, 'isSale') && $nhanVien->isSale()))) {
            $query->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_phu_trach_id', $nhanVien->id)
                    ->orWhereNull('nhan_vien_phu_trach_id');
            });
        }

        return $query->orderByDesc('so_chua_doc')
            ->orderByRaw("FIELD(trang_thai, 'dang_cho', 'dang_chat', 'dang_bot', 'da_dong')")
            ->orderByDesc('tin_nhan_cuoi_at')
            ->get();
    }
}
