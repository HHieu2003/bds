<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\KhachHang;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\LichHen;
use App\Models\KyGui;
use App\Models\YeuCauLienHe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\NhanVien $nhanVien */
        $nhanVien = Auth::guard('nhanvien')->user();

        // Điều phối tới các Dashboard riêng biệt dựa trên Role
        if ($nhanVien->isSale()) {
            return $this->dashboardSale($nhanVien);
        }

        if ($nhanVien->isNguonHang()) {
            return $this->dashboardNguonHang($nhanVien);
        }

        // Mặc định là Admin (Đã thêm Request để xử lý bộ lọc)
        return $this->dashboardAdmin($nhanVien, $request);
    }

    // ==========================================
    // 1. DASHBOARD SALE (Giữ nguyên logic chuẩn)
    // ==========================================
    private function dashboardSale($nhanVien)
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $leadBase = YeuCauLienHe::with(['batDongSan'])->where(function ($q) use ($nhanVien) {
            $q->where('nhan_vien_phu_trach_id', $nhanVien->id)->orWhereNull('nhan_vien_phu_trach_id');
        });

        $leadsCuaToi = (clone $leadBase)->whereIn('trang_thai', ['moi', 'dang_tu_van', 'da_lien_he'])
            ->orderByRaw("CASE WHEN trang_thai = 'moi' THEN 1 WHEN trang_thai = 'dang_tu_van' THEN 2 ELSE 3 END")
            ->latest()->limit(10)->get();

        $leadsQuaHan = (clone $leadBase)->where('trang_thai', 'moi')->where('created_at', '<=', $now->copy()->subHours(2))->count();
        $leadMoiHomNay = (clone $leadBase)->whereDate('created_at', $today)->where('trang_thai', 'moi')->count();
        $leadDaChotThang = (clone $leadBase)->where('trang_thai', 'da_chot')->whereBetween('updated_at', [$monthStart, $monthEnd])->count();

        $lichHenBase = LichHen::with(['khachHang', 'batDongSan'])->where('nhan_vien_sale_id', $nhanVien->id);

        $lichHenHomNay = (clone $lichHenBase)->whereDate('thoi_gian_hen', $today)
            ->whereNotIn('trang_thai', ['huy', 'tu_choi'])->orderBy('thoi_gian_hen')->get();

        $lichHenSapToi = (clone $lichHenBase)->whereBetween('thoi_gian_hen', [$now, $now->copy()->addDays(7)])
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])->orderBy('thoi_gian_hen')->limit(12)->get();

        $lichHenQuaHan = (clone $lichHenBase)->where('thoi_gian_hen', '<', $now)
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])->orderByDesc('thoi_gian_hen')->limit(8)->get();

        $lichHenCanXuLy = (clone $lichHenBase)->whereIn('trang_thai', ['sale_da_nhan', 'cho_sale_xac_nhan_doi_gio'])->count();

        $lichHen2hToi = (clone $lichHenBase)->whereBetween('thoi_gian_hen', [$now, $now->copy()->addHours(2)])
            ->where('trang_thai', 'da_xac_nhan')->count();

        $lichHenThang = (clone $lichHenBase)->whereBetween('thoi_gian_hen', [$monthStart, $monthEnd])->count();
        $lichHenHoanThanhThang = (clone $lichHenBase)->where('trang_thai', 'hoan_thanh')->whereBetween('thoi_gian_hen', [$monthStart, $monthEnd])->count();
        $lichHenHuyThang = (clone $lichHenBase)->whereIn('trang_thai', ['huy', 'tu_choi'])->whereBetween('thoi_gian_hen', [$monthStart, $monthEnd])->count();

        $tongClosedThang = $lichHenHoanThanhThang + $lichHenHuyThang;
        $tiLeChotLich = $tongClosedThang > 0 ? round(($lichHenHoanThanhThang / $tongClosedThang) * 100, 1) : 0;

        $tongKhachCuaToi = KhachHang::where('nhan_vien_phu_trach_id', $nhanVien->id)->count();

        return view('admin.dashboard.sale', compact(
            'nhanVien',
            'leadsCuaToi',
            'lichHenHomNay',
            'tongKhachCuaToi',
            'lichHenThang',
            'leadMoiHomNay',
            'leadDaChotThang',
            'leadsQuaHan',
            'lichHenCanXuLy',
            'lichHen2hToi',
            'lichHenSapToi',
            'lichHenQuaHan',
            'lichHenHoanThanhThang',
            'lichHenHuyThang',
            'tiLeChotLich'
        ));
    }

    // ==========================================
    // 2. DASHBOARD NGUỒN HÀNG (Giữ nguyên logic chuẩn)
    // ==========================================
    private function dashboardNguonHang($nhanVien)
    {
        $today = Carbon::today();
        $now = Carbon::now();

        $bdsBase = BatDongSan::with(['duAn', 'chuNha'])->where('nhan_vien_phu_trach_id', $nhanVien->id)->whereNull('deleted_at');

        $lichHenBase = LichHen::with(['khachHang', 'batDongSan', 'nhanVienSale', 'nhanVienNguonHang'])
            ->where('nhan_vien_nguon_hang_id', $nhanVien->id);

        $kyGuiBase = KyGui::with('khachHang')->where(function ($q) use ($nhanVien) {
            $q->where('nhan_vien_phu_trach_id', $nhanVien->id)->orWhereNull('nhan_vien_phu_trach_id');
        })->whereNull('deleted_at');

        $tongQuan = [
            'tong_bds' => (clone $bdsBase)->count(),
            'bds_con_hang' => (clone $bdsBase)->where('trang_thai', 'con_hang')->count(),
            'bds_da_ban_thue' => (clone $bdsBase)->whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
            'ky_gui_cho' => (clone $kyGuiBase)->where('trang_thai', 'cho_duyet')->count(),
            'tong_chu_nha' => \App\Models\ChuNha::where('nhan_vien_phu_trach_id', $nhanVien->id)->whereNull('deleted_at')->count(),
            'lich_can_xu_ly' => (clone $lichHenBase)->where('trang_thai', 'cho_xac_nhan')->count(),
        ];

        $lichCanXuLyNgay = (clone $lichHenBase)
            ->whereIn('trang_thai', ['cho_xac_nhan', 'cho_sale_xac_nhan_doi_gio'])
            ->orderBy('thoi_gian_hen', 'asc')->limit(10)->get();

        $lichHomNayDaXacNhan = (clone $lichHenBase)->whereDate('thoi_gian_hen', $today)->where('trang_thai', 'da_xac_nhan')->orderBy('thoi_gian_hen')->get();
        $lichQuaHan = (clone $lichHenBase)->where('thoi_gian_hen', '<', $now)->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])->orderByDesc('thoi_gian_hen')->limit(8)->get();

        $kyGuiChoDuyet = (clone $kyGuiBase)->where('trang_thai', 'cho_duyet')->latest()->limit(8)->get();
        $bdsMoiNhat = (clone $bdsBase)->latest()->limit(8)->get();

        $chuNhaNoiBat = \App\Models\ChuNha::withCount(['batDongSans' => function ($query) {
            $query->where('trang_thai', 'con_hang')->where('hien_thi', 1);
        }])
        ->where('nhan_vien_phu_trach_id', $nhanVien->id)
        ->whereNull('deleted_at')
        ->having('bat_dong_sans_count', '>', 0)
        ->orderByDesc('bat_dong_sans_count')
        ->limit(6)
        ->get();

        return view('admin.dashboard.nguon_hang', compact(
            'nhanVien',
            'tongQuan',
            'kyGuiChoDuyet',
            'bdsMoiNhat',
            'lichCanXuLyNgay',
            'lichHomNayDaXacNhan',
            'lichQuaHan',
            'chuNhaNoiBat'
        ));
    }

    // ==========================================
    // 3. DASHBOARD ADMIN (Cập nhật Bộ lọc & Export CSV)
    // ==========================================
    private function dashboardAdmin($nhanVien, Request $request)
    {
        $now = Carbon::now();

        // 1. Khởi tạo giá trị lọc thời gian
        $loai_loc = $request->input('loai_loc', 'thang');
        if (!in_array($loai_loc, ['ngay', 'thang', 'nam'], true)) {
            $loai_loc = 'thang';
        }

        $startDate = null;
        $endDate = null;
        $labelKyBaoCao = "";

        if ($loai_loc === 'ngay') {
            $ngayInput = $request->input('ngay', $now->toDateString());
            try {
                $date = Carbon::parse($ngayInput);
            } catch (\Throwable $e) {
                $date = $now->copy();
            }
            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();
            $labelKyBaoCao = "Ngày " . $date->format('d/m/Y');
        } elseif ($loai_loc === 'nam') {
            $year = (int) $request->input('nam', $now->year);
            if ($year < 2020 || $year > 2099) {
                $year = (int) $now->year;
            }
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($year, 1, 1)->endOfYear();
            $labelKyBaoCao = "Năm " . $year;
        } else {
            // Mặc định lọc theo Tháng
            $monthInput = $request->input('thang', $now->format('Y-m')); // Format: YYYY-MM
            if (!preg_match('/^\d{4}-\d{2}$/', (string) $monthInput)) {
                $monthInput = $now->format('Y-m');
            }

            try {
                $date = Carbon::parse($monthInput . '-01');
            } catch (\Throwable $e) {
                $date = $now->copy()->startOfMonth();
            }

            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
            $labelKyBaoCao = "Tháng " . $date->format('m/Y');
        }

        // 2. Query dữ liệu KPI theo thời gian đã lọc (Lấy dữ liệu "Trong kỳ")
        $tongQuan = [
            'bds_moi'           => BatDongSan::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'bds_da_ban'        => BatDongSan::whereIn('trang_thai', ['da_ban', 'da_thue'])->whereNull('deleted_at')->whereBetween('updated_at', [$startDate, $endDate])->count(),
            'khach_hang_moi'    => KhachHang::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'du_an_moi'         => DuAn::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'yeu_cau_moi'       => YeuCauLienHe::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'lich_hen_trong_ky' => LichHen::whereNotIn('trang_thai', ['huy', 'tu_choi'])->whereNull('deleted_at')->whereBetween('thoi_gian_hen', [$startDate, $endDate])->count(),
            'lich_chot'         => LichHen::where('trang_thai', 'hoan_thanh')->whereNull('deleted_at')->whereBetween('hoan_thanh_at', [$startDate, $endDate])->count(),
            'ky_gui_moi'        => KyGui::whereNull('deleted_at')->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // 3. Xử lý Yêu cầu Xuất File (Export CSV)
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportDashboardCsv($tongQuan, $labelKyBaoCao, $startDate, $endDate, $loai_loc);
        }

        // 4. Các dữ liệu phụ cho Biểu đồ và Danh sách bên dưới
        $bdsMoiNhat = BatDongSan::with(['duAn', 'nhanVienPhuTrach'])->whereNull('deleted_at')->latest()->limit(6)->get();
        // Lịch hẹn hôm nay vẫn fix cứng là "Ngày hôm nay" để Admin theo dõi tiến độ ngày
        $lichHenHomNay = LichHen::with(['khachHang', 'batDongSan'])->whereDate('thoi_gian_hen', $now->toDateString())->whereNotIn('trang_thai', ['huy', 'tu_choi'])->orderBy('thoi_gian_hen')->limit(6)->get();
        $khachHangMoi = KhachHang::whereNull('deleted_at')->latest()->limit(5)->get();
        $kyGuiChoDuyet = KyGui::with('khachHang')->where('trang_thai', 'cho_duyet')->whereNull('deleted_at')->latest()->limit(5)->get();

        $bdsByLoai = [
            'ban_con_hang' => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'ban')->where('trang_thai', 'con_hang')->count(),
            'ban_da_ban'   => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'ban')->whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
            'thue_con_hang' => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'thue')->where('trang_thai', 'con_hang')->count(),
            'thue_da_thue' => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'thue')->whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
        ];

        // Biểu đồ 6 tháng — Tối ưu: 2 query GROUP BY thay vì 12 query riêng lẻ
        $baseChartDate = $endDate->copy();
        $chartStart = $baseChartDate->copy()->subMonths(5)->startOfMonth();

        // Query 1: BĐS thêm mới theo tháng
        $themRaw = BatDongSan::whereNull('deleted_at')
            ->where('created_at', '>=', $chartStart)
            ->where('created_at', '<=', $endDate)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as thang, COUNT(*) as so_luong")
            ->groupBy('thang')
            ->pluck('so_luong', 'thang');

        // Query 2: BĐS đã bán/thuê theo tháng
        $banRaw = BatDongSan::whereNull('deleted_at')
            ->whereIn('trang_thai', ['da_ban', 'da_thue'])
            ->where('updated_at', '>=', $chartStart)
            ->where('updated_at', '<=', $endDate)
            ->selectRaw("DATE_FORMAT(updated_at, '%Y-%m') as thang, COUNT(*) as so_luong")
            ->groupBy('thang')
            ->pluck('so_luong', 'thang');

        $labels6Thang = [];
        $dataThem6Thang = [];
        $dataBan6Thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $baseChartDate->copy()->subMonths($i);
            $key = $month->format('Y-m');
            $labels6Thang[] = $month->format('m/Y');
            $dataThem6Thang[] = $themRaw[$key] ?? 0;
            $dataBan6Thang[] = $banRaw[$key] ?? 0;
        }
        $chart6Thang = ['labels' => $labels6Thang, 'them' => $dataThem6Thang, 'ban' => $dataBan6Thang];

        $danhSachNhanVien = NhanVien::whereNull('deleted_at')
            ->withCount([
                'batDongSans as so_bds' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                },
                'lichHenSale as so_lich_hen' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                },
                'kyGuis as so_ky_gui' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->get()
            ->sortByDesc(function ($nv) {
                return $nv->so_bds + $nv->so_lich_hen + $nv->so_ky_gui;
            })
            ->take(5);

        return view('admin.dashboard.admin', compact(
            'nhanVien',
            'tongQuan',
            'loai_loc',
            'labelKyBaoCao',
            'bdsMoiNhat',
            'lichHenHomNay',
            'khachHangMoi',
            'kyGuiChoDuyet',
            'bdsByLoai',
            'chart6Thang',
            'danhSachNhanVien'
        ));
    }

    /**
     * Hàm hỗ trợ xuất file CSV
     */
    private function exportDashboardCsv(array $data, string $label, Carbon $startDate, Carbon $endDate, string $loaiLoc)
    {
        $labelSafe = preg_replace('/[^A-Za-z0-9_\-]/', '_', str_replace([' ', '/'], '_', $label));
        $fileName = 'Bao_Cao_Tong_Quan_' . trim((string) $labelSafe, '_') . '_' . now()->format('Ymd_His') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "no-store, no-cache, must-revalidate, max-age=0",
            "Expires"             => "0"
        );

        $columns = ['Chi so thong ke', 'So luong (trong ky)'];

        $tongLich = (int) ($data['lich_hen_trong_ky'] ?? 0);
        $lichChot = (int) ($data['lich_chot'] ?? 0);
        $tiLeChot = $tongLich > 0 ? round(($lichChot / $tongLich) * 100, 2) : 0;

        $loaiLocLabel = match ($loaiLoc) {
            'ngay' => 'Theo ngay',
            'nam' => 'Theo nam',
            default => 'Theo thang',
        };

        $callback = function () use ($data, $columns, $label, $startDate, $endDate, $loaiLocLabel, $tiLeChot) {
            $file = fopen('php://output', 'w');

            // Thêm BOM để Excel đọc được tiếng Việt (UTF-8)
            fputs($file, "\xEF\xBB\xBF");

            // Dùng dấu ; để tương thích tốt hơn với Excel theo locale VN
            $delimiter = ';';

            // Dòng Tiêu đề File
            fputcsv($file, ["BAO CAO THONG KE HE THONG - " . mb_strtoupper($label)], $delimiter);
            fputcsv($file, ['Thoi gian xuat', now()->format('d/m/Y H:i:s')], $delimiter);
            fputcsv($file, ['Loai loc', $loaiLocLabel], $delimiter);
            fputcsv($file, ['Tu ngay', $startDate->format('d/m/Y H:i:s')], $delimiter);
            fputcsv($file, ['Den ngay', $endDate->format('d/m/Y H:i:s')], $delimiter);
            fputcsv($file, [], $delimiter); // Dòng trống
            fputcsv($file, $columns, $delimiter);

            // Ghi dữ liệu
            fputcsv($file, ['Bat dong san them moi', (int) ($data['bds_moi'] ?? 0)], $delimiter);
            fputcsv($file, ['Bat dong san chot/ban', (int) ($data['bds_da_ban'] ?? 0)], $delimiter);
            fputcsv($file, ['Khach hang moi', (int) ($data['khach_hang_moi'] ?? 0)], $delimiter);
            fputcsv($file, ['Du an them moi', (int) ($data['du_an_moi'] ?? 0)], $delimiter);
            fputcsv($file, ['Yeu cau lien he (lead moi)', (int) ($data['yeu_cau_moi'] ?? 0)], $delimiter);
            fputcsv($file, ['Ky gui moi', (int) ($data['ky_gui_moi'] ?? 0)], $delimiter);
            fputcsv($file, ['Lich xem nha dien ra', (int) ($data['lich_hen_trong_ky'] ?? 0)], $delimiter);
            fputcsv($file, ['Lich xem nha thanh cong (chot deal)', (int) ($data['lich_chot'] ?? 0)], $delimiter);
            fputcsv($file, ['Ti le chot lich (%)', $tiLeChot], $delimiter);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
