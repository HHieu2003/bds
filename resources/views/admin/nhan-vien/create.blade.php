@extends('admin.layouts.master')
@section('title', 'Thêm Nhân viên')
@section('page_title', 'Thêm nhân viên')
@section('page_parent', 'Nhân viên')

@section('content')
    <div style="max-width:780px;margin:0 auto">

        {{-- Header --}}
        <div class="nv-page-header" style="margin-bottom:1.25rem">
            <div>
                <h1 class="nv-page-title" style="font-size:1.15rem">
                    <i class="fas fa-user-plus"></i> Thêm nhân viên mới
                </h1>
                <p class="nv-page-sub">Tạo tài khoản và phân quyền cho nhân viên</p>
            </div>
            <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="nv-btn-cancel"
                style="display:inline-flex;align-items:center;gap:.4rem;text-decoration:none">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @include('admin.nhan-vien._form', ['nhanVien' => null])
    </div>
@endsection
