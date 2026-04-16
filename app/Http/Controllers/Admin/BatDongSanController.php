<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\ChuNha;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatDongSanController extends Controller
{
    private const TRANG_THAI_HOP_LE = ['con_hang', 'dat_coc', 'da_ban', 'dang_thue', 'da_thue', 'tam_an'];

    private function redirectVeDanhSach(Request $request)
    {
        $redirectTo = (string) $request->input('redirect_to', '');
        $base = rtrim(url('/'), '/');

        if (
            $redirectTo !== ''
            && str_starts_with($redirectTo, $base)
            && str_contains($redirectTo, '/nhan-vien/admin/bat-dong-san')
        ) {
            return redirect()->to($redirectTo);
        }

        return redirect()->route('nhanvien.admin.bat-dong-san.index');
    }

    private function nhanVienDangNhap(): ?NhanVien
    {
        return Auth::guard('nhanvien')->user();
    }

    private function chongSaleQuanLyTonKho(): void
    {
        $nhanVien = $this->nhanVienDangNhap();
        if ($nhanVien && $nhanVien->isSale()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
    }

    // ── Lấy tất cả khu_vuc_id bao gồm khu vực cha và con ──
    private function getKhuVucIdsWithChildren($khuVucId): array
    {
        $khuVuc = KhuVuc::find($khuVucId);
        if (!$khuVuc) {
            return [];
        }

        $ids = [$khuVucId];

        // Lấy tất cả khu vực con
        $children = $khuVuc->con()->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            // Nếu con có con nữa, lấy tiếp (recursive)
            $grandChildren = $child->con()->get();
            foreach ($grandChildren as $grandChild) {
                $ids[] = $grandChild->id;
            }
        }

        return $ids;
    }

    // ── LẤY DỮ LIỆU CONSTANTS CHO FORM ──
    private function getConstants(): array
    {
        return [
            'loai_hinh' => ['can_ho' => 'Căn hộ chung cư', 'nha_pho' => 'Nhà phố', 'biet_thu' => 'Biệt thự', 'dat_nen' => 'Đất nền', 'shophouse' => 'Shophouse'],
            'nhu_cau'   => ['ban' => 'Bán', 'thue' => 'Cho thuê'],
            'noi_that'  => ['nguyen_ban' => 'Nguyên bản', 'co_ban' => 'Cơ bản', 'full' => 'Full nội thất', 'cao_cap' => 'Cao cấp'],
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

        if ($request->filled('nhu_cau')) {
            $query->where('nhu_cau', $request->nhu_cau);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('du_an_id')) $query->where('du_an_id', $request->du_an_id);
        if ($request->filled('khu_vuc_id')) {
            $khuVucIds = $this->getKhuVucIdsWithChildren($request->khu_vuc_id);
            $query->whereHas('duAn', function ($q) use ($khuVucIds) {
                $q->whereIn('khu_vuc_id', $khuVucIds);
            });
        }

        if ($request->filled('toa')) {
            $query->where('toa', $request->toa);
        }

        if ($request->filled('so_phong_ngu') || $request->input('so_phong_ngu') === '0') {
            $query->where('so_phong_ngu', (string) $request->input('so_phong_ngu'));
        }

        if ($request->filled('noi_that')) {
            $query->where('noi_that', $request->noi_that);
        }

        $giaTu = $request->filled('gia_tu') ? (float) str_replace(',', '', (string) $request->gia_tu) : null;
        $giaDen = $request->filled('gia_den') ? (float) str_replace(',', '', (string) $request->gia_den) : null;

        if ($giaTu !== null) {
            $query->whereRaw('COALESCE(gia, gia_thue) >= ?', [$giaTu]);
        }

        if ($giaDen !== null) {
            $query->whereRaw('COALESCE(gia, gia_thue) <= ?', [$giaDen]);
        }

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

        // Tính thống kê theo filter (trước khi paginate)
        $thongKe = [
            'tong'      => (clone $query)->count(),
            'con_hang'  => (clone $query)->where('trang_thai', 'con_hang')->count(),
            'dang_thue' => (clone $query)->where('trang_thai', 'dang_thue')->count(),
            'dat_coc'   => (clone $query)->where('trang_thai', 'dat_coc')->count(),
            'da_ban'    => (clone $query)->whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
        ];

        $batDongSans = $query->paginate(15)->withQueryString();

        $duAns = DuAn::query()
            ->when($request->filled('khu_vuc_id'), function ($q) use ($request) {
                $khuVucIds = $this->getKhuVucIdsWithChildren($request->khu_vuc_id);
                $q->whereIn('khu_vuc_id', $khuVucIds);
            })
            ->orderBy('ten_du_an')
            ->get();

        $khuVucs = KhuVuc::query()
            ->where('hien_thi', true)
            ->orderBy('ten_khu_vuc')
            ->get();

        $toaOptions = collect();
        if ($request->filled('du_an_id')) {
            $toaOptions = BatDongSan::query()
                ->where('du_an_id', $request->du_an_id)
                ->whereNotNull('toa')
                ->where('toa', '!=', '')
                ->pluck('toa')
                ->map(fn($toa) => trim((string) $toa))
                ->filter()
                ->unique()
                ->sort()
                ->values();
        }

        // Lấy danh sách phòng ngủ độc nhất từ BDS
        $soPhongNguOptions = BatDongSan::query()
            ->whereNotNull('so_phong_ngu')
            ->distinct('so_phong_ngu')
            ->pluck('so_phong_ngu')
            ->map(fn($pn) => (string) $pn)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $noiThatOptions = $this->getConstants()['noi_that'];

        return view('admin.bat-dong-san.index', compact('batDongSans', 'thongKe', 'duAns', 'khuVucs', 'toaOptions', 'soPhongNguOptions', 'noiThatOptions'));
    }

    // ── FORM TẠO MỚI ──
    public function create(Request $request)
    {
        $this->chongSaleQuanLyTonKho();

        $duAns = DuAn::orderBy('ten_du_an')->get();
        $nhanViens = NhanVien::where('kich_hoat', true)->get();
        $chuNhas = ChuNha::orderBy('ho_ten')->get();
        $constants = $this->getConstants();
        $defaultNhanVienPhuTrachId = Auth::guard('nhanvien')->id();
        $prefillNhuCau = in_array($request->query('nhu_cau'), ['ban', 'thue'], true)
            ? $request->query('nhu_cau')
            : null;
        $prefillDuAnId = $request->filled('du_an_id') && $duAns->contains('id', (int) $request->query('du_an_id'))
            ? (int) $request->query('du_an_id')
            : null;

        return view('admin.bat-dong-san.create', compact(
            'duAns',
            'nhanViens',
            'chuNhas',
            'constants',
            'defaultNhanVienPhuTrachId',
            'prefillNhuCau',
            'prefillDuAnId'
        ));
    }

    // ── LƯU DỮ LIỆU TẠO MỚI ──
    public function store(Request $request)
    {
        $this->chongSaleQuanLyTonKho();

        $data = $this->validateData($request);

        // Xử lý Checkbox & Mã BĐS
        $data['hien_thi'] = $request->has('hien_thi');
        $data['noi_bat']  = $request->has('noi_bat');
        $data['gui_mail_canh_bao_gia'] = $request->has('gui_mail_canh_bao_gia');
        $data['nhan_vien_phu_trach_id'] = Auth::guard('nhanvien')->id();
        $data['slug']     = Str::slug($data['tieu_de']) . '-' . time();
        $data['ma_bat_dong_san'] = 'BDS-' . strtoupper(Str::random(6));

        // Chỉ "còn hàng" mới được phép hiển thị ra ngoài.
        if (($data['trang_thai'] ?? null) !== 'con_hang') {
            $data['hien_thi'] = false;
        }

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

        return $this->redirectVeDanhSach($request)->with('success', '✅ Đã thêm Bất động sản mới!');
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
        $nhanVien = $this->nhanVienDangNhap();
        if ($nhanVien && $nhanVien->isSale()) {
            $data = $request->validate([
                'tieu_de' => 'required|string|max:255',
                'mo_ta' => 'nullable|string',
            ]);

            $data['slug'] = Str::slug($data['tieu_de']) . '-' . $batDongSan->id;
            $batDongSan->update($data);

            return $this->redirectVeDanhSach($request)->with('success', '✅ Đã cập nhật thông tin Bất động sản!');
        }

        $data = $this->validateData($request);

        $data['hien_thi'] = $request->has('hien_thi');
        $data['noi_bat']  = $request->has('noi_bat');
        $data['gui_mail_canh_bao_gia'] = $request->has('gui_mail_canh_bao_gia');
        $data['slug']     = Str::slug($data['tieu_de']) . '-' . $batDongSan->id;

        // Chỉ "còn hàng" mới được phép hiển thị ra ngoài.
        if (($data['trang_thai'] ?? null) !== 'con_hang') {
            $data['hien_thi'] = false;
        }

        // Xử lý Ảnh Đại Diện
        if ($request->hasFile('hinh_anh')) {
            if ($batDongSan->hinh_anh) {
                Storage::disk('public')->delete($batDongSan->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('bat-dong-san', 'public');
        }

        // --- XỬ LÝ ALBUM ẢNH (Fix lỗi P1006 Intelephense) ---
        $albumCu = $this->normalizeAlbumAnh($batDongSan->album_anh);
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

        return $this->redirectVeDanhSach($request)->with('success', '✅ Đã cập nhật thông tin Bất động sản!');
    }

    // ── XÓA BẤT ĐỘNG SẢN ──
    public function destroy(Request $request, BatDongSan $batDongSan)
    {
        $this->chongSaleQuanLyTonKho();

        // Xóa ảnh đại diện
        if ($batDongSan->hinh_anh) {
            Storage::disk('public')->delete($batDongSan->hinh_anh);
        }

        // Xóa toàn bộ album
        $album = $this->normalizeAlbumAnh($batDongSan->album_anh);
        if (!empty($album)) {
            foreach ($album as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $batDongSan->delete();

        return $this->redirectVeDanhSach($request)->with('success', '🗑️ Đã xóa Bất động sản khỏi hệ thống!');
    }

    // ════ CÁC HÀM AJAX ════

    // Bật/Tắt Hiển thị (Toggle)
    public function toggle(BatDongSan $batDongSan)
    {
        $this->chongSaleQuanLyTonKho();

        if ($batDongSan->trang_thai !== 'con_hang') {
            if ($batDongSan->hien_thi) {
                $batDongSan->update(['hien_thi' => false]);
            }

            return response()->json([
                'ok' => false,
                'hien_thi' => false,
                'message' => 'Chỉ bất động sản còn hàng mới được bật hiển thị.'
            ], 422);
        }

        $batDongSan->update(['hien_thi' => !$batDongSan->hien_thi]);
        return response()->json(['ok' => true, 'hien_thi' => $batDongSan->hien_thi]);
    }

    // Alias cho route nhóm sale (giữ tương thích tên method cũ)
    public function toggleHienThi(BatDongSan $batDongSan)
    {
        return $this->toggle($batDongSan);
    }

    // Đổi trạng thái trực tiếp ở ngoài bảng Index
    public function updateTrangThai(Request $request, BatDongSan $batDongSan)
    {
        $this->chongSaleQuanLyTonKho();

        $request->validate(['trang_thai' => 'required|in:' . implode(',', self::TRANG_THAI_HOP_LE)]);

        $payload = ['trang_thai' => $request->trang_thai];
        if ($request->trang_thai !== 'con_hang') {
            $payload['hien_thi'] = false;
        }

        $batDongSan->update($payload);

        return response()->json([
            'ok' => true,
            'hien_thi' => $batDongSan->hien_thi,
            'trang_thai' => $batDongSan->trang_thai,
        ]);
    }

    // Alias cho route nhóm sale (giữ tương thích tên method cũ)
    public function doiTrangThai(Request $request, BatDongSan $batDongSan)
    {
        return $this->updateTrangThai($request, $batDongSan);
    }

    // Xóa từng ảnh riêng lẻ bằng nút X (AJAX)
    public function xoaAnh(Request $request, BatDongSan $batDongSan)
    {
        $this->chongSaleQuanLyTonKho();

        $path = $request->path;
        $album = $this->normalizeAlbumAnh($batDongSan->album_anh);

        if (in_array($path, $album)) {
            Storage::disk('public')->delete($path);
            $album = array_filter($album, fn($p) => $p !== $path);
            $batDongSan->update(['album_anh' => array_values($album)]);
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false], 400);
    }

    private function normalizeAlbumAnh(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($decoded) && trim($decoded) !== '') {
            $decodedNested = json_decode($decoded, true);
            if (is_array($decodedNested)) {
                return $decodedNested;
            }
        }

        return [];
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
            'so_phong_ngu'           => 'nullable|string|max:100',
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
