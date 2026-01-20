<?php

namespace App\Http\Controllers;

use App\Models\LienHe;
use Illuminate\Http\Request;

class LienHeController extends Controller
{
    // 0. Trang liên hệ (FRONTEND)
    public function index()
    {
        return view('frontend.lien_he.index');
    }

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

    public function adminIndex()
    {
        // Lấy danh sách liên hệ, mới nhất lên đầu
        $lien_hes = LienHe::with('batDongSan')->orderBy('created_at', 'desc')->get();
        return view('admin.lien_he.index', ['lien_hes' => $lien_hes]);
    }
    public function updateStatus(Request $request, $id)
    {
        $lienHe = LienHe::findOrFail($id);
        $lienHe->trang_thai = $request->trang_thai;
        $lienHe->save();

        return response()->json(['success' => 'Cập nhật trạng thái thành công!']);
    }
}
