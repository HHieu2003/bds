<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NganHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NganHangController extends Controller
{
    public function index(Request $request)
    {
        $query = NganHang::query();

        // 1. Lọc theo từ khóa tìm kiếm
        if ($request->filled('keyword')) {
            $query->where('ten_ngan_hang', 'like', '%' . $request->keyword . '%');
        }

        // 2. Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $nganHangs = $query->latest()->get();

        // Tính toán số lượng
        $tongNganHang = NganHang::count(); // Tổng số tất cả trong DB
        $tongTimKiem = $nganHangs->count(); // Số lượng sau khi lọc

        return view('admin.ngan-hang.index', compact('nganHangs', 'tongNganHang', 'tongTimKiem'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_ngan_hang' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lai_suat_uu_dai' => 'required|numeric|min:0|max:100',
            'thoi_gian_uu_dai' => 'required|integer|min:0',
            'lai_suat_tha_noi' => 'nullable|numeric|min:0|max:100',
            'ty_le_vay_toi_da' => 'required|integer|min:0|max:100',
            'thoi_gian_vay_toi_da' => 'required|integer|min:1',
            'trang_thai' => 'nullable|boolean',
        ]);

        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ngan_hang', 'r2');
        }

        NganHang::create($data);

        return redirect()->route('nhanvien.admin.ngan-hang.index')->with('success', 'Đã thêm chính sách ngân hàng thành công!');
    }

    public function update(Request $request, NganHang $nganHang)
    {
        $data = $request->validate([
            'ten_ngan_hang' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lai_suat_uu_dai' => 'required|numeric|min:0|max:100',
            'thoi_gian_uu_dai' => 'required|integer|min:0',
            'lai_suat_tha_noi' => 'nullable|numeric|min:0|max:100',
            'ty_le_vay_toi_da' => 'required|integer|min:0|max:100',
            'thoi_gian_vay_toi_da' => 'required|integer|min:1',
            'trang_thai' => 'nullable|boolean',
        ]);

        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        if ($request->hasFile('logo')) {
            if ($nganHang->logo) Storage::disk('r2')->delete($nganHang->logo);
            $data['logo'] = $request->file('logo')->store('ngan_hang', 'r2');
        }

        $nganHang->update($data);

        return redirect()->route('nhanvien.admin.ngan-hang.index')->with('success', 'Cập nhật ngân hàng thành công!');
    }

    public function destroy(NganHang $nganHang)
    {
        if ($nganHang->logo) {
            Storage::disk('r2')->delete($nganHang->logo);
        }
        $nganHang->delete();
        return redirect()->route('nhanvien.admin.ngan-hang.index')->with('success', 'Đã xóa ngân hàng!');
    }
}
