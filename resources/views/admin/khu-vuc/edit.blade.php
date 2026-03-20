{{-- edit.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Sửa — ' . $khuVuc->ten_khu_vuc)
@section('content')

    <div class="kv-form-hdr">
        <div>
            <div class="kv-bc">
                <a href="{{ route('nhanvien.admin.khu-vuc.index') }}">
                    <i class="fas fa-map-marked-alt"></i> Khu vực
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $khuVuc->ten_khu_vuc }}</span>
            </div>
            <h1 class="kv-form-ttl"><i class="fas fa-edit"></i> Chỉnh sửa khu vực</h1>
        </div>
    </div>

    <form id="kvForm" method="POST" action="{{ route('nhanvien.admin.khu-vuc.update', $khuVuc) }}">
        @csrf @method('PUT')
        @include('admin.khu-vuc._form', [
            'khuVuc' => $khuVuc,
            'capMacDinh' => $khuVuc->cap_khu_vuc,
            'chaMacDinh' => $khuVuc->khu_vuc_cha_id,
            'tinhThanhs' => $tinhThanhs,
            'quanHuyens' => $quanHuyens,
        ])
    </form>

@endsection
