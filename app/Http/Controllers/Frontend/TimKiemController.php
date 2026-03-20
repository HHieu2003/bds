<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use Illuminate\Http\Request;

class TimKiemController extends Controller
{
    // Gợi ý tìm kiếm nhanh (AJAX autocomplete)
    public function goiY(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) return response()->json([]);

        $results = BatDongSan::with('duAn')
            ->where('hien_thi', true)
            ->where('trang_thai', 'con_hang')
            ->where(
                fn($query) =>
                $query->where('tieu_de', 'like', "%$q%")
                    ->orWhere('ma_can', 'like', "%$q%")
            )
            ->limit(5)
            ->get(['id', 'tieu_de', 'slug', 'ma_can', 'hinh_anh']);

        return response()->json($results);
    }
}
