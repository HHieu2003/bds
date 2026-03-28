{{-- resources/views/admin/nhan-vien/create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Thêm nhân viên mới')

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.3rem;color:var(--navy)">
                <i class="fas fa-user-plus me-2" style="color:var(--primary)"></i>Thêm nhân viên mới
            </h1>
            <nav style="font-size:.78rem;color:var(--text-sub)" aria-label="breadcrumb">
                <a href="{{ route('nhanvien.admin.dashboard') }}" class="text-decoration-none" style="color:var(--text-sub)">
                    <i class="fas fa-home"></i>
                </a>
                <span class="mx-1" style="color:var(--text-muted)"><i class="fas fa-chevron-right"
                        style="font-size:.6rem"></i></span>
                <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="text-decoration-none"
                    style="color:var(--text-sub);transition:color .2s" onmouseover="this.style.color='var(--primary)'"
                    onmouseout="this.style.color='var(--text-sub)'">
                    Nhân viên
                </a>
                <span class="mx-1" style="color:var(--text-muted)"><i class="fas fa-chevron-right"
                        style="font-size:.6rem"></i></span>
                <span style="font-weight:700;color:var(--navy)">Thêm mới</span>
            </nav>
        </div>
        <a href="{{ route('nhanvien.admin.nhan-vien.index') }}"
            class="btn btn-secondary d-flex align-items-center gap-2 flex-shrink-0">
            <i class="fas fa-arrow-left"></i>
            <span class="d-none d-sm-inline">Quay lại</span>
        </a>
    </div>

    {{-- Flash errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
            <div>
                <div class="fw-bold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $err)
                        <li style="font-size:.83rem">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('nhanvien.admin.nhan-vien.store') }}" method="POST" enctype="multipart/form-data"
        id="nvCreateForm" novalidate>
        @csrf
        @include('admin.nhan-vien._form', ['nhanVien' => null, 'isEdit' => false])
    </form>

@endsection
