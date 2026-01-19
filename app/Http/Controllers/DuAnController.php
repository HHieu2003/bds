<?php

namespace App\Http\Controllers;

use App\Models\DuAn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Thư viện xử lý file

class DuAnController extends Controller
{
    // 1. Danh sách
    public function index()
    {
        $danhSachDuAn = DuAn::orderBy('created_at', 'desc')->get();
        return view('admin.du_an.index', compact('danhSachDuAn'));
    }

    // 2. Form thêm mới
    public function create()
    {
        return view('admin.du_an.create');
    }

    // 3. Xử lý lưu (Có upload ảnh)
    public function store(Request $request)
    {
        $request->validate([
            'ten_du_an' => 'required|max:255',
            'hinh_anh' => 'nullable|image|max:2048', // Ảnh tối đa 2MB
        ]);

        $data = $request->all();

        // Xử lý upload ảnh
        if ($request->hasFile('hinh_anh')) {
            // Lưu vào storage/app/public/uploads/duan
            $path = $request->file('hinh_anh')->store('uploads/duan', 'public');
            $data['hinh_anh'] = $path;
        }

        DuAn::create($data);

        return redirect()->route('du-an.index')->with('success', 'Thêm dự án thành công!');
    }

    // 4. Form sửa
    public function edit(DuAn $duAn)
    {
        return view('admin.du_an.edit', compact('duAn'));
    }

    // 5. Xử lý cập nhật
    public function update(Request $request, DuAn $duAn)
    {
        $request->validate([
            'ten_du_an' => 'required|max:255',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Nếu người dùng chọn ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // (Tùy chọn) Xóa ảnh cũ đi để đỡ rác server
            if ($duAn->hinh_anh && Storage::disk('public')->exists($duAn->hinh_anh)) {
                Storage::disk('public')->delete($duAn->hinh_anh);
            }

            // Lưu ảnh mới
            $path = $request->file('hinh_anh')->store('uploads/duan', 'public');
            $data['hinh_anh'] = $path;
        }

        $duAn->update($data);

        return redirect()->route('du-an.index')->with('success', 'Cập nhật dự án thành công!');
    }

    // 6. Xóa
    public function destroy(DuAn $duAn)
    {
        // Xóa ảnh khi xóa dự án
        if ($duAn->hinh_anh && Storage::disk('public')->exists($duAn->hinh_anh)) {
            Storage::disk('public')->delete($duAn->hinh_anh);
        }

        $duAn->delete();
        return redirect()->route('du-an.index')->with('success', 'Đã xóa dự án!');
    }
}
