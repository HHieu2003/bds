<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\LichHen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhachHangController extends Controller
{
    /**
     * MỨC ĐỘ TIỀM NĂNG (Theo CSDL của bạn: lanh, am, nong)
     */
    private function getMucDoTiemNang()
    {
        return [
            'nong' => ['label' => 'Nóng (Sắp chốt)', 'color' => 'danger'],
            'am'   => ['label' => 'Ấm (Đang chăm)', 'color' => 'warning'],
            'lanh' => ['label' => 'Lạnh (Chưa nhu cầu)', 'color' => 'secondary'],
        ];
    }

    public function index(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $query = KhachHang::with('nhanVienPhuTrach')->withCount('lichHens');

        // PHÂN QUYỀN
        if ($nhanVien->isSale()) {
            $query->where('nhan_vien_phu_trach_id', $nhanVien->id);
        }

        // BỘ LỌC TÌM KIẾM
        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ho_ten', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw)
                    ->orWhere('email', 'like', $kw);
            });
        }
        if ($request->filled('muc_do_tiem_nang')) {
            $query->where('muc_do_tiem_nang', $request->muc_do_tiem_nang);
        }
        if ($request->filled('nguon_khach_hang')) {
            $query->where('nguon_khach_hang', $request->nguon_khach_hang);
        }
        if ($request->filled('nhan_vien_id') && !$nhanVien->isSale()) {
            $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_id);
        }

        $khachHangs = $query->orderByDesc('lien_he_cuoi_at')->latest()->paginate(15)->withQueryString();

        $dsSale = NhanVien::where('vai_tro', 'sale')->where('kich_hoat', 1)->get();
        $mucDoTiemNang = $this->getMucDoTiemNang();

        // THỐNG KÊ 
        $stats = [
            'tong' => (clone $query)->count(),
            'nong' => (clone $query)->where('muc_do_tiem_nang', 'nong')->count(),
            'am'   => (clone $query)->where('muc_do_tiem_nang', 'am')->count(),
        ];

        return view('admin.khach-hang.index', compact('khachHangs', 'dsSale', 'mucDoTiemNang', 'stats', 'nhanVien'));
    }

    public function store(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $data = $request->validate([
            'ho_ten'                 => 'required|string|max:100',
            'so_dien_thoai'          => 'required|string|max:20|unique:khach_hang,so_dien_thoai',
            'email'                  => 'nullable|email|max:100',
            'muc_do_tiem_nang'       => 'nullable|in:lanh,am,nong',
            'nguon_khach_hang'       => 'nullable|string',
            'ghi_chu_noi_bo'         => 'nullable|string',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id'
        ]);

        $data['nhan_vien_phu_trach_id'] = $nhanVien->isSale() ? $nhanVien->id : ($request->nhan_vien_phu_trach_id ?? null);
        $data['muc_do_tiem_nang'] = $request->muc_do_tiem_nang ?? 'am';
        $data['nguon_khach_hang'] = $request->nguon_khach_hang ?? 'sale';

        KhachHang::create($data);

        return back()->with('success', 'Đã thêm Khách hàng mới thành công!');
    }

    public function show(KhachHang $khachHang)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        if ($nhanVien->isSale() && $khachHang->nhan_vien_phu_trach_id !== $nhanVien->id) {
            abort(403, 'Bạn không có quyền truy cập hồ sơ khách hàng của nhân sự khác.');
        }

        $lichHens = LichHen::with(['batDongSan.khuVuc', 'nhanVienNguonHang'])
            ->where('khach_hang_id', $khachHang->id)
            ->latest('thoi_gian_hen')
            ->get();

        $mucDoTiemNang = $this->getMucDoTiemNang();
        $dsSale = NhanVien::where('vai_tro', 'sale')->where('kich_hoat', 1)->get();

        return view('admin.khach-hang.show', compact('khachHang', 'lichHens', 'mucDoTiemNang', 'dsSale', 'nhanVien'));
    }

    public function update(Request $request, KhachHang $khachHang)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        if ($nhanVien->isSale() && $khachHang->nhan_vien_phu_trach_id !== $nhanVien->id) {
            abort(403, 'Bạn không có quyền sửa khách hàng này.');
        }

        $data = $request->validate([
            'ho_ten'                 => 'required|string|max:100',
            'so_dien_thoai'          => 'required|string|max:20|unique:khach_hang,so_dien_thoai,' . $khachHang->id,
            'email'                  => 'nullable|email|max:100',
            'muc_do_tiem_nang'       => 'required|in:lanh,am,nong',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id'
        ]);

        if ($nhanVien->isSale()) unset($data['nhan_vien_phu_trach_id']);

        $khachHang->update($data);

        return back()->with('success', 'Cập nhật hồ sơ Khách hàng thành công!');
    }

    public function storeNhatKy(Request $request, KhachHang $khachHang)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $request->validate([
            'noi_dung_cham_soc' => 'required|string|max:2000',
            'muc_do_tiem_nang'  => 'nullable|in:lanh,am,nong'
        ]);

        $thoiGian = now()->format('d/m/Y H:i');
        $nhatKyMoi = "[{$thoiGian}] {$nhanVien->ho_ten}:\n" . $request->noi_dung_cham_soc;

        $ghiChuHienTai = $khachHang->ghi_chu_noi_bo ? "\n\n---\n" . $khachHang->ghi_chu_noi_bo : "";
        $ghiChuMoiNhat = $nhatKyMoi . $ghiChuHienTai;

        $khachHang->update([
            'ghi_chu_noi_bo'   => $ghiChuMoiNhat,
            'muc_do_tiem_nang' => $request->muc_do_tiem_nang ?? $khachHang->muc_do_tiem_nang,
            'lien_he_cuoi_at'  => now() // Cập nhật thời gian gọi khách lần cuối
        ]);

        return back()->with('success', 'Đã lưu nhật ký chăm sóc thành công!');
    }

    public function destroy(KhachHang $khachHang)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        abort_unless($nhanVien->hasRole('admin'), 403, 'Chỉ Admin mới có quyền xóa dữ liệu.');

        if ($khachHang->lichHens()->count() > 0) {
            return back()->with('error', 'Không thể xóa khách đã có Lịch hẹn xem nhà.');
        }

        $khachHang->delete();
        return redirect()->route('nhanvien.admin.khach-hang.index')->with('success', 'Đã xóa dữ liệu Khách hàng!');
    }
}
