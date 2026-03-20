@extends('admin.layouts.master')
@section('title', 'Thêm bất động sản mới')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="fas fa-plus-circle"></i> Thêm BĐS mới</h1>
            <p class="page-sub">Hệ thống sẽ tự động tạo mã BĐS sau khi lưu</p>
        </div>
        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
        <div class="err-box">
            <strong><i class="fas fa-exclamation-triangle"></i> Có {{ $errors->count() }} lỗi cần sửa:</strong>
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('nhanvien.admin.bat-dong-san.store') }}" method="POST" enctype="multipart/form-data"
        id="bdsForm">
        @csrf
        @php $batDongSan = null; @endphp
        @include('admin.bat-dong-san._form')
    </form>

@endsection
