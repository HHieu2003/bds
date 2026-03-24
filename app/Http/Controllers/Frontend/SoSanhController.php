<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use Illuminate\Http\Request;

class SoSanhController extends Controller
{
    public function index(Request $request)
    {
        $danhSachBds = collect();

        // Đọc từ ?ids= trên URL (từ localStorage client)
        if ($request->filled('ids')) {
            $idArray = explode(',', $request->query('ids'));

            $danhSachBds = BatDongSan::with(['duAn', 'khuVuc'])
                ->whereIn('id', $idArray)
                ->where('hien_thi', 1)   // ← đúng tên cột gốc của bạn
                ->get();
        }
        // Giữ tương thích ngược với session cũ
        elseif (session()->has('so_sanh')) {
            $idArray = session('so_sanh', []);

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
    public function modal(Request $request)
    {
        $danhSachBds = collect();

        if ($request->filled('ids')) {
            $ids = array_filter(
                array_map('intval', explode(',', $request->ids))
            );
            if (!empty($ids)) {
                $danhSachBds = \App\Models\BatDongSan::with(['khuVuc', 'duAn'])
                    ->whereIn('id', $ids)
                    ->get();
            }
        }

        return view('frontend.so-sanh._table', compact('danhSachBds'));
    }
}
