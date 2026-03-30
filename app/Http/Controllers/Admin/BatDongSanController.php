<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\ChuNha;
use App\Models\DuAn;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatDongSanController extends Controller
{
    // ── LẤY DỮ LIỆU CONSTANTS CHO FORM ──
    private function getConstants(): array
    {
        return [
            'loai_hinh' => ['can_ho' => 'Căn hộ chung cư', 'nha_pho' => 'Nhà phố', 'biet_thu' => 'Biệt thự', 'dat_nen' => 'Đất nền', 'shophouse' => 'Shophouse'],
            'nhu_cau'   => ['ban' => 'Bán', 'thue' => 'Cho thuê'],
            'noi_that'  => ['nguyen_ban' => 'Nguyên bản', 'co_ban' => 'Cơ bản', 'full' => 'Full nội thất', 'cao_cap' => 'Cao cấp'],
            'phap_ly'   => ['so_hong' => 'Sổ hồng', 'so_do' => 'Sổ đỏ', 'hop_dong' => 'Hợp đồng mua bán', 'chua_co' => 'Chưa có sổ'],
            'trang_thai' => [
                'con_hang'  => ['label' => '✅ Còn hàng'],
                'dat_coc'   => ['label' => '🤝 Đặt cọc'],
                'da_ban'    => ['label' => '❌ Đã bán'],
                'dang_thue' => ['label' => '🔑 Đang cho thuê'],
                'da_thue'   => ['label' => '📦 Đã cho thuê'],
                'tam_an'    => ['label' => '⏸ Tạm ẩn'],
            ],
            'thoi_gian_vao_thue' => ['ngay_lap_tuc' => 'Vào ở ngay', 'sau_1_tuan' => 'Sau 1 tuần', 'sau_1_thang' => 'Sau 1 tháng', 'thoa_thuan' => 'Thỏa thuận'],
            'hinh_thuc_tt' => ['thang_1' => 'Thanh toán 1 tháng/lần', 'thang_3' => 'Thanh toán 3 tháng/lần', 'thang_6' => 'Thanh toán 6 tháng/lần', 'nam_1' => 'Thanh toán 1 năm/lần']
        ];
    }

    // ── INDEX: DANH SÁCH & BỘ LỌC ──
    public function index(Request $request)
    {
        // Load các quan hệ cần thiết để tránh lỗi N+1 Query
        $query = BatDongSan::with(['duAn', 'nhanVienPhuTrach', 'chuNha']);

        // Tìm kiếm từ khóa
        if ($request->filled('tukhoa')) {
            $kw = '%' . $request->tukhoa . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('tieu_de', 'like', $kw)
                    ->orWhere('ma_bat_dong_san', 'like', $kw);
            });
        }

        // Lọc theo dropdown
        if ($request->filled('nhu_cau')) $query->where('nhu_cau', $request->nhu_cau);
        if ($request->filled('loai_hinh')) $query->where('loai_hinh', $request->loai_hinh);
        if ($request->filled('trang_thai')) $query->where('trang_thai', $request->trang_thai);
        if ($request->filled('du_an_id')) $query->where('du_an_id', $request->du_an_id);

        // Sắp xếp
        $sort = $request->sapxep ?? 'moi_nhat';
        if ($sort === 'moi_nhat') {
            $query->orderByDesc('thoi_diem_dang')->latest();
        } elseif ($sort === 'gia_tang') {
            $query->orderByRaw('COALESCE(gia, gia_thue) ASC');
        } elseif ($sort === 'gia_giam') {
            $query->orderByRaw('COALESCE(gia, gia_thue) DESC');
        } elseif ($sort === 'luot_xem') {
            $query->orderByDesc('luot_xem');
        }

        $batDongSans = $query->paginate(15)->withQueryString();

        // Thống kê nhanh trên đầu trang
        $thongKe = [
            'tong'      => BatDongSan::count(),
            'con_hang'  => BatDongSan::where('trang_thai', 'con_hang')->count(),
            'dang_thue' => BatDongSan::where('trang_thai', 'dang_thue')->count(),
            'dat_coc'   => BatDongSan::where('trang_thai', 'dat_coc')->count(),
            'da_ban'    => BatDongSan::whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
        ];

        $duAns = DuAn::orderBy('ten_du_an')->get();

        return view('admin.bat-dong-san.index', compact('batDongSans', 'thongKe', 'duAns'));
    }

    // ── FORM TẠO MỚI ──
    public function create()
    {
        $duAns = DuAn::orderBy('ten_du_an')->get();
        $nhanViens = NhanVien::where('kich_hoat', true)->get();
        $chuNhas = ChuNha::orderBy('ho_ten')->get();
        $constants = $this->getConstants();

        return view('admin.bat-dong-san.create', compact('duAns', 'nhanViens', 'chuNhas', 'constants'));
    }

    // ── LƯU DỮ LIỆU TẠO MỚI ──
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Xử lý Checkbox & Mã BĐS
        $data['hien_thi'] = $request->has('hien_thi');
        $data['noi_bat']  = $request->has('noi_bat');
        $data['slug']     = Str::slug($data['tieu_de']) . '-' . time();
        $data['ma_bat_dong_san'] = 'BDS-' . strtoupper(Str::random(6));

        if (empty($data['thoi_diem_dang'])) {
            $data['thoi_diem_dang'] = now();
        }

        // Upload Ảnh Đại Diện
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('bat-dong-san', 'public');
        }

        // Upload Album Ảnh
        $album = [];
        if ($request->hasFile('album_anh')) {
            foreach ($request->file('album_anh') as $file) {
                $album[] = $file->store('bat-dong-san/album', 'public');
            }
        }
        $data['album_anh'] = $album;

        BatDongSan::create($data);

        return redirect()->route('nhanvien.admin.bat-dong-san.index')->with('success', '✅ Đã thêm Bất động sản mới!');
    }

    // ── FORM CHỈNH SỬA ──
    public function edit(BatDongSan $batDongSan)
    {
        $duAns = DuAn::orderBy('ten_du_an')->get();
        $nhanViens = NhanVien::where('kich_hoat', true)->get();
        $chuNhas = ChuNha::orderBy('ho_ten')->get();
        $constants = $this->getConstants();

        return view('admin.bat-dong-san.edit', compact('batDongSan', 'duAns', 'nhanViens', 'chuNhas', 'constants'));
    }

    // ── CẬP NHẬT DỮ LIỆU ──
    public function update(Request $request, BatDongSan $batDongSan)
    {
        $data = $this->validateData($request);

        $data['hien_thi'] = $request->has('hien_thi');
        $data['noi_bat']  = $request->has('noi_bat');
        $data['slug']     = Str::slug($data['tieu_de']) . '-' . $batDongSan->id;

        // Xử lý Ảnh Đại Diện
        if ($request->hasFile('hinh_anh')) {
            if ($batDongSan->hinh_anh) {
                Storage::disk('public')->delete($batDongSan->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('bat-dong-san', 'public');
        }

        // --- XỬ LÝ ALBUM ẢNH (Fix lỗi P1006 Intelephense) ---
        $albumCu = is_array($batDongSan->album_anh) ? $batDongSan->album_anh : [];
        $xoaAnh = $request->input('xoa_hinh_anh', []);

        // 1. Xóa ảnh cũ nếu người dùng đánh dấu X trên form
        if (is_array($xoaAnh) && count($xoaAnh) > 0) {
            foreach ($xoaAnh as $path) {
                Storage::disk('public')->delete($path);
                $albumCu = array_filter($albumCu, fn($p) => $p !== $path);
            }
        }

        // 2. Thêm ảnh mới
        $albumMoi = [];
        if ($request->hasFile('album_anh')) {
            foreach ($request->file('album_anh') as $file) {
                $albumMoi[] = $file->store('bat-dong-san/album', 'public');
            }
        }

        // 3. Gộp mảng ảnh
        $data['album_anh'] = array_values(array_merge($albumCu, $albumMoi));

        $batDongSan->update($data);

        return redirect()->route('nhanvien.admin.bat-dong-san.index')->with('success', '✅ Đã cập nhật thông tin Bất động sản!');
    }

    // ── XÓA BẤT ĐỘNG SẢN ──
    public function destroy(BatDongSan $batDongSan)
    {
        // Xóa ảnh đại diện
        if ($batDongSan->hinh_anh) {
            Storage::disk('public')->delete($batDongSan->hinh_anh);
        }

        // Xóa toàn bộ album
        $album = $batDongSan->album_anh;
        if (is_array($album)) {
            foreach ($album as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $batDongSan->delete();

        return redirect()->back()->with('success', '🗑️ Đã xóa Bất động sản khỏi hệ thống!');
    }

    // ════ CÁC HÀM AJAX ════

    // Bật/Tắt Hiển thị (Toggle)
    public function toggle(BatDongSan $batDongSan)
    {
        $batDongSan->update(['hien_thi' => !$batDongSan->hien_thi]);
        return response()->json(['ok' => true, 'hien_thi' => $batDongSan->hien_thi]);
    }

    // Đổi trạng thái trực tiếp ở ngoài bảng Index
    public function updateTrangThai(Request $request, BatDongSan $batDongSan)
    {
        $request->validate(['trang_thai' => 'required|string']);
        $batDongSan->update(['trang_thai' => $request->trang_thai]);
        return response()->json(['ok' => true]);
    }

    // Xóa từng ảnh riêng lẻ bằng nút X (AJAX)
    public function xoaAnh(Request $request, BatDongSan $batDongSan)
    {
        $path = $request->path;
        $album = is_array($batDongSan->album_anh) ? $batDongSan->album_anh : [];

        if (in_array($path, $album)) {
            Storage::disk('public')->delete($path);
            $album = array_filter($album, fn($p) => $p !== $path);
            $batDongSan->update(['album_anh' => array_values($album)]);
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false], 400);
    }

    // ── HÀM KIỂM TRA DỮ LIỆU ĐẦU VÀO (VALIDATION) ──
    private function validateData(Request $request)
    {
        return $request->validate([
            'tieu_de'                => 'required|string|max:255',
            'loai_hinh'              => 'required|string',
            'nhu_cau'                => 'required|in:ban,thue',
            'du_an_id'               => 'nullable|exists:du_an,id',
            'chu_nha_id'             => 'nullable|exists:chu_nha,id',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'toa'                    => 'nullable|string|max:50',
            'tang'                   => 'nullable|string|max:50',
            'ma_can'                 => 'nullable|string|max:50',
            'dien_tich'              => 'required|numeric|min:1',
            'so_phong_ngu'           => 'nullable|integer|min:0',
            'so_phong_tam'           => 'nullable|integer|min:0',
            'huong_cua'              => 'nullable|string',
            'huong_ban_cong'         => 'nullable|string',
            'noi_that'               => 'nullable|string',
            'phap_ly'                => 'nullable|string',
            'gia'                    => 'nullable|numeric|min:0',
            'phi_moi_gioi'           => 'nullable|numeric|min:0',
            'phi_sang_ten'           => 'nullable|numeric|min:0',
            'gia_thue'               => 'nullable|numeric|min:0',
            'thoi_gian_vao_thue'     => 'nullable|string',
            'hinh_thuc_thanh_toan'   => 'nullable|string',
            'mo_ta'                  => 'nullable|string',
            'seo_title'              => 'nullable|string|max:255',
            'seo_description'        => 'nullable|string|max:500',
            'seo_keywords'           => 'nullable|string|max:255',
            'hinh_anh'               => 'nullable|image|max:3072',
            'album_anh.*'            => 'nullable|image|max:3072',
            'trang_thai'             => 'required|string',
            'thu_tu_hien_thi'        => 'nullable|integer|min:0',
            'thoi_diem_dang'         => 'nullable|date',
        ]);
    }
}
