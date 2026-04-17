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
        $ngay_input     = $request->input('ngay', $today->toDateString());
        $thang_input    = $request->input('thang', $now->format('Y-m'));
        $nam_input      = (int) $request->input('nam', $now->year);
        $tu_ngay_input  = $request->input('tu_ngay');
        $den_ngay_input = $request->input('den_ngay');

        switch ($loai_loc) {
            case 'ngay':
                try {
                    $date = Carbon::parse($ngay_input);
                } catch (\Throwable $e) {
                    $date = $today->copy();
                    $ngay_input = $date->toDateString();
                }

                $startDate = $date->copy()->startOfDay();
                $endDate   = $date->copy()->endOfDay();
                $label     = 'Ngày ' . $date->format('d/m/Y');
                break;
            case 'tuan':
                $startDate = $now->copy()->startOfWeek();
                $endDate   = $now->copy()->endOfWeek();
                $label     = 'Tuần này';
                break;
            case 'thang':
                if (!preg_match('/^\d{4}-\d{2}$/', (string) $thang_input)) {
                    $thang_input = $now->format('Y-m');
                }

                try {
                    $monthDate = Carbon::parse($thang_input . '-01');
                } catch (\Throwable $e) {
                    $monthDate = $now->copy()->startOfMonth();
                    $thang_input = $monthDate->format('Y-m');
                }

                $startDate = $monthDate->copy()->startOfMonth();
                $endDate   = $monthDate->copy()->endOfMonth();
                $label     = 'Tháng ' . $monthDate->format('m/Y');
                break;
            case 'nam':
                if ($nam_input < 2020 || $nam_input > 2099) {
                    $nam_input = (int) $now->year;
                }

                $startDate = Carbon::createFromDate($nam_input, 1, 1)->startOfYear();
                $endDate   = Carbon::createFromDate($nam_input, 1, 1)->endOfYear();
                $label     = 'Năm ' . $nam_input;
                break;
            case 'tuy_chon':
                try {
                    $startDate = $tu_ngay_input
                        ? Carbon::parse($tu_ngay_input)->startOfDay()
                        : $now->copy()->startOfMonth();
                } catch (\Throwable $e) {
                    $startDate = $now->copy()->startOfMonth();
                }

                try {
                    $endDate = $den_ngay_input
                        ? Carbon::parse($den_ngay_input)->endOfDay()
                        : $now->copy()->endOfMonth();
                } catch (\Throwable $e) {
                    $endDate = $now->copy()->endOfMonth();
                }

                if ($startDate->gt($endDate)) {
                    [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
                }

                $tu_ngay_input = $startDate->toDateString();
                $den_ngay_input = $endDate->toDateString();
                $label     = $startDate->format('d/m/Y') . ' → ' . $endDate->format('d/m/Y');
                break;
            default:
                $loai_loc  = 'thang';
                $startDate = $now->copy()->startOfMonth();
                $endDate   = $now->copy()->endOfMonth();
                $label     = 'Tháng ' . $now->format('m/Y');
                $thang_input = $now->format('Y-m');
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
                'home' => 'Trang chủ',
                'bds_list' => 'Danh sách BĐS',
                'bds_detail' => 'Chi tiết BĐS',
                'du_an_list' => 'Danh sách Dự án',
                'du_an_detail' => 'Chi tiết Dự án',
                'tim_kiem' => 'Tìm kiếm',
                'lien_he' => 'Liên hệ',
                'tin_tuc' => 'Tin tức',
                'ky_gui' => 'Ký gửi',
                default => 'Khác',
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

        if ($request->get('export') === 'csv') {
            return $this->exportThongKeCsv([
                'label' => $label,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'loai_loc' => $loai_loc,
                'visitorNow' => $visitorNow,
                'visitorPrev' => $visitorPrev,
                'visitorDelta' => $visitorDelta,
                'visitorHom' => $visitorHom,
                'visitorTuan' => $visitorTuan,
                'visitorThang' => $visitorThang,
                'visitorNam' => $visitorNam,
                'lichHenNow' => $lichHenNow,
                'lichHenPrev' => $lichHenPrev,
                'lichHenDelta' => $lichHenDelta,
                'lichHenHoanThanh' => $lichHenHoanThanh,
                'lichHenChoXuLy' => $lichHenChoXuLy,
                'leadsNow' => $leadsNow,
                'leadsPrev' => $leadsPrev,
                'leadsDelta' => $leadsDelta,
                'leadsChuaXuLy' => $leadsChuaXuLy,
                'bdsNow' => $bdsNow,
                'bdsPrev' => $bdsPrev,
                'bdsTong' => $bdsTong,
                'bdsConHang' => $bdsConHang,
                'bdsDaBan' => $bdsDaBan,
                'chuNhaNow' => $chuNhaNow,
                'chuNhaTong' => $chuNhaTong,
                'kyGuiNow' => $kyGuiNow,
                'kyGuiChoDuyet' => $kyGuiChoDuyet,
                'khachHangNow' => $khachHangNow,
                'khachHangTong' => $khachHangTong,
                'trangNoiBat' => $trangNoiBat,
                'khuVucNoiBat' => $khuVucNoiBat,
                'duAnNoiBat' => $duAnNoiBat,
                'bdsNoiBat' => $bdsNoiBat,
                'tuKhoaTop' => $tuKhoaTop,
            ], $request);
        }

        $nhanVien = Auth::guard('nhanvien')->user();

        return view('admin.thong-ke.index', compact(
            'nhanVien',
            'loai_loc',
            'label',
            'startDate',
            'endDate',
            'visitorNow',
            'visitorPrev',
            'visitorDelta',
            'visitorHom',
            'visitorTuan',
            'visitorThang',
            'visitorNam',
            'chart30Ngay',
            'trangNoiBat',
            'khuVucNoiBat',
            'duAnNoiBat',
            'bdsNoiBat',
            'tuKhoaTop',
            'lichHenNow',
            'lichHenPrev',
            'lichHenDelta',
            'lichHenHoanThanh',
            'lichHenChoXuLy',
            'chartLichHen6Thang',
            'leadsNow',
            'leadsPrev',
            'leadsDelta',
            'leadsChuaXuLy',
            'bdsNow',
            'bdsPrev',
            'bdsTong',
            'bdsConHang',
            'bdsDaBan',
            'chuNhaNow',
            'chuNhaTong',
            'kyGuiNow',
            'kyGuiChoDuyet',
            'khachHangNow',
            'khachHangTong',
            'ngay_input',
            'thang_input',
            'nam_input',
            'tu_ngay_input',
            'den_ngay_input'
        ));
    }

    private function exportThongKeCsv(array $data, Request $request)
    {
        $fileName = 'Bao_Cao_Thong_Ke_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Expires'             => '0',
        ];

        $boLoc = [];
        $boLoc[] = 'Loai loc: ' . ($data['loai_loc'] ?? 'thang');
        $boLoc[] = 'Khoang bao cao: ' . ($data['label'] ?? '');

        if ($request->filled('ngay')) {
            $boLoc[] = 'Ngay: ' . $request->input('ngay');
        }
        if ($request->filled('thang')) {
            $boLoc[] = 'Thang: ' . $request->input('thang');
        }
        if ($request->filled('nam')) {
            $boLoc[] = 'Nam: ' . $request->input('nam');
        }
        if ($request->filled('tu_ngay') || $request->filled('den_ngay')) {
            $boLoc[] = 'Tuy chon: ' . ($request->input('tu_ngay', '') . ' -> ' . $request->input('den_ngay', ''));
        }

        $callback = function () use ($data, $boLoc) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            $delimiter = ';';

            fputcsv($file, ['BAO CAO THONG KE TONG HOP'], $delimiter);
            fputcsv($file, ['Thoi gian xuat', now()->format('d/m/Y H:i:s')], $delimiter);
            fputcsv($file, ['Ky bao cao', (string) ($data['label'] ?? '')], $delimiter);
            fputcsv($file, ['Bo loc', implode(' | ', $boLoc)], $delimiter);
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['CHI SO TONG QUAN'], $delimiter);
            fputcsv($file, ['Luot truy cap trong ky', (int) ($data['visitorNow'] ?? 0)], $delimiter);
            fputcsv($file, ['Luot truy cap ky truoc', (int) ($data['visitorPrev'] ?? 0)], $delimiter);
            fputcsv($file, ['Ty le thay doi truy cap (%)', (float) ($data['visitorDelta'] ?? 0)], $delimiter);
            fputcsv($file, ['Lich hen trong ky', (int) ($data['lichHenNow'] ?? 0)], $delimiter);
            fputcsv($file, ['Lich hen ky truoc', (int) ($data['lichHenPrev'] ?? 0)], $delimiter);
            fputcsv($file, ['Ty le thay doi lich hen (%)', (float) ($data['lichHenDelta'] ?? 0)], $delimiter);
            fputcsv($file, ['Lich hen hoan thanh', (int) ($data['lichHenHoanThanh'] ?? 0)], $delimiter);
            fputcsv($file, ['Lich hen cho xu ly (hien tai)', (int) ($data['lichHenChoXuLy'] ?? 0)], $delimiter);
            fputcsv($file, ['Leads trong ky', (int) ($data['leadsNow'] ?? 0)], $delimiter);
            fputcsv($file, ['Leads ky truoc', (int) ($data['leadsPrev'] ?? 0)], $delimiter);
            fputcsv($file, ['Ty le thay doi leads (%)', (float) ($data['leadsDelta'] ?? 0)], $delimiter);
            fputcsv($file, ['Leads chua xu ly (hien tai)', (int) ($data['leadsChuaXuLy'] ?? 0)], $delimiter);
            fputcsv($file, ['BDS moi trong ky', (int) ($data['bdsNow'] ?? 0)], $delimiter);
            fputcsv($file, ['BDS ky truoc', (int) ($data['bdsPrev'] ?? 0)], $delimiter);
            fputcsv($file, ['Tong kho BDS', (int) ($data['bdsTong'] ?? 0)], $delimiter);
            fputcsv($file, ['BDS con hang', (int) ($data['bdsConHang'] ?? 0)], $delimiter);
            fputcsv($file, ['BDS da giao dich', (int) ($data['bdsDaBan'] ?? 0)], $delimiter);
            fputcsv($file, ['Chu nha moi trong ky', (int) ($data['chuNhaNow'] ?? 0)], $delimiter);
            fputcsv($file, ['Tong chu nha', (int) ($data['chuNhaTong'] ?? 0)], $delimiter);
            fputcsv($file, ['Ky gui moi trong ky', (int) ($data['kyGuiNow'] ?? 0)], $delimiter);
            fputcsv($file, ['Ky gui cho duyet', (int) ($data['kyGuiChoDuyet'] ?? 0)], $delimiter);
            fputcsv($file, ['Khach hang moi trong ky', (int) ($data['khachHangNow'] ?? 0)], $delimiter);
            fputcsv($file, ['Tong khach hang', (int) ($data['khachHangTong'] ?? 0)], $delimiter);
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TOP TRANG DUOC XEM'], $delimiter);
            fputcsv($file, ['STT', 'Trang', 'Luot'], $delimiter);
            foreach (collect($data['trangNoiBat'] ?? [])->values() as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    (string) ($item['trang'] ?? ''),
                    (int) ($item['luot'] ?? 0),
                ], $delimiter);
            }
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TOP KHU VUC'], $delimiter);
            fputcsv($file, ['STT', 'Ten khu vuc', 'Luot xem'], $delimiter);
            foreach (collect($data['khuVucNoiBat'] ?? [])->values() as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    (string) ($item->ten_khu_vuc ?? ''),
                    (int) ($item->luot_xem ?? 0),
                ], $delimiter);
            }
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TOP DU AN'], $delimiter);
            fputcsv($file, ['STT', 'Ten du an', 'Luot xem'], $delimiter);
            foreach (collect($data['duAnNoiBat'] ?? [])->values() as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    (string) ($item->ten_du_an ?? ''),
                    (int) ($item->luot_xem ?? 0),
                ], $delimiter);
            }
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TOP BAT DONG SAN'], $delimiter);
            fputcsv($file, ['STT', 'ID BDS', 'Tieu de', 'Luot xem'], $delimiter);
            foreach (collect($data['bdsNoiBat'] ?? [])->values() as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    (int) ($item->id ?? 0),
                    (string) ($item->tieu_de ?? ''),
                    (int) ($item->luot_xem ?? 0),
                ], $delimiter);
            }
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TOP TU KHOA TIM KIEM'], $delimiter);
            fputcsv($file, ['STT', 'Tu khoa', 'So lan'], $delimiter);
            foreach (collect($data['tuKhoaTop'] ?? [])->values() as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    (string) ($item->tu_khoa ?? ''),
                    (int) ($item->so_lan ?? 0),
                ], $delimiter);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
