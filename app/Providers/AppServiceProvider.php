<?php

namespace App\Providers;

use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\BatDongSan;
use App\Observers\BatDongSanObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Chống spam cho các form công khai: key theo IP để tránh lách bằng sdt/email ảo.
        RateLimiter::for('anti-spam', function (Request $request) {
            $routeName = $request->route()?->getName() ?? 'unknown';
            $ip = (string) $request->ip();

            $response = function (Request $request, array $headers) {
                $message = 'Bạn thao tác quá nhanh. Vui lòng thử lại sau 2 phút.';

                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 429, $headers);
                }

                return back()->withInput()->with('error', $message);
            };

            return [
                // Giới hạn theo từng endpoint: cùng IP, đổi sdt/email vẫn bị tính.
                Limit::perMinutes(2, 4)
                    ->by('anti-spam|route|' . $routeName . '|' . $ip)
                    ->response($response),

                // Giới hạn tổng trên tất cả endpoint anti-spam để chặn spam luân phiên form.
                Limit::perMinutes(2, 12)
                    ->by('anti-spam|global|' . $ip)
                    ->response($response),
            ];
        });

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
