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

        $query = YeuCauLienHe::with(['batDongSan', 'nhanVienPhuTrach', 'khachHang']);

        // Xử lý sắp xếp
        $sapXep = $request->get('sap_xep', 'moi_nhat');
        if ($sapXep === 'cu_nhat') {
            $query->oldest('thoi_diem_lien_he')->oldest('created_at');
        } else {
            $query->latest('thoi_diem_lien_he')->latest('created_at');
        }

        // Sale chỉ xem được Lead của mình hoặc Lead mới chưa ai nhận
        if ($nhanVienAuth->isSale()) {
            $query->where(function ($q) use ($nhanVienAuth) {
                $q->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)
                    ->orWhereNull('nhan_vien_phu_trach_id');
            });
        }

        // Lấy tab hiện tại (mặc định là 'can_xu_ly')
        $activeTab = $request->get('tab', 'can_xu_ly');

        // Logic Tab "Cần xử lý"
        if ($activeTab === 'can_xu_ly') {
            $query->whereNotIn('trang_thai', ['da_chot', 'hoan_thanh', 'huy']);
        }

        // Nếu ở tab "Tất cả" thì mới áp dụng lọc trạng thái từ form (nếu có chọn)
        if ($activeTab === 'tat_ca' && $request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo Nhân viên
        if ($request->filled('nhan_vien') && !$nhanVienAuth->isSale()) {
            $query->where('nhan_vien_phu_trach_id', $request->nhan_vien);
        }

        // Lọc theo khoảng ngày (dựa trên ngày tạo lead)
        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        // Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ho_ten', 'like', "%$s%")
                    ->orWhere('so_dien_thoai', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        $lienHes  = $query->paginate(20)->withQueryString();
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', true)->orderBy('ho_ten')->get();

        // Khối tạo thống kê
        $thongKe = collect(array_keys(YeuCauLienHe::TRANG_THAI))->mapWithKeys(function ($tt) use ($nhanVienAuth) {
            $q = YeuCauLienHe::where('trang_thai', $tt);
            if ($nhanVienAuth->isSale()) {
                $q->where(fn($sq) => $sq->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)->orWhereNull('nhan_vien_phu_trach_id'));
            }
            return [$tt => $q->count()];
        });

        // Tổng tất cả
        $qTatCa = YeuCauLienHe::query();
        if ($nhanVienAuth->isSale()) {
            $qTatCa->where(fn($sq) => $sq->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)->orWhereNull('nhan_vien_phu_trach_id'));
        }
        $thongKe['tat_ca'] = $qTatCa->count();

        // Tổng cần xử lý (loại trừ đã chốt, hoàn thành, hủy)
        $qCanXuLy = YeuCauLienHe::whereNotIn('trang_thai', ['da_chot', 'hoan_thanh', 'huy']);
        if ($nhanVienAuth->isSale()) {
            $qCanXuLy->where(fn($sq) => $sq->where('nhan_vien_phu_trach_id', $nhanVienAuth->id)->orWhereNull('nhan_vien_phu_trach_id'));
        }
        $thongKe['can_xu_ly'] = $qCanXuLy->count();

        // Đếm Lead chưa nhận
        $leadChuaNhan = YeuCauLienHe::whereNull('nhan_vien_phu_trach_id')->count();

        return view('admin.lien-he.index', compact('lienHes', 'nhanViens', 'thongKe', 'nhanVienAuth', 'leadChuaNhan', 'activeTab'));
    }

    public function show(YeuCauLienHe $lienHe)
    {
        $lienHe->load(['batDongSan', 'nhanVienPhuTrach', 'khachHang']);
        $nhanViens = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', true)->orderBy('ho_ten')->get();

        // Nếu là AJAX request, trả về JSON
        if (request()->expectsJson()) {
            return response()->json([
                'ho_ten' => $lienHe->ho_ten,
                'ma_yeu_cau' => $lienHe->ma_yeu_cau,
                'so_dien_thoai' => $lienHe->so_dien_thoai,
                'email' => $lienHe->email ?: 'Không cập nhật',
                'noi_dung' => $lienHe->noi_dung,
                'trang_thai_info' => YeuCauLienHe::TRANG_THAI[$lienHe->trang_thai] ?? [],
                'nhan_vien' => $lienHe->nhanVienPhuTrach?->ho_ten,
                'muc_do' => $lienHe->muc_do_quan_tam ? YeuCauLienHe::MUC_DO[$lienHe->muc_do_quan_tam]['label'] : '—',
                'thoi_gian_nhan' => $lienHe->updated_at?->format('d/m/Y H:i') ?: '—',
                'ghi_chu_admin' => $lienHe->ghi_chu_admin,
                'bat_dong_san' => $lienHe->batDongSan ? [
                    'ten' => $lienHe->batDongSan->ten_bat_dong_san,
                    'url' => route('frontend.bat-dong-san.show', $lienHe->batDongSan->slug)
                ] : null,
                'detail_url' => route('nhanvien.admin.lien-he.show', $lienHe),
            ]);
        }

        return view('admin.lien-he.show', compact('lienHe', 'nhanViens'));
    }

    public function nhanLead(YeuCauLienHe $lienHe)
    {
        if ($lienHe->nhan_vien_phu_trach_id) {
            return back()->with('error', 'Rất tiếc, Lead này đã có người khác nhận!');
        }
        $lienHe->update([
            'nhan_vien_phu_trach_id' => Auth::guard('nhanvien')->id(),
            'trang_thai' => 'da_lien_he'
        ]);
        return back()->with('success', 'Bạn đã nhận xử lý Lead này thành công! Hãy gọi điện cho khách ngay nhé.');
    }

    public function update(Request $request, YeuCauLienHe $lienHe)
    {
        if ($request->has('is_quick_update')) {
            $request->validate(['ghi_chu_moi' => 'required|string|max:1000']);
            $oldGhiChu = $lienHe->ghi_chu_admin ? $lienHe->ghi_chu_admin . "\n" : "";
            $newGhiChu = $oldGhiChu . "- [" . now()->format('d/m/Y H:i') . "] " . $request->ghi_chu_moi;

            $lienHe->update(['ghi_chu_admin' => $newGhiChu]);
            return back()->with('success', 'Đã lưu lại lịch sử chăm sóc Lead!');
        }

        $request->validate([
            'trang_thai'             => 'required|in:' . implode(',', array_keys(YeuCauLienHe::TRANG_THAI)),
            'ghi_chu_admin'          => 'nullable|string|max:2000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'muc_do_quan_tam'        => 'nullable|in:' . implode(',', array_keys(YeuCauLienHe::MUC_DO)),
        ]);

        $lienHe->update($request->only(['trang_thai', 'ghi_chu_admin', 'nhan_vien_phu_trach_id', 'muc_do_quan_tam']));
        return redirect()->route('nhanvien.admin.lien-he.show', $lienHe)->with('success', 'Cập nhật thành công!');
    }

    public function capNhatNhanh(Request $request, YeuCauLienHe $lienHe)
    {
        $request->validate(['trang_thai' => 'required|in:' . implode(',', array_keys(YeuCauLienHe::TRANG_THAI))]);
        $lienHe->update(['trang_thai' => $request->trang_thai]);
        return response()->json([
            'success' => true,
            'info'    => YeuCauLienHe::TRANG_THAI[$request->trang_thai],
        ]);
    }

    public function chuyenKhachHang(Request $request, YeuCauLienHe $lienHe)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
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
        $lienHe->update(['trang_thai' => 'da_chot', 'nhan_vien_phu_trach_id' => $nhanVien->id]);
        return back()->with('success', 'Đã chốt Lead và tạo Khách hàng vào CRM!');
    }

    public function destroy(YeuCauLienHe $lienHe)
    {
        $lienHe->delete();
        return redirect()->route('nhanvien.admin.lien-he.index')->with('success', 'Đã xóa yêu cầu liên hệ!');
    }
}
