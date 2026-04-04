<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\YeuThich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DuAnController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo query lấy các dự án đang hiển thị
        $query = DuAn::with('khuVuc')->where('hien_thi', 1);

        // 2. Lọc theo KHU VỰC
        if ($request->filled('khu_vuc')) {
            $khu_vuc = $request->khu_vuc;

            // Xử lý linh hoạt: Nếu truyền ID (số), hoặc truyền Slug (chuỗi như 'smart-city')
            if (is_numeric($khu_vuc)) {
                $query->where('khu_vuc_id', $khu_vuc);
            } else {
                // Nếu truyền chuỗi từ các link cứng trên header
                $query->whereHas('khuVuc', function ($q) use ($khu_vuc) {
                    $q->where('slug', $khu_vuc)->orWhere('ten_khu_vuc', 'LIKE', '%' . $khu_vuc . '%');
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
        ]);
    }
}
