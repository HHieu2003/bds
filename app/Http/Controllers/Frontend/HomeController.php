<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\YeuThich;
use Illuminate\Support\Facades\Auth;
use App\Services\GoiYService;

class HomeController extends Controller
{
    public function index(GoiYService $goiYService)
    {
        $bdsNoiBat = BatDongSan::with('duAn')->where('hien_thi', true)->where('noi_bat', true)->where('trang_thai', 'con_hang')->orderBy('thu_tu_hien_thi')->limit(8)->get();
        $bdsBan = BatDongSan::with('duAn')->where('hien_thi', true)->where('nhu_cau', 'ban')->where('trang_thai', 'con_hang')->latest()->limit(6)->get();
        $bdsThue = BatDongSan::with('duAn')->where('hien_thi', true)->where('nhu_cau', 'thue')->where('trang_thai', 'con_hang')->latest()->limit(6)->get();
        $khachHangId = Auth::guard('customer')->id();
        $sessionId   = session()->getId();

        $goiYBds = $goiYService->layGoiY($khachHangId, $sessionId);
        $favoriteMap = collect();
        if (Auth::guard('customer')->check()) {
            $allBdsIds = $bdsNoiBat->pluck('id')
                ->merge($bdsBan->pluck('id'))
                ->merge($bdsThue->pluck('id'))
                ->unique()
                ->values();

            $favoriteMap = YeuThich::where('khach_hang_id', Auth::guard('customer')->id())
                ->whereIn('bat_dong_san_id', $allBdsIds)
                ->pluck('bat_dong_san_id')
                ->flip();
        }

        $bdsNoiBat->transform(function ($item) use ($favoriteMap) {
            $item->setAttribute('isYeuThich', $favoriteMap->has($item->id));
            return $item;
        });
        $bdsBan->transform(function ($item) use ($favoriteMap) {
            $item->setAttribute('isYeuThich', $favoriteMap->has($item->id));
            return $item;
        });
        $bdsThue->transform(function ($item) use ($favoriteMap) {
            $item->setAttribute('isYeuThich', $favoriteMap->has($item->id));
            return $item;
        });

        $duAnNoiBat = DuAn::with('khuVuc')->orderBy('thu_tu_hien_thi')->get();
        $baiVietMoi = BaiViet::where('hien_thi', true)->orderByDesc('thoi_diem_dang')->limit(8)->get();

        $khuVuc = KhuVuc::where('hien_thi', true)->orderBy('thu_tu_hien_thi')->get();

        // THÊM DÒNG NÀY: Lấy toàn bộ dự án để đưa vào Form tìm kiếm
        $danhSachDuAn = DuAn::where('hien_thi', true)->orderBy('ten_du_an')->get();

        // Thêm 'danhSachDuAn' vào compact
        return view('frontend.home.index', compact(
            'bdsNoiBat',
            'bdsBan',
            'bdsThue',
            'duAnNoiBat',
            'baiVietMoi',
            'khuVuc',
            'goiYBds',
            'danhSachDuAn'
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
