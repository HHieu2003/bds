<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;
use Illuminate\Http\Request;

class BatDongSanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo Query mặc định: Chỉ lấy BĐS đang hiển thị và còn hàng
        $query = BatDongSan::with(['duAn', 'khuVuc'])
            ->where('hien_thi', 1)
            ->when($request->toa,     fn($q) => $q->where('toa', $request->toa))
            ->when($request->noithat, fn($q) => $q->where('noi_that', $request->noithat))
            ->where('trang_thai', 'con_hang');

        // 2. Lọc theo Nhu cầu (Bán / Thuê)
        if ($request->filled('nhu_cau')) {
            $query->where('nhu_cau', $request->nhu_cau);
        }

        // 3. Lọc theo Từ khóa (Tìm trong Tiêu đề hoặc Địa chỉ)
        if ($request->filled('tu_khoa')) {
            $tuKhoa = $request->tu_khoa;
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('tieu_de', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('dia_chi', 'like', '%' . $tuKhoa . '%');
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
            $query->orderBy('noi_bat', 'desc')->orderBy('created_at', 'desc'); // Mặc định: Nổi bật lên trước, sau đó là mới nhất
        }


        $toaList = BatDongSan::whereNotNull('toa')
            ->when($request->du_an, fn($q) => $q->where('du_an_id', $request->du_an))
            ->distinct()->pluck('toa')->sort()->values();
        // 10. Phân trang (12 BĐS / 1 trang) và giữ nguyên param trên URL
        $batDongSans = $query->paginate(12)->withQueryString();

        // 11. Lấy dữ liệu cho Sidebar Filter
        $khuVucs = KhuVuc::where('hien_thi', 1)->whereNull('khu_vuc_cha_id')->get();
        $duAns = DuAn::where('hien_thi', 1)->orderBy('ten_du_an')->get();

        return view('frontend.bat-dong-san.index', compact('batDongSans', 'khuVucs', 'duAns'));
    }

    public function show($slug)
    {
        $bds = BatDongSan::with(['duAn', 'khuVuc'])->where('slug', $slug)->where('hien_thi', 1)->firstOrFail();

        // Tăng lượt xem (Tính năng thực tế)
        $bds->increment('luot_xem');

        // Bất động sản liên quan (Cùng dự án hoặc cùng khu vực)
        $bdsLienQuan = BatDongSan::where('id', '!=', $bds->id)
            ->where('hien_thi', 1)
            ->where('nhu_cau', $bds->nhu_cau)
            ->where(function ($q) use ($bds) {
                if ($bds->du_an_id) $q->where('du_an_id', $bds->du_an_id);
                else $q->where('khu_vuc_id', $bds->khu_vuc_id);
            })
            ->limit(4)->get();

        return view('frontend.bat-dong-san.show', compact('bds', 'bdsLienQuan'));
    }
}
