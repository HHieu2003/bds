<?php

namespace App\Http\Controllers;

use App\Models\DuAn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DuAnController extends Controller
{
    // ==========================================
    // 1. PHẦN DÀNH CHO ADMIN (QUẢN TRỊ)
    // ==========================================

    // Danh sách dự án (Admin)
    public function index()
    {
        $du_ans = DuAn::orderBy('created_at', 'desc')->get();
        return view('admin.du_an.index', ['du_ans' => $du_ans]);
    }

    // Form thêm mới
    public function create()
    {
        return view('admin.du_an.create');
    }

    // Xử lý lưu
    public function store(Request $request)
    {
        $request->validate([
            'ten_du_an' => 'required|max:255',
            'vi_tri' => 'required',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->ten_du_an) . '-' . time();

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('uploads/du_an', 'public');
        }

        DuAn::create($data);
        return redirect()->route('admin.du-an.index')->with('success', 'Thêm dự án thành công!');
    }

    // Form sửa
    public function edit(DuAn $duAn)
    {
        return view('admin.du_an.edit', compact('duAn'));
    }

    // Xử lý cập nhật
    public function update(Request $request, DuAn $duAn)
    {
        $request->validate([
            'ten_du_an' => 'required|max:255',
            'vi_tri' => 'required',
        ]);

        $data = $request->all();
        if ($duAn->ten_du_an != $request->ten_du_an) {
            $data['slug'] = Str::slug($request->ten_du_an) . '-' . time();
        }

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('uploads/du_an', 'public');
        }

        $duAn->update($data);
        return redirect()->route('admin.du-an.index')->with('success', 'Cập nhật thành công!');
    }

    // Xóa
    public function destroy(DuAn $duAn)
    {
        $duAn->delete();
        return redirect()->route('admin.du-an.index')->with('success', 'Đã xóa dự án!');
    }

    // ==========================================
    // 2. PHẦN DÀNH CHO KHÁCH HÀNG (FRONTEND)
    // ==========================================

    // Danh sách dự án ngoài trang chủ
    public function frontendIndex()
    {
        $du_ans = DuAn::orderBy('created_at', 'desc')->get();
        // Trả về view của khách hàng
        return view('frontend.du_an.index', ['du_ans' => $du_ans]);
    }

    // Chi tiết dự án
    public function show($slug)
    {
        $duAn = DuAn::where('slug', $slug)->firstOrFail();
        return view('frontend.du_an.show', compact('duAn'));
    }
}
