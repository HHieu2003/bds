<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\KhachHang;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\LichHen;
use App\Models\KyGui;
use App\Models\PhienChat;
use App\Models\YeuCauLienHe;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\NhanVien $nhanVien */
        $nhanVien = Auth::guard('nhanvien')->user();

        // Điều phối (Dispatch) tới các hàm xử lý riêng biệt dựa trên Role
        if ($nhanVien->isSale()) {
            return $this->dashboardSale($nhanVien);
        }

        if ($nhanVien->isNguonHang()) {
            return $this->dashboardNguonHang($nhanVien);
        }

        // Mặc định là Admin
        return $this->dashboardAdmin($nhanVien);
    }

    // ==========================================
    // 1. DASHBOARD SALE (Giao diện To-Do List)
    // ==========================================
    private function dashboardSale($nhanVien)
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $leadBase = YeuCauLienHe::with(['batDongSan'])
            ->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_phu_trach_id', $nhanVien->id)
                    ->orWhereNull('nhan_vien_phu_trach_id');
            });

        $leadsCuaToi = (clone $leadBase)
            ->whereIn('trang_thai', ['moi', 'dang_tu_van', 'da_lien_he'])
            ->orderByRaw("CASE WHEN trang_thai = 'moi' THEN 1 WHEN trang_thai = 'dang_tu_van' THEN 2 ELSE 3 END")
            ->latest()
            ->limit(10)
            ->get();

        $leadsQuaHan = (clone $leadBase)
            ->where('trang_thai', 'moi')
            ->where('created_at', '<=', $now->copy()->subHours(2))
            ->count();

        $leadMoiHomNay = (clone $leadBase)
            ->whereDate('created_at', $today)
            ->where('trang_thai', 'moi')
            ->count();

        $leadDaChotThang = (clone $leadBase)
            ->where('trang_thai', 'da_chot')
            ->whereBetween('updated_at', [$monthStart, $monthEnd])
            ->count();

        $lichHenBase = LichHen::with(['khachHang', 'batDongSan'])
            ->where('nhan_vien_sale_id', $nhanVien->id);

        $lichHenHomNay = (clone $lichHenBase)
            ->whereDate('thoi_gian_hen', $today)
            ->whereNotIn('trang_thai', ['huy', 'tu_choi', 'hoan_thanh'])
            ->orderBy('thoi_gian_hen')
            ->get();

        $lichHenSapToi = (clone $lichHenBase)
            ->whereBetween('thoi_gian_hen', [$now, $now->copy()->addDays(7)])
            ->whereNotIn('trang_thai', ['huy', 'tu_choi', 'hoan_thanh'])
            ->orderBy('thoi_gian_hen')
            ->limit(12)
            ->get();

        $lichHenQuaHan = (clone $lichHenBase)
            ->where('thoi_gian_hen', '<', $now)
            ->whereNotIn('trang_thai', ['huy', 'tu_choi', 'hoan_thanh'])
            ->orderByDesc('thoi_gian_hen')
            ->limit(8)
            ->get();

        $lichHenCanXuLy = (clone $lichHenBase)
            ->whereIn('trang_thai', ['moi_dat', 'cho_tiep_nhan', 'cho_xac_nhan', 'bao_lai_gio', 'sale_doi_gio'])
            ->count();

        $lichHen2hToi = (clone $lichHenBase)
            ->whereBetween('thoi_gian_hen', [$now, $now->copy()->addHours(2)])
            ->whereIn('trang_thai', ['da_xac_nhan', 'cho_xac_nhan'])
            ->count();

        $lichHenThang = (clone $lichHenBase)
            ->whereBetween('thoi_gian_hen', [$monthStart, $monthEnd])
            ->count();

        $lichHenHoanThanhThang = (clone $lichHenBase)
            ->where('trang_thai', 'hoan_thanh')
            ->whereBetween('thoi_gian_hen', [$monthStart, $monthEnd])
            ->count();

        $lichHenHuyThang = (clone $lichHenBase)
            ->whereIn('trang_thai', ['huy', 'tu_choi'])
            ->whereBetween('thoi_gian_hen', [$monthStart, $monthEnd])
            ->count();

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
    // 2. DASHBOARD NGUỒN HÀNG (Giao diện Quản lý Kho)
    // ==========================================
    private function dashboardNguonHang($nhanVien)
    {
        $today = Carbon::today();
        $now = Carbon::now();

        $bdsBase = BatDongSan::with(['duAn', 'chuNha'])
            ->where('nhan_vien_phu_trach_id', $nhanVien->id)
            ->whereNull('deleted_at');

        $lichHenBase = LichHen::with(['khachHang', 'batDongSan', 'nhanVienSale', 'nhanVienNguonHang'])
            ->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_nguon_hang_id', $nhanVien->id)
                    ->orWhereHas('batDongSan', function ($bq) use ($nhanVien) {
                        $bq->where('nhan_vien_phu_trach_id', $nhanVien->id);
                    });
            });

        $kyGuiBase = KyGui::with('khachHang')
            ->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_phu_trach_id', $nhanVien->id)
                    ->orWhereNull('nhan_vien_phu_trach_id');
            })
            ->whereNull('deleted_at');

        $tongQuan = [
            'tong_bds' => (clone $bdsBase)->count(),
            'bds_con_hang' => (clone $bdsBase)->where('trang_thai', 'con_hang')->count(),
            'bds_da_ban_thue' => (clone $bdsBase)->whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
            'ky_gui_cho' => (clone $kyGuiBase)->where('trang_thai', 'cho_duyet')->count(),
            'tong_chu_nha' => \App\Models\ChuNha::where('nhan_vien_phu_trach_id', $nhanVien->id)->whereNull('deleted_at')->count(),
            'lich_can_xu_ly' => (clone $lichHenBase)->whereIn('trang_thai', ['cho_xac_nhan', 'sale_doi_gio', 'bao_lai_gio'])->count(),
        ];

        $lichCanXuLyNgay = (clone $lichHenBase)
            ->whereIn('trang_thai', ['cho_xac_nhan', 'sale_doi_gio', 'bao_lai_gio'])
            ->where('thoi_gian_hen', '>=', $now->copy()->subHours(6))
            ->orderByRaw("CASE WHEN trang_thai = 'sale_doi_gio' THEN 1 WHEN trang_thai = 'cho_xac_nhan' THEN 2 ELSE 3 END")
            ->orderBy('thoi_gian_hen', 'asc')
            ->limit(10)
            ->get();

        $lichHomNayDaXacNhan = (clone $lichHenBase)
            ->whereDate('thoi_gian_hen', $today)
            ->where('trang_thai', 'da_xac_nhan')
            ->orderBy('thoi_gian_hen')
            ->get();

        $lichQuaHan = (clone $lichHenBase)
            ->where('thoi_gian_hen', '<', $now)
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])
            ->orderByDesc('thoi_gian_hen')
            ->limit(8)
            ->get();

        $kyGuiChoDuyet = (clone $kyGuiBase)
            ->where('trang_thai', 'cho_duyet')
            ->latest()
            ->limit(8)
            ->get();

        $kyGuiDangThamDinh = (clone $kyGuiBase)
            ->where('trang_thai', 'dang_tham_dinh')
            ->latest()
            ->limit(8)
            ->get();

        $bdsMoiNhat = (clone $bdsBase)
            ->latest()
            ->limit(8)
            ->get();

        $chuNhaNoiBat = \App\Models\ChuNha::withCount('batDongSans')
            ->where('nhan_vien_phu_trach_id', $nhanVien->id)
            ->whereNull('deleted_at')
            ->orderByDesc('bat_dong_sans_count')
            ->limit(6)
            ->get();

        return view('admin.dashboard.nguon_hang', compact(
            'nhanVien',
            'tongQuan',
            'kyGuiChoDuyet',
            'kyGuiDangThamDinh',
            'bdsMoiNhat',
            'lichCanXuLyNgay',
            'lichHomNayDaXacNhan',
            'lichQuaHan',
            'chuNhaNoiBat'
        ));
    }

    // ==========================================
    // 3. DASHBOARD ADMIN (Giao diện Biểu đồ Tổng thể)
    // ==========================================
    private function dashboardAdmin($nhanVien)
    {
        $now = Carbon::now();

        $tongQuan = [
            'tong_bds'         => BatDongSan::whereNull('deleted_at')->count(),
            'bds_con_hang'     => BatDongSan::where('trang_thai', 'con_hang')->whereNull('deleted_at')->count(),
            'tong_khach_hang'  => KhachHang::whereNull('deleted_at')->count(),
            'tong_du_an'       => DuAn::whereNull('deleted_at')->count(),
            'yeu_cau_moi'      => YeuCauLienHe::where('trang_thai', 'cho_xu_ly')->whereNull('deleted_at')->count(),
            'lich_hen_hom_nay' => LichHen::whereDate('thoi_gian_hen', $now->toDateString())->whereNotIn('trang_thai', ['huy', 'tu_choi'])->whereNull('deleted_at')->count(),
            'ky_gui_cho_duyet' => KyGui::where('trang_thai', 'cho_duyet')->whereNull('deleted_at')->count(),
            'chat_dang_mo'     => PhienChat::where('trang_thai', 'dang_mo')->count(),
        ];

        $bdsMoiNhat = BatDongSan::with('duAn')->whereNull('deleted_at')->latest()->limit(6)->get();
        $lichHenHomNay = LichHen::with(['khachHang', 'batDongSan'])->whereDate('thoi_gian_hen', $now->toDateString())->whereNotIn('trang_thai', ['huy', 'tu_choi'])->orderBy('thoi_gian_hen')->limit(6)->get();
        $khachHangMoi = KhachHang::whereNull('deleted_at')->latest()->limit(5)->get();
        $kyGuiChoDuyet = KyGui::with('khachHang')->where('trang_thai', 'cho_duyet')->whereNull('deleted_at')->latest()->limit(5)->get();

        $bdsByLoai = [
            'ban_con_hang' => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'ban')->where('trang_thai', 'con_hang')->count(),
            'ban_da_ban'   => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'ban')->where('trang_thai', 'da_ban')->count(),
            'thue_con_hang' => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'thue')->where('trang_thai', 'con_hang')->count(),
            'thue_da_thue' => BatDongSan::whereNull('deleted_at')->where('nhu_cau', 'thue')->where('trang_thai', 'da_thue')->count(),
        ];

        $labels6Thang = [];
        $dataThem6Thang = [];
        $dataBan6Thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $labels6Thang[] = $month->format('m/Y');
            $dataThem6Thang[] = BatDongSan::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->whereNull('deleted_at')->count();
            $dataBan6Thang[] = BatDongSan::where('trang_thai', 'da_ban')->whereYear('updated_at', $month->year)->whereMonth('updated_at', $month->month)->whereNull('deleted_at')->count();
        }
        $chart6Thang = ['labels' => $labels6Thang, 'them' => $dataThem6Thang, 'ban' => $dataBan6Thang];

        $danhSachNhanVien = NhanVien::whereNull('deleted_at')->withCount(['batDongSans as so_bds', 'lichHenSale as so_lich_hen', 'kyGuis as so_ky_gui'])->orderBy('so_bds', 'desc')->limit(5)->get();

        return view('admin.dashboard.admin', compact('nhanVien', 'tongQuan', 'bdsMoiNhat', 'lichHenHomNay', 'khachHangMoi', 'kyGuiChoDuyet', 'bdsByLoai', 'chart6Thang', 'danhSachNhanVien'));
    }
}
