@extends('admin.layouts.master')
@section('title', 'Thẩm định Ký gửi - ' . $kyGui->ho_ten_chu_nha)

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.ky-gui.index') }}"
                        class="text-decoration-none text-muted"><i class="fas fa-file-signature"></i> Quản lý Ký gửi</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $kyGui->ho_ten_chu_nha }}</li>
            </ol>
        </nav>
        <h1 class="page-header-title"><i class="fas fa-search text-warning"></i> Thẩm định Hồ sơ <span
                class="badge bg-light text-primary border ms-2">#KG-{{ str_pad($kyGui->id, 5, '0', STR_PAD_LEFT) }}</span>
        </h1>
    </div>

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

    <form method="POST" action="{{ route('nhanvien.admin.ky-gui.update', $kyGui) }}" enctype="multipart/form-data"
        id="kgAdminForm">
        @csrf @method('PUT')
        @include('admin.ky-gui._form', ['kyGui' => $kyGui])
    </form>
@endsection
