<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\KhachHang;
use App\Models\LichHen;
use App\Models\NhanVien;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LichHenController extends Controller
{
    public function index(Request $request)
    {
        $nhanVien = Auth::user();

        $lichHen = LichHen::with(['khachHang', 'batDongSan', 'nhanVienSale', 'nhanVienNguonHang'])
            ->when($request->trang_thai, fn($q) => $q->where('trang_thai', $request->trang_thai))
            ->when($request->ngay, fn($q) => $q->whereDate('thoi_gian_hen', $request->ngay))
            ->when($nhanVien->isSale(), fn($q) => $q->where('nhan_vien_sale_id', $nhanVien->id))
            ->when($nhanVien->isNguon(), fn($q) => $q->where('nhan_vien_nguon_hang_id', $nhanVien->id))
            ->orderBy('thoi_gian_hen', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.lich-hen.index', compact('lichHen'));
    }

    // Sale tạo lịch hẹn mới
    public function store(Request $request)
    {
        $request->validate([
            'bat_dong_san_id'        => 'required|exists:bat_dong_san,id',
            'nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id',
            'ten_khach_hang'         => 'required|string|max:100',
            'sdt_khach_hang'         => 'required|string|max:20',
            'thoi_gian_hen'          => 'required|date|after:now',
            'dia_diem_hen'           => 'nullable|string|max:255',
            'ghi_chu_sale'           => 'nullable|string',
        ]);

        $lichHen = LichHen::create([
            ...$request->only([
                'bat_dong_san_id',
                'nhan_vien_nguon_hang_id',
                'ten_khach_hang',
                'sdt_khach_hang',
                'email_khach_hang',
                'thoi_gian_hen',
                'dia_diem_hen',
                'ghi_chu_sale',
            ]),
            'khach_hang_id'      => $request->khach_hang_id,
            'nhan_vien_sale_id'  => Auth::id(),
            'trang_thai'         => 'cho_xac_nhan',
            'nguon_dat_lich'     => 'sale',
        ]);

        // Gửi thông báo cho nhân viên nguồn hàng
        ThongBao::create([
            'loai'              => 'lich_hen_moi',
            'doi_tuong_nhan'    => 'nhan_vien',
            'doi_tuong_nhan_id' => $request->nhan_vien_nguon_hang_id,
            'tieu_de'           => 'Lịch hẹn mới cần xác nhận',
            'noi_dung'          => Auth::user()->ho_ten . ' đặt lịch xem căn ' .
                optional($lichHen->batDongSan)->ma_can,
            'du_lieu'           => ['lich_hen_id' => $lichHen->id],
            'lien_ket'          => route('admin.lich-hen.index'),
        ]);

        return back()->with('success', 'Đã tạo lịch hẹn, chờ xác nhận từ nguồn hàng!');
    }

    // Nguồn hàng xác nhận lịch
    public function xacNhan(LichHen $lichHen)
    {
        $lichHen->update([
            'trang_thai'  => 'da_xac_nhan',
            'xac_nhan_at' => now(),
        ]);

        // Thông báo cho sale
        ThongBao::create([
            'loai'              => 'lich_hen_xac_nhan',
            'doi_tuong_nhan'    => 'nhan_vien',
            'doi_tuong_nhan_id' => $lichHen->nhan_vien_sale_id,
            'tieu_de'           => 'Lịch hẹn đã được xác nhận',
            'noi_dung'          => 'Lịch hẹn lúc ' . $lichHen->thoi_gian_hen->format('H:i d/m/Y') . ' đã được xác nhận.',
            'du_lieu'           => ['lich_hen_id' => $lichHen->id],
            'lien_ket'          => route('admin.lich-hen.index'),
        ]);

        return back()->with('success', 'Đã xác nhận lịch hẹn!');
    }

    // Nguồn hàng từ chối lịch
    public function tuChoi(Request $request, LichHen $lichHen)
    {
        $request->validate(['ly_do_tu_choi' => 'required|string']);

        $lichHen->update([
            'trang_thai'    => 'tu_choi',
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'tu_choi_at'    => now(),
        ]);

        // Thông báo cho sale
        ThongBao::create([
            'loai'              => 'lich_hen_tu_choi',
            'doi_tuong_nhan'    => 'nhan_vien',
            'doi_tuong_nhan_id' => $lichHen->nhan_vien_sale_id,
            'tieu_de'           => 'Lịch hẹn bị từ chối',
            'noi_dung'          => 'Lý do: ' . $request->ly_do_tu_choi,
            'du_lieu'           => ['lich_hen_id' => $lichHen->id],
            'lien_ket'          => route('admin.lich-hen.index'),
        ]);

        return back()->with('success', 'Đã từ chối lịch hẹn.');
    }

    public function hoanThanh(LichHen $lichHen)
    {
        $lichHen->update(['trang_thai' => 'hoan_thanh', 'hoan_thanh_at' => now()]);
        return back()->with('success', 'Đã đánh dấu hoàn thành.');
    }
}
