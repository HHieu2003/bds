@extends('admin.layouts.master')
@section('title', 'Sửa bài viết')

@section('content')
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div>
            <h1
                style="font-size:1.4rem;font-weight:800;color:#1a3c5e;margin:0 0 4px;display:flex;align-items:center;gap:9px">
                <i class="fas fa-edit" style="color:#FF8C42"></i> Sửa bài viết
            </h1>
        </div>
        <div style="display:flex;gap:8px">

            {{-- NÚT XEM TRƯỚC ĐÃ ĐƯỢC ĐƯA TRỞ LẠI --}}
            <a href="{{ route('frontend.tin-tuc.show', $baiViet->slug) }}" target="_blank"
                style="background:#f0f7ff;color:#2d6a9f;border:1.5px solid #d0e8ff;padding:9px 16px;border-radius:10px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px">
                <i class="fas fa-eye"></i> Xem trước
            </a>

            <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
                style="background:#f5f7ff;color:#555;border:1.5px solid #e8e8e8;padding:9px 18px;border-radius:10px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    {{-- GỌI FORM DÙNG CHUNG --}}
    <form method="POST" action="{{ route('nhanvien.admin.bai-viet.update', $baiViet) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.bai-viet._form')
    </form>
@endsection
