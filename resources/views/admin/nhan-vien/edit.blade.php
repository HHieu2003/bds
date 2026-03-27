@extends('admin.layouts.master')
@section('title', 'Sửa — ' . $nhanVien->ho_ten)
@section('page_title', 'Chỉnh sửa nhân viên')
@section('page_parent', 'Nhân viên')

@section('content')
    <div style="max-width:780px;margin:0 auto">

        {{-- Header --}}
        <div class="nv-page-header" style="margin-bottom:1.25rem">
            <div style="display:flex;align-items:center;gap:.85rem">
                <div class="nv-ava-wrap">
                    <img src="{{ $nhanVien->anh_dai_dien_url }}" alt="{{ $nhanVien->ho_ten }}"
                        style="width:48px;height:48px;border-radius:50%;object-fit:cover;
                            border:2.5px solid #eef0f5"
                        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                    <span class="nv-dot {{ $nhanVien->kich_hoat ? 'on' : 'off' }}"></span>
                </div>
                <div>
                    <h1 class="nv-page-title" style="font-size:1.1rem">
                        {{ $nhanVien->ho_ten }}
                    </h1>
                    <p class="nv-page-sub">
                        Tham gia {{ $nhanVien->created_at->format('d/m/Y') }}
                        @if ($nhanVien->dang_nhap_cuoi_at)
                            &nbsp;·&nbsp; Đăng nhập {{ $nhanVien->dang_nhap_cuoi_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="nv-btn-cancel"
                style="display:inline-flex;align-items:center;gap:.4rem;text-decoration:none">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @include('admin.nhan-vien._form', ['nhanVien' => $nhanVien])
    </div>
@endsection
