<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KhachHangController extends Controller
{
    const NGUON = [
        'website' => ['label' => 'Website',     'icon' => 'fas fa-globe',       'color' => '#2d6a9f'],
        'chat'    => ['label' => 'Chat / Zalo',  'icon' => 'fas fa-comments',    'color' => '#27ae60'],
        'lien_he' => ['label' => 'Liên hệ trực tiếp', 'icon' => 'fas fa-phone', 'color' => '#e67e22'],
        'ky_gui'  => ['label' => 'Ký gửi',       'icon' => 'fas fa-file-contract', 'color' => '#8e44ad'],
        'sale'    => ['label' => 'Sale giới thiệu', 'icon' => 'fas fa-user-tie',  'color' => '#c0392b'],
    ];

    const MUC_DO = [
        'nong' => ['label' => '🔥 Nóng', 'color' => '#e74c3c', 'bg' => '#fff0f0'],
        'am'   => ['label' => '🌤 Ấm',   'color' => '#e67e22', 'bg' => '#fff8f0'],
        'lanh' => ['label' => '❄️ Lạnh', 'color' => '#95a5a6', 'bg' => '#f5f5f5'],
    ];

    // ──────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────
    public function index(Request $request)
    {

        $query = KhachHang::with('nhanVienPhuTrach');

        // Tìm kiếm
        if ($request->filled('tukhoa')) {
            $kw = '%' . $request->tukhoa . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ho_ten', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw)
                    ->orWhere('email', 'like', $kw);
            });
        }

        // Lọc
        if ($request->filled('nguon'))       $query->where('nguon_khach_hang',   $request->nguon);
        if ($request->filled('muc_do'))      $query->where('muc_do_tiem_nang',   $request->muc_do);
        if ($request->filled('kich_hoat'))   $query->where('kich_hoat',          $request->kich_hoat);
        if ($request->filled('nhan_vien_id'))
            $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_id);

        if ($request->filled('xac_thuc')) {
            if ($request->xac_thuc === 'co') {
                $query->where(function ($q) {
                    $q->whereNotNull('sdt_xac_thuc_at')
                        ->orWhereNotNull('email_xac_thuc_at');
                });
            } else {
                $query->whereNull('sdt_xac_thuc_at')->whereNull('email_xac_thuc_at');
            }
        }

        // Sắp xếp
        match ($request->get('sapxep', 'moi_nhat')) {
            'cu_nhat'     => $query->oldest(),
            'ten_az'      => $query->orderBy('ho_ten'),
            'lien_he_gan' => $query->orderByDesc('lien_he_cuoi_at'),
            'lich_nhieu'  => $query->orderByDesc('lich_hens_count'),
            default       => $query->orderByDesc('created_at'),
        };

        $khachHangs = $query->paginate(20)->withQueryString();
        $nhanViens  = NhanVien::orderBy('ho_ten')->get();

        // Thống kê
        $thongKe = [
            'tong'      => KhachHang::count(),
            'nong'      => KhachHang::where('muc_do_tiem_nang', 'nong')->count(),
            'am'        => KhachHang::where('muc_do_tiem_nang', 'am')->count(),
            'lanh'      => KhachHang::where('muc_do_tiem_nang', 'lanh')->count(),
            'kich_hoat' => KhachHang::where('kich_hoat', true)->count(),
        ];

        return view('admin.khach-hang.index', compact(
            'khachHangs',
            'nhanViens',
            'thongKe'
        ));
    }

    // ──────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────
    public function create()
    {
        return view('admin.khach-hang.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['kich_hoat'] = $request->boolean('kich_hoat', true);

        KhachHang::create($data);

        return redirect()
            ->route('nhanvien.admin.khach-hang.index')
            ->with('success', '✅ Đã thêm khách hàng <strong>' . ($data['ho_ten'] ?? $data['so_dien_thoai'] ?? '') . '</strong>!');
    }

    // ──────────────────────────────────────
    // SHOW (chi tiết)
    // ──────────────────────────────────────
    public function show(KhachHang $khachHang)
    {

        $khachHang->load(['nhanVienPhuTrach']);

        return view('admin.khach-hang.show', compact('khachHang'));
    }

    // ──────────────────────────────────────
    // EDIT / UPDATE
    // ──────────────────────────────────────
    public function edit(KhachHang $khachHang)
    {
        return view(
            'admin.khach-hang.edit',
            array_merge($this->formData(), compact('khachHang'))
        );
    }

    public function update(Request $request, KhachHang $khachHang)
    {
        $data = $this->validateRequest($request, $khachHang->id);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['kich_hoat'] = $request->boolean('kich_hoat');

        $khachHang->update($data);

        return redirect()
            ->route('nhanvien.admin.khach-hang.index')
            ->with('success', '✅ Đã cập nhật khách hàng <strong>' . $khachHang->ten_hien_thi . '</strong>!');
    }

    // ──────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────
    public function destroy(KhachHang $khachHang)
    {
        $ten = $khachHang->ten_hien_thi;
        $khachHang->delete();

        return redirect()
            ->route('nhanvien.admin.khach-hang.index')
            ->with('success', '🗑️ Đã xóa khách hàng <strong>' . $ten . '</strong>!');
    }

    // ──────────────────────────────────────
    // AJAX: Toggle kích hoạt
    // ──────────────────────────────────────
    public function toggleKichHoat(KhachHang $khachHang)
    {
        $khachHang->update(['kich_hoat' => !$khachHang->kich_hoat]);
        return response()->json(['ok' => true, 'kich_hoat' => $khachHang->kich_hoat]);
    }

    // ──────────────────────────────────────
    // AJAX: Đổi mức độ tiềm năng
    // ──────────────────────────────────────
    public function doiMucDo(Request $request, KhachHang $khachHang)
    {
        $request->validate(['muc_do' => 'required|in:nong,am,lanh']);
        $khachHang->update(['muc_do_tiem_nang' => $request->muc_do]);
        return response()->json(['ok' => true]);
    }

    // ──────────────────────────────────────
    // AJAX: Cập nhật ghi chú nội bộ
    // ──────────────────────────────────────
    public function capNhatGhiChu(Request $request, KhachHang $khachHang)
    {
        $request->validate(['ghi_chu_noi_bo' => 'nullable|string|max:2000']);
        $khachHang->update(['ghi_chu_noi_bo' => $request->ghi_chu_noi_bo]);
        return response()->json(['ok' => true]);
    }

    // ──────────────────────────────────────
    // AJAX: Cập nhật lần liên hệ cuối
    // ──────────────────────────────────────
    public function capNhatLienHe(KhachHang $khachHang)
    {
        $khachHang->update(['lien_he_cuoi_at' => now()]);
        return response()->json(['ok' => true, 'time' => now()->format('d/m/Y H:i')]);
    }

    // ══════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════
    private function formData(): array
    {
        return [
            'nhanViens' => NhanVien::orderBy('ho_ten')->get(),
            'constants' => [
                'nguon'   => self::NGUON,
                'muc_do'  => self::MUC_DO,
            ],
        ];
    }

    private function validateRequest(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'ho_ten'                 => 'nullable|string|max:100',
            'so_dien_thoai'          => 'nullable|string|max:20|unique:khach_hang,so_dien_thoai' . ($ignoreId ? ',' . $ignoreId : ''),
            'email'                  => 'nullable|email|max:150|unique:khach_hang,email' . ($ignoreId ? ',' . $ignoreId : ''),
            'password'               => 'nullable|string|min:6|max:50',
            'nguon_khach_hang'       => 'required|in:website,chat,lien_he,ky_gui,sale',
            'muc_do_tiem_nang'       => 'required|in:nong,am,lanh',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'ghi_chu_noi_bo'         => 'nullable|string|max:2000',
            'kich_hoat'              => 'nullable|boolean',
            'lien_he_cuoi_at'        => 'nullable|date',
        ]);
    }
}
