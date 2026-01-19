<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatDongSan;
use App\Models\DuAn;

class HomeController extends Controller
{
    // 1. TRANG CHỦ
    public function index()
    {
        // Lấy 6 BĐS mới nhất
        $dsBatDongSan = BatDongSan::with(['duAn', 'user'])
            ->where('trang_thai', 'con_hang')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Lấy 3 Dự án nổi bật
        $duAnNoiBat = DuAn::orderBy('created_at', 'desc')->limit(3)->get();

        // --- SỬA DÒNG NÀY ---
        // Cũ: return view('welcome', ...);
        return view('frontend.home.index', compact('dsBatDongSan', 'duAnNoiBat'));
    }

    // 2. CHI TIẾT CĂN HỘ (BẤT ĐỘNG SẢN LẺ)
    public function show($slug)
    {
        $bds = BatDongSan::with(['duAn', 'user'])->where('slug', $slug)->firstOrFail();

        // --- SỬA DÒNG NÀY ---
        // Cũ: return view('chitiet', ...);
        return view('frontend.bat_dong_san.show', compact('bds'));
    }

    // 3. DANH SÁCH DỰ ÁN
    public function listProjects()
    {
        $duAn = DuAn::orderBy('created_at', 'desc')->paginate(9);

        // Đường dẫn này chuẩn theo cấu trúc thư mục mới
        return view('frontend.du_an.index', compact('duAn'));
    }

    // 4. CHI TIẾT DỰ ÁN
    public function showProject($id)
    {
        $duAn = DuAn::with(['batDongSans' => function ($query) {
            $query->where('trang_thai', 'con_hang');
        }])->findOrFail($id);

        // Đường dẫn này chuẩn theo cấu trúc thư mục mới
        return view('frontend.du_an.show', compact('duAn'));
    }

    // 5. TRANG GIỚI THIỆU
    public function about()
    {
        return view('frontend.home.about');
    }
}
