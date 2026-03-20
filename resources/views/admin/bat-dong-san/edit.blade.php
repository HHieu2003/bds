@extends('admin.layouts.master')
@section('title', 'Sửa BĐS: ' . $batDongSan->ma_bat_dong_san)

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-edit"></i> Chỉnh sửa BĐS
                <span style="font-size:.85rem;font-weight:600;color:#FF8C42;margin-left:6px">
                    #{{ $batDongSan->ma_bat_dong_san }}
                </span>
            </h1>
            <p class="page-sub">{{ Str::limit($batDongSan->tieu_de, 70) }}</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            @if ($batDongSan->slug)
                <a href="{{ route('frontend.bat-dong-san.show', $batDongSan->slug) }}" target="_blank" class="btn-preview">
                    <i class="fas fa-eye"></i> Xem trang
                </a>
            @endif
            <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="flash-success">{!! session('success') !!}</div>
    @endif

    @if ($errors->any())
        <div class="err-box">
            <strong><i class="fas fa-exclamation-triangle"></i> Có {{ $errors->count() }} lỗi:</strong>
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('nhanvien.admin.bat-dong-san.update', $batDongSan) }}" method="POST"
        enctype="multipart/form-data" id="bdsForm">
        @csrf @method('PUT')
        @include('admin.bat-dong-san._form')
    </form>

@endsection
