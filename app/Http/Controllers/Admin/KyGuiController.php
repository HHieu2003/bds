<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KyGui;
use App\Models\NhanVien;
use App\Models\ChuNha;
use App\Models\BatDongSan;
use App\Models\DuAn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KyGuiController extends Controller
{
    public function index(Request $request)
    {
        $query = KyGui::with(['khachHang', 'nhanVienPhuTrach'])->withCount([]);

        // Lấy tab hiện tại (mặc định là 'can_xu_ly')
        $activeTab = $request->get('tab', 'can_xu_ly');

        // Xử lý logic lọc theo Tab
        if ($activeTab === 'can_xu_ly') {
            $query->whereIn('trang_thai', ['cho_duyet', 'dang_tham_dinh']);
        }

        // Xử lý các bộ lọc tìm kiếm
        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(fn($q) => $q->where('ho_ten_chu_nha', 'like', $kw)
                ->orWhere('so_dien_thoai', 'like', $kw)
                ->orWhere('dia_chi', 'like', $kw));
        }

        // Nếu ở tab "Tất cả" thì mới áp dụng lọc trạng thái (nếu có chọn)
        // Nếu ở tab "Cần xử lý" thì bỏ qua lọc trạng thái từ form để tránh xung đột
        if ($activeTab === 'tat_ca' && $request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('nhu_cau')) $query->where('nhu_cau', $request->nhu_cau);
        if ($request->filled('nhan_vien_id')) $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_id);

        $sapXep = $request->get('sapxep', 'moi_nhat');
        if ($sapXep === 'cu_nhat') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $kyGuis = $query->paginate(15)->withQueryString();

        $thongKe = [
            'tong'           => KyGui::count(),
            'cho_duyet'      => KyGui::where('trang_thai', 'cho_duyet')->count(),
            'dang_tham_dinh' => KyGui::where('trang_thai', 'dang_tham_dinh')->count(),
            'da_duyet'       => KyGui::where('trang_thai', 'da_duyet')->count(),
            'tu_choi'        => KyGui::where('trang_thai', 'tu_choi')->count(),
        ];

        // Chỉ lấy nhân sự Nguồn hàng (và admin) để xử lý ký gửi
        $nhanViens = NhanVien::where('kich_hoat', true)->whereIn('vai_tro', ['admin', 'nguon_hang'])->orderBy('ho_ten')->get();

        return view('admin.ky-gui.index', compact('kyGuis', 'thongKe', 'nhanViens', 'activeTab'));
    }

    public function show(KyGui $kyGui)
    {
        $kyGui->load(['khachHang', 'nhanVienPhuTrach']);
        $nhanViens = NhanVien::where('kich_hoat', true)->whereIn('vai_tro', ['admin', 'nguon_hang'])->orderBy('ho_ten')->get();
        return view('admin.ky-gui.show', compact('kyGui', 'nhanViens'));
    }

    // AJAX cập nhật trạng thái
    public function xuLy(Request $request, KyGui $kyGui)
    {
        $request->validate([
            'trang_thai' => 'required|in:cho_duyet,dang_tham_dinh,da_duyet,tu_choi',
        ]);

        $kyGui->update([
            'trang_thai' => $request->trang_thai,
            'nhan_vien_phu_trach_id' => $kyGui->nhan_vien_phu_trach_id ?? Auth::guard('nhanvien')->id(),
            'thoi_diem_xu_ly' => now(),
        ]);

        if ($request->expectsJson()) return response()->json(['ok' => true]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function create()
    {
        return view('admin.ky-gui.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['nguon_ky_gui'] = $request->get('nguon_ky_gui', 'phone');
        $data['trang_thai']   = $request->get('trang_thai', 'cho_duyet');
        $data['hinh_anh_tham_khao'] = $this->uploadImages($request);

        KyGui::create($data);
        return redirect()->route('nhanvien.admin.ky-gui.index')->with('success', 'Đã tạo ký gửi thành công!');
    }

    public function edit(KyGui $kyGui)
    {
        return view('admin.ky-gui.edit', compact('kyGui'));
    }

    public function update(Request $request, KyGui $kyGui)
    {
        $data = $this->validateData($request);

        // Đảm bảo $hinhCu luôn là mảng
        $hinhCu = is_array($kyGui->hinh_anh_tham_khao) ? $kyGui->hinh_anh_tham_khao : [];

        // Ép kiểu xoa_hinh_anh lấy từ form về mảng mặc định
        $xoaHinhAnh = $request->input('xoa_hinh_anh', []);

        if (is_array($xoaHinhAnh) && count($xoaHinhAnh) > 0) {
            foreach ($xoaHinhAnh as $path) {
                Storage::disk('public')->delete($path);
                $hinhCu = array_filter($hinhCu, fn($p) => $p !== $path);
            }
        }

        $hinhMoi  = $this->uploadImages($request);
        $data['hinh_anh_tham_khao'] = array_values(array_merge($hinhCu, $hinhMoi));

        $kyGui->update($data);
        return redirect()->route('nhanvien.admin.ky-gui.index')->with('success', 'Đã cập nhật ký gửi!');
    }

    public function destroy(KyGui $kyGui)
    {
        $hinhAnh = $kyGui->hinh_anh_tham_khao;
        if (is_array($hinhAnh) && count($hinhAnh) > 0) {
            foreach ($hinhAnh as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $kyGui->delete();
        return redirect()->route('nhanvien.admin.ky-gui.index')->with('success', 'Đã xóa yêu cầu ký gửi!');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'ho_ten_chu_nha'         => 'required|string|max:100',
            'so_dien_thoai'          => 'required|string|max:15',
            'email'                  => 'nullable|email|max:100',
            'loai_hinh'              => 'required|in:can_ho,nha_pho,biet_thu,dat_nen,shophouse',
            'nhu_cau'                => 'required|in:ban,thue',
            'dia_chi'                => 'nullable|string|max:255',
            'dien_tich'              => 'required|numeric|min:1',
            'tang'                   => 'nullable|string|max:100',
            'so_phong_ngu'           => 'nullable|string|max:20',
            'noi_that'               => 'nullable|string',
            'gia_ban_mong_muon'      => 'nullable|numeric|min:0',
            'phap_ly'                => 'nullable|string',
            'gia_thue_mong_muon'     => 'nullable|numeric|min:0',
            'hinh_thuc_thanh_toan'   => 'nullable|string',
            'ghi_chu'                => 'nullable|string|max:2000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'trang_thai'             => 'nullable|in:cho_duyet,dang_tham_dinh,da_duyet,tu_choi',
            'nguon_ky_gui'           => 'nullable|string'
        ]);
    }

    private function uploadImages(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('hinh_anh_tham_khao')) {
            foreach ($request->file('hinh_anh_tham_khao') as $file) {
                $paths[] = $file->store('ky-gui', 'public');
            }
        }
        return $paths;
    }

    // ========================================================
    // FORM XÁC NHẬN VÀ CHUYỂN ĐỔI KÝ GỬI -> BĐS
    // ========================================================

    public function duyetForm(KyGui $kyGui)
    {
        if (in_array($kyGui->trang_thai, ['da_duyet', 'tu_choi'])) {
            return redirect()->route('nhanvien.admin.ky-gui.index')->with('error', 'Yêu cầu ký gửi này đã được xử lý!');
        }

        $duAns = DuAn::orderBy('ten_du_an')->get();
        return view('admin.ky-gui.convert', compact('kyGui', 'duAns'));
    }

    public function duyetSubmit(Request $request, KyGui $kyGui)
    {
        $request->validate([
            'ho_ten_chu_nha' => 'required|string|max:100',
            'so_dien_thoai'  => 'required|string|max:20',
            'tieu_de'        => 'required|string|max:255',
            'nhu_cau'        => 'required|in:ban,thue',
            'loai_hinh'      => 'required|string',
            'dien_tich'      => 'required|numeric|min:1',
            'so_phong_ngu'   => 'nullable|integer|min:0',
            'tang'           => 'nullable|string|max:100',
            'gia'            => 'nullable|numeric|min:0',
            'gia_thue'       => 'nullable|numeric|min:0',
        ], [
            'tieu_de.required'    => 'Vui lòng nhập tiêu đề tin đăng.',
            'dien_tich.required'  => 'Vui lòng nhập diện tích.',
            'dien_tich.min'       => 'Diện tích phải lớn hơn 0.',
        ]);

        $nhanVienHienTai = Auth::guard('nhanvien')->id();

        // 1. TẠO HOẶC LẤY CHỦ NHÀ
        $chuNha = ChuNha::firstOrCreate(
            ['so_dien_thoai' => $request->so_dien_thoai],
            [
                'ho_ten'             => $request->ho_ten_chu_nha,
                'email'              => $request->email,
                'ghi_chu'            => 'Khách hàng từ hệ thống Ký gửi',
                'trang_thai_hop_tac' => 'dang_hop_tac'
            ]
        );

        // 2. TẠO BẤT ĐỘNG SẢN
        $bdsData = [
            'chu_nha_id'             => $chuNha->id,
            'nhan_vien_phu_trach_id' => $nhanVienHienTai,
            'du_an_id'               => $request->du_an_id ?: null,

            'tieu_de'         => $request->tieu_de,
            'slug'            => Str::slug($request->tieu_de) . '-' . time(),
            'ma_bat_dong_san' => 'KG-' . strtoupper(Str::random(6)),

            'loai_hinh'    => $request->loai_hinh,
            'nhu_cau'      => $request->nhu_cau,
            'dien_tich'    => $request->dien_tich,
            'tang'         => $request->tang,
            'gia'          => $request->nhu_cau === 'ban' ? $request->gia : null,
            'gia_thue'     => $request->nhu_cau === 'thue' ? $request->gia_thue : null,
            'mo_ta'        => $request->mo_ta,
            'so_phong_ngu' => $request->so_phong_ngu ? (int)$request->so_phong_ngu : null,
            'noi_that'     => $request->noi_that,
            'phap_ly'      => $request->nhu_cau === 'ban' ? $request->phap_ly : null,
            'hinh_thuc_thanh_toan' => $request->nhu_cau === 'thue' ? $request->hinh_thuc_thanh_toan : null,

            'trang_thai'     => 'con_hang',
            'hien_thi'       => 1,
            'thoi_diem_dang' => now(),
        ];

        // Copy ảnh tự động từ ky gởi sang BDS
        // Kiểm tra album_anh có phải JSON cast không — nếu có, trực tiếp gán mảng
        if ($kyGui->hinh_anh_tham_khao && is_array($kyGui->hinh_anh_tham_khao) && count($kyGui->hinh_anh_tham_khao) > 0) {
            $bdsData['hinh_anh']  = $kyGui->hinh_anh_tham_khao[0];  // ảnh đại diện
            $bdsData['album_anh'] = $kyGui->hinh_anh_tham_khao;     // mảng — Eloquent tự encode nếu cast array
        }

        BatDongSan::create($bdsData);

        // 3. CẬP NHẬT TRẠNG THÁI KÝ GỬI
        $kyGui->update([
            'trang_thai' => 'da_duyet',
            'nhan_vien_phu_trach_id' => $nhanVienHienTai,
            'thoi_diem_xu_ly' => now()
        ]);

        return redirect()->route('nhanvien.admin.bat-dong-san.index')
            ->with('success', '🎉 Tuyệt vời! Đã duyệt thành công, Chủ nhà và BĐS đã được tự động thêm vào Kho!');
    }
}
