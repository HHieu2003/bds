{{-- create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Thêm nhân viên mới')

@section('content')
    <div class="nv-form-hdr">
        <div>
            <div class="nv-bc">
                <a href="{{ route('nhanvien.admin.nhan-vien.index') }}">
                    <i class="fas fa-id-badge"></i> Nhân viên
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>Thêm mới</span>
            </div>
            <h1 class="nv-form-ttl">
                <i class="fas fa-user-plus"></i> Thêm nhân viên mới
            </h1>
        </div>
    </div>

    <form id="nvForm" method="POST" action="{{ route('nhanvien.admin.nhan-vien.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.nhan-vien._form')
    </form>
@endsection
