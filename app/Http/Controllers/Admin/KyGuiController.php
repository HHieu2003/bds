<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KyGui;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KyGuiController extends Controller
{
    // ── INDEX ──
    public function index(Request $request)
    {
        $query = KyGui::with(['khachHang', 'nhanVienPhuTrach'])
            ->withCount([]);

        // Tìm kiếm
        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ho_ten_chu_nha', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw)
                    ->orWhere('dia_chi', 'like', $kw)
                    ->orWhere('email', 'like', $kw);
            });
        }

        // Bộ lọc
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('loai_hinh')) {
            $query->where('loai_hinh', $request->loai_hinh);
        }
        if ($request->filled('nhu_cau')) {
            $query->where('nhu_cau', $request->nhu_cau);
        }
        if ($request->filled('nhan_vien_id')) {
            $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_id);
        }
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        $kyGuis = $query->latest()->paginate(15)->withQueryString();

        // Thống kê nhanh
        $thongKe = [
            'tong'        => KyGui::count(),
            'cho_duyet'   => KyGui::where('trang_thai', 'cho_duyet')->count(),
            'da_lien_he'  => KyGui::where('trang_thai', 'da_lien_he')->count(),
            'da_nhan'     => KyGui::where('trang_thai', 'da_nhan')->count(),
            'tu_choi'     => KyGui::where('trang_thai', 'tu_choi')->count(),
        ];

        $nhanViens = NhanVien::where('kich_hoat', true)
            ->whereIn('vai_tro', ['admin', 'nguon_hang'])
            ->orderBy('ho_ten')
            ->get();

        return view('admin.ky-gui.index', compact('kyGuis', 'thongKe', 'nhanViens'));
    }

    // ── SHOW ──
    public function show(KyGui $kyGui)
    {
        $kyGui->load(['khachHang', 'nhanVienPhuTrach']);

        $nhanViens = NhanVien::where('kich_hoat', true)
            ->whereIn('vai_tro', ['admin', 'nguon_hang'])
            ->orderBy('ho_ten')->get();

        return view('admin.ky-gui.show', compact('kyGui', 'nhanViens'));
    }

    // ── XỬ LÝ (duyệt / từ chối / phân công) ──
    public function xuLy(Request $request, KyGui $kyGui)
    {
        $request->validate([
            'trang_thai'            => 'required|in:da_lien_he,da_nhan,tu_choi',
            'phan_hoi_cua_admin'    => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
        ]);

        $kyGui->update([
            'trang_thai'                => $request->trang_thai,
            'phan_hoi_cua_admin'        => $request->phan_hoi_cua_admin,
            'nhan_vien_phu_trach_id'    => $request->nhan_vien_phu_trach_id
                ?? $kyGui->nhan_vien_phu_trach_id
                ?? Auth::guard('nhanvien')->id(),
            'thoi_diem_xu_ly'           => now(),
        ]);

        $labels = KyGui::TRANG_THAI;
        $label  = $labels[$request->trang_thai]['label'] ?? $request->trang_thai;

        return redirect()
            ->route('nhanvien.admin.ky-gui.show', $kyGui)
            ->with('success', "✅ Đã chuyển trạng thái sang <strong>{$label}</strong>!");
    }

    // ── PHÂN CÔNG NHANH (AJAX) ──
    public function phanCong(Request $request, KyGui $kyGui)
    {
        $request->validate([
            'nhan_vien_phu_trach_id' => 'required|exists:nhan_vien,id',
        ]);

        $kyGui->update(['nhan_vien_phu_trach_id' => $request->nhan_vien_phu_trach_id]);

        return response()->json(['ok' => true, 'ho_ten' => $kyGui->nhanVienPhuTrach?->ho_ten]);
    }

    // ── TẠO MỚI THỦ CÔNG (admin nhập thay khách) ──
    public function create()
    {
        return view('admin.ky-gui.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['nguon_ky_gui'] = $request->get('nguon_ky_gui', 'phone');
        $data['trang_thai']   = $request->get('trang_thai', 'cho_duyet');
        $data['hinh_anh_tham_khao'] = $this->uploadImages($request);

        KyGui::create($data);

        return redirect()
            ->route('nhanvien.admin.ky-gui.index')
            ->with('success', '✅ Đã tạo ký gửi thành công!');
    }

    // ── EDIT / UPDATE ──
    public function edit(KyGui $kyGui)
    {
        return view('admin.ky-gui.edit', compact('kyGui'));
    }

    public function update(Request $request, KyGui $kyGui)
    {
        $data = $this->validateData($request);

        // Xử lý hình ảnh: giữ cũ + thêm mới
        $hinhCu = $kyGui->hinh_anh_tham_khao ?? [];

        // Xóa ảnh đã chọn xóa
        if ($request->has('xoa_hinh_anh')) {
            foreach ($request->xoa_hinh_anh as $path) {
                Storage::disk('public')->delete($path);
                $hinhCu = array_filter($hinhCu, fn($p) => $p !== $path);
            }
        }

        $hinhMoi  = $this->uploadImages($request);
        $data['hinh_anh_tham_khao'] = array_values(array_merge($hinhCu, $hinhMoi));

        $kyGui->update($data);

        return redirect()
            ->route('nhanvien.admin.ky-gui.show', $kyGui)
            ->with('success', '✅ Đã cập nhật ký gửi!');
    }

    // ── DELETE ──
    public function destroy(KyGui $kyGui)
    {
        // Xóa ảnh tham khảo
        if ($kyGui->hinh_anh_tham_khao) {
            foreach ($kyGui->hinh_anh_tham_khao as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $kyGui->delete();

        return redirect()
            ->route('nhanvien.admin.ky-gui.index')
            ->with('success', '🗑️ Đã xóa yêu cầu ký gửi!');
    }

    // ══ PRIVATE ══
    private function validateData(Request $request): array
    {
        return $request->validate([
            'ho_ten_chu_nha'         => 'required|string|max:100',
            'so_dien_thoai'          => 'required|string|max:15',
            'email'                  => 'nullable|email|max:100',
            'loai_hinh'              => 'required|in:can_ho,nha_pho,biet_thu,dat_nen,shophouse',
            'nhu_cau'                => 'required|in:ban,thue',
            'dia_chi'                => 'nullable|string|max:255',
            'dien_tich'              => 'required|numeric|min:1',
            'huong_nha'              => 'nullable|string',
            'so_phong_ngu'           => 'nullable|integer|min:0|max:20',
            'so_phong_tam'           => 'nullable|integer|min:0|max:20',
            'noi_that'               => 'nullable|string',
            'gia_ban_mong_muon'      => 'nullable|numeric|min:0',
            'phap_ly'                => 'nullable|string',
            'gia_thue_mong_muon'     => 'nullable|numeric|min:0',
            'hinh_thuc_thanh_toan'   => 'nullable|string',
            'ghi_chu'                => 'nullable|string|max:2000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
        ]);
    }

    private function uploadImages(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('hinh_anh_tham_khao')) {
            foreach ($request->file('hinh_anh_tham_khao') as $file) {
                $paths[] = $file->store('ky-gui', 'public');
            }
        }
        return $paths;
    }
}
