<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\YeuCauLienHe;
use App\Models\BatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LienHeController extends Controller
{
    // Trang liên hệ chung
    public function index()
    {
        return view('frontend.lien-he.index');
    }

    // Gửi form liên hệ chung hoặc từ trang BDS
    public function store(Request $request)
    {
        $request->validate([
            'ho_ten'       => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:15',
            'email'        => 'nullable|email|max:100',
            'noi_dung'     => 'nullable|string|max:2000',
            'bat_dong_san_id' => 'nullable|exists:bat_dong_san,id',
        ], [
            'ho_ten.required'       => 'Vui lòng nhập họ tên.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'email.email'           => 'Email không hợp lệ.',
        ]);

        $khachHangId = Auth::guard('customer')->id();

        YeuCauLienHe::create([
            'khach_hang_id'    => $khachHangId,
            'bat_dong_san_id'  => $request->bat_dong_san_id,
            'ho_ten'           => $request->ho_ten,
            'so_dien_thoai'    => $request->so_dien_thoai,
            'email'            => $request->email,
            'noi_dung'         => $request->noi_dung,
            'nguon_lien_he'    => $request->bat_dong_san_id ? 'form_bds' : 'website',
            'muc_do_quan_tam'  => $request->muc_do_quan_tam ?? 'chua_ro',
            'trang_thai'       => 'moi',
            'thoi_diem_lien_he' => now(),
        ]);

        // Nếu là AJAX (từ form popup BDS)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu thành công! Chúng tôi sẽ liên hệ bạn sớm nhất.',
            ]);
        }

        return redirect()->back()->with('lien_he_success', true);
    }
}
