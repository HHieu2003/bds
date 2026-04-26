<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TinTuyenDung;
use App\Models\DonUngTuyen;
use App\Models\NhatKyEmail;
use App\Mail\UngTuyenThanhCongMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TuyenDungController extends Controller
{
    public function index()
    {
        $tinTuyenDungs = TinTuyenDung::hienThi()->get();

        return view('frontend.pages.tuyen-dung', compact('tinTuyenDungs'));
    }

    public function show(string $slug)
    {
        $tin = TinTuyenDung::where('slug', $slug)->where('hien_thi', true)->firstOrFail();
        $tinKhac = TinTuyenDung::hienThi()->where('id', '!=', $tin->id)->limit(3)->get();

        return view('frontend.pages.tuyen-dung-chi-tiet', compact('tin', 'tinKhac'));
    }

    public function ungTuyen(Request $request)
    {
        $request->validate([
            'tin_tuyen_dung_id' => 'required|exists:tin_tuyen_dung,id',
            'ho_ten'            => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'so_dien_thoai'     => 'required|string|max:20',
            'nam_sinh'          => 'nullable|integer|min:1970|max:' . date('Y'),
            'link_cv'           => 'nullable|url|max:500',
            'file_cv'           => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'gioi_thieu'        => 'nullable|string|max:2000',
        ], [
            'ho_ten.required'        => 'Vui lòng nhập họ và tên.',
            'email.required'         => 'Vui lòng nhập email.',
            'email.email'            => 'Email không đúng định dạng.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'file_cv.mimes'          => 'CV chỉ chấp nhận file PDF, DOC hoặc DOCX.',
            'file_cv.max'            => 'File CV không được vượt quá 5MB.',
        ]);

        $data = $request->except('_token', 'file_cv');

        // Upload file CV lên R2 nếu có
        if ($request->hasFile('file_cv')) {
            $file = $request->file('file_cv');
            $path = $file->store('tuyen-dung/cv', 'r2');
            $data['file_cv'] = $path;
        }

        $don = DonUngTuyen::create($data);

        // Gửi email xác nhận SAU khi đã trả response cho ứng viên
        $donId = $don->id;
        $emailUngVien = $don->email;

        dispatch(function () use ($donId, $emailUngVien) {
            try {
                $don = DonUngTuyen::with('tinTuyenDung')->find($donId);
                if (!$don) return;

                Mail::to($emailUngVien)->send(new UngTuyenThanhCongMail($don));

                NhatKyEmail::create([
                    'loai'       => 'ung_tuyen_thanh_cong',
                    'nguoi_nhan' => $emailUngVien,
                    'tieu_de'    => 'Xác nhận ứng tuyển thành công',
                    'noi_dung'   => "Ứng tuyển vị trí: {$don->tinTuyenDung->tieu_de}",
                    'trang_thai' => 'thanh_cong',
                ]);
            } catch (\Exception $e) {
                NhatKyEmail::create([
                    'loai'       => 'ung_tuyen_thanh_cong',
                    'nguoi_nhan' => $emailUngVien,
                    'tieu_de'    => 'Xác nhận ứng tuyển thành công',
                    'noi_dung'   => null,
                    'trang_thai' => 'that_bai',
                    'loi'        => $e->getMessage(),
                ]);
            }
        })->afterResponse();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hồ sơ ứng tuyển đã được gửi thành công! Bộ phận Nhân sự sẽ liên hệ với bạn trong vòng 24h làm việc.',
            ]);
        }

        return back()->with('success', 'Hồ sơ ứng tuyển đã được gửi thành công! Bộ phận Nhân sự sẽ liên hệ với bạn trong vòng 24h.');
    }
}
