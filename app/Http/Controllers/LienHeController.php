<?php

namespace App\Http\Controllers;

use App\Models\LienHe;
use Illuminate\Http\Request;

class LienHeController extends Controller
{
    // 1. Xử lý khi khách bấm nút "Gửi yêu cầu"
    public function store(Request $request)
    {
        // Validate: Bắt buộc phải có SĐT
        $request->validate([
            'customer_phone' => 'required|numeric|digits_between:9,11', // SĐT phải là số, từ 9-11 ký tự
            'property_id' => 'required|exists:bat_dong_san,id'
        ], [
            'customer_phone.required' => 'Vui lòng nhập số điện thoại!',
            'customer_phone.numeric' => 'Số điện thoại không hợp lệ!',
        ]);

        // Lưu vào DB
        LienHe::create([
            'so_dien_thoai' => $request->customer_phone,
            'loi_nhan' => $request->message,
            'bat_dong_san_id' => $request->property_id,
            'trang_thai' => 'chua_xu_ly'
        ]);

        // Quay lại trang cũ và thông báo
        return redirect()->back()->with('success', 'Đã gửi yêu cầu! Nhân viên sẽ gọi lại cho bạn sớm.');
    }

    // 2. Trang danh sách KH dành cho Admin (Chúng ta sẽ làm giao diện này ngay sau đây)
    public function index()
    {
        // Lấy danh sách liên hệ, mới nhất lên đầu
        $dsLienHe = LienHe::with('batDongSan')->orderBy('created_at', 'desc')->get();
        return view('admin.lien_he.index', compact('dsLienHe'));
    }
}
