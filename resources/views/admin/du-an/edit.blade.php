@extends('admin.layouts.master')
@section('title', 'Sửa dự án: ' . $duAn->ten_du_an)

@section('content')

    <div class="page-header">
        <div class="page-header-left">
            <h1><i class="fas fa-edit"></i> Chỉnh sửa dự án</h1>
            <p>{{ $duAn->ten_du_an }}</p>
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

    <form action="{{ route('nhanvien.admin.du-an.update', $duAn) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.du-an._form')
    </form>

@endsection
