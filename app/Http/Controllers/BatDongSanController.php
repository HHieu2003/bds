<?php

namespace App\Http\Controllers;

use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BatDongSanController extends Controller
{
    // 1. DANH SÁCH (ADMIN)
    public function index()
    {
        $bat_dong_sans = BatDongSan::with('duAn')->orderBy('created_at', 'desc')->get();
        return view('admin.bat_dong_san.index', ['bat_dong_sans' => $bat_dong_sans]);
    }

    // 2. FORM TẠO MỚI
    public function create()
    {
        $du_ans = DuAn::all();
        return view('admin.bat_dong_san.create', ['du_ans' => $du_ans]);
    }

    // 3. XỬ LÝ LƯU
    public function store(Request $request)
    {
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
        $data['user_id'] = Auth::id();

        if ($request->hasFile('hinh_anh')) {
            $mediaPaths = [];
            foreach ($request->file('hinh_anh') as $file) {
                $path = $file->store('uploads/bds', 'public');
                $mediaPaths[] = $path;
            }
            $data['hinh_anh'] = $mediaPaths;
        }

        BatDongSan::create($data);
        return redirect()->route('admin.bat-dong-san.index')->with('success', 'Đăng tin thành công!');
    }

    // 4. HIỂN THỊ CHI TIẾT (FRONTEND) - Đây là phần bạn bị thiếu
    public function show($id)
    {
        // Tìm BĐS theo ID, kèm thông tin dự án
        $batDongSan = BatDongSan::with('duAn')->findOrFail($id);

        // Trả về view chi tiết phía khách hàng
        return view('frontend.bat_dong_san.show', compact('batDongSan'));
    }

    // 5. FORM SỬA (ADMIN)
    public function edit(BatDongSan $batDongSan)
    {
        $du_ans = DuAn::all();
        return view('admin.bat_dong_san.edit', ['batDongSan' => $batDongSan, 'du_ans' => $du_ans]);
    }

    // 6. XỬ LÝ CẬP NHẬT
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

        if ($request->hasFile('hinh_anh')) {
            $currentMedia = $batDongSan->hinh_anh ?? [];
            foreach ($request->file('hinh_anh') as $file) {
                $path = $file->store('uploads/bds', 'public');
                $currentMedia[] = $path;
            }
            $data['hinh_anh'] = $currentMedia;
        }

        $batDongSan->update($data);
        return redirect()->route('admin.bat-dong-san.index')->with('success', 'Cập nhật thành công!');
    }

    // 7. XÓA BĐS
    public function destroy(BatDongSan $batDongSan)
    {
        $batDongSan->delete();
        return redirect()->route('admin.bat-dong-san.index')->with('success', 'Đã xóa tin đăng!');
    }
}
