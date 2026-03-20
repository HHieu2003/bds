<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YeuThichController extends Controller
{
    /**
     * Trang hiển thị danh sách BĐS đã lưu
     */
    public function index()
    {
        // 1. Kiểm tra đăng nhập (Bảo mật)
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('frontend.home')->with('info', 'Vui lòng đăng nhập để xem danh sách yêu thích.');
        }

        $khachHang = Auth::guard('customer')->user();

        // 2. Lấy danh sách BĐS thông qua relationship đã tạo ở Bước 1
        $batDongSans = $khachHang->danhSachYeuThich()
            ->with(['duAn', 'khuVuc'])
            ->orderBy('yeu_thich.created_at', 'desc') // Sắp xếp theo thời gian lưu mới nhất
            ->paginate(12);

        return view('frontend.yeu-thich.index', compact('batDongSans'));
    }

    /**
     * API xử lý Thêm / Bỏ yêu thích (Gọi từ Javascript)
     */
    public function toggle(Request $request)
    {
        // 1. Nếu chưa đăng nhập, báo lỗi về cho JS
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu tin.']);
        }

        $bdsId = $request->bat_dong_san_id;
        $khachHang = Auth::guard('customer')->user();

        // 2. Kiểm tra xem BĐS này đã được lưu chưa
        $daLuu = $khachHang->danhSachYeuThich()->where('bat_dong_san_id', $bdsId)->exists();

        if ($daLuu) {
            // Nếu đã lưu -> Gỡ bỏ (Hủy yêu thích)
            $khachHang->danhSachYeuThich()->detach($bdsId);
            return response()->json([
                'success' => true,
                'is_liked' => false,
                'message' => 'Đã bỏ lưu bất động sản.'
            ]);
        } else {
            // Nếu chưa lưu -> Thêm vào
            $khachHang->danhSachYeuThich()->attach($bdsId);
            return response()->json([
                'success' => true,
                'is_liked' => true,
                'message' => 'Đã lưu vào danh sách yêu thích.'
            ]);
        }
    }
}
