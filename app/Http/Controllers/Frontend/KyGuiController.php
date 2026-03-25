<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KyGui;
use App\Mail\KyGuiSuccessMail; // Import Class Mail vừa tạo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Import Facade Mail
use Illuminate\Support\Facades\Storage;

class KyGuiController extends Controller
{
    public function create()
    {
        return view('frontend.ky-gui.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten_chu_nha' => 'required|string|max:100',
            'so_dien_thoai'  => 'required|string|max:15',
            'email'          => 'nullable|email|max:100',
            'loai_hinh'      => 'required|in:can_ho,nha_pho,biet_thu,dat_nen,shophouse',
            'nhu_cau'        => 'required|in:ban,thue',
            'dia_chi'        => 'nullable|string|max:255',
            'dien_tich'      => 'required|numeric|min:1|max:99999',
            'huong_nha'      => 'nullable|string',
            'so_phong_ngu'   => 'nullable|integer|min:0|max:20',
            'so_phong_tam'   => 'nullable|integer|min:0|max:20',
            'noi_that'       => 'nullable|string',
            'gia_ban_mong_muon'    => 'nullable|numeric|min:0',
            'phap_ly'              => 'nullable|string',
            'gia_thue_mong_muon'   => 'nullable|numeric|min:0',
            'hinh_thuc_thanh_toan' => 'nullable|string',
            'hinh_anh_tham_khao.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'ghi_chu'        => 'nullable|string|max:2000',
        ]);

        $hinhAnh = [];
        if ($request->hasFile('hinh_anh_tham_khao')) {
            foreach ($request->file('hinh_anh_tham_khao') as $file) {
                $hinhAnh[] = $file->store('ky-gui', 'public');
            }
        }

        // 1. Gán kết quả tạo vào biến $kyGui
        $kyGui = KyGui::create([
            'khach_hang_id'        => Auth::guard('customer')->id(),
            'ho_ten_chu_nha'       => $request->ho_ten_chu_nha,
            'so_dien_thoai'        => $request->so_dien_thoai,
            'email'                => $request->email,
            'loai_hinh'            => $request->loai_hinh,
            'nhu_cau'              => $request->nhu_cau,
            'dia_chi'              => $request->dia_chi,
            'dien_tich'            => $request->dien_tich,
            'huong_nha'            => $request->huong_nha,
            'so_phong_ngu'         => $request->so_phong_ngu ?? 0,
            'so_phong_tam'         => $request->so_phong_tam ?? 0,
            'noi_that'             => $request->noi_that,
            'gia_ban_mong_muon'    => $request->nhu_cau === 'ban'  ? $request->gia_ban_mong_muon    : null,
            'phap_ly'              => $request->nhu_cau === 'ban'  ? $request->phap_ly              : null,
            'gia_thue_mong_muon'   => $request->nhu_cau === 'thue' ? $request->gia_thue_mong_muon   : null,
            'hinh_thuc_thanh_toan' => $request->nhu_cau === 'thue' ? $request->hinh_thuc_thanh_toan : null,
            'hinh_anh_tham_khao'   => $hinhAnh ?: null,
            'ghi_chu'              => $request->ghi_chu,
            'nguon_ky_gui'         => 'website',
            'trang_thai'           => 'cho_duyet',
        ]);

        // 2. Xác định địa chỉ email để gửi (Lấy email từ form, nếu không có thì lấy email của tài khoản đang đăng nhập)
        $sendToEmail = $request->email ?? (Auth::guard('customer')->check() ? Auth::guard('customer')->user()->email : null);

        // 3. Tiến hành gửi mail nếu có email
        if ($sendToEmail) {
            // Mẹo: Dùng queue() thay vì send() nếu bạn đã cấu hình Queue để web không bị load chậm khi gửi mail
            Mail::to($sendToEmail)->send(new KyGuiSuccessMail($kyGui));
        }

        return redirect()->route('frontend.ky-gui.success')->with('success', true);
    }

    public function success()
    {
        return view('frontend.ky-gui.success');
    }

    public function myKyGui()
    {
        $kyGuis = KyGui::where('khach_hang_id', Auth::guard('customer')->id())
            ->latest()
            ->paginate(10);

        return view('frontend.ky-gui.my-list', compact('kyGuis'));
    }
}
