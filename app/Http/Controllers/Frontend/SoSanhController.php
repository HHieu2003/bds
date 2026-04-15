<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use Illuminate\Http\Request;

class SoSanhController extends Controller
{
    private function parseIds(?string $ids): array
    {
        if (!$ids) {
            return [];
        }

        return array_values(array_filter(array_map('intval', explode(',', $ids)), fn($id) => $id > 0));
    }

    private function getSoSanhQuery(array $ids)
    {
        return BatDongSan::with(['duAn', 'khuVuc'])
            ->whereIn('id', $ids)
            ->where('hien_thi', 1)
            ->orderByRaw('FIELD(id,' . implode(',', $ids) . ')');
    }

    public function index(Request $request)
    {
        $danhSachBds = collect();

        // Đọc từ ?ids= trên URL (từ localStorage client)
        if ($request->filled('ids')) {
            $idArray = $this->parseIds($request->query('ids'));

            if (!empty($idArray)) {
                $danhSachBds = $this->getSoSanhQuery($idArray)->get();
            }
        }
        // Giữ tương thích ngược với session cũ
        elseif (session()->has('so_sanh')) {
            $idArray = array_values(array_filter(array_map('intval', session('so_sanh', [])), fn($id) => $id > 0));

            if (!empty($idArray)) {
                $danhSachBds = $this->getSoSanhQuery($idArray)->get();
            }
        }

        return view('frontend.so-sanh.index', compact('danhSachBds'));
    }

    public function loadModal(Request $request)
    {
        $ids = $this->parseIds($request->query('ids'));
        $danhSachBds = collect();

        if (!empty($ids)) {
            $danhSachBds = $this->getSoSanhQuery($ids)->get();
        }

        // Trả về view chỉ chứa duy nhất HTML của cái Bảng (Table)
        return view('frontend.so-sanh._table', compact('danhSachBds'));
    }
    public function modal(Request $request)
    {
        $danhSachBds = collect();

        $ids = $this->parseIds($request->query('ids'));
        if (!empty($ids)) {
            $danhSachBds = $this->getSoSanhQuery($ids)->get();
        }

        return view('frontend.so-sanh._table', compact('danhSachBds'));
    }
}
