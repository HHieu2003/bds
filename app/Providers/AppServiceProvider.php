<?php

namespace App\Providers;

use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\BatDongSan;
use App\Observers\BatDongSanObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ĐỐI TÁC LẬP TRÌNH: Đăng ký Observer theo dõi thay đổi giá Bất động sản
        BatDongSan::observe(BatDongSanObserver::class);

        try {
            // ── Khu vực có dự án → dùng cho dropdown header ──
            $khuVucMenu = KhuVuc::withCount([
                'duAn as so_du_an' => fn($q) =>
                $q->where('hien_thi', true)
            ])
                ->having('so_du_an', '>', 0)
                ->where('hien_thi', true)
                ->orderBy('thu_tu_hien_thi')
                ->get();

            $tongSoDuAn = DuAn::where('hien_thi', true)->count();
            // Thêm vào bên trong try{} cùng chỗ với $khuVucMenu

            $duAnMenu = DuAn::withCount([
                'batDongSans as so_can_ban' => fn($q) =>
                $q->where('nhu_cau', 'ban')
                    ->where('hien_thi', true)
                    ->where('trang_thai', 'con_hang'),

                'batDongSans as so_can_thue' => fn($q) =>
                $q->where('nhu_cau', 'thue')
                    ->where('hien_thi', true)
                    ->where('trang_thai', 'con_hang'),
            ])
                ->where('hien_thi', true)
                ->orderBy('thu_tu_hien_thi')
                ->limit(5)
                ->get();

            View::share('duAnMenu', $duAnMenu);

            // ── Share xuống TẤT CẢ views (frontend + admin) ──
            View::share('khuVucMenu',  $khuVucMenu);
            View::share('tongSoDuAn', $tongSoDuAn);
        } catch (\Exception $e) {
            // Tránh crash khi chưa migrate
            View::share('khuVucMenu',  collect());
            View::share('tongSoDuAn', 0);
        }
    }
}
