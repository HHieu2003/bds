<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\YeuThich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DuAnController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo query lấy các dự án đang hiển thị
        $query = DuAn::with('khuVuc')->where('hien_thi', 1);

        // 2. Lọc theo KHU VỰC
        if ($request->filled('khu_vuc')) {
            $khu_vuc = $request->khu_vuc;

            // Ưu tiên resolve theo ID/slug để có thể lấy cả khu vực con cháu.
            $khuVucDaChon = is_numeric($khu_vuc)
                ? KhuVuc::find($khu_vuc)
                : KhuVuc::where('slug', $khu_vuc)->first();

            if ($khuVucDaChon) {
                $khuVucIds = [$khuVucDaChon->id];
                $currentParentIds = [$khuVucDaChon->id];

                // Lấy toàn bộ cây con theo từng tầng để lọc dự án bao trùm khu vực cha.
                while (! empty($currentParentIds)) {
                    $childIds = KhuVuc::whereIn('khu_vuc_cha_id', $currentParentIds)->pluck('id')->all();

                    if (empty($childIds)) {
                        break;
                    }

                    $khuVucIds = array_merge($khuVucIds, $childIds);
                    $currentParentIds = $childIds;
                }

                $query->whereIn('khu_vuc_id', array_values(array_unique($khuVucIds)));
            } else {
                // Fallback cho trường hợp truyền chuỗi tên khu vực từ nguồn cũ.
                $query->whereHas('khuVuc', function ($q) use ($khu_vuc) {
                    $q->where('ten_khu_vuc', 'LIKE', '%' . $khu_vuc . '%');
                });
            }
        }

        // 3. Lọc theo TỪ KHÓA (Tên dự án) - Bổ sung thêm để form search ngoài view hoạt động
        if ($request->filled('tu_khoa')) {
            $tu_khoa = $request->tu_khoa;
            $query->where('ten_du_an', 'LIKE', '%' . $tu_khoa . '%');
        }

        // 4. Phân trang: Lấy 12 dự án mỗi trang, sắp xếp theo thứ tự hiển thị ưu tiên
        $duAns = $query->orderBy('thu_tu_hien_thi', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // 5. SỬA TẠI ĐÂY: Bỏ whereNull('khu_vuc_cha_id') để lấy TOÀN BỘ khu vực đang được cho phép hiển thị
        $khuVucs = KhuVuc::where('hien_thi', 1)->get();

        return view('frontend.du-an.index', compact('duAns', 'khuVucs'));
    }

    public function show($slug)
    {
        $duAn = DuAn::with([
            'khuVuc',
            'batDongSans' => function ($q) {
                $q->where('hien_thi', 1)
                    ->where('trang_thai', 'con_hang')
                    ->orderByDesc('id');
            },
        ])->where('slug', $slug)
            ->where('hien_thi', 1)
            ->firstOrFail();

        $favoriteMap = collect();
        if (Auth::guard('customer')->check()) {
            $favoriteMap = YeuThich::where('khach_hang_id', Auth::guard('customer')->id())
                ->whereIn('bat_dong_san_id', $duAn->batDongSans->pluck('id'))
                ->pluck('bat_dong_san_id')
                ->flip();
        }

        $duAn->batDongSans->transform(function ($item) use ($favoriteMap) {
            $item->setAttribute('isYeuThich', $favoriteMap->has($item->id));
            return $item;
        });

        $duAnKhac = DuAn::query()
            ->with('khuVuc')
            ->withCount([
                'batDongSans as so_can_ban' => function ($q) {
                    $q->where('hien_thi', 1)->where('trang_thai', 'con_hang')->where('nhu_cau', 'ban');
                },
                'batDongSans as so_can_thue' => function ($q) {
                    $q->where('hien_thi', 1)->where('trang_thai', 'con_hang')->where('nhu_cau', 'thue');
                },
            ])
            ->where('hien_thi', 1)
            ->where('id', '!=', $duAn->id)
            ->orderByRaw('CASE WHEN khu_vuc_id = ? THEN 0 ELSE 1 END', [$duAn->khu_vuc_id])
            ->orderBy('thu_tu_hien_thi', 'asc')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        // return view('frontend.du-an.show', compact('duAn'));
        return view('frontend.du-an.show', [
            'duAn'              => $duAn,
            'duAnKhac'          => $duAnKhac,
            // Context cho chat widget
            'chat_loai_ngu_canh' => 'du_an',
            'chat_ngu_canh_id'  => $duAn->id,
            'chat_ten_ngu_canh' => $duAn->ten_du_an,

            // ═══ SEO META TAGS ═══
            'seo_title'       => ($duAn->seo_title ?: $duAn->ten_du_an) . ' — Thành Công Land',
            'seo_description' => $duAn->seo_description ?: Str::limit(strip_tags($duAn->mo_ta_ngan ?? $duAn->noi_dung_chi_tiet), 160),
            'seo_image'       => $duAn->hinh_anh_dai_dien
                ? \Illuminate\Support\Facades\Storage::disk('r2')->url($duAn->hinh_anh_dai_dien)
                : asset('images/og-default.jpg'),
            'seo_keywords'    => $duAn->seo_keywords ?: null,
            'seo_type'        => 'article',
        ]);
    }
}
