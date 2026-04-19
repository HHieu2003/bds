<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\DatLichXemNhaMail;
use App\Models\LichHen;
use App\Models\BatDongSan;
use App\Models\ThongBao;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LichHenController extends Controller
{
    public function datLich(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'bat_dong_san_id' => 'required|exists:bat_dong_san,id',
            'ten_khach_hang'  => 'required|string|max:100',
            'sdt_khach_hang'  => 'required|string|max:20',
            'email_khach_hang' => 'nullable|email|max:100',
            'thoi_gian_hen'   => 'required|date|after:now',
            'ghi_chu'         => 'nullable|string|max:1000'
        ], [
            'thoi_gian_hen.after' => 'Thời gian hẹn phải lớn hơn thời gian hiện tại.'
        ]);

        // 2. Lấy ID khách hàng nếu họ đã đăng nhập (Nếu web bạn có tính năng User/Khách hàng đăng nhập)
        // Nếu không có, cứ để null, hệ thống vẫn dựa vào Tên và SĐT.
        $khachHangId = Auth::guard('customer')->id() ?? null;

        // 3. Lời nhắn của khách sẽ được ghi vào ghi_chu_sale để Sale đọc
        $loiNhan = $request->ghi_chu ? "Khách nhắn từ Web: " . $request->ghi_chu : "Khách đặt lịch từ Website";

        // 4. Tạo lịch hẹn
        $lichHen = LichHen::create([
            'bat_dong_san_id'  => $request->bat_dong_san_id,
            'khach_hang_id'    => $khachHangId,
            'ten_khach_hang'   => $request->ten_khach_hang,
            'sdt_khach_hang'   => $request->sdt_khach_hang,
            'email_khach_hang' => $request->email_khach_hang,
            'thoi_gian_hen'    => $request->thoi_gian_hen,
            'ghi_chu_sale'     => $loiNhan,
            'trang_thai'       => 'moi_dat', // Quan trọng: Đẩy thẳng lên FullCalendar của Sale
            'nguon_dat_lich'   => 'website'
        ]);

        // 5. Bắn thông báo cho toàn bộ Sale và Admin biết có kèo mới để vào nhận
        $saleVaAdmin = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', 1)->get();
        foreach ($saleVaAdmin as $nv) {
            ThongBao::create([
                'loai'              => 'lich_hen_moi',
                'doi_tuong_nhan'    => 'nhan_vien',
                'doi_tuong_nhan_id' => $nv->id,
                'tieu_de'           => '🔥 CÓ KHÁCH ĐẶT LỊCH XEM NHÀ',
                'noi_dung'          => $request->ten_khach_hang . ' vừa đặt lịch lúc ' . date('H:i d/m', strtotime($request->thoi_gian_hen)) . '. Hãy vào nhận lịch ngay!',
                'lien_ket'          => route('nhanvien.admin.lich-hen.index'),
            ]);
        }

        // 6. Gửi email xác nhận cho khách hàng (nếu họ có nhập email)
        $emailKhach = $request->email_khach_hang;
        if ($emailKhach) {
            try {
                $lichHen->load('batDongSan');
                Mail::to($emailKhach)->send(new DatLichXemNhaMail($lichHen));
            } catch (\Throwable $e) {
                Log::error('Không gửi được email xác nhận đặt lịch.', [
                    'email' => $emailKhach,
                    'error' => $e->getMessage(),
                ]);
                // Không throw — lịch hẹn đã được tạo, chỉ log lỗi mail
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Cảm ơn bạn! Yêu cầu đặt lịch đã được gửi thành công. Chuyên viên của chúng tôi sẽ liên hệ lại trong ít phút.'
        ]);
    }


    /**
     * HIỂN THỊ DANH SÁCH LỊCH HẸN CỦA KHÁCH HÀNG
     */
    public function lichHen()
    {
        $khachHang = Auth::guard('customer')->user();

        if (!$khachHang) {
            return redirect()->route('khach-hang.login')->with('error', 'Vui lòng đăng nhập để xem lịch hẹn.');
        }

        // Lấy lịch hẹn của chính khách hàng này, load kèm thông tin BĐS và Sale
        $lichHens = LichHen::with(['batDongSan', 'nhanVienSale'])
            ->where('khach_hang_id', $khachHang->id)
            ->orderBy('thoi_gian_hen', 'desc')
            ->paginate(10);

        return view('frontend.tai-khoan.lich-hen', compact('lichHens', 'khachHang'));
    }

    /**
     * KHÁCH HÀNG CHỦ ĐỘNG HỦY LỊCH
     */
    public function huyLichHen(Request $request, $id)
    {
        $khachHang = Auth::guard('customer')->user();

        if (!$khachHang) {
            return redirect()->route('khach-hang.login')->with('error', 'Vui lòng đăng nhập để thao tác.');
        }

        // Tìm lịch hẹn, đảm bảo nó thuộc về khách hàng đang đăng nhập
        $lichHen = LichHen::where('id', $id)->where('khach_hang_id', $khachHang->id)->firstOrFail();

        // Chỉ cho phép hủy nếu lịch chưa hoàn thành hoặc chưa bị hủy
        if (!in_array($lichHen->trang_thai, ['moi_dat', 'cho_xac_nhan', 'da_xac_nhan'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn ở trạng thái này!');
        }

        $request->validate([
            'ly_do' => 'required|string|max:500'
        ]);

        // Cập nhật trạng thái
        $lichHen->update([
            'trang_thai'    => 'huy',
            'ly_do_tu_choi' => 'Khách hàng tự hủy: ' . $request->ly_do,
            'huy_at'        => now()
        ]);

        // Bắn thông báo cho Sale đang phụ trách (nếu có)
        if ($lichHen->nhan_vien_sale_id) {
            ThongBao::create([
                'loai'              => 'khach_huy_lich',
                'doi_tuong_nhan'    => 'nhan_vien',
                'doi_tuong_nhan_id' => $lichHen->nhan_vien_sale_id,
                'tieu_de'           => '⚠️ KHÁCH HÀNG HỦY LỊCH XEM NHÀ',
                'noi_dung'          => "Khách hàng {$khachHang->ho_ten} đã hủy lịch xem nhà lúc {$lichHen->thoi_gian_hen->format('H:i d/m')}. Lý do: {$request->ly_do}",
                'lien_ket'          => route('nhanvien.admin.lich-hen.index'),
            ]);
        }

        // Bắn thông báo cho Nguồn hàng (nếu có) để họ báo lại chủ nhà
        if ($lichHen->nhan_vien_nguon_hang_id) {
            ThongBao::create([
                'loai'              => 'khach_huy_lich',
                'doi_tuong_nhan'    => 'nhan_vien',
                'doi_tuong_nhan_id' => $lichHen->nhan_vien_nguon_hang_id,
                'tieu_de'           => '⚠️ KHÁCH HÀNG HỦY LỊCH XEM NHÀ',
                'noi_dung'          => "Lịch xem nhà lúc {$lichHen->thoi_gian_hen->format('H:i d/m')} đã bị khách hủy. Vui lòng báo lại chủ nhà.",
                'lien_ket'          => route('nhanvien.admin.lich-hen.index'),
            ]);
        }

        return back()->with('success', 'Bạn đã hủy lịch hẹn thành công!');
    }
}
