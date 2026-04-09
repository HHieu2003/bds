<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YeuCauLienHe;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LienHeController extends Controller
{
    public function index(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $query = YeuCauLienHe::with(['batDongSan', 'nhanVienPhuTrach', 'khachHang']);

        // Phân quyền: Sale chỉ xem Data của mình hoặc Data rảnh (chưa ai nhận)
        if ($nhanVien->isSale()) {
            $query->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_phu_trach_id', $nhanVien->id)
                    ->orWhereNull('nhan_vien_phu_trach_id');
            });
        }

        // Bộ lọc
        if ($request->filled('trang_thai')) $query->where('trang_thai', $request->trang_thai);
        if ($request->filled('nguon')) $query->where('nguon_lien_he', $request->nguon);
        if ($request->filled('nhan_vien') && !$nhanVien->isSale()) $query->where('nhan_vien_phu_trach_id', $request->nhan_vien);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ho_ten', 'like', "%$s%")->orWhere('so_dien_thoai', 'like', "%$s%")->orWhere('email', 'like', "%$s%");
            });
        }

        $lienHes = $query->latest('created_at')->paginate(20)->withQueryString();
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', true)->orderBy('ho_ten')->get();

        // Thống kê nhanh
        $stats = [
            'tat_ca'     => (clone $query)->count(),
            'moi'        => (clone $query)->where('trang_thai', 'moi')->count(),
            'dang_xu_ly' => (clone $query)->where('trang_thai', 'dang_xu_ly')->count(),
            'hoan_thanh' => (clone $query)->where('trang_thai', 'hoan_thanh')->count(),
        ];

        return view('admin.lien-he.index', compact('lienHes', 'nhanViens', 'stats', 'nhanVien'));
    }

    public function show(YeuCauLienHe $lienHe)
    {
        $lienHe->load(['batDongSan', 'nhanVienPhuTrach', 'khachHang']);
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', true)->orderBy('ho_ten')->get();
        return view('admin.lien-he.show', compact('lienHe', 'nhanViens'));
    }

    public function update(Request $request, YeuCauLienHe $lienHe)
    {
        $request->validate([
            'trang_thai'             => 'required|string',
            'ghi_chu_admin'          => 'nullable|string|max:2000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'muc_do_quan_tam'        => 'nullable|string',
        ]);

        $lienHe->update($request->only(['trang_thai', 'ghi_chu_admin', 'nhan_vien_phu_trach_id', 'muc_do_quan_tam']));
        return redirect()->route('nhanvien.admin.lien-he.index')->with('success', 'Cập nhật thành công!');
    }

    // API CẬP NHẬT NHANH TRẠNG THÁI NGAY TẠI BẢNG (INLINE EDIT)
    public function updateStatus(Request $request, YeuCauLienHe $lienHe)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $dataUpdate = ['trang_thai' => $request->trang_thai];

        // Tự động nhận Khách nếu Sale đổi trạng thái mà khách chưa có người phụ trách
        if ($request->trang_thai !== 'moi' && is_null($lienHe->nhan_vien_phu_trach_id)) {
            $dataUpdate['nhan_vien_phu_trach_id'] = $nhanVien->id;
        }

        $lienHe->update($dataUpdate);

        // Trả về thông tin màu sắc để đổi màu Dropdown bằng JS
        $info = \App\Models\YeuCauLienHe::TRANG_THAI[$request->trang_thai] ?? ['bg' => '#e2e8f0', 'color' => '#475569'];

        return response()->json(['success' => true, 'info' => $info, 'nhan_vien' => $nhanVien->ho_ten]);
    }

    // Chuyển đổi Lead -> Khách Hàng CRM
    public function convertToKhachHang(Request $request, YeuCauLienHe $lienHe)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        $khachHang = KhachHang::where('so_dien_thoai', $lienHe->so_dien_thoai)->first();

        if (!$khachHang) {
            $ghiChuNhuCau = "Tạo từ Lead Tư Vấn.\nNội dung: " . $lienHe->noi_dung;
            KhachHang::create([
                'ho_ten'                 => $lienHe->ho_ten ?? 'Khách từ Web',
                'so_dien_thoai'          => $lienHe->so_dien_thoai,
                'email'                  => $lienHe->email,
                'nguon_khach_hang'       => 'lien_he',
                'muc_do_tiem_nang'       => 'am',
                'nhan_vien_phu_trach_id' => $nhanVien->id,
                'ghi_chu_noi_bo'         => $ghiChuNhuCau,
            ]);
        }

        $lienHe->update(['trang_thai' => 'hoan_thanh', 'nhan_vien_phu_trach_id' => $nhanVien->id]);
        return back()->with('success', 'Đã chuyển thành Khách Hàng chính thức!');
    }

    public function destroy(YeuCauLienHe $lienHe)
    {
        $lienHe->delete();
        return redirect()->route('nhanvien.admin.lien-he.index')->with('success', 'Đã xóa yêu cầu liên hệ!');
    }
}
