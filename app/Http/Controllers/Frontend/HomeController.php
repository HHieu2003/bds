<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\YeuThich;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\GoiYService;

class HomeController extends Controller
{
    public function index(GoiYService $goiYService)
    {
        // ── Dữ liệu tĩnh: cache 10 phút (ít thay đổi) ──
        $bdsNoiBat = Cache::remember('home:bds_noi_bat', now()->addMinutes(10), function () {
            return BatDongSan::with('duAn')->where('hien_thi', true)->where('noi_bat', true)->where('trang_thai', 'con_hang')->orderBy('thu_tu_hien_thi')->limit(8)->get();
        });
        $bdsBan = Cache::remember('home:bds_ban', now()->addMinutes(10), function () {
            return BatDongSan::with('duAn')->where('hien_thi', true)->where('nhu_cau', 'ban')->where('trang_thai', 'con_hang')->latest()->limit(6)->get();
        });
        $bdsThue = Cache::remember('home:bds_thue', now()->addMinutes(10), function () {
            return BatDongSan::with('duAn')->where('hien_thi', true)->where('nhu_cau', 'thue')->where('trang_thai', 'con_hang')->latest()->limit(6)->get();
        });

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

        // Clone để không biến đổi bản gốc trong cache
        $bdsNoiBat = $bdsNoiBat->map(function ($item) use ($favoriteMap) {
            $clone = clone $item;
            $clone->setAttribute('isYeuThich', $favoriteMap->has($clone->id));
            return $clone;
        });
        $bdsBan = $bdsBan->map(function ($item) use ($favoriteMap) {
            $clone = clone $item;
            $clone->setAttribute('isYeuThich', $favoriteMap->has($clone->id));
            return $clone;
        });
        $bdsThue = $bdsThue->map(function ($item) use ($favoriteMap) {
            $clone = clone $item;
            $clone->setAttribute('isYeuThich', $favoriteMap->has($clone->id));
            return $clone;
        });

        $duAnNoiBat = Cache::remember('home:du_an_noi_bat', now()->addMinutes(10), function () {
            return DuAn::with('khuVuc')->orderBy('thu_tu_hien_thi')->get();
        });
        $baiVietMoi = Cache::remember('home:bai_viet_moi', now()->addMinutes(10), function () {
            return BaiViet::where('hien_thi', true)->orderByDesc('thoi_diem_dang')->limit(8)->get();
        });
        $khuVuc = Cache::remember('home:khu_vuc', now()->addMinutes(10), function () {
            return KhuVuc::where('hien_thi', true)->orderBy('thu_tu_hien_thi')->get();
        });
        $danhSachDuAn = Cache::remember('home:danh_sach_du_an', now()->addMinutes(10), function () {
            return DuAn::where('hien_thi', true)->orderBy('ten_du_an')->get();
        });

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
    public function congCuTaiChinh()
    {
        // Lấy danh sách ngân hàng đang hiển thị
        $nganHangs = \App\Models\NganHang::where('trang_thai', 1)->orderBy('lai_suat_uu_dai', 'asc')->get();

        return view('frontend.pages.cong-cu-tai-chinh', compact('nganHangs'));
    }
}
