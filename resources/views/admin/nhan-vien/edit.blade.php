{{-- edit.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Sửa — ' . $nhanVien->ho_ten)

@section('content')
    <div class="nv-form-hdr">
        <div>
            <div class="nv-bc">
                <a href="{{ route('nhanvien.admin.nhan-vien.index') }}">
                    <i class="fas fa-id-badge"></i> Nhân viên
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $nhanVien->ho_ten }}</span>
            </div>
            <h1 class="nv-form-ttl">
                <i class="fas fa-user-edit"></i> Chỉnh sửa nhân viên
            </h1>
        </div>
        <a href="{{ route('nhanvien.admin.nhan-vien.show', $nhanVien) }}" class="nv-btn-show">
            <i class="fas fa-eye"></i> Xem chi tiết
        </a>
    </div>

    <form id="nvForm" method="POST" action="{{ route('nhanvien.admin.nhan-vien.update', $nhanVien) }}"
        enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.nhan-vien._form')
    </form>
@endsection

@push('styles')
    <style>
        .nv-btn-show {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 9px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none;
            transition: all .2s
        }

        .nv-btn-show:hover {
            background: #1a3c5e;
            color: #fff
        }
    </style>
@endpush
