<?php

namespace App\Providers;

use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\BatDongSan;
use App\Observers\BatDongSanObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Cache TTL cho menu header (phút) */
    private const MENU_CACHE_MINUTES = 30;

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

        // ── Menu header: Cache 30 phút, chỉ share cho frontend views ──
        View::composer('frontend.*', function ($view) {
            try {
                $view->with('khuVucMenu', $this->getCachedKhuVucMenu());
                $view->with('tongSoDuAn', $this->getCachedTongSoDuAn());
                $view->with('duAnMenu', $this->getCachedDuAnMenu());
            } catch (\Exception $e) {
                $view->with('khuVucMenu', collect());
                $view->with('tongSoDuAn', 0);
                $view->with('duAnMenu', collect());
            }
        });
    }

    /**
     * Cache danh sách khu vực cho menu header.
     */
    private function getCachedKhuVucMenu()
    {
        return Cache::remember('menu:khu_vuc', now()->addMinutes(self::MENU_CACHE_MINUTES), function () {
            return KhuVuc::withCount([
                'duAn as so_du_an' => fn($q) => $q->where('hien_thi', true)
            ])
                ->having('so_du_an', '>', 0)
                ->where('hien_thi', true)
                ->orderBy('thu_tu_hien_thi')
                ->get();
        });
    }

    /**
     * Cache tổng số dự án.
     */
    private function getCachedTongSoDuAn()
    {
        return Cache::remember('menu:tong_du_an', now()->addMinutes(self::MENU_CACHE_MINUTES), function () {
            return DuAn::where('hien_thi', true)->count();
        });
    }

    /**
     * Cache danh sách dự án cho menu dropdown.
     */
    private function getCachedDuAnMenu()
    {
        return Cache::remember('menu:du_an', now()->addMinutes(self::MENU_CACHE_MINUTES), function () {
            return DuAn::withCount([
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
        });
    }

    /**
     * Xóa toàn bộ cache menu (gọi khi admin thay đổi DuAn/KhuVuc/BDS).
     */
    public static function clearMenuCache(): void
    {
        Cache::forget('menu:khu_vuc');
        Cache::forget('menu:tong_du_an');
        Cache::forget('menu:du_an');
    }
}
