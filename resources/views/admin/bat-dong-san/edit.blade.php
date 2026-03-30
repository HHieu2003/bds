@extends('admin.layouts.master')
@section('title', 'Sửa BĐS: ' . $batDongSan->ma_bat_dong_san)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                    <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.bat-dong-san.index') }}"
                            class="text-decoration-none text-muted"><i class="fas fa-building"></i> Bất động sản</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $batDongSan->ma_bat_dong_san }}</li>
                </ol>
            </nav>
            <h1 class="page-header-title"><i class="fas fa-edit text-primary"></i> Chỉnh sửa BĐS <span
                    class="badge bg-warning text-dark ms-2 fs-6">#{{ $batDongSan->ma_bat_dong_san }}</span></h1>
        </div>
        @if ($batDongSan->slug)
            <a href="{{ route('frontend.bat-dong-san.show', $batDongSan->slug) }}" target="_blank"
                class="btn btn-outline-primary bg-white shadow-sm">
                <i class="fas fa-external-link-alt me-1"></i> Xem trang hiển thị
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Vui lòng kiểm tra lại:</div>
            <ul class="mb-0 ps-3" style="font-size: 0.85rem">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
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
