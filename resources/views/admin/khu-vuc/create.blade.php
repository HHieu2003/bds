{{-- create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Thêm khu vực mới')
@section('content')

    <div class="kv-form-hdr">
        <div>
            <div class="kv-bc">
                <a href="{{ route('nhanvien.admin.khu-vuc.index') }}">
                    <i class="fas fa-map-marked-alt"></i> Khu vực
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>Thêm mới</span>
            </div>
            <h1 class="kv-form-ttl"><i class="fas fa-plus-circle"></i> Thêm khu vực mới</h1>
        </div>
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
