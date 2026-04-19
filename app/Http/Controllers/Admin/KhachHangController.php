<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\LichHen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KhachHangController extends Controller
{
    private function currentNhanVien(): NhanVien
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        abort_unless($nhanVien instanceof NhanVien, 401, 'Phiên đăng nhập nhân viên không hợp lệ.');

        return $nhanVien;
    }

    /**
     * MỨC ĐỘ TIỀM NĂNG (Theo CSDL của bạn: lanh, am, nong)
     */
    private function getMucDoTiemNang()
    {
        return [
            'nong' => ['label' => 'Nóng', 'color' => 'danger'],
            'am'   => ['label' => 'Ấm', 'color' => 'warning'],
            'lanh' => ['label' => 'Lạnh', 'color' => 'secondary'],
        ];
    }

    public function index(Request $request)
    {
        $nhanVien = $this->currentNhanVien();

        $query = KhachHang::with('nhanVienPhuTrach')->withCount('lichHens');

        // PHÂN QUYỀN
        if ($nhanVien->isSale()) {
            $query->where('nhan_vien_phu_trach_id', $nhanVien->id);
        }

        // BỘ LỌC TÌM KIẾM
        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ho_ten', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw)
                    ->orWhere('email', 'like', $kw);
            });
        }
        if ($request->filled('muc_do_tiem_nang')) {
            $query->where('muc_do_tiem_nang', $request->muc_do_tiem_nang);
        }
        if ($request->filled('nguon_khach_hang')) {
            $query->where('nguon_khach_hang', $request->nguon_khach_hang);
        }
        if ($request->filled('nhan_vien_id') && !$nhanVien->isSale()) {
            $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_id);
        }

        $khachHangs = $query->orderByDesc('lien_he_cuoi_at')->latest()->paginate(15)->withQueryString();

        $dsSale = NhanVien::where('vai_tro', 'sale')->where('kich_hoat', 1)->get();
        $mucDoTiemNang = $this->getMucDoTiemNang();

        // THỐNG KÊ
        $stats = [
            'tong' => (clone $query)->count(),
            'nong' => (clone $query)->where('muc_do_tiem_nang', 'nong')->count(),
            'am'   => (clone $query)->where('muc_do_tiem_nang', 'am')->count(),
        ];

        return view('admin.khach-hang.index', compact('khachHangs', 'dsSale', 'mucDoTiemNang', 'stats', 'nhanVien'));
    }

    public function store(Request $request)
    {
        $nhanVien = $this->currentNhanVien();

        $data = $request->validate([
            'ho_ten'                 => 'required|string|max:100',
            'so_dien_thoai'          => [
                'required', 'string', 'max:20',
                Rule::unique('khach_hang', 'so_dien_thoai')->whereNull('deleted_at'),
            ],
            'email'                  => [
                'nullable', 'email', 'max:100',
                Rule::unique('khach_hang', 'email')->whereNull('deleted_at'),
            ],
            'password'               => 'required|string|min:6|confirmed',
            'muc_do_tiem_nang'       => 'nullable|in:lanh,am,nong',
            'nguon_khach_hang'       => 'nullable|string',
            'ghi_chu_noi_bo'         => 'nullable|string',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id'
        ], [
            'ho_ten.required'                => 'Vui lòng nhập họ tên khách hàng.',
            'ho_ten.max'                     => 'Họ tên không được vượt quá 100 ký tự.',
            'so_dien_thoai.required'         => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.max'              => 'Số điện thoại không được vượt quá 20 ký tự.',
            'so_dien_thoai.unique'           => 'Số điện thoại này đã được sử dụng bởi khách hàng khác.',
            'email.email'                    => 'Địa chỉ email không hợp lệ.',
            'email.max'                      => 'Email không được vượt quá 100 ký tự.',
            'email.unique'                   => 'Email này đã được sử dụng bởi khách hàng khác.',
            'password.required'              => 'Vui lòng nhập mật khẩu.',
            'password.min'                   => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed'             => 'Xác nhận mật khẩu không khớp.',
            'muc_do_tiem_nang.in'            => 'Mức độ tiềm năng không hợp lệ.',
            'nhan_vien_phu_trach_id.exists'  => 'Nhân viên phụ trách không tồn tại.',
        ]);

        $data['nhan_vien_phu_trach_id'] = $nhanVien->isSale() ? $nhanVien->id : ($request->nhan_vien_phu_trach_id ?? null);
        $data['muc_do_tiem_nang'] = $request->muc_do_tiem_nang ?? 'am';
        $data['nguon_khach_hang'] = $request->nguon_khach_hang ?? 'sale';
        $data['password'] = Hash::make($data['password']);

        // Kiểm tra xem có record bị soft-delete trùng email/SĐT không.
        // Nếu có, cần restore thay vì create mới để tránh UniqueConstraintViolationException.
        $trashed = KhachHang::withTrashed()
            ->where(function ($q) use ($data) {
                if (!empty($data['email'])) {
                    $q->orWhere('email', $data['email']);
                }
                $q->orWhere('so_dien_thoai', $data['so_dien_thoai']);
            })
            ->whereNotNull('deleted_at')
            ->first();

        if ($trashed) {
            $trashed->restore();
            $trashed->forceFill($data)->save();
        } else {
            KhachHang::create($data);
        }

        return back()->with('success', 'Đã thêm Khách hàng mới thành công!');
    }


    public function show(KhachHang $khachHang)
    {
        $nhanVien = $this->currentNhanVien();

        if ($nhanVien->isSale() && $khachHang->nhan_vien_phu_trach_id !== $nhanVien->id) {
            abort(403, 'Bạn không có quyền truy cập hồ sơ khách hàng của nhân sự khác.');
        }

        $lichHens = LichHen::with(['batDongSan.khuVuc', 'nhanVienNguonHang'])
            ->where('khach_hang_id', $khachHang->id)
            ->latest('thoi_gian_hen')
            ->get();

        $mucDoTiemNang = $this->getMucDoTiemNang();
        $dsSale = NhanVien::where('vai_tro', 'sale')->where('kich_hoat', 1)->get();

        return view('admin.khach-hang.show', compact('khachHang', 'lichHens', 'mucDoTiemNang', 'dsSale', 'nhanVien'));
    }

    public function update(Request $request, KhachHang $khachHang)
    {
        $nhanVien = $this->currentNhanVien();
        $isAdmin = $nhanVien->hasRole('admin');

        if ($nhanVien->isSale() && $khachHang->nhan_vien_phu_trach_id !== $nhanVien->id) {
            abort(403, 'Bạn không có quyền sửa khách hàng này.');
        }

        // Checkbox duoc check => co key email_verified trong request.
        // Cach nay tranh xung dot gia tri khi form gui nhieu input cung ten.
        $emailVerifiedInput = $isAdmin ? $request->has('email_verified') : null;

        // Admin luon co the thay doi trang thai xac thuc,
        // khong bi phu thuoc vao validate cac field ho so.
        if ($isAdmin) {
            $verificationData = $emailVerifiedInput
                ? [
                    'email_xac_thuc_at' => $khachHang->email_xac_thuc_at ?? now(),
                    'kich_hoat' => true,
                    'verification_token' => null,
                    'token_expiry' => null,
                ]
                : [
                    'email_xac_thuc_at' => null,
                    'kich_hoat' => false,
                    'verification_token' => null,
                    'token_expiry' => null,
                ];

            $khachHang->forceFill($verificationData)->save();
            $khachHang->refresh();
        }

        $data = $request->validate([
            'ho_ten'                 => 'required|string|max:100',
            'so_dien_thoai'          => [
                'required', 'string', 'max:20',
                Rule::unique('khach_hang', 'so_dien_thoai')->ignore($khachHang->id)->whereNull('deleted_at'),
            ],
            'email'                  => [
                'nullable', 'email', 'max:100',
                Rule::unique('khach_hang', 'email')->ignore($khachHang->id)->whereNull('deleted_at'),
            ],
            'password'               => 'nullable|string|min:6|confirmed',
            'muc_do_tiem_nang'       => 'required|in:lanh,am,nong',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id'
        ], [
            'ho_ten.required'                => 'Vui lòng nhập họ tên khách hàng.',
            'ho_ten.max'                     => 'Họ tên không được vượt quá 100 ký tự.',
            'so_dien_thoai.required'         => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.max'              => 'Số điện thoại không được vượt quá 20 ký tự.',
            'so_dien_thoai.unique'           => 'Số điện thoại này đã được sử dụng bởi khách hàng khác.',
            'email.email'                    => 'Địa chỉ email không hợp lệ.',
            'email.max'                      => 'Email không được vượt quá 100 ký tự.',
            'email.unique'                   => 'Email này đã được sử dụng bởi khách hàng khác.',
            'password.min'                   => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed'             => 'Xác nhận mật khẩu không khớp.',
            'muc_do_tiem_nang.required'      => 'Vui lòng chọn mức độ tiềm năng.',
            'muc_do_tiem_nang.in'            => 'Mức độ tiềm năng không hợp lệ.',
            'nhan_vien_phu_trach_id.exists'  => 'Nhân viên phụ trách không tồn tại.',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($nhanVien->isSale()) unset($data['nhan_vien_phu_trach_id']);

        if ($isAdmin) {
            $data['email_xac_thuc_at'] = $khachHang->email_xac_thuc_at;
            $data['kich_hoat'] = $khachHang->kich_hoat;
            $data['verification_token'] = $khachHang->verification_token;
            $data['token_expiry'] = $khachHang->token_expiry;
        }

        $khachHang->update($data);

        return back()->with('success', 'Cập nhật hồ sơ Khách hàng thành công!');
    }

    public function storeNhatKy(Request $request, KhachHang $khachHang)
    {
        $nhanVien = $this->currentNhanVien();

        $request->validate([
            'noi_dung_cham_soc' => 'required|string|max:2000',
            'muc_do_tiem_nang'  => 'nullable|in:lanh,am,nong'
        ]);

        $thoiGian = now()->format('d/m/Y H:i');
        $nhatKyMoi = "[{$thoiGian}] {$nhanVien->ho_ten}:\n" . $request->noi_dung_cham_soc;

        $ghiChuHienTai = $khachHang->ghi_chu_noi_bo ? "\n\n---\n" . $khachHang->ghi_chu_noi_bo : "";
        $ghiChuMoiNhat = $nhatKyMoi . $ghiChuHienTai;

        $khachHang->update([
            'ghi_chu_noi_bo'   => $ghiChuMoiNhat,
            'muc_do_tiem_nang' => $request->muc_do_tiem_nang ?? $khachHang->muc_do_tiem_nang,
            'lien_he_cuoi_at'  => now() // Cập nhật thời gian gọi khách lần cuối
        ]);

        return back()->with('success', 'Đã lưu nhật ký chăm sóc thành công!');
    }

    public function destroy(KhachHang $khachHang)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole('admin'), 403, 'Chỉ Admin mới có quyền xóa dữ liệu.');

        if ($khachHang->lichHens()->count() > 0) {
            return back()->with('error', 'Không thể xóa khách đã có Lịch hẹn xem nhà.');
        }

        $khachHang->delete();
        return redirect()->route('nhanvien.admin.khach-hang.index')->with('success', 'Đã xóa dữ liệu Khách hàng!');
    }
}
