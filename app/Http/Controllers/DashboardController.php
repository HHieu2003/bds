<?php

namespace App\Http\Controllers;

use App\Models\BatDongSan;
use App\Models\LienHe;
use App\Models\LichHen;
use App\Models\BaiViet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Thống kê số lượng tổng (Card)
        $stats = [
            'tong_bds' => BatDongSan::count(),
            'tong_lien_he' => LienHe::count(),
            'tong_lich_hen' => LichHen::count(),
            'tong_bai_viet' => BaiViet::count(),
        ];

        // 2. Thống kê Lịch hẹn theo trạng thái (để vẽ biểu đồ tròn)
        $lichHenStats = LichHen::select('trang_thai', DB::raw('count(*) as total'))
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai')
            ->toArray();

        // Chuẩn hóa dữ liệu cho biểu đồ (Mặc định là 0 nếu không có)
        $chartData = [
            $lichHenStats['moi_dat'] ?? 0,
            $lichHenStats['da_xac_nhan'] ?? 0,
            $lichHenStats['hoan_thanh'] ?? 0,
            $lichHenStats['huy'] ?? 0,
        ];

        // 3. Lấy 5 khách hàng liên hệ mới nhất
        $newContacts = LienHe::orderBy('created_at', 'desc')->take(5)->get();

        // 4. Top 5 Bất động sản xem nhiều nhất
        $topViewed = BatDongSan::orderBy('luot_xem', 'desc')->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'chartData', 'newContacts', 'topViewed'));
    }
}
