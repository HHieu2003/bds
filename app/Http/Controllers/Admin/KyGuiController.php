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
    public function index(Request $request)
    {
        $query = KyGui::with(['khachHang', 'nhanVienPhuTrach'])->withCount([]);

        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(fn($q) => $q->where('ho_ten_chu_nha', 'like', $kw)
                ->orWhere('so_dien_thoai', 'like', $kw)
                ->orWhere('dia_chi', 'like', $kw)
                ->orWhere('du_an', 'like', $kw)
                ->orWhere('ma_can', 'like', $kw));
        }

        if ($request->filled('trang_thai')) $query->where('trang_thai', $request->trang_thai);
        if ($request->filled('loai_hinh')) $query->where('loai_hinh', $request->loai_hinh);
        if ($request->filled('nhu_cau')) $query->where('nhu_cau', $request->nhu_cau);
        if ($request->filled('nhan_vien_id')) $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_id);
        if ($request->filled('tu_ngay')) $query->whereDate('created_at', '>=', $request->tu_ngay);
        if ($request->filled('den_ngay')) $query->whereDate('created_at', '<=', $request->den_ngay);

        $kyGuis = $query->latest()->paginate(15)->withQueryString();

        $thongKe = [
            'tong'           => KyGui::count(),
            'cho_duyet'      => KyGui::where('trang_thai', 'cho_duyet')->count(),
            'dang_tham_dinh' => KyGui::where('trang_thai', 'dang_tham_dinh')->count(),
            'da_duyet'       => KyGui::where('trang_thai', 'da_duyet')->count(),
            'tu_choi'        => KyGui::where('trang_thai', 'tu_choi')->count(),
        ];

        // Chỉ lấy nhân sự Nguồn hàng (và admin) để xử lý ký gửi
        $nhanViens = NhanVien::where('kich_hoat', true)->whereIn('vai_tro', ['admin', 'nguon_hang'])->orderBy('ho_ten')->get();

        return view('admin.ky-gui.index', compact('kyGuis', 'thongKe', 'nhanViens'));
    }

    public function show(KyGui $kyGui)
    {
        $kyGui->load(['khachHang', 'nhanVienPhuTrach']);
        $nhanViens = NhanVien::where('kich_hoat', true)->whereIn('vai_tro', ['admin', 'nguon_hang'])->orderBy('ho_ten')->get();
        return view('admin.ky-gui.show', compact('kyGui', 'nhanViens'));
    }

    // AJAX cập nhật trạng thái
    public function xuLy(Request $request, KyGui $kyGui)
    {
        $request->validate([
            'trang_thai' => 'required|in:cho_duyet,dang_tham_dinh,da_duyet,tu_choi',
        ]);

        $kyGui->update([
            'trang_thai' => $request->trang_thai,
            'nhan_vien_phu_trach_id' => $kyGui->nhan_vien_phu_trach_id ?? Auth::guard('nhanvien')->id(),
            'thoi_diem_xu_ly' => now(),
        ]);

        if ($request->expectsJson()) return response()->json(['ok' => true]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

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
        return redirect()->route('nhanvien.admin.ky-gui.index')->with('success', 'Đã tạo ký gửi thành công!');
    }

    public function edit(KyGui $kyGui)
    {
        return view('admin.ky-gui.edit', compact('kyGui'));
    }
    public function update(Request $request, KyGui $kyGui)
    {
        $data = $this->validateData($request);

        // Đảm bảo $hinhCu luôn là mảng
        $hinhCu = is_array($kyGui->hinh_anh_tham_khao) ? $kyGui->hinh_anh_tham_khao : [];

        // Ép kiểu xoa_hinh_anh lấy từ form về mảng mặc định
        $xoaHinhAnh = $request->input('xoa_hinh_anh', []);

        if (is_array($xoaHinhAnh) && count($xoaHinhAnh) > 0) {
            foreach ($xoaHinhAnh as $path) {
                Storage::disk('public')->delete($path);
                $hinhCu = array_filter($hinhCu, fn($p) => $p !== $path);
            }
        }

        $hinhMoi  = $this->uploadImages($request);
        $data['hinh_anh_tham_khao'] = array_values(array_merge($hinhCu, $hinhMoi));

        $kyGui->update($data);
        return redirect()->route('nhanvien.admin.ky-gui.index')->with('success', 'Đã cập nhật ký gửi!');
    }

    public function destroy(KyGui $kyGui)
    {
        // Kiểm tra an toàn trước khi lặp để tránh lỗi P1006
        $hinhAnh = $kyGui->hinh_anh_tham_khao;
        if (is_array($hinhAnh) && count($hinhAnh) > 0) {
            foreach ($hinhAnh as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $kyGui->delete();
        return redirect()->route('nhanvien.admin.ky-gui.index')->with('success', 'Đã xóa yêu cầu ký gửi!');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'ho_ten_chu_nha'         => 'required|string|max:100',
            'so_dien_thoai'          => 'required|string|max:15',
            'email'                  => 'nullable|email|max:100',
            'loai_hinh'              => 'required|in:can_ho,nha_pho,biet_thu,dat_nen,shophouse',
            'nhu_cau'                => 'required|in:ban,thue',
            'du_an'                  => 'nullable|string|max:150',
            'ma_can'                 => 'nullable|string|max:50',
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
            'trang_thai'             => 'nullable|in:cho_duyet,dang_tham_dinh,da_duyet,tu_choi',
            'nguon_ky_gui'           => 'nullable|string'
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
