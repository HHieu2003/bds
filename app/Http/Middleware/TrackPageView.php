<?php

namespace App\Http\Middleware;

use App\Models\LuotTruyCap;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    // Chỉ track các URL frontend, bỏ qua admin/api/assets
    private static $skipPatterns = [
        '/nhan-vien/',
        '/tai-khoan/',
        '/api/',
        '/storage/',
        '/admin',
        'track-time',
        'long-poll',
        '.css',
        '.js',
        '.png',
        '.jpg',
        '.ico',
        '.svg',
        '.webp',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Chỉ track GET request, bỏ qua admin/bot/asset
        if (
            $request->isMethod('GET') &&
            !$request->ajax() &&
            !$this->shouldSkip($request)
        ) {
            try {
                $trang = $this->detectPage($request);
                $duAnId    = $request->route('duAn')  ? (int) $request->route('duAn')  : null;
                $bdsId     = null;

                // Thêm log vào DB (async-friendly: fire-and-forget nếu sau này dùng queue)
                LuotTruyCap::create([
                    'session_id'     => $request->session()->getId(),
                    'ip_address'     => $request->ip(),
                    'url'            => substr($request->fullUrl(), 0, 499),
                    'trang'          => $trang,
                    'du_an_id'       => $duAnId,
                    'bat_dong_san_id' => $bdsId,
                    'user_agent'     => substr($request->userAgent() ?? '', 0, 499),
                    'created_at'     => now(),
                ]);
            } catch (\Throwable $e) {
                // Không để lỗi tracking làm crash trang
            }
        }

        return $response;
    }

    private function shouldSkip(Request $request): bool
    {
        $url = $request->getPathInfo();
        foreach (self::$skipPatterns as $pattern) {
            if (str_contains($url, $pattern)) return true;
        }
        // Bỏ qua bot/crawler
        $ua = strtolower($request->userAgent() ?? '');
        foreach (['bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit'] as $bot) {
            if (str_contains($ua, $bot)) return true;
        }
        return false;
    }

    private function detectPage(Request $request): string
    {
        $path = $request->getPathInfo();
        if ($path === '/')                    return 'home';
        if (str_contains($path, '/bat-dong-san/') && strlen($path) > 14) return 'bds_detail';
        if (str_contains($path, '/bat-dong-san')) return 'bds_list';
        if (str_contains($path, '/du-an/') && strlen($path) > 7)   return 'du_an_detail';
        if (str_contains($path, '/du-an'))    return 'du_an_list';
        if (str_contains($path, '/tim-kiem')) return 'tim_kiem';
        if (str_contains($path, '/lien-he'))  return 'lien_he';
        if (str_contains($path, '/tin-tuc'))  return 'tin_tuc';
        if (str_contains($path, '/ky-gui'))   return 'ky_gui';
        return 'khac';
    }
}
