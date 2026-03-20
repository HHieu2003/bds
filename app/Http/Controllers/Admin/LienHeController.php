<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YeuCauLienHe;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class LienHeController extends Controller
{
    public function index(Request $request)
    {
        $query = YeuCauLienHe::with(['batDongSan', 'nhanVienPhuTrach', 'khachHang'])
            ->latest('thoi_diem_lien_he');

        // Bộ lọc
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('nguon')) {
            $query->where('nguon_lien_he', $request->nguon);
        }
        if ($request->filled('nhan_vien')) {
            $query->where('nhan_vien_phu_trach_id', $request->nhan_vien);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ho_ten', 'like', "%$s%")
                    ->orWhere('so_dien_thoai', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        $lienHes  = $query->paginate(20)->withQueryString();
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])
            ->where('kich_hoat', true)
            ->orderBy('ho_ten')->get();

        // Thống kê nhanh
        $thongKe = collect(array_keys(YeuCauLienHe::TRANG_THAI))
            ->mapWithKeys(fn($tt) => [$tt => YeuCauLienHe::where('trang_thai', $tt)->count()]);
        $thongKe['tat_ca'] = YeuCauLienHe::count();

        return view('admin.lien-he.index', compact('lienHes', 'nhanViens', 'thongKe'));
    }

    public function show(YeuCauLienHe $lienHe)
    {
        $lienHe->load(['batDongSan', 'nhanVienPhuTrach', 'khachHang']);
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])
            ->where('kich_hoat', true)
            ->orderBy('ho_ten')->get();
        return view('admin.lien-he.show', compact('lienHe', 'nhanViens'));
    }

    public function update(Request $request, YeuCauLienHe $lienHe)
    {
        $request->validate([
            'trang_thai'  => 'required|in:' . implode(',', array_keys(YeuCauLienHe::TRANG_THAI)),
            'ghi_chu_admin'         => 'nullable|string|max:2000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'muc_do_quan_tam'       => 'nullable|in:' . implode(',', array_keys(YeuCauLienHe::MUC_DO)),
        ]);

        $lienHe->update([
            'trang_thai'             => $request->trang_thai,
            'ghi_chu_admin'          => $request->ghi_chu_admin,
            'nhan_vien_phu_trach_id' => $request->nhan_vien_phu_trach_id,
            'muc_do_quan_tam'        => $request->muc_do_quan_tam,
        ]);

        return redirect()->route('nhanvien.admin.lien-he.show', $lienHe)
            ->with('success', 'Cập nhật thành công!');
    }

    public function destroy(YeuCauLienHe $lienHe)
    {
        $lienHe->delete();
        return redirect()->route('nhanvien.admin.lien-he.index')
            ->with('success', 'Đã xóa yêu cầu liên hệ!');
    }

    // Cập nhật nhanh trạng thái (AJAX)
    public function capNhatNhanh(Request $request, YeuCauLienHe $lienHe)
    {
        $request->validate([
            'trang_thai' => 'required|in:' . implode(',', array_keys(YeuCauLienHe::TRANG_THAI)),
        ]);

        $lienHe->update(['trang_thai' => $request->trang_thai]);

        return response()->json([
            'success' => true,
            'info'    => YeuCauLienHe::TRANG_THAI[$request->trang_thai],
        ]);
    }
}
