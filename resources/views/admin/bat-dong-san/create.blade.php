@extends('admin.layouts.master')
@section('title', 'Thêm bất động sản mới')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.bat-dong-san.index') }}"
                        class="text-decoration-none text-muted"><i class="fas fa-building"></i> Bất động sản</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
        <h1 class="page-header-title"><i class="fas fa-plus-circle text-primary"></i> Đăng BĐS mới</h1>
        <p class="text-muted" style="font-size: 0.85rem">Hệ thống sẽ tự động tạo mã BĐS sau khi lưu thành công.</p>
    </div>

    <form action="{{ route('nhanvien.admin.bat-dong-san.store') }}" method="POST" enctype="multipart/form-data"
        id="bdsForm">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
        @php $batDongSan = null; @endphp
        @include('admin.bat-dong-san._form')
    </form>
@endsection
