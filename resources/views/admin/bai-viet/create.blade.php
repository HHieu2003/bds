@extends('admin.layouts.master')
@section('title', 'Thêm bài viết mới')

@section('content')
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <h1 style="font-size:1.4rem;font-weight:800;color:#1a3c5e;margin:0;display:flex;align-items:center;gap:9px">
            <i class="fas fa-plus-circle" style="color:#FF8C42"></i> Thêm bài viết mới
        </h1>
        <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
            style="background:#f5f7ff;color:#555;border:1.5px solid #e8e8e8;padding:9px 18px;border-radius:10px;font-weight:600;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- GỌI FORM DÙNG CHUNG --}}
    <form method="POST" action="{{ route('nhanvien.admin.bai-viet.store') }}" enctype="multipart/form-data">
        @csrf
        @php $baiViet = null; @endphp
        @include('admin.bai-viet._form')
    </form>
@endsection
