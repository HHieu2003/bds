<?php

namespace App\Http\Controllers;

use App\Models\BaiViet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BaiVietController extends Controller
{
    // ==========================================
    // 1. PHẦN DÀNH CHO ADMIN (QUẢN TRỊ)
    // ==========================================

    public function index()
    {
        $bai_viets = BaiViet::orderBy('created_at', 'desc')->get();
        return view('admin.bai_viet.index', ['bai_viets' => $bai_viets]);
    }

    public function create()
    {
        return view('admin.bai_viet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|max:255',
            'noi_dung' => 'required',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->tieu_de) . '-' . time();
        $data['user_id'] = Auth::id(); // Lưu người đăng

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('uploads/bai_viet', 'public');
        }

        BaiViet::create($data);
        return redirect()->route('admin.bai-viet.index')->with('success', 'Đăng bài thành công!');
    }

    public function edit(BaiViet $baiViet)
    {
        return view('admin.bai_viet.edit', compact('baiViet'));
    }

    public function update(Request $request, BaiViet $baiViet)
    {
        $request->validate([
            'tieu_de' => 'required|max:255',
            'noi_dung' => 'required',
        ]);

        $data = $request->all();
        if ($baiViet->tieu_de != $request->tieu_de) {
            $data['slug'] = Str::slug($request->tieu_de) . '-' . time();
        }

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('uploads/bai_viet', 'public');
        }

        $baiViet->update($data);
        return redirect()->route('admin.bai-viet.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(BaiViet $baiViet)
    {
        $baiViet->delete();
        return redirect()->route('admin.bai-viet.index')->with('success', 'Đã xóa bài viết!');
    }

    // ==========================================
    // 2. PHẦN DÀNH CHO KHÁCH HÀNG (FRONTEND)
    // ==========================================

    public function frontendIndex()
    {
        // Lấy tin tức mới nhất, phân trang 10 bài
        $bai_viets = BaiViet::orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.bai_viet.index', ['bai_viets' => $bai_viets]);
    }

    public function show($slug)
    {
        $baiViet = BaiViet::where('slug', $slug)->firstOrFail();

        // Lấy 5 bài viết liên quan (trừ bài hiện tại)
        $lienQuan = BaiViet::where('id', '!=', $baiViet->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('frontend.bai_viet.show', compact('baiViet', 'lienQuan'));
    }
}
