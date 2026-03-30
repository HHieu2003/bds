<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BatDongSanController extends Controller
{
    // ── Hằng số dùng toàn bộ module ──
    const LOAI_HINH = [
        'can_ho'    => 'Căn hộ chung cư',
        'nha_pho'   => 'Nhà phố',
        'biet_thu'  => 'Biệt thự',
        'dat_nen'   => 'Đất nền',
        'shophouse' => 'Shophouse',
    ];

    const NHU_CAU = [
        'ban'  => 'Bán',
        'thue' => 'Cho thuê',
    ];

    const NOI_THAT = [
        'co_ban'     => 'Cơ bản',
        'full'       => 'Full nội thất',
        'cao_cap'    => 'Cao cấp',
        'nguyen_ban' => 'Nguyên bản',
    ];

    const PHAP_LY = [
        'so_hong'  => 'Sổ hồng',
        'so_do'    => 'Sổ đỏ',
        'hop_dong' => 'Hợp đồng mua bán',
        'chua_co'  => 'Chưa có sổ',
    ];

    const TRANG_THAI = [
        'con_hang'  => ['label' => 'Còn hàng',       'color' => '#27ae60', 'bg' => '#e8f8f0'],
        'dat_coc'   => ['label' => 'Đặt cọc',        'color' => '#e67e22', 'bg' => '#fff3e0'],
        'da_ban'    => ['label' => 'Đã bán',          'color' => '#e74c3c', 'bg' => '#ffeaea'],
        'dang_thue' => ['label' => 'Đang cho thuê',  'color' => '#2d6a9f', 'bg' => '#e8f4fd'],
        'da_thue'   => ['label' => 'Đã cho thuê',    'color' => '#8e44ad', 'bg' => '#f5eeff'],
        'tam_an'    => ['label' => 'Tạm ẩn',         'color' => '#95a5a6', 'bg' => '#f5f5f5'],
    ];

    const THOI_GIAN_VAO_THUE = [
        'vao_luon'   => 'Vào ở ngay',
        'thang_1'    => 'Sau 1 tháng',
        'thang_3'    => 'Sau 3 tháng',
        'sau_ngay_X' => 'Theo thỏa thuận',
    ];

    const HINH_THUC_TT = [
        'tien_mat'     => 'Tiền mặt',
        'chuyen_khoan' => 'Chuyển khoản',
        '3_coc_1'      => '3 tháng đặt cọc 1 tháng',
        '3_coc_10tr'   => '3 tháng đặt cọc 10 triệu',
    ];

    // ──────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────
    public function index(Request $request)
    {
        $query = BatDongSan::with(['duAn.khuVuc', 'nhanVienPhuTrach'])
            ->withCount('lichHens');

        if ($request->filled('tukhoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('tieu_de', 'like', '%' . $request->tukhoa . '%')
                    ->orWhere('ma_bat_dong_san', 'like', '%' . $request->tukhoa . '%')
                    ->orWhere('ma_can', 'like', '%' . $request->tukhoa . '%');
            });
        }

        if ($request->filled('nhu_cau'))    $query->where('nhu_cau', $request->nhu_cau);
        if ($request->filled('loai_hinh'))  $query->where('loai_hinh', $request->loai_hinh);
        if ($request->filled('trang_thai')) $query->where('trang_thai', $request->trang_thai);
        if ($request->filled('hien_thi'))   $query->where('hien_thi', $request->hien_thi);

        if ($request->filled('du_an_id')) {
            $query->where('du_an_id', $request->du_an_id);
        }

        if ($request->filled('khu_vuc_id')) {
            $query->whereHas(
                'duAn',
                fn($q) =>
                $q->where('khu_vuc_id', $request->khu_vuc_id)
            );
        }

        // Sắp xếp
        match ($request->get('sapxep', 'moi_nhat')) {
            'cu_nhat'   => $query->oldest(),
            'gia_tang'  => $query->orderByRaw('COALESCE(gia, gia_thue) ASC'),
            'gia_giam'  => $query->orderByRaw('COALESCE(gia, gia_thue) DESC'),
            'luot_xem'  => $query->orderByDesc('luot_xem'),
            default     => $query->orderByDesc('created_at'),
        };

        $batDongSans = $query->paginate(15)->withQueryString();
        $duAns       = DuAn::where('hien_thi', 1)->orderBy('ten_du_an')->get();
        $khuVucs     = KhuVuc::whereNull('deleted_at')->orderBy('ten_khu_vuc')->get();
        $nhanViens = NhanVien::orderBy('ho_ten')->get();

        // Thống kê nhanh
        $thongKe = [
            'tong'       => BatDongSan::count(),
            'con_hang'   => BatDongSan::where('trang_thai', 'con_hang')->count(),
            'dang_thue'  => BatDongSan::where('trang_thai', 'dang_thue')->count(),
            'dat_coc'    => BatDongSan::where('trang_thai', 'dat_coc')->count(),
            'da_ban'     => BatDongSan::whereIn('trang_thai', ['da_ban', 'da_thue'])->count(),
        ];

        return view('admin.bat-dong-san.index', compact(
            'batDongSans',
            'duAns',
            'khuVucs',
            'nhanViens',
            'thongKe'
        ));
    }

    // ──────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────
    public function create()
    {
        return view('admin.bat-dong-san.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        $data['slug']            = $this->taoSlug($data['tieu_de']);
        $data['ma_bat_dong_san'] = $this->taoMaBDS();
        $data['hien_thi']        = $request->boolean('hien_thi');
        $data['noi_bat']         = $request->boolean('noi_bat');
        $data['thoi_diem_dang']  = $data['thoi_diem_dang'] ?? now();

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')
                ->store('bat-dong-san', 'public');
        }

        if ($request->hasFile('album_anh')) {
            $album = [];
            foreach ($request->file('album_anh') as $file) {
                $album[] = $file->store('bat-dong-san/album', 'public');
            }
            $data['album_anh'] = $album;
        }

        BatDongSan::create($data);

        return redirect()
            ->route('nhanvien.admin.bat-dong-san.index')
            ->with('success', '✅ Đã thêm bất động sản <strong>' . $data['tieu_de'] . '</strong>!');
    }

    // ──────────────────────────────────────
    // EDIT / UPDATE
    // ──────────────────────────────────────
    public function edit(BatDongSan $batDongSan)
    {
        return view(
            'admin.bat-dong-san.edit',
            array_merge($this->formData(), compact('batDongSan'))
        );
    }

    public function update(Request $request, BatDongSan $batDongSan)
    {
        $data = $this->validateRequest($request, $batDongSan->id);
        $data['hien_thi'] = $request->boolean('hien_thi');
        $data['noi_bat']  = $request->boolean('noi_bat');

        if ($request->hasFile('hinh_anh')) {
            if ($batDongSan->hinh_anh) Storage::disk('public')->delete($batDongSan->hinh_anh);
            $data['hinh_anh'] = $request->file('hinh_anh')
                ->store('bat-dong-san', 'public');
        }

        if ($request->hasFile('album_anh')) {
            $album = $batDongSan->album_anh ?? [];
            foreach ($request->file('album_anh') as $file) {
                $album[] = $file->store('bat-dong-san/album', 'public');
            }
            $data['album_anh'] = $album;
        }

        $batDongSan->update($data);

        return redirect()
            ->route('nhanvien.admin.bat-dong-san.index')
            ->with('success', '✅ Đã cập nhật <strong>' . $batDongSan->tieu_de . '</strong>!');
    }

    // ──────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────
    public function destroy(BatDongSan $batDongSan)
    {
        $nhanVien = auth('nhanvien')->user();
        $vaiTro = $nhanVien
            ? \App\Models\NhanVien::normalizeVaiTro((string) ($nhanVien->vai_tro ?? ''))
            : null;
        $canDelete = in_array($vaiTro, ['admin', 'nguon_hang'], true);

        if (! $canDelete) {
            if (request()->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Bạn không có quyền xóa bất động sản.',
                ], 403);
            }

            return redirect()
                ->route('nhanvien.admin.bat-dong-san.index')
                ->with('error', '❌ Nhân viên sale không có quyền xóa bất động sản. Chỉ Admin và Nguồn hàng được phép.');
        }

        if ($batDongSan->hinh_anh) {
            Storage::disk('public')->delete($batDongSan->hinh_anh);
        }
        foreach ($batDongSan->album_anh ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }
        $batDongSan->delete();

        return redirect()
            ->route('nhanvien.admin.bat-dong-san.index')
            ->with('success', '🗑️ Đã xóa bất động sản!');
    }

    // ──────────────────────────────────────
    // AJAX: Toggle hiển thị
    // ──────────────────────────────────────
    public function toggleHienThi(BatDongSan $batDongSan)
    {
        $batDongSan->update(['hien_thi' => !$batDongSan->hien_thi]);
        return response()->json([
            'ok'       => true,
            'hien_thi' => $batDongSan->hien_thi,
        ]);
    }

    // ──────────────────────────────────────
    // AJAX: Đổi trạng thái
    // ──────────────────────────────────────
    public function doiTrangThai(Request $request, BatDongSan $batDongSan)
    {
        $request->validate([
            'trang_thai' => 'required|in:con_hang,dat_coc,da_ban,dang_thue,da_thue,tam_an',
        ]);
        $batDongSan->update(['trang_thai' => $request->trang_thai]);
        return response()->json(['ok' => true, 'trang_thai' => $request->trang_thai]);
    }

    // ──────────────────────────────────────
    // AJAX: Xóa ảnh trong album
    // ──────────────────────────────────────
    public function xoaAnh(Request $request, BatDongSan $batDongSan)
    {
        $path  = $request->input('path');
        $album = $batDongSan->album_anh ?? [];

        if (($key = array_search($path, $album)) !== false) {
            Storage::disk('public')->delete($path);
            unset($album[$key]);
            $batDongSan->update(['album_anh' => array_values($album)]);
        }

        return response()->json(['ok' => true]);
    }

    // ══════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════
    private function formData(): array
    {
        return [
            'duAns'     => DuAn::where('hien_thi', 1)->orderBy('ten_du_an')->get(),
            'nhanViens' => NhanVien::orderBy('ho_ten')->get(),
            'constants'  => [
                'loai_hinh'          => self::LOAI_HINH,
                'nhu_cau'            => self::NHU_CAU,
                'noi_that'           => self::NOI_THAT,
                'phap_ly'            => self::PHAP_LY,
                'trang_thai'         => self::TRANG_THAI,
                'thoi_gian_vao_thue' => self::THOI_GIAN_VAO_THUE,
                'hinh_thuc_tt'       => self::HINH_THUC_TT,
            ],
        ];
    }

    private function validateRequest(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'tieu_de'                => 'required|string|max:255',
            'du_an_id'               => 'nullable|exists:du_an,id',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'loai_hinh'              => 'required|in:can_ho,nha_pho,biet_thu,dat_nen,shophouse',
            'nhu_cau'                => 'required|in:ban,thue',
            'ma_can'                 => 'nullable|string|max:50',
            'toa'                    => 'nullable|string|max:50',
            'tang'                   => 'nullable|string|max:20',
            'dien_tich'              => 'required|numeric|min:1|max:99999',
            'so_phong_ngu'           => 'nullable|integer|min:0|max:20',
            'huong_cua'              => 'nullable|string|max:30',
            'huong_ban_cong'         => 'nullable|string|max:30',
            'noi_that'               => 'nullable|in:co_ban,full,cao_cap,nguyen_ban',
            'mo_ta'                  => 'nullable|string',
            // Bán
            'gia'                    => 'nullable|numeric|min:0',
            'phi_moi_gioi'           => 'nullable|numeric|min:0',
            'phi_sang_ten'           => 'nullable|numeric|min:0',
            'phap_ly'                => 'nullable|in:so_hong,so_do,hop_dong,chua_co',
            // Thuê
            'gia_thue'               => 'nullable|numeric|min:0',
            'thoi_gian_vao_thue'     => 'nullable|string|max:30',
            'hinh_thuc_thanh_toan'   => 'nullable|string|max:30',
            // Media
            'hinh_anh'               => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'album_anh.*'            => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            // Cài đặt
            'noi_bat'                => 'nullable|boolean',
            'hien_thi'               => 'nullable|boolean',
            'trang_thai'             => 'nullable|in:con_hang,dat_coc,da_ban,dang_thue,da_thue,tam_an',
            'thu_tu_hien_thi'        => 'nullable|integer|min:0|max:9999',
            'thoi_diem_dang'         => 'nullable|date',
            // SEO
            'seo_title'              => 'nullable|string|max:255',
            'seo_description'        => 'nullable|string|max:500',
            'seo_keywords'           => 'nullable|string|max:255',
        ]);
    }

    private function taoSlug(string $tieude): string
    {
        $slug = Str::slug($tieude);
        $base = $slug;
        $i    = 1;
        while (BatDongSan::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function taoMaBDS(): string
    {
        $prefix = 'BDS';
        $year   = date('y');    // 26
        $last   = BatDongSan::withTrashed()
            ->where('ma_bat_dong_san', 'like', "{$prefix}{$year}%")
            ->orderByDesc('id')->first();
        $seq = $last ? (intval(substr($last->ma_bat_dong_san, -4)) + 1) : 1;
        return $prefix . $year . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
