@extends('admin.layouts.master')
@section('title', 'Thêm dự án mới')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.du-an.index') }}"
                        class="text-decoration-none text-muted"><i class="fas fa-city"></i> Dự án</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
        <h1 class="page-header-title"><i class="fas fa-plus-circle text-primary"></i> Thêm dự án mới</h1>
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

    <form action="{{ route('nhanvien.admin.du-an.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @php $duAn = null; @endphp
        @include('admin.du-an._form')
    </form>
@endsection
