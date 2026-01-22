<?php

namespace App\Http\Controllers;

use App\Models\YeuThich;
use App\Models\BatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YeuThichController extends Controller
{
    public function toggle($id)
    {
        $user = Auth::guard('customer')->user();

        // 1. Chưa login -> Báo Frontend hiện Popup Quick Login
        if (!$user) {
            return response()->json([
                'status' => 'require_login',
                'message' => 'Đăng nhập để lưu tin.'
            ]);
        }

        // 2. Đã login -> Xử lý lưu/xóa
        $existingLike = YeuThich::where('bat_dong_san_id', $id)
            ->where('khach_hang_id', $user->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['status' => 'removed', 'message' => 'Đã bỏ lưu tin']);
        } else {
            YeuThich::create([
                'bat_dong_san_id' => $id,
                'khach_hang_id' => $user->id
            ]);
            return response()->json(['status' => 'added', 'message' => 'Đã lưu tin']);
        }
    }

    public function index()
    {
        $user = Auth::guard('customer')->user();
        if (!$user) return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để xem tin đã lưu');

        $yeuThichs = YeuThich::where('khach_hang_id', $user->id)
            ->with('batDongSan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.yeu_thich.index', compact('yeuThichs'));
    }
}
