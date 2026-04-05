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
use Illuminate\Support\Facades\Schema;
use App\Services\GoiYService;

class BatDongSanController extends Controller
{
    public function index(Request $request, GoiYService $goiYService)
    {
        $nhuCau = $request->input('nhu_cau', 'ban');

        // 1. Khởi tạo Query mặc định: Chỉ lấy BĐS đang hiển thị và còn hàng
        $query = BatDongSan::with(['duAn.khuVuc']) // Tối ưu N+1 query lồng nhau
            ->where('hien_thi', 1)
            ->when($request->toa,     fn($q) => $q->where('toa', $request->toa))
            ->when($request->noithat, fn($q) => $q->where('noi_that', $request->noithat))
            ->where('trang_thai', 'con_hang');

        // 2. Lọc theo Nhu cầu (Bán / Thuê)
        $query->where('nhu_cau', $nhuCau);

        // 3. Lọc theo TỪ KHÓA (Tối ưu tìm kiếm đa tầng: Tiêu đề, Tòa, Mã BĐS, Tên Dự án, Địa chỉ, Tên Khu vực)
        if ($request->filled('timkiem')) {
            $tuKhoa = trim((string) $request->timkiem);
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
        if ($request->filled('khu_vuc')) {
            $khuVucDaChon = KhuVuc::find($request->khu_vuc);

            if ($khuVucDaChon) {
                $khuVucIds = [$khuVucDaChon->id];
                $currentParentIds = [$khuVucDaChon->id];

                // Gom tất cả khu vực con cháu để lọc bao trùm khi chọn khu vực cha.
                while (! empty($currentParentIds)) {
                    $childIds = KhuVuc::whereIn('khu_vuc_cha_id', $currentParentIds)->pluck('id')->all();

                    if (empty($childIds)) {
                        break;
                    }

                    $khuVucIds = array_merge($khuVucIds, $childIds);
                    $currentParentIds = $childIds;
                }

                $query->whereHas('duAn', function ($q) use ($khuVucIds) {
                    $q->whereIn('khu_vuc_id', array_values(array_unique($khuVucIds)));
                });
            }
        }

        // 5. Lọc theo Dự án
        if ($request->filled('du_an')) {
            $query->where('du_an_id', $request->du_an);
        }

        // 6. Lọc theo Số phòng ngủ
        if ($request->filled('sophongngu')) {
            if ($request->sophongngu === 'studio') {
                $query->where('so_phong_ngu', 0); // 0 đại diện cho Studio
            } elseif ((int) $request->sophongngu >= 3) {
                $query->where('so_phong_ngu', '>=', 3);
            } else {
                $query->where('so_phong_ngu', (int) $request->sophongngu);
            }
        }

        // 7. Lọc theo Mức giá (Xử lý linh hoạt cho cả Bán và Thuê)
        if ($request->filled('muc_gia')) {
            $mucGia = $request->muc_gia;

            if ($nhuCau == 'ban') {
                // Logic giá BÁN
                if ($mucGia == 'duoi2ty') $query->where('gia', '<', 2000000000);
                elseif ($mucGia == '2-5ty') $query->whereBetween('gia', [2000000000, 5000000000]);
                elseif ($mucGia == '5-10ty') $query->whereBetween('gia', [5000000000, 10000000000]);
                elseif ($mucGia == 'tren10ty') $query->where('gia', '>', 10000000000);
            } else {
                // Logic giá THUÊ
                if ($mucGia == 'duoi10tr') $query->where('gia_thue', '<', 10000000);
                elseif ($mucGia == '10-20tr') $query->whereBetween('gia_thue', [10000000, 20000000]);
                elseif ($mucGia == '20-50tr') $query->whereBetween('gia_thue', [20000000, 50000000]);
                elseif ($mucGia == 'tren50tr') $query->where('gia_thue', '>', 50000000);
            }
        }

        // 8. Lọc BĐS Nổi bật
        if ($request->boolean('noibat')) {
            $query->where('noi_bat', 1);
        }

        // 8.1. Lọc BĐS vào ở ngay (nếu có cột)
        if ($request->boolean('vao_o_ngay') && Schema::hasColumn('bat_dong_san', 'vao_o_ngay')) {
            $query->where('vao_o_ngay', 1);
        }

        // 9. Sắp xếp kết quả
        $sapXep = $request->input('sap_xep', 'moinhat');
        if ($sapXep === 'giatang') {
            $query->orderBy($nhuCau === 'thue' ? 'gia_thue' : 'gia', 'asc');
        } elseif ($sapXep === 'giagiam') {
            $query->orderBy($nhuCau === 'thue' ? 'gia_thue' : 'gia', 'desc');
        } elseif ($sapXep === 'dientich') {
            $query->orderBy('dien_tich', 'desc');
        } else {
            // Mặc định: Nổi bật lên trước, sau đó là mới nhất
            $query->orderBy('noi_bat', 'desc')->orderBy('created_at', 'desc');
        }

        $toaList = BatDongSan::whereNotNull('toa')
            ->where('hien_thi', 1)
            ->where('trang_thai', 'con_hang')
            ->when($request->du_an, fn($q) => $q->where('du_an_id', $request->du_an))
            ->where('nhu_cau', $nhuCau)
            ->distinct()->pluck('toa')->sort()->values();

        // 10. Phân trang (12 BĐS / 1 trang) và giữ nguyên param trên URL
        $batDongSans = $query->paginate(12)->withQueryString();

        // Ghi lịch sử tìm kiếm để mô hình gợi ý có dữ liệu thật từ hành vi lọc/tìm.
        [$giaTu, $giaDen] = $this->resolveGiaKhoang($request->muc_gia, $request->nhu_cau);

        $boLoc = [
            'nhu_cau' => $nhuCau,
            'khu_vuc_id' => $request->khu_vuc,
            'du_an_id' => $request->du_an,
            'so_phong_ngu' => $request->sophongngu,
            'loai_hinh' => $request->loai_hinh,
            'gia_tu' => $giaTu,
            'gia_den' => $giaDen,
            'toa' => $request->toa,
            'noi_that' => $request->noithat,
            'noi_bat' => $request->noibat,
            'vao_o_ngay' => $request->vao_o_ngay,
        ];

        $goiYService->ghiLichSuTimKiem(
            Auth::guard('customer')->id(),
            session()->getId(),
            [
                'tu_khoa' => $request->timkiem,
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
        $khuVucs = KhuVuc::where('hien_thi', 1)->orderBy('ten_khu_vuc')->get();
        $duAns = DuAn::where('hien_thi', 1)->orderBy('ten_du_an')->get();

        // =================================================================
        // ĐỐI TÁC LẬP TRÌNH: TẠO DANH SÁCH TỪ KHÓA GỢI Ý THÔNG MINH
        // =================================================================
        $profile = $goiYService->xayDungProfile(Auth::guard('customer')->id(), session()->getId());
        $tuKhoaGoiY = [];
        $nhuCauHienTai = $request->nhu_cau ?? 'ban';

        // 1. Gợi ý theo Dự án quan tâm nhất
        if (!empty($profile['du_an_top_ids'])) {
            $topDuAnId = $profile['du_an_top_ids'][0];
            $duAnGoiY = $duAns->firstWhere('id', $topDuAnId);
            if ($duAnGoiY) {
                $tuKhoaGoiY[] = [
                    'label' => 'Dự án ' . $duAnGoiY->ten_du_an,
                    'url' => route('frontend.bat-dong-san.index', ['du_an' => $topDuAnId, 'nhu_cau' => $nhuCauHienTai])
                ];
            }
        }

        // 2. Gợi ý theo Loại hình BĐS
        if (!empty($profile['loai_hinh_top'])) {
            $tuKhoaGoiY[] = [
                'label' => 'Tìm ' . $profile['loai_hinh_top'],
                'url' => route('frontend.bat-dong-san.index', ['timkiem' => $profile['loai_hinh_top'], 'nhu_cau' => $nhuCauHienTai])
            ];
        }

        // 3. Gợi ý theo Khu vực
        if (!empty($profile['khu_vuc_top_ids'])) {
            $topKhuVucId = $profile['khu_vuc_top_ids'][0];
            $khuVucGoiY = $khuVucs->firstWhere('id', $topKhuVucId);
            if ($khuVucGoiY) {
                $tuKhoaGoiY[] = [
                    'label' => 'Khu vực ' . $khuVucGoiY->ten_khu_vuc,
                    'url' => route('frontend.bat-dong-san.index', ['khu_vuc' => $topKhuVucId, 'nhu_cau' => $nhuCauHienTai])
                ];
            }
        }

        // 4. Gợi ý theo Khoảng giá trung bình
        if (!empty($profile['gia_trung_binh'])) {
            $giaTB = $profile['gia_trung_binh'];
            if ($nhuCauHienTai == 'ban') {
                if ($giaTB <= 2000000000) $tuKhoaGoiY[] = ['label' => 'Giá dưới 2 Tỷ', 'url' => route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'muc_gia' => 'duoi2ty'])];
                elseif ($giaTB <= 5000000000) $tuKhoaGoiY[] = ['label' => 'Khoảng 2 - 5 Tỷ', 'url' => route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'muc_gia' => '2-5ty'])];
                else $tuKhoaGoiY[] = ['label' => 'Từ 5 - 10 Tỷ', 'url' => route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'muc_gia' => '5-10ty'])];
            } else {
                if ($giaTB <= 10000000) $tuKhoaGoiY[] = ['label' => 'Thuê dưới 10 Triệu', 'url' => route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'muc_gia' => 'duoi10tr'])];
                elseif ($giaTB <= 20000000) $tuKhoaGoiY[] = ['label' => 'Thuê 10 - 20 Triệu', 'url' => route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'muc_gia' => '10-20tr'])];
                else $tuKhoaGoiY[] = ['label' => 'Thuê 20 - 50 Triệu', 'url' => route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'muc_gia' => '20-50tr'])];
            }
        }

        // 5. Nếu khách hàng mới vào (chưa có lịch sử), hiển thị gợi ý Mặc định hấp dẫn
        if (empty($tuKhoaGoiY)) {
            $tuKhoaGoiY = [
                ['label' => 'Căn Studio nhỏ gọn', 'url' => route('frontend.bat-dong-san.index', ['sophongngu' => 'studio', 'nhu_cau' => $nhuCauHienTai])],
                ['label' => $nhuCauHienTai == 'ban' ? 'Giá rẻ dưới 2 Tỷ' : 'Thuê dưới 10 Triệu', 'url' => route('frontend.bat-dong-san.index', ['muc_gia' => $nhuCauHienTai == 'ban' ? 'duoi2ty' : 'duoi10tr', 'nhu_cau' => $nhuCauHienTai])],
                ['label' => 'Căn hộ Nội thất đầy đủ', 'url' => route('frontend.bat-dong-san.index', ['noithat' => 'full', 'nhu_cau' => $nhuCauHienTai])],
            ];
        }

        // Giới hạn lấy tối đa 4 từ khóa để giao diện không bị tràn
        $tuKhoaGoiY = array_slice($tuKhoaGoiY, 0, 4);

        return view('frontend.bat-dong-san.index', compact('batDongSans', 'khuVucs', 'duAns', 'toaList', 'tuKhoaGoiY'));
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
        $nganHangs = \App\Models\NganHang::where('trang_thai', 1)->orderBy('lai_suat_uu_dai', 'asc')->get();
        // return view('frontend.bat-dong-san.show', compact('bds', 'bdsLienQuan',));
        return view('frontend.bat-dong-san.show', [
            'bds'               => $bds,
            'bdsLienQuan'       => $bdsLienQuan,
            'goiYBds'           => $goiYBds,
            'nganHangs'         => $nganHangs,
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
     * API: Lấy danh sách tòa của một dự án (dùng cho cascade filtering)
     */
    public function toaByDuAn(int $duAnId)
    {
        $nhuCau = request('nhu_cau', 'ban');

        $toaList = BatDongSan::where('du_an_id', $duAnId)
            ->where('hien_thi', 1)
            ->where('trang_thai', 'con_hang')
            ->where('nhu_cau', $nhuCau)
            ->whereNotNull('toa')
            ->where('toa', '!=', '')
            ->distinct()
            ->orderBy('toa')
            ->pluck('toa')
            ->values();

        return response()->json($toaList);
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
                'duoi10tr' => [0, 10000000],
                '10-20tr' => [10000000, 20000000],
                '20-50tr' => [20000000, 50000000],
                'tren50tr' => [50000000, null],
                default => [null, null],
            };
        }

        return match ($mucGia) {
            'duoi2ty' => [0, 2000000000],
            '2-5ty' => [2000000000, 5000000000],
            '5-10ty' => [5000000000, 10000000000],
            'tren10ty' => [10000000000, null],
            default => [null, null],
        };
    }
}
