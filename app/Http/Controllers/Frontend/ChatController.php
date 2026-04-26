<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use App\Services\GeminiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private const OPEN_STATUSES = ['dang_mo', 'dang_bot', 'dang_cho', 'dang_chat'];
    private const HISTORY_LIMIT = 80;

    public function __construct(private GeminiChatService $geminiChatService) {}


    public function khoiTao(Request $request)
    {
        $request->validate([
            'ten_khach' => ['nullable', 'string', 'max:120'],
            'sdt' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'loai_ngu_canh' => ['nullable', 'in:bat_dong_san,du_an,khu_vuc'],
            'ngu_canh_id' => ['nullable', 'integer'],
            'ten_ngu_canh' => ['nullable', 'string', 'max:255'],
        ]);

        $khachHang = Auth::guard('customer')->user();
        $sessionId = session()->getId();

        $phienChat = null;

        if ($khachHang) {
            $phienChat = PhienChat::where('khach_hang_id', $khachHang->id)
                ->whereIn('trang_thai', self::OPEN_STATUSES)
                ->latest('updated_at')
                ->first();
        }

        if (!$phienChat) {
            $phienChat = PhienChat::where('session_id', $sessionId)
                ->whereIn('trang_thai', self::OPEN_STATUSES)
                ->latest('updated_at')
                ->first();
        }

        if (!$phienChat) {
            $phienChat = PhienChat::create([
                'khach_hang_id' => $khachHang?->id,
                'session_id' => $sessionId,
                'ten_khach_vang_lai' => $khachHang?->ho_ten ?: ($request->ten_khach ?: 'Khach vang lai'),
                'sdt_khach_vang_lai' => $khachHang?->so_dien_thoai ?: $request->sdt,
                'email_khach_vang_lai' => $khachHang?->email ?: $request->email,
                'url_ngu_canh' => $request->header('referer'),
                'loai_ngu_canh' => $request->loai_ngu_canh,
                'ngu_canh_id' => $request->ngu_canh_id,
                'ten_ngu_canh' => $request->ten_ngu_canh,
                'trang_thai' => 'dang_bot',
                'dang_bot_xu_ly' => true,
                'het_han_at' => $khachHang ? null : now()->addDay(),
            ]);

            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'khach_hang_id' => null,
                'nhan_vien_id' => null,
                'nguoi_gui' => 'he_thong',
                'loai_tin_nhan' => 'van_ban',
                'noi_dung' => 'Xin chào! Tôi là trợ lý AI của Thành Công Land. Tôi có thể hỗ trợ bạn ngay bây giờ.',
                'da_doc' => true,
            ]);

            if (!$khachHang) {
                TinNhanChat::create([
                    'phien_chat_id' => $phienChat->id,
                    'khach_hang_id' => null,
                    'nhan_vien_id' => null,
                    'nguoi_gui' => 'he_thong',
                    'loai_tin_nhan' => 'van_ban',
                    'noi_dung' => 'Luu y: Ban chua xac thuc tai khoan. Noi dung chat se bi xoa sau 24 gio neu khong dang nhap/xac thuc.',
                    'da_doc' => true,
                ]);
            }
        } else {
            $phienChat->fill([
                'khach_hang_id' => $khachHang?->id ?: $phienChat->khach_hang_id,
                'ten_khach_vang_lai' => $khachHang?->ho_ten ?: ($request->ten_khach ?: ($phienChat->ten_khach_vang_lai ?: 'Khach vang lai')),
                'sdt_khach_vang_lai' => $khachHang?->so_dien_thoai ?: ($request->sdt ?: $phienChat->sdt_khach_vang_lai),
                'email_khach_vang_lai' => $khachHang?->email ?: ($request->email ?: $phienChat->email_khach_vang_lai),
                'url_ngu_canh' => $request->header('referer') ?: $phienChat->url_ngu_canh,
                'loai_ngu_canh' => $request->loai_ngu_canh ?: $phienChat->loai_ngu_canh,
                'ngu_canh_id' => $request->ngu_canh_id ?: $phienChat->ngu_canh_id,
                'ten_ngu_canh' => $request->ten_ngu_canh ?: $phienChat->ten_ngu_canh,
                'het_han_at' => $khachHang ? null : ($phienChat->het_han_at ?: now()->addDay()),
            ])->save();
        }

        return response()->json([
            'success' => true,
            'phien_chat_id' => $phienChat->id,
            'guest_unverified' => !$khachHang,
        ]);
    }

    // ══════════════════════════════════════════════
    // GỬI TIN NHẮN
    // POST /chat/gui
    // ══════════════════════════════════════════════
    public function guiTinNhan(Request $request)
    {
        $request->validate([
            'phien_chat_id' => ['required', 'integer', 'exists:phien_chat,id'],
            'noi_dung' => ['nullable', 'string', 'max:2000', 'required_without:tep_tin'],
            'tep_tin' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime', 'max:20480', 'required_without:noi_dung'],
        ]);

        $khachHang = Auth::guard('customer')->user();
        $sessionId = session()->getId();

        $phienChat = PhienChat::where('id', (int) $request->phien_chat_id)
            ->where(function ($q) use ($khachHang, $sessionId) {
                if ($khachHang) {
                    $q->where('khach_hang_id', $khachHang->id)
                        ->orWhere('session_id', $sessionId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$phienChat) {
            return response()->json([
                'success' => false,
                'message' => 'Khong tim thay phien chat phu hop.',
            ], 404);
        }

        if (!$khachHang && $phienChat->het_han_at && now()->greaterThan($phienChat->het_han_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Phien chat da het han. Vui long dang nhap/xac thuc de tiep tuc.',
            ], 422);
        }

        $tepDinhKem = null;
        $loaiTinNhan = 'van_ban';
        $noiDung = trim((string) $request->noi_dung);

        if ($request->hasFile('tep_tin')) {
            $file = $request->file('tep_tin');
            $tepDinhKem = $file->store('chat', 'r2');
            $mime = (string) $file->getMimeType();
            $loaiTinNhan = str_starts_with($mime, 'video/') ? 'video' : 'hinh_anh';
            $noiDung = $noiDung ?: ($loaiTinNhan === 'video' ? '[Video]' : '[Hinh anh]');
        }

        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $phienChat->id,
            'khach_hang_id' => $khachHang?->id,
            'nhan_vien_id' => null,
            'nguoi_gui' => 'khach_hang',
            'loai_tin_nhan' => $loaiTinNhan,
            'noi_dung' => $noiDung,
            'tep_dinh_kem' => $tepDinhKem,
            'da_doc' => false,
        ]);

        $phienChat->update(['tin_nhan_cuoi_at' => now()]);

        // Xu ly bot ngay de dam bao local/dev van sinh tin nhan AI on dinh.
        if ($phienChat->dang_bot_xu_ly && !$phienChat->nhan_vien_phu_trach_id && $phienChat->trang_thai !== 'da_dong') {
            $this->xuLyBotSauPhanHoi((int) $phienChat->id, (int) $tinNhan->id);
        }

        return response()->json([
            'success' => true,
            'tin_nhan' => $tinNhan,
            'bot_reply' => null,
            'da_chuyen_nhan_vien' => false,
        ]);
    }

    // ══════════════════════════════════════════════
    // LẤY LỊCH SỬ CHAT
    // GET /chat/lich-su/{id}
    // ══════════════════════════════════════════════
    public function lichSu(int $id)
    {
        $khachHang = Auth::guard('customer')->user();
        $sessionId = session()->getId();

        // Kiểm tra phiên chat có thuộc về người dùng này không
        $phienChat = PhienChat::where('id', $id)
            ->where(function ($q) use ($khachHang, $sessionId) {
                if ($khachHang) {
                    $q->where('khach_hang_id', $khachHang->id);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$phienChat) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phiên chat.',
            ], 404);
        }

        $limit = max(20, min((int) request('limit', self::HISTORY_LIMIT), 200));

        $tinNhans = TinNhanChat::where('phien_chat_id', $id)
            ->latest('id')
            ->limit($limit)
            ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'tep_dinh_kem', 'da_doc', 'created_at'])
            ->reverse()
            ->values();

        TinNhanChat::where('phien_chat_id', $id)
            ->whereIn('nguoi_gui', ['nhan_vien', 'bot', 'he_thong'])
            ->where('da_doc', false)
            ->update(['da_doc' => true, 'da_doc_at' => now()]);

        return response()->json([
            'success' => true,
            'tin_nhans' => $tinNhans,
            'trang_thai' => $phienChat->trang_thai,
            'dang_bot_xu_ly' => (bool) $phienChat->dang_bot_xu_ly,
        ]);
    }

    public function chuyenNhanVien(Request $request, ?int $id = null, bool $fromSystem = false)
    {
        $phienChatId = $id ?: (int) $request->input('phien_chat_id');
        $sessionId = session()->getId();
        $khachHang = Auth::guard('customer')->user();

        if (!$phienChatId) {
            return response()->json(['success' => false, 'message' => 'Thieu phien chat.'], 422);
        }

        $phienChat = PhienChat::where('id', $phienChatId)
            ->where(function ($q) use ($khachHang, $sessionId) {
                if ($khachHang) {
                    $q->where('khach_hang_id', $khachHang->id)
                        ->orWhere('session_id', $sessionId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$phienChat) {
            return response()->json(['success' => false, 'message' => 'Khong tim thay phien chat.'], 404);
        }

        $phienChat->update([
            'trang_thai' => 'dang_cho',
            'dang_bot_xu_ly' => false,
            'tin_nhan_cuoi_at' => now(),
        ]);

        if (!$fromSystem) {
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'nguoi_gui' => 'he_thong',
                'loai_tin_nhan' => 'van_ban',
                'noi_dung' => 'Yeu cau cua ban da duoc chuyen cho nhan vien kinh doanh. Vui long doi trong giay lat.',
                'da_doc' => true,
            ]);
        } else {
            TinNhanChat::create([
                'phien_chat_id' => $phienChat->id,
                'nguoi_gui' => 'he_thong',
                'loai_tin_nhan' => 'van_ban',
                'noi_dung' => 'Toi da chuyen cuoc hoi thoai nay cho nhan vien kinh doanh de ho tro sau hon.',
                'da_doc' => true,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function chuyenLaiHeThong(Request $request)
    {
        $phienChatId = (int) $request->input('phien_chat_id');
        $sessionId = session()->getId();
        $khachHang = Auth::guard('customer')->user();

        if (!$phienChatId) {
            return response()->json(['success' => false, 'message' => 'Thieu phien chat.'], 422);
        }

        $phienChat = PhienChat::where('id', $phienChatId)
            ->where(function ($q) use ($khachHang, $sessionId) {
                if ($khachHang) {
                    $q->where('khach_hang_id', $khachHang->id)
                        ->orWhere('session_id', $sessionId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$phienChat) {
            return response()->json(['success' => false, 'message' => 'Khong tim thay phien chat.'], 404);
        }

        if ($phienChat->trang_thai === 'da_dong') {
            return response()->json(['success' => false, 'message' => 'Phien chat da dong.'], 422);
        }

        $phienChat->update([
            'trang_thai' => 'dang_bot',
            'dang_bot_xu_ly' => true,
            'nhan_vien_phu_trach_id' => null,
            'tin_nhan_cuoi_at' => now(),
        ]);

        TinNhanChat::create([
            'phien_chat_id' => $phienChat->id,
            'nguoi_gui' => 'he_thong',
            'loai_tin_nhan' => 'van_ban',
            'noi_dung' => 'Da chuyen lai hoi thoai ve tro ly AI. Ban co the tiep tuc dat cau hoi ngay.',
            'da_doc' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function longPoll(Request $request, int $id)
    {
        $sauId = (int) $request->input('sau_id', 0);

        $khachHang = Auth::guard('customer')->user();
        $sessionId = session()->getId();

        $phienChat = PhienChat::where('id', $id)
            ->where(function ($q) use ($khachHang, $sessionId) {
                if ($khachHang) {
                    $q->where('khach_hang_id', $khachHang->id)
                        ->orWhere('session_id', $sessionId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$phienChat) {
            return response()->json(['success' => false, 'message' => 'Khong tim thay phien chat.'], 404);
        }

        // Fast short-poll: tra ngay de tranh giu ket noi lau lam nghen worker PHP.
        $tinNhans = TinNhanChat::where('phien_chat_id', $id)
            ->where('id', '>', $sauId)
            ->orderBy('id', 'asc')
            ->limit(50)
            ->get(['id', 'nguoi_gui', 'loai_tin_nhan', 'noi_dung', 'tep_dinh_kem', 'da_doc', 'created_at']);

        if ($tinNhans->isNotEmpty()) {
            TinNhanChat::where('phien_chat_id', $id)
                ->whereIn('nguoi_gui', ['nhan_vien', 'bot', 'he_thong'])
                ->where('da_doc', false)
                ->update(['da_doc' => true, 'da_doc_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'tin_nhans' => $tinNhans,
            'trang_thai' => $phienChat->trang_thai,
            'dang_bot_xu_ly' => (bool) $phienChat->dang_bot_xu_ly,
        ]);
    }

    private function xuLyBotSauPhanHoi(int $phienChatId, int $tinNhanKhachId): void
    {
        $phienChat = PhienChat::find($phienChatId);
        if (!$phienChat || !$phienChat->dang_bot_xu_ly || $phienChat->nhan_vien_phu_trach_id || $phienChat->trang_thai === 'da_dong') {
            return;
        }

        $tinNhanKhach = TinNhanChat::where('id', $tinNhanKhachId)
            ->where('phien_chat_id', $phienChatId)
            ->first();

        if (!$tinNhanKhach) {
            return;
        }

        $aiResult = $this->geminiChatService->chat($phienChat, (string) $tinNhanKhach->noi_dung);

        TinNhanChat::create([
            'phien_chat_id' => $phienChat->id,
            'khach_hang_id' => null,
            'nhan_vien_id' => null,
            'nguoi_gui' => 'bot',
            'loai_tin_nhan' => 'van_ban',
            'noi_dung' => $aiResult['reply'],
            'da_doc' => false,
        ]);

        $phienChat->update(['tin_nhan_cuoi_at' => now()]);

        if ((bool) ($aiResult['can_chuyen_nv'] ?? false)) {
            $req = Request::create('', 'POST', ['phien_chat_id' => $phienChat->id]);
            $this->chuyenNhanVien($req, $phienChat->id, true);
        }
    }
}
