<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NganHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NganHangController extends Controller
{
    public function index()
    {
        // Lấy danh sách ngân hàng, sắp xếp mới nhất lên đầu
        $nganHangs = NganHang::latest()->paginate(10);
        return view('admin.ngan-hang.index', compact('nganHangs'));
    }

    public function create()
    {
        return redirect()->route('nhanvien.admin.ngan-hang.index')
            ->with('info', 'Vui lòng thao tác thêm ngân hàng bằng popup trên trang danh sách.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_ngan_hang' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'lai_suat_uu_dai' => 'required|numeric|min:0|max:100',
            'thoi_gian_uu_dai' => 'required|integer|min:0',
            'lai_suat_tha_noi' => 'nullable|numeric|min:0|max:100',
            'ty_le_vay_toi_da' => 'required|integer|min:1|max:100',
            'thoi_gian_vay_toi_da' => 'required|integer|min:1|max:50',
            'trang_thai' => 'required|boolean',
        ]);

        // Xử lý lưu ảnh Logo nếu có
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ngan_hang', 'public');
        }

        NganHang::create($data);

        return redirect()->route('nhanvien.admin.ngan-hang.index')->with('success', 'Thêm ngân hàng thành công!');
    }

    public function edit(NganHang $nganHang)
    {
        return redirect()->route('nhanvien.admin.ngan-hang.index')
            ->with('info', 'Vui lòng thao tác sửa ngân hàng bằng popup trên trang danh sách.');
    }

    public function update(Request $request, NganHang $nganHang)
    {
        $data = $request->validate([
            'ten_ngan_hang' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'lai_suat_uu_dai' => 'required|numeric|min:0|max:100',
            'thoi_gian_uu_dai' => 'required|integer|min:0',
            'lai_suat_tha_noi' => 'nullable|numeric|min:0|max:100',
            'ty_le_vay_toi_da' => 'required|integer|min:1|max:100',
            'thoi_gian_vay_toi_da' => 'required|integer|min:1|max:50',
            'trang_thai' => 'required|boolean',
        ]);

        // Kiểm tra nếu có cập nhật ảnh mới thì xóa ảnh cũ
        if ($request->hasFile('logo')) {
            if ($nganHang->logo) {
                Storage::disk('public')->delete($nganHang->logo);
            }
            $data['logo'] = $request->file('logo')->store('ngan_hang', 'public');
        }

        $nganHang->update($data);

        return redirect()->route('nhanvien.admin.ngan-hang.index')->with('success', 'Cập nhật ngân hàng thành công!');
    }

    public function destroy(NganHang $nganHang)
    {
        if ($nganHang->logo) {
            Storage::disk('public')->delete($nganHang->logo);
        }
        $nganHang->delete();

        return redirect()->route('nhanvien.admin.ngan-hang.index')->with('success', 'Xóa ngân hàng thành công!');
    }
}
