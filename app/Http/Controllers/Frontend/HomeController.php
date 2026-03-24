<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;

class HomeController extends Controller
{
    public function index()
    {
        $bdsNoiBat = BatDongSan::with('duAn')
            ->where('hien_thi', true)
            ->where('noi_bat', true)
            ->where('trang_thai', 'con_hang')
            ->orderBy('thu_tu_hien_thi')
            ->limit(8)
            ->get();

        $bdsBan = BatDongSan::with('duAn')
            ->where('hien_thi', true)
            ->where('nhu_cau', 'ban')
            ->where('trang_thai', 'con_hang')
            ->latest()
            ->limit(6)
            ->get();

        $bdsThue = BatDongSan::with('duAn')
            ->where('hien_thi', true)
            ->where('nhu_cau', 'thue')
            ->where('trang_thai', 'con_hang')
            ->latest()
            ->limit(6)
            ->get();

        $duAnNoiBat = DuAn::with('khuVuc')

            ->orderBy('thu_tu_hien_thi')

            ->get();

        $baiVietMoi = BaiViet::where('hien_thi', true)
            ->orderByDesc('thoi_diem_dang')
            ->limit(3)
            ->get();

        $khuVuc = KhuVuc::where('hien_thi', true)
            ->whereNull('khu_vuc_cha_id')
            ->orderBy('thu_tu_hien_thi')
            ->get();

        return view('frontend.home.index', compact(
            'bdsNoiBat',
            'bdsBan',
            'bdsThue',
            'duAnNoiBat',
            'baiVietMoi',
            'khuVuc'
        ));
    }

    public function gioiThieu()
    {
        return view('frontend.pages.about');
    }
    public function noithat()
    {
        return view('frontend.pages.noi-that');
    }
    public function tuyendung()
    {
        return view('frontend.pages.tuyen-dung');
    }
}
