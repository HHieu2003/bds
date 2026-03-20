<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use Illuminate\Http\Request;

class BaiVietController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo query lấy bài viết đang hiển thị
        $query = BaiViet::where('hien_thi', 1);

        // 2. Lọc theo loại (chuyên mục) từ Header truyền xuống
        if ($request->filled('loai')) {
            $query->where('loai_bai_viet', $request->loai);
        }

        // 3. Lọc theo từ khóa tìm kiếm
        if ($request->filled('tu_khoa')) {
            $query->where('tieu_de', 'like', '%' . $request->tu_khoa . '%');
        }

        // 4. Lấy danh sách phân trang (9 bài 1 trang)
        $baiViets = $query->orderBy('thoi_diem_dang', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        // 5. Lấy 5 bài viết nổi bật cho Sidebar bên phải
        $tinNoiBat = BaiViet::where('hien_thi', 1)
            ->where('noi_bat', 1)
            ->orderBy('thoi_diem_dang', 'desc')
            ->limit(5)
            ->get();

        return view('frontend.bai-viet.index', compact('baiViets', 'tinNoiBat'));
    }

    public function show($slug)
    {
        // 1. Lấy chi tiết bài viết
        $baiViet = BaiViet::where('slug', $slug)->where('hien_thi', 1)->firstOrFail();

        // 2. Tự động tăng lượt xem khi có người click vào đọc (Tính năng thực tế)
        $baiViet->increment('luot_xem');

        // 3. Lấy 3 bài viết liên quan (Cùng thể loại, trừ bài hiện tại)
        $tinLienQuan = BaiViet::where('hien_thi', 1)
            ->where('id', '!=', $baiViet->id)
            ->where('loai_bai_viet', $baiViet->loai_bai_viet)
            ->orderBy('thoi_diem_dang', 'desc')
            ->limit(3)
            ->get();

        // 4. Lấy tin nổi bật cho sidebar
        $tinNoiBat = BaiViet::where('hien_thi', 1)->where('noi_bat', 1)->limit(5)->get();

        return view('frontend.bai-viet.show', compact('baiViet', 'tinLienQuan', 'tinNoiBat'));
    }
}
