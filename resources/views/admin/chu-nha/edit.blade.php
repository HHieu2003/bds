@extends('admin.layouts.master')
@section('title', 'Sửa Chủ nhà: ' . $chuNha->ho_ten)
@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.chu-nha.index') }}"
                        class="text-decoration-none text-muted"><i class="fas fa-user-tie"></i> Chủ nhà</a></li>
                <li class="breadcrumb-item active">{{ $chuNha->ho_ten }}</li>
            </ol>
        </nav>
        <h1 class="page-header-title"><i class="fas fa-edit text-primary"></i> Chỉnh sửa thông tin</h1>
    </div>
    <form action="{{ route('nhanvien.admin.chu-nha.update', $chuNha) }}" method="POST">
        @csrf @method('PUT') @include('admin.chu-nha._form', ['chuNha' => $chuNha])
    </form>
@endsection
