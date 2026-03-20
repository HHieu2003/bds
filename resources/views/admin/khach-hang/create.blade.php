@extends('admin.layouts.master')
@section('title', 'Thêm khách hàng mới')

@section('content')

    <div class="kh-form-header">
        <div>
            <div class="kh-breadcrumb">
                <a href="{{ route('nhanvien.admin.khach-hang.index') }}">
                    <i class="fas fa-users"></i> Khách hàng
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>Thêm mới</span>
            </div>
            <h1 class="kh-form-title">
                <i class="fas fa-user-plus"></i> Thêm khách hàng mới
            </h1>
        </div>
    </div>

    <form id="khForm" method="POST" action="{{ route('nhanvien.admin.khach-hang.store') }}">
        @csrf
        @include('admin.khach-hang._form')
    </form>

@endsection
