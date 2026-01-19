<?php

namespace App\Http\Controllers;

use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\User; // Thêm dòng này
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Thư viện xử lý chuỗi cho SEO
use Illuminate\Support\Facades\Auth;

class BatDongSanController extends Controller
{
    public function index()
    {
        $danhSachBDS = BatDongSan::with('duAn')->orderBy('created_at', 'desc')->get();
        return view('admin.bat_dong_san.index', compact('danhSachBDS'));
    }

    public function create()
    {
        $danhSachDuAn = DuAn::all();
        return view('admin.bat_dong_san.create', compact('danhSachDuAn'));
    }

    public function store(Request $request)
    {
        // 1. Validate: Cho phép Ảnh + Video (tối đa 50MB)
        $request->validate([
            'tieu_de' => 'required|max:255',
            'du_an_id' => 'required|exists:du_an,id',
            'toa' => 'required',
            'ma_can' => 'required',
            'gia' => 'required|numeric',
            'hinh_anh.*' => 'mimes:jpeg,png,jpg,gif,mp4,mov,avi,qt|max:51200',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->tieu_de) . '-' . time();
        $data['user_id'] = Auth::id(); // Lấy ID người đang đăng nhập

        // 2. Upload Ảnh & Video
        if ($request->hasFile('hinh_anh')) {
            $mediaPaths = [];
            foreach ($request->file('hinh_anh') as $file) {
                // Lưu vào storage/app/public/uploads/bds
                $path = $file->store('uploads/bds', 'public');
                $mediaPaths[] = $path;
            }
            $data['hinh_anh'] = $mediaPaths;
        }

        BatDongSan::create($data);
        return redirect()->route('bat-dong-san.index')->with('success', 'Đăng tin thành công!');
    }

    // 4. FORM SỬA
    public function edit(BatDongSan $batDongSan)
    {
        $danhSachDuAn = DuAn::all();
        // Trả về view edit cùng dữ liệu BĐS cần sửa
        return view('admin.bat_dong_san.edit', compact('batDongSan', 'danhSachDuAn'));
    }

    // 5. XỬ LÝ CẬP NHẬT
    public function update(Request $request, BatDongSan $batDongSan)
    {
        $request->validate([
            'tieu_de' => 'required|max:255',
            'hinh_anh.*' => 'mimes:jpeg,png,jpg,gif,mp4,mov,avi,qt|max:51200',
        ]);

        $data = $request->all();
        if ($batDongSan->tieu_de != $request->tieu_de) {
            $data['slug'] = Str::slug($request->tieu_de) . '-' . time();
        }

        // Nếu có upload thêm file mới thì gộp vào mảng cũ
        if ($request->hasFile('hinh_anh')) {
            $currentMedia = $batDongSan->hinh_anh ?? [];
            foreach ($request->file('hinh_anh') as $file) {
                $path = $file->store('uploads/bds', 'public');
                $currentMedia[] = $path;
            }
            $data['hinh_anh'] = $currentMedia;
        }

        $batDongSan->update($data);
        return redirect()->route('bat-dong-san.index')->with('success', 'Cập nhật thành công!');
    }
    // 6. XÓA BĐS
    public function destroy(BatDongSan $batDongSan)
    {
        // (Nâng cao: Có thể code thêm đoạn xóa file ảnh trong Storage để đỡ rác)
        $batDongSan->delete();
        return redirect()->route('bat-dong-san.index')->with('success', 'Đã xóa tin đăng!');
    }
}
