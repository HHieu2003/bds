<?php

namespace App\Http\Controllers;

use App\Models\YeuThich;
use App\Models\BatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class YeuThichController extends Controller
{
    // --- Xử lý Thả tim / Hủy tim ---
    public function toggle($id)
    {
        // Kiểm tra BĐS có tồn tại không
        $bds = BatDongSan::findOrFail($id);

        // Xác định định danh người dùng (User ID hoặc Session ID)
        $userId = Auth::id();
        $sessionId = Session::getId();

        // Tìm xem đã like chưa
        $query = YeuThich::where('bat_dong_san_id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existingLike = $query->first();

        if ($existingLike) {
            // Nếu đã like rồi thì xóa (Unlike)
            $existingLike->delete();
            return response()->json(['status' => 'removed', 'message' => 'Đã bỏ lưu tin này']);
        } else {
            // Chưa like thì tạo mới
            YeuThich::create([
                'bat_dong_san_id' => $id,
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId // Nếu đã login thì không cần lưu session_id
            ]);
            return response()->json(['status' => 'added', 'message' => 'Đã lưu tin thành công']);
        }
    }

    // --- Hiển thị danh sách đã lưu ---
    public function index()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $query = YeuThich::with('batDongSan');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        // Lấy danh sách BĐS từ bảng YeuThich
        $yeuThichs = $query->orderBy('created_at', 'desc')->get();

        return view('frontend.yeu_thich.index', compact('yeuThichs'));
    }
}
