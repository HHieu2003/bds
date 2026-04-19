<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\NhatKyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NhatKyEmailController extends Controller
{
    public function index(Request $request)
    {
        $query = NhatKyEmail::with(['khachHang', 'nhanVien']);

        if ($request->filled('tim_kiem')) {
            $kw = '%' . trim((string) $request->tim_kiem) . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('email_nguoi_nhan', 'like', $kw)
                    ->orWhere('tieu_de', 'like', $kw)
                    ->orWhere('noi_dung', 'like', $kw)
                    ->orWhereHas('khachHang', function ($qKh) use ($kw) {
                        $qKh->where('ho_ten', 'like', $kw)
                            ->orWhere('so_dien_thoai', 'like', $kw)
                            ->orWhere('email', 'like', $kw);
                    });
            });
        }

        if ($request->filled('loai_email')) {
            $query->where('loai_email', $request->loai_email);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('thoi_diem_gui', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('thoi_diem_gui', '<=', $request->den_ngay);
        }

        $nhatKyEmails = $query
            ->orderByDesc('thoi_diem_gui')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $thongKe = [
            'tong' => NhatKyEmail::count(),
            'thanh_cong' => NhatKyEmail::where('trang_thai', 'thanh_cong')->count(),
            'that_bai' => NhatKyEmail::where('trang_thai', 'that_bai')->count(),
            'thu_cong' => NhatKyEmail::where('loai_email', 'thu_cong')->count(),
        ];

        $khachHangs = KhachHang::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('ho_ten')
            ->limit(300)
            ->get(['id', 'ho_ten', 'email', 'so_dien_thoai']);

        return view('admin.nhat-ky-email.index', compact('nhatKyEmails', 'thongKe', 'khachHangs'));
    }

    public function sendManual(Request $request)
    {
        $validated = $request->validate([
            'khach_hang_id' => 'nullable|exists:khach_hang,id',
            'email_nguoi_nhan' => 'required|email|max:150',
            'loai_email' => 'required|in:dat_lich_hen,ky_gui,canh_bao_gia,xac_thuc,chao_mung,thu_cong',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string|max:20000',
        ], [
            'email_nguoi_nhan.required' => 'Vui lòng nhập email người nhận.',
            'email_nguoi_nhan.email' => 'Email người nhận không hợp lệ.',
            'tieu_de.required' => 'Vui lòng nhập tiêu đề email.',
            'noi_dung.required' => 'Vui lòng nhập nội dung email.',
        ]);

        $nhanVienId = Auth::guard('nhanvien')->id();

        try {
            $htmlNoiDung = nl2br(e($validated['noi_dung']));

            Mail::html($htmlNoiDung, function ($message) use ($validated) {
                $message->to($validated['email_nguoi_nhan'])
                    ->subject($validated['tieu_de']);
            });

            NhatKyEmail::create([
                'khach_hang_id' => $validated['khach_hang_id'] ?? null,
                'nhan_vien_id' => $nhanVienId,
                'loai_email' => $validated['loai_email'],
                'email_nguoi_nhan' => $validated['email_nguoi_nhan'],
                'tieu_de' => $validated['tieu_de'],
                'noi_dung' => $validated['noi_dung'],
                'trang_thai' => 'thanh_cong',
                'doi_tuong_lien_quan' => !empty($validated['khach_hang_id']) ? 'khach_hang' : null,
                'doi_tuong_id' => $validated['khach_hang_id'] ?? null,
                'thoi_diem_gui' => now(),
            ]);

            return redirect()->route('nhanvien.admin.nhat-ky-email.index')->with('success', 'Đã gửi email thủ công thành công!');
        } catch (\Throwable $e) {
            NhatKyEmail::create([
                'khach_hang_id' => $validated['khach_hang_id'] ?? null,
                'nhan_vien_id' => $nhanVienId,
                'loai_email' => $validated['loai_email'],
                'email_nguoi_nhan' => $validated['email_nguoi_nhan'],
                'tieu_de' => $validated['tieu_de'],
                'noi_dung' => $validated['noi_dung'],
                'trang_thai' => 'that_bai',
                'loi' => mb_substr($e->getMessage(), 0, 1000),
                'doi_tuong_lien_quan' => !empty($validated['khach_hang_id']) ? 'khach_hang' : null,
                'doi_tuong_id' => $validated['khach_hang_id'] ?? null,
                'thoi_diem_gui' => now(),
            ]);

            return back()->withInput()->with('error', 'Gửi email thất bại: ' . $e->getMessage());
        }
    }
}
