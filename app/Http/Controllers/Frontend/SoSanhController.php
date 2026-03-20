<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use Illuminate\Http\Request;

class SoSanhController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy chuỗi ID từ URL (VD: ?ids=1,5,8)
        $ids = $request->query('ids');
        $danhSachBds = collect(); // Mảng rỗng mặc định

        if ($ids) {
            // Cắt chuỗi thành mảng
            $idArray = explode(',', $ids);

            // 2. Truy vấn dữ liệu các BĐS này kèm theo Dự Án và Khu Vực
            $danhSachBds = BatDongSan::with(['duAn', 'khuVuc'])
                ->whereIn('id', $idArray)
                ->where('hien_thi', 1)
                ->get();
        }

        return view('frontend.so-sanh.index', compact('danhSachBds'));
    }
    public function loadModal(Request $request)
    {
        $ids = $request->query('ids');
        $danhSachBds = collect();

        if ($ids) {
            $idArray = explode(',', $ids);
            $danhSachBds = BatDongSan::with(['duAn', 'khuVuc'])
                ->whereIn('id', $idArray)
                ->where('hien_thi', 1)
                ->get();
        }

        // Trả về view chỉ chứa duy nhất HTML của cái Bảng (Table)
        return view('frontend.so-sanh._table', compact('danhSachBds'));
    }
}
