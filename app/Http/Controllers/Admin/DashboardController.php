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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        $now      = Carbon::now();

        // ── Thống kê tổng quan ──
        $tongQuan = [
            'tong_bds'         => BatDongSan::whereNull('deleted_at')->count(),
            'bds_con_hang'     => BatDongSan::where('trang_thai', 'con_hang')
                ->whereNull('deleted_at')->count(),
            'bds_da_ban'       => BatDongSan::where('trang_thai', 'da_ban')
                ->whereNull('deleted_at')->count(),
            'tong_khach_hang'  => KhachHang::whereNull('deleted_at')->count(),
            'tong_du_an'       => DuAn::whereNull('deleted_at')->count(),

            // ✅ Fix: lịch hẹn hôm nay — trạng thái đúng
            'lich_hen_hom_nay' => LichHen::whereDate('thoi_gian_hen', $now->toDateString())
                ->whereNotIn('trang_thai', ['huy', 'tu_choi'])
                ->whereNull('deleted_at')->count(),

            // ✅ Fix: ký gửi chờ duyệt
            'ky_gui_cho_duyet' => KyGui::where('trang_thai', 'cho_duyet')
                ->whereNull('deleted_at')->count(),

            // ✅ Fix: phiên chat đang mở (không phải 'dang_cho')
            'chat_dang_mo'     => PhienChat::where('trang_thai', 'dang_mo')->count(),
        ];

        // ── BĐS mới nhất (8 cái) ──
        $bdsMoiNhat = BatDongSan::with('duAn')
            ->whereNull('deleted_at')
            ->latest()
            ->limit(8)
            ->get();

        // ── Lịch hẹn hôm nay (chưa hủy/từ chối) ──
        $lichHenHomNay = LichHen::with(['khachHang', 'batDongSan'])
            ->whereDate('thoi_gian_hen', $now->toDateString())
            ->whereNotIn('trang_thai', ['huy', 'tu_choi'])
            ->whereNull('deleted_at')
            ->orderBy('thoi_gian_hen')
            ->limit(6)
            ->get();

        // ── Khách hàng mới nhất ──
        $khachHangMoi = KhachHang::whereNull('deleted_at')
            ->latest()
            ->limit(6)
            ->get();

        // ── Ký gửi chờ duyệt ──
        $kyGuiChoDuyet = KyGui::with('khachHang')
            ->where('trang_thai', 'cho_duyet')
            ->whereNull('deleted_at')
            ->latest()
            ->limit(5)
            ->get();

        // ── Thống kê BĐS theo loại ──
        $bdsByLoai = BatDongSan::whereNull('deleted_at')
            ->select('nhu_cau', 'trang_thai', DB::raw('count(*) as tong'))
            ->groupBy('nhu_cau', 'trang_thai')
            ->get();

        // ── Thống kê BĐS 6 tháng gần đây ──
        $bdsSixMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $bdsSixMonths[] = [
                'thang' => $month->format('m/Y'),
                'them'  => BatDongSan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->whereNull('deleted_at')
                    ->count(),
                'ban'   => BatDongSan::where('trang_thai', 'da_ban')
                    ->whereYear('updated_at', $month->year)
                    ->whereMonth('updated_at', $month->month)
                    ->whereNull('deleted_at')
                    ->count(),
            ];
        }

        // ── Nhân viên (chỉ Admin) ──
        // ✅ Fix: bỏ withCount('khachHangs') vì khach_hang không có cột nhan_vien_phu_trach_id
        // Thay bằng đếm qua bảng liên quan thực tế: lich_hen
        $danhSachNhanVien = null;
        if ($nhanVien->isAdmin()) {
            $danhSachNhanVien = NhanVien::whereNull('deleted_at')
                ->withCount([
                    // BĐS phụ trách (nhan_vien_phu_trach_id tồn tại trong bat_dong_san)
                    'batDongSans as so_bds',

                    // Lịch hẹn với tư cách sale
                    'lichHenSale as so_lich_hen',

                    // Ký gửi phụ trách
                    'kyGuis as so_ky_gui',
                ])
                ->orderBy('so_bds', 'desc')
                ->limit(5)
                ->get();
        }

        return view('admin.dashboard.index', compact(
            'nhanVien',
            'tongQuan',
            'bdsMoiNhat',
            'lichHenHomNay',
            'khachHangMoi',
            'kyGuiChoDuyet',
            'bdsByLoai',
            'bdsSixMonths',
            'danhSachNhanVien',
        ));
    }
}
