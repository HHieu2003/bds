<?php

namespace App\Http\Controllers;

use App\Models\KyGui;
use Illuminate\Http\Request;

class KyGuiController extends Controller
{
    // --- FRONTEND: Hiển thị form ký gửi ---
    public function create()
    {
        return view('frontend.ky_gui.create');
    }

    // --- FRONTEND: Xử lý lưu form ---
    public function store(Request $request)
    {
        $request->validate([
            'ho_ten_chu_nha' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'dia_chi' => 'required|string',
            'loai_hinh' => 'required',
            'gia_mong_muon' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['trang_thai'] = 'cho_duyet';

        // Xử lý upload ảnh minh họa
        if ($request->hasFile('hinh_anh_tham_khao')) {
            $file = $request->file('hinh_anh_tham_khao');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kygui'), $filename);
            $data['hinh_anh_tham_khao'] = 'uploads/kygui/' . $filename;
        }

        KyGui::create($data);

        return redirect()->back()->with('success', 'Gửi yêu cầu ký gửi thành công! Nhân viên sẽ liên hệ lại sớm.');
    }

    // --- ADMIN: Danh sách ký gửi ---
    public function adminIndex()
    {
        // Lấy danh sách mới nhất
        $ky_guis = KyGui::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.ky_gui.index', ['ky_guis' => $ky_guis]);
    }

    // --- ADMIN: Cập nhật trạng thái ---
    public function updateStatus(Request $request, $id)
    {
        $ky_gui = KyGui::findOrFail($id);

        $ky_gui->update([
            'trang_thai' => $request->trang_thai,
            'phan_hoi_cua_admin' => $request->phan_hoi_cua_admin
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái yêu cầu!');
    }

    // --- ADMIN: Xóa ký gửi ---
    public function destroy($id)
    {
        $ky_gui = KyGui::findOrFail($id);
        $ky_gui->delete();

        return redirect()->back()->with('success', 'Đã xóa yêu cầu ký gửi!');
    }
}
