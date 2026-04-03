<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\LichSuXemBds;
use App\Models\YeuThich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GoiYService;

class BatDongSanController extends Controller
{
    public function index(Request $request, GoiYService $goiYService)
    {
        // 1. Khởi tạo Query mặc định: Chỉ lấy BĐS đang hiển thị và còn hàng
        $query = BatDongSan::with(['duAn.khuVuc']) // Tối ưu N+1 query lồng nhau
            ->where('hien_thi', 1)
            ->when($request->toa,     fn($q) => $q->where('toa', $request->toa))
            ->when($request->noithat, fn($q) => $q->where('noi_that', $request->noithat))
            ->where('trang_thai', 'con_hang');

        // 2. Lọc theo Nhu cầu (Bán / Thuê)
        if ($request->filled('nhu_cau')) {
            $query->where('nhu_cau', $request->nhu_cau);
        }

        // 3. Lọc theo TỪ KHÓA (Tối ưu tìm kiếm đa tầng: Tiêu đề, Tòa, Mã BĐS, Tên Dự án, Địa chỉ, Tên Khu vực)
        if ($request->filled('tu_khoa')) {
            $tuKhoa = $request->tu_khoa;
            $query->where(function ($q) use ($tuKhoa) {
                // Các trường nằm trực tiếp trên bảng bat_dong_san
                $q->where('tieu_de', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('toa', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('ma_bat_dong_san', 'like', '%' . $tuKhoa . '%')

                    // Tìm lồng vào bảng du_an (Dự án)
                    ->orWhereHas('duAn', function ($qDuAn) use ($tuKhoa) {
                        $qDuAn->where('ten_du_an', 'like', '%' . $tuKhoa . '%')
                            ->orWhere('dia_chi', 'like', '%' . $tuKhoa . '%') // dia_chi nằm ở bảng dự án

                            // Tiếp tục tìm lồng vào bảng khu_vuc (Từ dự án chọc sang khu vực)
                            ->orWhereHas('khuVuc', function ($qKhuVuc) use ($tuKhoa) {
                                $qKhuVuc->where('ten_khu_vuc', 'like', '%' . $tuKhoa . '%');
                            });
                    });
            });
        }

        // 4. Lọc theo Khu vực
        if ($request->filled('khu_vuc_id')) {
            $query->whereHas('duAn', function ($q) use ($request) {
                // Truy vấn vào bảng du_an để tìm các dự án thuộc khu vực này
                $q->where('khu_vuc_id', $request->khu_vuc_id);
            });
        }

        // 5. Lọc theo Dự án
        if ($request->filled('du_an')) {
            $query->where('du_an_id', $request->du_an);
        }

        // 6. Lọc theo Số phòng ngủ
        if ($request->filled('so_phong_ngu')) {
            if ($request->so_phong_ngu === 'studio') {
                $query->where('so_phong_ngu', 0); // 0 đại diện cho Studio
            } elseif ($request->so_phong_ngu >= 3) {
                $query->where('so_phong_ngu', '>=', 3);
            } else {
                $query->where('so_phong_ngu', $request->so_phong_ngu);
            }
        }

        // 7. Lọc theo Mức giá (Xử lý linh hoạt cho cả Bán và Thuê)
        if ($request->filled('muc_gia')) {
            $mucGia = $request->muc_gia;
            $nhuCau = $request->nhu_cau ?? 'ban';

            if ($nhuCau == 'ban') {
                // Logic giá BÁN
                if ($mucGia == 'duoi-2') $query->where('gia', '<', 2000000000);
                elseif ($mucGia == '2-5') $query->whereBetween('gia', [2000000000, 5000000000]);
                elseif ($mucGia == '5-10') $query->whereBetween('gia', [5000000000, 10000000000]);
                elseif ($mucGia == 'tren-10') $query->where('gia', '>', 10000000000);
            } else {
                // Logic giá THUÊ
                if ($mucGia == 'duoi-10') $query->where('gia', '<', 10000000);
                elseif ($mucGia == '10-20') $query->whereBetween('gia', [10000000, 20000000]);
                elseif ($mucGia == '20-50') $query->whereBetween('gia', [20000000, 50000000]);
                elseif ($mucGia == 'tren-50') $query->where('gia', '>', 50000000);
            }
        }

        // 8. Lọc BĐS Nổi bật
        if ($request->filled('noi_bat')) {
            $query->where('noi_bat', 1);
        }

        // 9. Sắp xếp kết quả
        if ($request->filled('sap_xep')) {
            if ($request->sap_xep == 'gia_thap') $query->orderBy('gia', 'asc');
            elseif ($request->sap_xep == 'gia_cao') $query->orderBy('gia', 'desc');
            elseif ($request->sap_xep == 'moi_nhat') $query->orderBy('created_at', 'desc');
        } else {
            // Mặc định: Nổi bật lên trước, sau đó là mới nhất
            $query->orderBy('noi_bat', 'desc')->orderBy('created_at', 'desc');
        }

        $toaList = BatDongSan::whereNotNull('toa')
            ->when($request->du_an, fn($q) => $q->where('du_an_id', $request->du_an))
            ->distinct()->pluck('toa')->sort()->values();

        // 10. Phân trang (12 BĐS / 1 trang) và giữ nguyên param trên URL
        $batDongSans = $query->paginate(12)->withQueryString();

        // Ghi lịch sử tìm kiếm để mô hình gợi ý có dữ liệu thật từ hành vi lọc/tìm.
        [$giaTu, $giaDen] = $this->resolveGiaKhoang($request->muc_gia, $request->nhu_cau);
        $boLoc = [
            'nhu_cau' => $request->nhu_cau,
            'khu_vuc_id' => $request->khu_vuc_id,
            'du_an_id' => $request->du_an,
            'so_phong_ngu' => $request->so_phong_ngu,
            'loai_hinh' => $request->loai_hinh,
            'gia_tu' => $giaTu,
            'gia_den' => $giaDen,
            'toa' => $request->toa,
            'noi_that' => $request->noithat,
            'noi_bat' => $request->noi_bat,
        ];

        $goiYService->ghiLichSuTimKiem(
            Auth::guard('customer')->id(),
            session()->getId(),
            [
                'tu_khoa' => $request->tu_khoa,
                'bo_loc' => $boLoc,
                'sap_xep_theo' => $request->sap_xep,
                'so_ket_qua' => $batDongSans->total(),
            ]
        );

        $favoriteMap = collect();
        if (Auth::guard('customer')->check()) {
            $favoriteMap = YeuThich::where('khach_hang_id', Auth::guard('customer')->id())
                ->whereIn('bat_dong_san_id', $batDongSans->pluck('id'))
                ->pluck('bat_dong_san_id')
                ->flip();
        }
        $batDongSans->getCollection()->transform(function ($item) use ($favoriteMap) {
            $item->setAttribute('isYeuThich', $favoriteMap->has($item->id));
            return $item;
        });

        // 11. Lấy dữ liệu cho Sidebar Filter
        $khuVucs = KhuVuc::where('hien_thi', 1)->whereNull('khu_vuc_cha_id')->get();
        $duAns = DuAn::where('hien_thi', 1)->orderBy('ten_du_an')->get();

        return view('frontend.bat-dong-san.index', compact('batDongSans', 'khuVucs', 'duAns', 'toaList'));
    }

    public function show($slug, GoiYService $goiYService)
    {
        $bds = BatDongSan::with(['duAn.khuVuc'])->where('slug', $slug)->where('hien_thi', 1)->firstOrFail();

        // Tăng lượt xem
        $bds->increment('luot_xem');

        // Bất động sản liên quan (Cùng dự án hoặc cùng khu vực)
        $bdsLienQuan = BatDongSan::with(['duAn.khuVuc'])
            ->where('id', '!=', $bds->id)
            ->where('hien_thi', 1)
            ->where('nhu_cau', $bds->nhu_cau)
            ->where(function ($q) use ($bds) {
                if ($bds->du_an_id) {
                    $q->where('du_an_id', $bds->du_an_id);
                } else {
                    // Nếu không có dự án thì tìm theo khu vực của dự án
                    $q->whereHas('duAn', function ($qDuAn) use ($bds) {
                        $qDuAn->where('khu_vuc_id', $bds->duAn->khu_vuc_id ?? null);
                    });
                }
            })
            ->limit(4)->get();

        $favoriteMap = collect();
        if (Auth::guard('customer')->check()) {
            $ids = collect([$bds->id])->merge($bdsLienQuan->pluck('id'))->unique()->values();
            $favoriteMap = YeuThich::where('khach_hang_id', Auth::guard('customer')->id())
                ->whereIn('bat_dong_san_id', $ids)
                ->pluck('bat_dong_san_id')
                ->flip();
        }

        $bds->setAttribute('isYeuThich', $favoriteMap->has($bds->id));
        $bdsLienQuan->transform(function ($item) use ($favoriteMap) {
            $item->setAttribute('isYeuThich', $favoriteMap->has($item->id));
            return $item;
        });
        $khachHangId = Auth::guard('customer')->id();
        $sessionId   = session()->getId();

        // ✅ Ghi lịch sử xem + tăng lượt xem
        $goiYService->ghiLichSuXem($bds, $khachHangId, $sessionId);

        // ✅ Lấy BĐS gợi ý (loại trừ BĐS đang xem)
        $goiYBds = $goiYService->layGoiY($khachHangId, $sessionId, $bds->id);

        // return view('frontend.bat-dong-san.show', compact('bds', 'bdsLienQuan',));
        return view('frontend.bat-dong-san.show', [
            'bds'               => $bds,
            'bdsLienQuan'       => $bdsLienQuan,
            'goiYBds'           => $goiYBds,
            // Context cho chat widget
            'chat_loai_ngu_canh' => 'bat_dong_san',
            'chat_ngu_canh_id'  => $bds->id,
            'chat_ten_ngu_canh' => $bds->tieu_de,
        ]);
    }
    public function trackTime(Request $request)
    {
        $request->validate([
            'bds_id' => 'required|integer|exists:bat_dong_san,id',
            'seconds' => 'required|integer|min:0|max:1800',
        ]);

        LichSuXemBds::where('bat_dong_san_id', $request->bds_id)
            ->where(function ($q) {
                $id = Auth::guard('customer')->id();
                if ($id) $q->where('khach_hang_id', $id);
                else     $q->where('session_id', session()->getId());
            })
            ->latest()
            ->first()
            ?->update(['thoi_gian_xem' => min((int)$request->seconds, 1800)]);

        return response()->noContent();
    }

    /**
     * Chuẩn hóa khoảng giá số theo input filter để lưu lịch sử tìm kiếm.
     */
    protected function resolveGiaKhoang(?string $mucGia, ?string $nhuCau): array
    {
        if (!$mucGia) return [null, null];

        $nhuCau = $nhuCau ?: 'ban';
        if ($nhuCau === 'thue') {
            return match ($mucGia) {
                'duoi-10' => [0, 10000000],
                '10-20' => [10000000, 20000000],
                '20-50' => [20000000, 50000000],
                'tren-50' => [50000000, null],
                default => [null, null],
            };
        }

        return match ($mucGia) {
            'duoi-2' => [0, 2000000000],
            '2-5' => [2000000000, 5000000000],
            '5-10' => [5000000000, 10000000000],
            'tren-10' => [10000000000, null],
            default => [null, null],
        };
    }
}
