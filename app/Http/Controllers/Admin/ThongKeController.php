<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\ChuNha;
use App\Models\KhachHang;
use App\Models\KyGui;
use App\Models\LichHen;
use App\Models\LuotTruyCap;
use App\Models\YeuCauLienHe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $now   = Carbon::now();
        $today = Carbon::today();

        // ── Bộ lọc ngày ────────────────────────────────────────
        $loai_loc       = $request->input('loai_loc', 'thang');
        $tu_ngay_input  = $request->input('tu_ngay');
        $den_ngay_input = $request->input('den_ngay');

        switch ($loai_loc) {
            case 'ngay':
                $startDate = $today->copy()->startOfDay();
                $endDate   = $today->copy()->endOfDay();
                $label     = 'Hôm nay ' . $today->format('d/m/Y');
                break;
            case 'tuan':
                $startDate = $now->copy()->startOfWeek();
                $endDate   = $now->copy()->endOfWeek();
                $label     = 'Tuần này';
                break;
            case 'nam':
                $startDate = $now->copy()->startOfYear();
                $endDate   = $now->copy()->endOfYear();
                $label     = 'Năm ' . $now->year;
                break;
            case 'tuy_chon':
                $startDate = $tu_ngay_input  ? Carbon::parse($tu_ngay_input)->startOfDay()  : $now->copy()->startOfMonth();
                $endDate   = $den_ngay_input ? Carbon::parse($den_ngay_input)->endOfDay()   : $now->copy()->endOfMonth();
                $label     = $startDate->format('d/m/Y') . ' → ' . $endDate->format('d/m/Y');
                break;
            default:
                $loai_loc  = 'thang';
                $startDate = $now->copy()->startOfMonth();
                $endDate   = $now->copy()->endOfMonth();
                $label     = 'Tháng ' . $now->format('m/Y');
        }

        // Kỳ trước để so sánh
        $diffDays  = max(1, (int) $startDate->diffInDays($endDate) + 1);
        $prevStart = $startDate->copy()->subDays($diffDays);
        $prevEnd   = $endDate->copy()->subDays($diffDays);

        // Khoảng cố định (tuần/tháng thực) dùng cho một số widget không đổi
        $tuanStart   = $now->copy()->startOfWeek();
        $tuanEnd     = $now->copy()->endOfWeek();
        $thangStart  = $now->copy()->startOfMonth();
        $thangEnd    = $now->copy()->endOfMonth();
        $namStart    = $now->copy()->startOfYear();
        $namEnd      = $now->copy()->endOfYear();

        // ════════════════════════════════════════════════════
        // 1. LƯỢT TRUY CẬP
        // ════════════════════════════════════════════════════
        $visitorNow  = LuotTruyCap::whereBetween('created_at', [$startDate, $endDate])->distinct('session_id')->count('session_id');
        $visitorPrev = LuotTruyCap::whereBetween('created_at', [$prevStart, $prevEnd])->distinct('session_id')->count('session_id');
        $visitorDelta = $visitorPrev > 0 ? round(($visitorNow - $visitorPrev) / $visitorPrev * 100, 1) : ($visitorNow > 0 ? 100 : 0);

        $visitorHom   = LuotTruyCap::whereDate('created_at', $today)->distinct('session_id')->count('session_id');
        $visitorTuan  = LuotTruyCap::whereBetween('created_at', [$tuanStart, $tuanEnd])->distinct('session_id')->count('session_id');
        $visitorThang = LuotTruyCap::whereBetween('created_at', [$thangStart, $thangEnd])->distinct('session_id')->count('session_id');
        $visitorNam   = LuotTruyCap::whereBetween('created_at', [$namStart, $namEnd])->distinct('session_id')->count('session_id');

        // Biểu đồ 30 ngày (cố định, không phụ thuộc filter)
        $chart30Ngay = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i);
            $chart30Ngay[] = ['ngay' => $d->format('d/m'), 'luot' => LuotTruyCap::whereDate('created_at', $d->toDateString())->distinct('session_id')->count('session_id')];
        }

        // ════════════════════════════════════════════════════
        // 2. TRANG XEM NHIỀU NHẤT
        // ════════════════════════════════════════════════════
        $trangNoiBat = DB::table('luot_truy_cap')
            ->select('trang', DB::raw('COUNT(DISTINCT session_id) as luot'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('trang')->orderByDesc('luot')->get()
            ->map(fn($r) => ['trang' => match ($r->trang) {
                'home' => 'Trang chủ', 'bds_list' => 'Danh sách BĐS', 'bds_detail' => 'Chi tiết BĐS',
                'du_an_list' => 'Danh sách Dự án', 'du_an_detail' => 'Chi tiết Dự án',
                'tim_kiem' => 'Tìm kiếm', 'lien_he' => 'Liên hệ', 'tin_tuc' => 'Tin tức',
                'ky_gui' => 'Ký gửi', default => 'Khác',
            }, 'luot' => $r->luot]);

        // ════════════════════════════════════════════════════
        // 3. KHU VỰC XEM NHIỀU
        // ════════════════════════════════════════════════════
        $khuVucNoiBat = DB::table('lich_su_xem_bds as lx')
            ->join('khu_vuc as kv', 'lx.khu_vuc_id', '=', 'kv.id')
            ->select('kv.id', 'kv.ten_khu_vuc', DB::raw('COUNT(*) as luot_xem'))
            ->whereNotNull('lx.khu_vuc_id')
            ->whereBetween('lx.created_at', [$startDate, $endDate])
            ->groupBy('kv.id', 'kv.ten_khu_vuc')->orderByDesc('luot_xem')->limit(8)->get();

        // ════════════════════════════════════════════════════
        // 4. DỰ ÁN XEM NHIỀU
        // ════════════════════════════════════════════════════
        $duAnNoiBat = DB::table('lich_su_xem_bds as lx')
            ->join('du_an as da', 'lx.du_an_id', '=', 'da.id')
            ->select('da.id', 'da.ten_du_an', DB::raw('COUNT(*) as luot_xem'))
            ->whereNotNull('lx.du_an_id')
            ->whereBetween('lx.created_at', [$startDate, $endDate])
            ->groupBy('da.id', 'da.ten_du_an')->orderByDesc('luot_xem')->limit(8)->get();

        // ════════════════════════════════════════════════════
        // 5. BĐS XEM NHIỀU
        // ════════════════════════════════════════════════════
        $bdsNoiBat = DB::table('lich_su_xem_bds as lx')
            ->join('bat_dong_san as bds', 'lx.bat_dong_san_id', '=', 'bds.id')
            ->select('bds.id', 'bds.tieu_de', DB::raw('COUNT(*) as luot_xem'))
            ->whereNull('bds.deleted_at')
            ->whereBetween('lx.created_at', [$startDate, $endDate])
            ->groupBy('bds.id', 'bds.tieu_de')->orderByDesc('luot_xem')->limit(10)->get();

        // ════════════════════════════════════════════════════
        // 6. TỪ KHOÁ TÌM KIẾM
        // ════════════════════════════════════════════════════
        $tuKhoaTop = DB::table('lich_su_tim_kiem')
            ->select('tu_khoa', DB::raw('COUNT(*) as so_lan'))
            ->whereNotNull('tu_khoa')->where('tu_khoa', '!=', '')
            ->whereBetween('thoi_diem_tim_kiem', [$startDate, $endDate])
            ->groupBy('tu_khoa')->orderByDesc('so_lan')->limit(10)->get();

        // ════════════════════════════════════════════════════
        // 7. LỊCH HẸN
        // ════════════════════════════════════════════════════
        $lichHenNow  = LichHen::whereBetween('thoi_gian_hen', [$startDate, $endDate])->whereNotIn('trang_thai', ['huy', 'tu_choi'])->count();
        $lichHenPrev = LichHen::whereBetween('thoi_gian_hen', [$prevStart, $prevEnd])->whereNotIn('trang_thai', ['huy', 'tu_choi'])->count();
        $lichHenDelta = $lichHenPrev > 0 ? round(($lichHenNow - $lichHenPrev) / $lichHenPrev * 100, 1) : ($lichHenNow > 0 ? 100 : 0);
        $lichHenHoanThanh = LichHen::where('trang_thai', 'hoan_thanh')->whereBetween('hoan_thanh_at', [$startDate, $endDate])->count();
        $lichHenChoXuLy   = LichHen::whereIn('trang_thai', ['moi_dat', 'sale_da_nhan', 'cho_xac_nhan', 'cho_sale_xac_nhan_doi_gio'])->count();

        $chartLichHen6Thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $chartLichHen6Thang[] = [
                'thang'      => $m->format('m/Y'),
                'so_luong'   => LichHen::whereYear('thoi_gian_hen', $m->year)->whereMonth('thoi_gian_hen', $m->month)->whereNotIn('trang_thai', ['huy', 'tu_choi'])->count(),
                'hoan_thanh' => LichHen::whereYear('hoan_thanh_at', $m->year)->whereMonth('hoan_thanh_at', $m->month)->where('trang_thai', 'hoan_thanh')->count(),
            ];
        }

        // ════════════════════════════════════════════════════
        // 8. LEADS
        // ════════════════════════════════════════════════════
        $leadsNow    = YeuCauLienHe::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count();
        $leadsPrev   = YeuCauLienHe::whereNull('deleted_at')->whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $leadsDelta  = $leadsPrev > 0 ? round(($leadsNow - $leadsPrev) / $leadsPrev * 100, 1) : ($leadsNow > 0 ? 100 : 0);
        $leadsChuaXuLy = YeuCauLienHe::whereNull('deleted_at')->where('trang_thai', 'moi')->count();

        // ════════════════════════════════════════════════════
        // 9-12. BDS / CHỦ NHÀ / KÝ GỬI / KHÁCH HÀNG
        // ════════════════════════════════════════════════════
        $bdsNow  = BatDongSan::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count();
        $bdsPrev = BatDongSan::whereNull('deleted_at')->whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $bdsTong    = BatDongSan::whereNull('deleted_at')->count();
        $bdsConHang = BatDongSan::whereNull('deleted_at')->where('trang_thai', 'con_hang')->count();
        $bdsDaBan   = BatDongSan::whereNull('deleted_at')->whereIn('trang_thai', ['da_ban', 'da_thue'])->count();

        $chuNhaNow  = ChuNha::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count();
        $chuNhaTong = ChuNha::whereNull('deleted_at')->count();

        $kyGuiNow     = KyGui::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count();
        $kyGuiChoDuyet = KyGui::whereNull('deleted_at')->where('trang_thai', 'cho_duyet')->count();

        $khachHangNow  = KhachHang::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count();
        $khachHangTong = KhachHang::whereNull('deleted_at')->count();

        $nhanVien = Auth::guard('nhanvien')->user();

        return view('admin.thong-ke.index', compact(
            'nhanVien', 'loai_loc', 'label', 'startDate', 'endDate',
            'visitorNow', 'visitorPrev', 'visitorDelta',
            'visitorHom', 'visitorTuan', 'visitorThang', 'visitorNam',
            'chart30Ngay', 'trangNoiBat',
            'khuVucNoiBat', 'duAnNoiBat', 'bdsNoiBat', 'tuKhoaTop',
            'lichHenNow', 'lichHenPrev', 'lichHenDelta', 'lichHenHoanThanh', 'lichHenChoXuLy',
            'chartLichHen6Thang',
            'leadsNow', 'leadsPrev', 'leadsDelta', 'leadsChuaXuLy',
            'bdsNow', 'bdsPrev', 'bdsTong', 'bdsConHang', 'bdsDaBan',
            'chuNhaNow', 'chuNhaTong',
            'kyGuiNow', 'kyGuiChoDuyet',
            'khachHangNow', 'khachHangTong',
            'tu_ngay_input', 'den_ngay_input'
        ));
    }
}
