@extends('admin.layouts.master')
@section('title', 'Sửa khách hàng — ' . $khachHang->ten_hien_thi)

@section('content')

    <div class="kh-form-header">
        <div>
            <div class="kh-breadcrumb">
                <a href="{{ route('nhanvien.admin.khach-hang.index') }}">
                    <i class="fas fa-users"></i> Khách hàng
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $khachHang->ten_hien_thi }}</span>
            </div>
            <h1 class="kh-form-title">
                <i class="fas fa-user-edit"></i> Chỉnh sửa khách hàng
            </h1>
        </div>
        <a href="{{ route('nhanvien.admin.khach-hang.show', $khachHang) }}" class="kh-btn-detail">
            <i class="fas fa-eye"></i> Xem chi tiết
        </a>
    </div>

    <form id="khForm" method="POST" action="{{ route('nhanvien.admin.khach-hang.update', $khachHang) }}">
        @csrf @method('PUT')
        @include('admin.khach-hang._form')
    </form>

@endsection

@push('styles')
    <style>
        .kh-btn-detail {
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

        .kh-btn-detail:hover {
            background: #1a3c5e;
            color: #fff
        }
    </style>
@endpush
