<?php

namespace App\Http\Controllers;

use App\Models\BatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SoSanhController extends Controller
{
    // --- API: Trả về HTML bảng so sánh để nạp vào Modal ---
    public function loadTable()
    {
        $compareIds = Session::get('compare_list', []);
        $properties = BatDongSan::whereIn('id', $compareIds)->get();

        // Trả về view 'partial' (chỉ chứa cái bảng)
        return view('frontend.so_sanh.partial', compact('properties'))->render();
    }

    // --- Thêm vào danh sách (Giữ nguyên) ---
    public function add($id)
    {
        $compareIds = Session::get('compare_list', []);

        if (in_array($id, $compareIds)) {
            return response()->json(['status' => 'exist', 'message' => 'Bất động sản này đã có trong danh sách!']);
        }

        if (count($compareIds) >= 3) {
            return response()->json(['status' => 'limit', 'message' => 'Tối đa so sánh 3 bất động sản!']);
        }

        $compareIds[] = $id;
        Session::put('compare_list', $compareIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm vào so sánh!',
            'count' => count($compareIds)
        ]);
    }

    // --- Xóa khỏi danh sách (Sửa lại trả về JSON để JS xử lý) ---
    public function remove($id)
    {
        $compareIds = Session::get('compare_list', []);
        $compareIds = array_diff($compareIds, [$id]);
        Session::put('compare_list', $compareIds);

        return response()->json(['status' => 'success']);
    }
}
