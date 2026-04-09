<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YeuCauLienHe;
use App\Models\NhanVien;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LienHeController extends Controller
{
    public function index(Request $request)
    {
        $nhanVienAuth = Auth::guard('nhanvien')->user();

        $query = YeuCauLienHe::with(['batDongSan', 'nhanVienPhuTrach', 'khachHang'])
            ->latest('thoi_diem_lien_he');

        // Phân quyền: Sale chỉ xem lead của mình hoặc lead chưa có ai nhận
        if ($nhanVienAuth->isSale()) {
            $query->where(function($q) use ($nhanVienAuth) {
                $q->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)
                  ->orWhereNull('nhan_vien_phu_trach_id');
            });
        }

        // Bộ lọc
        if ($request->filled('trang_thai')) $query->where('trang_thai', $request->trang_thai);
        if ($request->filled('nguon')) $query->where('nguon_lien_he', $request->nguon);
        if ($request->filled('nhan_vien') && !$nhanVienAuth->isSale()) $query->where('nhan_vien_phu_trach_id', $request->nhan_vien);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ho_ten', 'like', "%$s%")->orWhere('so_dien_thoai', 'like', "%$s%")->orWhere('email', 'like', "%$s%");
            });
        }

        $lienHes  = $query->paginate(20)->withQueryString();
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', true)->orderBy('ho_ten')->get();

        // Thống kê nhanh (Dựa theo query hiện tại để đếm chính xác quyền của Sale)
        $thongKe = collect(array_keys(YeuCauLienHe::TRANG_THAI))->mapWithKeys(function($tt) use ($nhanVienAuth) {
            $q = YeuCauLienHe::where('trang_thai', $tt);
            if ($nhanVienAuth->isSale()) {
                $q->where(fn($sq) => $sq->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)->orWhereNull('nhan_vien_phu_trach_id'));
            }
            return [$tt => $q->count()];
        });

        $qTatCa = YeuCauLienHe::query();
        if ($nhanVienAuth->isSale()) $qTatCa->where(fn($sq) => $sq->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)->orWhereNull('nhan_vien_phu_trach_id'));
        $thongKe['tat_ca'] = $qTatCa->count();

        return view('admin.lien-he.index', compact('lienHes', 'nhanViens', 'thongKe', 'nhanVienAuth'));
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
            'trang_thai'             => 'required|in:' . implode(',', array_keys(YeuCauLienHe::TRANG_THAI)),
            'ghi_chu_admin'          => 'nullable|string|max:2000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'muc_do_quan_tam'        => 'nullable|in:' . implode(',', array_keys(YeuCauLienHe::MUC_DO)),
        ]);

        $lienHe->update($request->only(['trang_thai', 'ghi_chu_admin', 'nhan_vien_phu_trach_id', 'muc_do_quan_tam']));
        return redirect()->route('nhanvien.admin.lien-he.show', $lienHe)->with('success', 'Cập nhật thành công!');
    }

    // TÍNH NĂNG CRM: Cập nhật nhanh trực tiếp trên Bảng (AJAX)
    public function capNhatNhanh(Request $request, YeuCauLienHe $lienHe)
    {
        $request->validate(['trang_thai' => 'required|in:' . implode(',', array_keys(YeuCauLienHe::TRANG_THAI))]);

        $dataUpdate = ['trang_thai' => $request->trang_thai];

        // CRM Logic: Nếu Sale đổi trạng thái từ "Mới" sang trạng thái khác mà Lead chưa có ai nhận, tự động gán cho Sale đó.
        if ($request->trang_thai !== 'moi' && is_null($lienHe->nhan_vien_phu_trach_id)) {
            $dataUpdate['nhan_vien_phu_trach_id'] = Auth::guard('nhanvien')->id();
        }

        $lienHe->update($dataUpdate);

        return response()->json([
            'success' => true,
            'info'    => YeuCauLienHe::TRANG_THAI[$request->trang_thai],
            'nguoi_nhan' => $lienHe->nhanVienPhuTrach->ho_ten ?? 'Chưa có'
        ]);
    }

    // TÍNH NĂNG CRM: CHUYỂN ĐỔI LEAD -> KHÁCH HÀNG CHÍNH THỨC
    public function chuyenKhachHang(Request $request, YeuCauLienHe $lienHe)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        // Kiểm tra xem SĐT này đã có trong danh bạ Khách hàng chưa
        $kh = KhachHang::where('so_dien_thoai', $lienHe->so_dien_thoai)->first();

        if (!$kh) {
            $ghiChu = "Khách hàng được tạo từ Yêu cầu tư vấn (Mã: {$lienHe->ma_yeu_cau}).\nNội dung: {$lienHe->noi_dung}";
            KhachHang::create([
                'ho_ten'                 => $lienHe->ho_ten ?? 'Khách từ Web',
                'so_dien_thoai'          => $lienHe->so_dien_thoai,
                'email'                  => $lienHe->email,
                'nguon_khach_hang'       => 'lien_he',
                'muc_do_tiem_nang'       => 'am',
                'nhan_vien_phu_trach_id' => $nhanVien->id,
                'ghi_chu_noi_bo'         => $ghiChu,
            ]);
        }

        // Đóng Lead này lại
        $lienHe->update(['trang_thai' => 'hoan_thanh', 'nhan_vien_phu_trach_id' => $nhanVien->id]);

        return back()->with('success', 'Đã chuyển đổi thành công Khách Hàng vào Danh bạ CRM!');
    }

    public function destroy(YeuCauLienHe $lienHe)
    {
        $lienHe->delete();
        return redirect()->route('nhanvien.admin.lien-he.index')->with('success', 'Đã xóa yêu cầu liên hệ!');
    }
}
