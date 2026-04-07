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
        // Leads cần gọi ngay (Mới hoặc đang chờ xử lý)
        $leadsCuaToi = YeuCauLienHe::where('trang_thai', 'cho_xu_ly')
            ->latest()
            ->limit(10)
            ->get();

        // Lịch hẹn hôm nay của riêng Sale
        $lichHenHomNay = LichHen::with(['khachHang', 'batDongSan'])
            ->where('nhan_vien_sale_id', $nhanVien->id)
            ->whereDate('thoi_gian_hen', Carbon::today())
            ->whereNotIn('trang_thai', ['huy', 'tu_choi', 'hoan_thanh'])
            ->orderBy('thoi_gian_hen')
            ->get();

        // KPI Cá nhân (Tổng khách hàng đang chăm sóc)
        $tongKhachCuaToi = KhachHang::count(); // Tạm tính tổng, sau này có `nhan_vien_phu_trach_id` thì where theo ID
        $lichHenThang = LichHen::where('nhan_vien_sale_id', $nhanVien->id)->whereMonth('thoi_gian_hen', Carbon::now()->month)->count();

        return view('admin.dashboard.sale', compact('nhanVien', 'leadsCuaToi', 'lichHenHomNay', 'tongKhachCuaToi', 'lichHenThang'));
    }

    // ==========================================
    // 2. DASHBOARD NGUỒN HÀNG (Giao diện Quản lý Kho)
    // ==========================================
    private function dashboardNguonHang($nhanVien)
    {
        // Thống kê nhanh kho hàng
        $tongQuan = [
            'tong_bds' => BatDongSan::whereNull('deleted_at')->count(),
            'bds_con_hang' => BatDongSan::where('trang_thai', 'con_hang')->whereNull('deleted_at')->count(),
            'ky_gui_cho' => KyGui::where('trang_thai', 'cho_duyet')->whereNull('deleted_at')->count(),
            'tong_chu_nha' => \App\Models\ChuNha::whereNull('deleted_at')->count(),
        ];

        // Công việc cần duyệt
        $kyGuiChoDuyet = KyGui::with('khachHang')
            ->where('trang_thai', 'cho_duyet')
            ->latest()
            ->limit(8)
            ->get();

        // BĐS mới lên kệ
        $bdsMoiNhat = BatDongSan::with('duAn')->whereNull('deleted_at')->latest()->limit(6)->get();

        return view('admin.dashboard.nguon_hang', compact('nhanVien', 'tongQuan', 'kyGuiChoDuyet', 'bdsMoiNhat'));
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
