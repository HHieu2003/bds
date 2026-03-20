<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DuAn;
use App\Models\KhuVuc;
use Illuminate\Http\Request;

class DuAnController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo query lấy các dự án đang hiển thị
        $query = DuAn::with('khuVuc')->where('hien_thi', 1);

        // 2. Kiểm tra nếu có bấm lọc từ Header (Truyền lên ?khu_vuc=...)
        if ($request->filled('khu_vuc')) {
            $khu_vuc = $request->khu_vuc;

            // Xử lý linh hoạt: Nếu truyền ID (số), hoặc truyền Slug (chuỗi như 'smart-city')
            if (is_numeric($khu_vuc)) {
                $query->where('khu_vuc_id', $khu_vuc);
            } else {
                // Nếu truyền chuỗi từ các link cứng trên header
                $query->whereHas('khuVuc', function ($q) use ($khu_vuc) {
                    $q->where('slug', $khu_vuc)->orWhere('ten_khu_vuc', 'LIKE', '%' . $khu_vuc . '%');
                });
            }
        }

        // 3. Phân trang: Lấy 12 dự án mỗi trang, sắp xếp theo thứ tự hiển thị ưu tiên
        $duAns = $query->orderBy('thu_tu_hien_thi', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Lấy danh sách khu vực để làm bộ lọc ở thanh Sidebar (nếu cần)
        $khuVucs = KhuVuc::where('hien_thi', 1)->whereNull('khu_vuc_cha_id')->get();

        return view('frontend.du-an.index', compact('duAns', 'khuVucs'));
    }

    public function show($slug)
    {
        // Hàm này dành cho trang chi tiết dự án (bạn có thể code sau)
        $duAn = DuAn::with(['khuVuc', 'batDongSans'])->where('slug', $slug)->where('hien_thi', 1)->firstOrFail();

        return view('frontend.du-an.show', compact('duAn'));
    }
}
