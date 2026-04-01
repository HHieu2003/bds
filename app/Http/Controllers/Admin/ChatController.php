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
            ]);
        }

        return view('admin.chat.index', compact('phienChats', 'activeChat', 'currentMessages'));
    }

    // ── XEM PHIÊN CHAT CỤ THỂ ───────────────────────────────
    public function show($id, Request $request)
    {
        $activeChat = PhienChat::with(['khachHang', 'nhanVienPhuTrach'])->findOrFail($id);

        $tinNhan = TinNhanChat::where('phien_chat_id', $activeChat->id)
            ->latest('id')
            ->limit(self::HISTORY_LIMIT)
            ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'tep_dinh_kem', 'da_doc', 'created_at'])
            ->reverse()
            ->values();

        // Tự động tiếp nhận nếu đang chờ
        if ($activeChat->trang_thai === 'dang_cho') {
            $activeChat->update([
                'trang_thai'             => 'dang_chat',
                'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id(),
                'dang_bot_xu_ly'         => false,
            ]);
        }

        // Đánh dấu đã đọc
        TinNhanChat::where('phien_chat_id', $id)
            ->where('nguoi_gui', 'khach_hang')
            ->where('da_doc', 0)
            ->update(['da_doc' => 1, 'da_doc_at' => now()]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'phien_chat' => [
                    'id' => $activeChat->id,
                    'trang_thai' => $activeChat->trang_thai,
                    'ten_hien_thi' => $activeChat->ten_hien_thi,
                    'nhan_vien_phu_trach_id' => $activeChat->nhan_vien_phu_trach_id,
                ],
                'tin_nhans' => $tinNhan,
            ]);
        }

        $phienChats = $this->danhSach();
        $currentMessages = $tinNhan;
        return view('admin.chat.index', compact('phienChats', 'activeChat', 'currentMessages'));
    }

    // ── NV GỬI TIN NHẮN TRẢ LỜI ────────────────────────────
    public function traLoi(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => ['nullable', 'string', 'max:2000', 'required_without:tep_tin'],
            'tep_tin' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime', 'max:20480', 'required_without:noi_dung'],
        ]);

        $phienChat = PhienChat::findOrFail($id);
        if ($phienChat->trang_thai === 'da_dong') {
            return response()->json([
                'success' => false,
                'message' => 'Phien chat da dong.',
            ], 422);
        }

        if (!$phienChat->nhan_vien_phu_trach_id) {
            $phienChat->update([
                'trang_thai' => 'dang_chat',
                'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id(),
                'dang_bot_xu_ly' => false,
            ]);
        }

        $tepDinhKem = null;
        $loaiTinNhan = 'van_ban';
        $noiDung = trim((string) $request->noi_dung);

        if ($request->hasFile('tep_tin')) {
            $file = $request->file('tep_tin');
            $tepDinhKem = $file->store('chat', 'public');
            $mime = (string) $file->getMimeType();
            $loaiTinNhan = str_starts_with($mime, 'video/') ? 'video' : 'hinh_anh';
            $noiDung = $noiDung ?: ($loaiTinNhan === 'video' ? '[Video]' : '[Hinh anh]');
        }

        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $id,
            'nhan_vien_id'  => Auth::guard('nhanvien')->id(),
            'nguoi_gui'     => 'nhan_vien',
            'loai_tin_nhan' => $loaiTinNhan,
            'noi_dung'      => $noiDung,
            'tep_dinh_kem'  => $tepDinhKem,
            'da_doc'        => 0,
        ]);

        PhienChat::where('id', $id)->update([
            'trang_thai' => 'dang_chat',
            'dang_bot_xu_ly' => false,
            'tin_nhan_cuoi_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'tin_nhan' => $tinNhan,
        ]);
    }

    // ── TIẾP NHẬN PHIÊN (từ danh sách đang chờ) ─────────────
    public function tiepNhan($id)
    {
        $phien = PhienChat::findOrFail($id);

        if ($phien->trang_thai === 'da_dong') {
            return response()->json(['success' => false, 'message' => 'Phien da dong.'], 422);
        }

        $phien->update([
            'trang_thai'             => 'dang_chat',
            'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id(),
            'dang_bot_xu_ly'         => false,
            'tin_nhan_cuoi_at'       => now(),
        ]);

        TinNhanChat::create([
            'phien_chat_id' => $id,
            'nguoi_gui'     => 'he_thong',
            'loai_tin_nhan' => 'van_ban',
            'noi_dung'      => 'Nhan vien tu van da tiep nhan va dang ho tro ban.',
            'da_doc'        => 1,
        ]);

        return response()->json(['success' => true]);
    }

    // ── ĐÓNG PHIÊN CHAT ─────────────────────────────────────
    public function dongPhien($id)
    {
        $phien = PhienChat::findOrFail($id);
        $phien->update([
            'trang_thai' => 'da_dong',
            'dang_bot_xu_ly' => false,
            'tin_nhan_cuoi_at' => now(),
        ]);

        TinNhanChat::create([
            'phien_chat_id' => $id,
            'nguoi_gui' => 'he_thong',
            'loai_tin_nhan' => 'van_ban',
            'noi_dung' => 'Phien chat da duoc dong. Neu can ho tro tiep, vui long mo phien moi.',
            'da_doc' => true,
        ]);

        return response()->json(['success' => true]);
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

    // ── HELPER ──────────────────────────────────────────────
    private function danhSach()
    {
        return PhienChat::with(['khachHang', 'nhanVienPhuTrach'])
            ->withCount(['tinNhan as so_chua_doc' => fn($q) => $q->where('nguoi_gui', 'khach_hang')->where('da_doc', 0)])
            ->orderByRaw("FIELD(trang_thai, 'dang_cho', 'dang_bot', 'dang_chat', 'da_dong')")
            ->orderBy('tin_nhan_cuoi_at', 'desc')
            ->get();
    }
}
