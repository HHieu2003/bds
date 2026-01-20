<?php

namespace App\Http\Controllers;

use App\Models\LichHen;
use App\Models\BatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Dùng để gửi mail nếu cần

class LichHenController extends Controller
{
    // --- FRONTEND: Xử lý khách đặt lịch ---
    public function store(Request $request)
    {
        $request->validate([
            'ten_khach_hang' => 'required|string|max:255',
            'sdt_khach_hang' => 'required|string|max:20',
            'thoi_gian_hen' => 'required|date|after:now',
            'bat_dong_san_id' => 'required|exists:bat_dong_san,id',
        ]);

        $lichHen = LichHen::create([
            'ten_khach_hang' => $request->ten_khach_hang,
            'sdt_khach_hang' => $request->sdt_khach_hang,
            'email_khach_hang' => $request->email_khach_hang,
            'bat_dong_san_id' => $request->bat_dong_san_id,
            'thoi_gian_hen' => $request->thoi_gian_hen,
            'trang_thai' => 'moi_dat',
        ]);

        // Gửi email thông báo cho Admin (Demo đơn giản)
        // Bạn cần cấu hình MAIL_ trong file .env để chạy được dòng dưới
        // Mail::raw("Có lịch hẹn mới từ khách: " . $request->ten_khach_hang, function($msg) {
        //     $msg->to('admin@thanhcongland.com')->subject('Thông báo lịch hẹn xem nhà mới');
        // });

        return redirect()->back()->with('success', 'Đã đặt lịch xem nhà thành công! Nhân viên sẽ liên hệ lại sớm.');
    }

    // --- ADMIN: Danh sách lịch hẹn ---
    public function adminIndex()
    {
        // Lấy lịch hẹn mới nhất lên đầu
        $lich_hens = LichHen::with('batDongSan')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.lich_hen.index', ['lich_hens' => $lich_hens]);
    }

    // --- ADMIN: Xác nhận lịch hẹn ---
    public function confirm(Request $request, $id)
    {
        $lich_hen = LichHen::findOrFail($id);
        $lich_hen->trang_thai = 'da_xac_nhan';

        // Nếu xác nhận, tự động gán nhân viên đang đăng nhập là người phụ trách
        if (!$lich_hen->nhan_vien_id) {
            $lich_hen->nhan_vien_id = Auth::id();
        }

        $lich_hen->save();

        return redirect()->back()->with('success', 'Đã xác nhận lịch hẹn!');
    }

    // --- ADMIN: Xóa lịch hẹn ---
    public function destroy($id)
    {
        $lich_hen = LichHen::findOrFail($id);
        $lich_hen->delete();

        return redirect()->back()->with('success', 'Đã xóa lịch hẹn!');
    }
}
