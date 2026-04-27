<?php

namespace App\Console\Commands;

use App\Models\BaiViet;
use App\Models\BatDongSan;
use App\Models\DuAn;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * ┌────────────────────────────────────────────────────────────────┐
 * │  GENERATE SITEMAP                                              │
 * │  Tự động quét tất cả URL public (Trang chủ, BĐS, Dự án,      │
 * │  Bài viết) và tạo file sitemap.xml chuẩn SEO lưu vào public/  │
 * │                                                                │
 * │  Cách dùng:                                                    │
 * │  php artisan sitemap:generate                                  │
 * │                                                                │
 * │  Lên lịch tự động (Laravel Scheduler):                         │
 * │  $schedule->command('sitemap:generate')->daily();              │
 * └────────────────────────────────────────────────────────────────┘
 */
class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Tạo file sitemap.xml tĩnh vào thư mục public/ cho SEO';

    public function handle(): int
    {
        $this->info('🗺️  Đang tạo sitemap.xml...');

        $urls = collect();

        // ═══ 1. TRANG TĨNH (Static Pages) ═══
        $urls->push($this->makeUrl(route('frontend.home'), now(), 'daily', '1.0'));
        $urls->push($this->makeUrl(route('frontend.bat-dong-san.index'), now(), 'daily', '0.9'));
        $urls->push($this->makeUrl(route('frontend.du-an.index'), now(), 'daily', '0.9'));
        $urls->push($this->makeUrl(route('frontend.tin-tuc.index'), now(), 'daily', '0.8'));
        $urls->push($this->makeUrl(route('frontend.gioi-thieu'), now(), 'monthly', '0.5'));
        $urls->push($this->makeUrl(route('frontend.lien-he.index'), now(), 'monthly', '0.5'));
        $urls->push($this->makeUrl(route('frontend.tuyen-dung'), now(), 'weekly', '0.6'));
        $urls->push($this->makeUrl(route('frontend.cong-cu-tai-chinh'), now(), 'monthly', '0.5'));

        // ═══ 2. BẤT ĐỘNG SẢN (Dynamic) ═══
        $batDongSans = BatDongSan::where('hien_thi', 1)
            ->where('trang_thai', 'con_hang')
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        foreach ($batDongSans as $bds) {
            $urls->push($this->makeUrl(
                route('frontend.bat-dong-san.show', $bds->slug),
                $bds->updated_at,
                'weekly',
                '0.8'
            ));
        }

        $this->info("   ✅ Bất động sản: {$batDongSans->count()} URLs");

        // ═══ 3. DỰ ÁN (Dynamic) ═══
        $duAns = DuAn::where('hien_thi', 1)
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        foreach ($duAns as $duAn) {
            $urls->push($this->makeUrl(
                route('frontend.du-an.show', $duAn->slug),
                $duAn->updated_at,
                'weekly',
                '0.8'
            ));
        }

        $this->info("   ✅ Dự án: {$duAns->count()} URLs");

        // ═══ 4. BÀI VIẾT / TIN TỨC (Dynamic) ═══
        $baiViets = BaiViet::where('hien_thi', 1)
            ->whereNotNull('slug')
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        foreach ($baiViets as $baiViet) {
            $urls->push($this->makeUrl(
                route('frontend.tin-tuc.show', $baiViet->slug),
                $baiViet->updated_at,
                'monthly',
                '0.6'
            ));
        }

        $this->info("   ✅ Bài viết: {$baiViets->count()} URLs");

        // ═══ 5. RENDER XML ═══
        $xml = $this->renderXml($urls);

        // Ghi file vào public/sitemap.xml
        $path = public_path('sitemap.xml');
        file_put_contents($path, $xml);

        $totalUrls = $urls->count();
        $this->info("🎉 Đã tạo sitemap.xml thành công! Tổng cộng: {$totalUrls} URLs");
        $this->info("   📁 File: {$path}");

        Log::info("Sitemap generated: {$totalUrls} URLs → public/sitemap.xml");

        return self::SUCCESS;
    }

    /**
     * Tạo một entry URL cho sitemap.
     */
    private function makeUrl(string $loc, $lastmod, string $changefreq, string $priority): array
    {
        return [
            'loc'        => $loc,
            'lastmod'    => $lastmod ? $lastmod->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString(),
            'changefreq' => $changefreq,
            'priority'   => $priority,
        ];
    }

    /**
     * Render toàn bộ XML từ collection URLs.
     */
    private function renderXml($urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . PHP_EOL;
        $xml .= '            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>' . PHP_EOL;

        return $xml;
    }
}
