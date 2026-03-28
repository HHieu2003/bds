{{-- resources/views/admin/bai-viet/create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Viết bài mới')

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.3rem;color:var(--navy)">
                <i class="fas fa-pen-nib me-2" style="color:var(--primary)"></i>Viết bài mới
            </h1>
            <nav style="font-size:.78rem;color:var(--text-sub)" aria-label="breadcrumb">
                <a href="{{ route('nhanvien.dashboard') }}" class="text-decoration-none" style="color:var(--text-sub)">
                    <i class="fas fa-home"></i>
                </a>
                <span class="mx-1" style="color:var(--text-muted)"><i class="fas fa-chevron-right"
                        style="font-size:.6rem"></i></span>
                <a href="{{ route('nhanvien.admin.bai-viet.index') }}" class="text-decoration-none"
                    style="color:var(--text-sub)" onmouseover="this.style.color='var(--primary)'"
                    onmouseout="this.style.color='var(--text-sub)'">Bài
                    viết</a>
                <span class="mx-1" style="color:var(--text-muted)"><i class="fas fa-chevron-right"
                        style="font-size:.6rem"></i></span>
                <span style="font-weight:700;color:var(--navy)">Viết mới</span>
            </nav>
        </div>
        <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
            class="btn btn-secondary d-flex align-items-center gap-2 flex-shrink-0">
            <i class="fas fa-arrow-left"></i>
            <span class="d-none d-sm-inline">Quay lại</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
            <div>
                <div class="fw-bold mb-1">Vui lòng kiểm tra lại:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $err)
                        <li style="font-size:.83rem">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('nhanvien.admin.bai-viet.store') }}" method="POST" enctype="multipart/form-data" id="bvForm"
        novalidate>
        @csrf
        @include('admin.bai-viet._form', ['baiViet' => null, 'isEdit' => false])
    </form>

@endsection
