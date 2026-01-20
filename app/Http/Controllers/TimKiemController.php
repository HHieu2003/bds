<?php

namespace App\Http\Controllers;

use App\Models\BatDongSan;
use App\Models\DuAn;
use Illuminate\Http\Request;

class TimKiemController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query từ bảng Bất động sản
        // Chỉ lấy những tin chưa bán
        $query = BatDongSan::where('trang_thai', '!=', 'da_ban');

        // 1. Tìm theo Từ khóa (Tiêu đề hoặc Địa chỉ dự án)
        if ($request->has('tu_khoa') && $request->tu_khoa != '') {
            $tuKhoa = $request->tu_khoa;
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('tieu_de', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('ma_can', 'like', '%' . $tuKhoa . '%')
                    ->orWhereHas('duAn', function ($q2) use ($tuKhoa) {
                        $q2->where('dia_chi', 'like', '%' . $tuKhoa . '%')
                            ->orWhere('ten_du_an', 'like', '%' . $tuKhoa . '%');
                    });
            });
        }

        // 2. Lọc theo Dự án
        if ($request->filled('du_an')) {
            $query->where('du_an_id', $request->du_an);
        }

        // 3. Lọc theo Loại hình (Căn hộ, Đất nền...)
        if ($request->filled('loai_hinh')) {
            $query->where('loai_hinh', $request->loai_hinh);
        }

        // 4. Lọc theo Hướng nhà
        if ($request->filled('huong_nha')) {
            $query->where('huong_nha', $request->huong_nha);
        }

        // 5. Lọc theo Khoảng Giá (Logic phức tạp nhất)
        // Value gửi lên dạng: "duoi-2", "2-5", "5-8", "tren-8"
        if ($request->filled('muc_gia')) {
            switch ($request->muc_gia) {
                case 'duoi-2':
                    $query->where('gia', '<', 2);
                    break;
                case '2-5':
                    $query->whereBetween('gia', [2, 5]);
                    break;
                case '5-8':
                    $query->whereBetween('gia', [5, 8]);
                    break;
                case '8-15':
                    $query->whereBetween('gia', [8, 15]);
                    break;
                case 'tren-15':
                    $query->where('gia', '>=', 15);
                    break;
            }
        }

        // 6. Lọc theo Diện tích
        if ($request->filled('dien_tich')) {
            switch ($request->dien_tich) {
                case 'duoi-50':
                    $query->where('dien_tich', '<', 50);
                    break;
                case '50-80':
                    $query->whereBetween('dien_tich', [50, 80]);
                    break;
                case '80-120':
                    $query->whereBetween('dien_tich', [80, 120]);
                    break;
                case 'tren-120':
                    $query->where('dien_tich', '>=', 120);
                    break;
            }
        }

        // Sắp xếp dữ liệu
        $sort = $request->get('sort', 'newest');
        if ($sort == 'price_asc') {
            $query->orderBy('gia', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('gia', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Lấy dữ liệu và phân trang
        $batDongSans = $query->paginate(9)->withQueryString(); // withQueryString để giữ lại tham số khi chuyển trang

        // Lấy danh sách dự án để đổ vào Dropdown lọc
        $du_ans = DuAn::select('id', 'ten_du_an')->get();

        return view('frontend.tim_kiem.index', compact('batDongSans', 'du_ans'));
    }

    public function search(Request $request)
    {
        // Khởi tạo query từ bảng Bất động sản
        // Chỉ lấy những tin chưa bán
        $query = BatDongSan::where('trang_thai', '!=', 'da_ban');

        // 1. Tìm theo Từ khóa (Tiêu đề hoặc Địa chỉ dự án)
        if ($request->has('tu_khoa') && $request->tu_khoa != '') {
            $tuKhoa = $request->tu_khoa;
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('tieu_de', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('ma_can', 'like', '%' . $tuKhoa . '%')
                    ->orWhereHas('duAn', function ($q2) use ($tuKhoa) {
                        $q2->where('dia_chi', 'like', '%' . $tuKhoa . '%')
                            ->orWhere('ten_du_an', 'like', '%' . $tuKhoa . '%');
                    });
            });
        }

        // 2. Lọc theo Dự án
        if ($request->filled('du_an')) {
            $query->where('du_an_id', $request->du_an);
        }

        // 3. Lọc theo Loại hình (Căn hộ, Đất nền...)
        if ($request->filled('loai_hinh')) {
            $query->where('loai_hinh', $request->loai_hinh);
        }

        // 4. Lọc theo Hướng nhà
        if ($request->filled('huong_nha')) {
            $query->where('huong_nha', $request->huong_nha);
        }

        // 5. Lọc theo Khoảng Giá (Logic phức tạp nhất)
        // Value gửi lên dạng: "duoi-2", "2-5", "5-8", "tren-8"
        if ($request->filled('muc_gia')) {
            switch ($request->muc_gia) {
                case 'duoi-2':
                    $query->where('gia', '<', 2);
                    break;
                case '2-5':
                    $query->whereBetween('gia', [2, 5]);
                    break;
                case '5-8':
                    $query->whereBetween('gia', [5, 8]);
                    break;
                case '8-15':
                    $query->whereBetween('gia', [8, 15]);
                    break;
                case 'tren-15':
                    $query->where('gia', '>=', 15);
                    break;
            }
        }

        // 6. Lọc theo Diện tích
        if ($request->filled('dien_tich')) {
            switch ($request->dien_tich) {
                case 'duoi-50':
                    $query->where('dien_tich', '<', 50);
                    break;
                case '50-80':
                    $query->whereBetween('dien_tich', [50, 80]);
                    break;
                case '80-120':
                    $query->whereBetween('dien_tich', [80, 120]);
                    break;
                case 'tren-120':
                    $query->where('dien_tich', '>=', 120);
                    break;
            }
        }

        // Sắp xếp dữ liệu
        $sort = $request->get('sort', 'newest');
        if ($sort == 'price_asc') {
            $query->orderBy('gia', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('gia', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Lấy dữ liệu và phân trang
        $batDongSans = $query->paginate(9)->withQueryString(); // withQueryString để giữ lại tham số khi chuyển trang

        // Lấy danh sách dự án để đổ vào Dropdown lọc
        $du_ans = DuAn::select('id', 'ten_du_an')->get();

        return view('frontend.tim_kiem.index', compact('batDongSans', 'du_ans'));
    }
}
