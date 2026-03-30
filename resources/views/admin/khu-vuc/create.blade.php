@extends('admin.layouts.master')
@section('title', 'Thêm khu vực mới')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem">
                <li class="breadcrumb-item"><a href="{{ route('nhanvien.admin.khu-vuc.index') }}"
                        class="text-decoration-none text-muted"><i class="fas fa-map-marked-alt"></i> Khu vực</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
        <h1 class="page-header-title"><i class="fas fa-plus-circle"></i> Thêm khu vực mới</h1>
    </div>

    <form id="kvForm" method="POST" action="{{ route('nhanvien.admin.khu-vuc.store') }}">
        @csrf
        @include('admin.khu-vuc._form', [
            'khuVuc' => null,
            'capMacDinh' => $capMacDinh,
            'chaMacDinh' => $chaMacDinh,
            'tinhThanhs' => $tinhThanhs,
            'quanHuyens' => $quanHuyens,
        ])
    </form>
@endsection
