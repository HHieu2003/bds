@extends('admin.layouts.master')
@section('title', 'Thêm Chủ nhà mới')
@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.chu-nha.index') }}"
                        class="text-decoration-none text-muted"><i class="fas fa-user-tie"></i> Chủ nhà</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
        <h1 class="page-header-title"><i class="fas fa-plus-circle text-primary"></i> Thêm Chủ nhà (Nguồn hàng)</h1>
    </div>
    <form action="{{ route('nhanvien.admin.chu-nha.store') }}" method="POST">
        @csrf @include('admin.chu-nha._form', ['chuNha' => null])
    </form>
@endsection
