<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhienChat;
use App\Models\TinNhanChat;
use App\Models\TinNhanNoiBo;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Danh sách phiên chat đang mở
    public function index()
    {
        $nhanVien = Auth::user();

        $phienChat = PhienChat::with(['khachHang', 'tinNhanCuoi'])
            ->when($nhanVien->isSale(), fn($q) => $q->where('nhan_vien_phu_trach_id', $nhanVien->id))
            ->orderByDesc('tin_nhan_cuoi_at')
            ->paginate(20);

        return view('admin.chat.index', compact('phienChat'));
    }

    // Lấy tin nhắn trong phiên (AJAX)
    public function layTinNhan(PhienChat $phienChat)
    {
        $tinNhan = $phienChat->tinNhan()
            ->with(['khachHang', 'nhanVien'])
            ->get();

        // Đánh dấu đã đọc
        $phienChat->tinNhan()
            ->where('nguoi_gui', 'khach_hang')
            ->where('da_doc', false)
            ->update(['da_doc' => true, 'da_doc_at' => now()]);

        return response()->json($tinNhan);
    }

    // Gửi tin nhắn (Admin → Khách)
    public function guiTinNhan(Request $request, PhienChat $phienChat)
    {
        $request->validate([
            'noi_dung'     => 'nullable|string',
            'tep_dinh_kem' => 'nullable|file|max:5120',
        ]);

        $tinNhan = TinNhanChat::create([
            'phien_chat_id' => $phienChat->id,
            'nhan_vien_id'  => Auth::id(),
            'nguoi_gui'     => 'nhan_vien',
            'loai_tin_nhan' => $request->hasFile('tep_dinh_kem') ? 'dinh_kem' : 'van_ban',
            'noi_dung'      => $request->noi_dung,
            'tep_dinh_kem'  => $request->hasFile('tep_dinh_kem')
                ? $request->file('tep_dinh_kem')->store('chat', 'public')
                : null,
        ]);

        $phienChat->update(['tin_nhan_cuoi_at' => now()]);

        return response()->json(['success' => true, 'tin_nhan' => $tinNhan]);
    }

    // Tin nhắn nội bộ giữa nhân viên
    public function tinNhanNoiBo()
    {
        $nhanVien = Auth::user();

        $danhSachNhanVien = NhanVien::where('id', '!=', $nhanVien->id)
            ->where('kich_hoat', true)
            ->get()
            ->map(function ($nv) use ($nhanVien) {
                $nv->so_chua_doc = TinNhanNoiBo::where('nguoi_gui_id', $nv->id)
                    ->where('nguoi_nhan_id', $nhanVien->id)
                    ->where('da_doc', false)
                    ->count();
                return $nv;
            });

        return view('admin.chat.noi-bo', compact('danhSachNhanVien'));
    }

    // Lấy tin nhắn nội bộ với 1 nhân viên (AJAX)
    public function layTinNhanNoiBo(NhanVien $nguoiNhan)
    {
        $tinNhan = TinNhanNoiBo::giua(Auth::id(), $nguoiNhan->id)
            ->with(['nguoiGui', 'nguoiNhan'])
            ->orderBy('created_at')
            ->get();

        // Đánh dấu đã đọc
        TinNhanNoiBo::where('nguoi_gui_id', $nguoiNhan->id)
            ->where('nguoi_nhan_id', Auth::id())
            ->where('da_doc', false)
            ->update(['da_doc' => true, 'da_doc_at' => now()]);

        return response()->json($tinNhan);
    }

    // Gửi tin nhắn nội bộ (AJAX)
    public function guiTinNhanNoiBo(Request $request, NhanVien $nguoiNhan)
    {
        $request->validate([
            'noi_dung'  => 'required|string',
            'lich_hen_id' => 'nullable|exists:lich_hen,id',
        ]);

        $tinNhan = TinNhanNoiBo::create([
            'nguoi_gui_id'  => Auth::id(),
            'nguoi_nhan_id' => $nguoiNhan->id,
            'lich_hen_id'   => $request->lich_hen_id,
            'loai_tin_nhan' => 'van_ban',
            'noi_dung'      => $request->noi_dung,
        ]);

        return response()->json(['success' => true, 'tin_nhan' => $tinNhan->load('nguoiGui')]);
    }
}
