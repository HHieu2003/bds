<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuNha;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class ChuNhaController extends Controller
{
    public function index(Request $request)
    {
        $query = ChuNha::with(['nhanVienPhuTrach'])->withCount('batDongSans');

        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(fn($q) => $q->where('ho_ten', 'like', $kw)->orWhere('so_dien_thoai', 'like', $kw)->orWhere('cccd', 'like', $kw));
        }

        $chuNhas = $query->latest()->paginate(15)->withQueryString();
        $nhanViens = NhanVien::where('kich_hoat', true)
            ->whereIn('vai_tro', ['admin', 'nguon_hang'])
            ->get();

        return view('admin.chu-nha.index', compact('chuNhas', 'nhanViens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20|unique:chu_nha,so_dien_thoai',
            'email' => 'nullable|email|max:100',
            'cccd' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
        ]);

        ChuNha::create($data);
        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã thêm chủ nhà mới!');
    }

    public function update(Request $request, ChuNha $chuNha)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20|unique:chu_nha,so_dien_thoai,' . $chuNha->id,
            'email' => 'nullable|email|max:100',
            'cccd' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
        ]);

        $chuNha->update($data);
        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã cập nhật thông tin chủ nhà!');
    }

    public function destroy(ChuNha $chuNha)
    {
        if ($chuNha->batDongSans()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì chủ nhà này đang có Bất động sản trong hệ thống!');
        }
        $chuNha->delete();
        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã xóa chủ nhà!');
    }
}
