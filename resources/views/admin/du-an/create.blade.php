@extends('admin.layouts.master')
@section('title', 'Thêm dự án mới')

@section('content')

    <div class="page-header">
        <div class="page-header-left">
            <h1><i class="fas fa-plus-circle"></i> Thêm dự án mới</h1>
            <p>Điền đầy đủ thông tin để tạo dự án</p>
        </div>
        <a href="{{ route('nhanvien.admin.du-an.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
        <div class="errors-summary">
            <strong><i class="fas fa-exclamation-triangle"></i> Vui lòng kiểm tra lại:</strong>
            <ul>
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
