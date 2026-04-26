<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DuAn;
use App\Models\KyGui;
use App\Models\NhatKyEmail;
use App\Mail\KyGuiSuccessMail; // Import Class Mail vừa tạo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // Import Facade Mail
use Illuminate\Support\Facades\Storage;

class KyGuiController extends Controller
{
    public function create()
    {
        $duAnGoiY = DuAn::hienThi()
            ->orderBy('ten_du_an')
            ->pluck('ten_du_an')
            ->filter()
            ->values();

        return view('frontend.ky-gui.create', compact('duAnGoiY'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'so_dien_thoai' => preg_replace('/\D+/', '', (string) $request->input('so_dien_thoai', '')),
            'dien_tich' => $this->normalizeDecimalInput($request->input('dien_tich')),
            'gia_ban_mong_muon' => $this->normalizeDecimalInput($request->input('gia_ban_mong_muon')),
            'gia_thue_mong_muon' => $this->normalizeDecimalInput($request->input('gia_thue_mong_muon')),
        ]);

        $request->validate([
            'ho_ten_chu_nha' => 'required|string|max:100',
            'so_dien_thoai'  => 'required|string|regex:/^0\d{9,12}$/',
            'email'          => 'nullable|email|max:100',
            'loai_hinh'      => 'nullable|in:can_ho,nha_pho,biet_thu,dat_nen,shophouse',
            'nhu_cau'        => 'required|in:ban,thue',
            'du_an'          => 'nullable|string|max:150',
            'ma_can'         => 'required|string|max:50',
            'dia_chi'        => 'nullable|string|max:255',
            'dien_tich'      => 'nullable|numeric|min:1|max:99999',
            'tang'           => 'nullable|string|max:100',
            'so_phong_ngu'   => 'nullable|string|max:20',
            'noi_that'       => 'nullable|string',
            'gia_ban_mong_muon'    => 'nullable|numeric|min:0',
            'phap_ly'              => 'nullable|string',
            'gia_thue_mong_muon'   => 'nullable|numeric|min:0',
            'hinh_thuc_thanh_toan' => 'nullable|string',
            'hinh_anh_tham_khao.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'ghi_chu'        => 'nullable|string|max:2000',
        ], [
            'so_dien_thoai.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và có từ 10 đến 13 chữ số.',
            'email.email' => 'Email không đúng định dạng.',
            'ma_can.required' => 'Vui lòng nhập mã căn.',
            'dien_tich.numeric' => 'Diện tích chỉ được nhập số (có thể có phần thập phân).',
            'gia_ban_mong_muon.numeric' => 'Giá bán chỉ được nhập số nguyên.',
            'gia_thue_mong_muon.numeric' => 'Giá thuê chỉ được nhập số (có thể có phần thập phân).',
        ]);

        $hinhAnh = [];
        if ($request->hasFile('hinh_anh_tham_khao')) {
            foreach ($request->file('hinh_anh_tham_khao') as $file) {
                $hinhAnh[] = $file->store('ky-gui', 'r2');
            }
        }

        // 1. Gán kết quả tạo vào biến $kyGui
        $kyGui = KyGui::create([
            'khach_hang_id'        => Auth::guard('customer')->id(),
            'ho_ten_chu_nha'       => $request->ho_ten_chu_nha,
            'so_dien_thoai'        => $request->so_dien_thoai,
            'email'                => $request->email,
            'loai_hinh'            => $request->loai_hinh ?? 'can_ho',
            'nhu_cau'              => $request->nhu_cau,
            'du_an'                => $request->du_an,
            'ma_can'               => $request->ma_can,
            'dia_chi'              => $request->dia_chi,
            'dien_tich'            => $request->dien_tich ?? 1,
            'tang'                 => $request->tang,
            'so_phong_ngu'         => $request->so_phong_ngu,
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

        // 3. Tiến hành gửi mail nếu có email (gửi sau khi response đã trả về)
        if ($sendToEmail) {
            dispatch(function () use ($sendToEmail, $kyGui) {
                try {
                    $mail = new KyGuiSuccessMail($kyGui);
                    $noiDung = $mail->render();

                    Mail::to($sendToEmail)->send($mail);

                    NhatKyEmail::create([
                        'khach_hang_id' => $kyGui->khach_hang_id,
                        'nhan_vien_id' => null,
                        'loai_email' => 'ky_gui',
                        'email_nguoi_nhan' => $sendToEmail,
                        'tieu_de' => 'Xác nhận tiếp nhận yêu cầu ký gửi',
                        'noi_dung' => $noiDung,
                        'trang_thai' => 'thanh_cong',
                        'doi_tuong_lien_quan' => 'ky_gui',
                        'doi_tuong_id' => $kyGui->id,
                        'thoi_diem_gui' => now(),
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Gui email ky gui that bai', [
                        'ky_gui_id' => $kyGui->id,
                        'email' => $sendToEmail,
                        'error' => $e->getMessage(),
                    ]);

                    NhatKyEmail::create([
                        'khach_hang_id' => $kyGui->khach_hang_id,
                        'nhan_vien_id' => null,
                        'loai_email' => 'ky_gui',
                        'email_nguoi_nhan' => $sendToEmail,
                        'tieu_de' => 'Xác nhận tiếp nhận yêu cầu ký gửi',
                        'noi_dung' => null,
                        'trang_thai' => 'that_bai',
                        'loi' => mb_substr($e->getMessage(), 0, 1000),
                        'doi_tuong_lien_quan' => 'ky_gui',
                        'doi_tuong_id' => $kyGui->id,
                        'thoi_diem_gui' => now(),
                    ]);
                }
            })->afterResponse();
        }

        return redirect()->route('frontend.ky-gui.success')->with('success', 'Gửi ký gửi thành công!');
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

    public function huy(KyGui $kyGui)
    {
        $customerId = Auth::guard('customer')->id();

        if ((int) $kyGui->khach_hang_id !== (int) $customerId) {
            abort(403);
        }

        if (in_array($kyGui->trang_thai, ['da_duyet'], true)) {
            return back()->with('error', 'Yêu cầu đã được duyệt, bạn không thể hủy trực tiếp. Vui lòng liên hệ chuyên viên hỗ trợ.');
        }

        $kyGui->delete();

        return back()->with('success', 'Đã hủy yêu cầu ký gửi thành công.');
    }

    private function normalizeDecimalInput($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $raw = str_replace([' ', "\u{00A0}"], '', $raw);

        // vi-VN input: 1.234,56 => 1234.56
        if (str_contains($raw, ',') && str_contains($raw, '.')) {
            $raw = str_replace('.', '', $raw);
            $raw = str_replace(',', '.', $raw);
        } elseif (str_contains($raw, ',')) {
            // 123,45 => 123.45
            $raw = str_replace(',', '.', $raw);
        } else {
            // 1,234.56 or 1,234
            $raw = str_replace(',', '', $raw);
        }

        return $raw;
    }
}
