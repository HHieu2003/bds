{{-- edit.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Sửa — ' . Str::limit($baiViet->tieu_de, 40))
@section('content')
    <div class="bv-form-hdr">
        <div>
            <div class="bv-bc">
                <a href="{{ route('nhanvien.admin.bai-viet.index') }}">
                    <i class="fas fa-pen-alt"></i> Bài viết
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ Str::limit($baiViet->tieu_de, 40) }}</span>
            </div>
            <h1 class="bv-form-ttl"><i class="fas fa-edit"></i> Chỉnh sửa bài viết</h1>
        </div>
        <a href="{{ route('nhanvien.admin.bai-viet.show', $baiViet) }}" class="bv-btn-preview">
            <i class="fas fa-eye"></i> Xem trước
        </a>
    </div>

    <form id="bvForm" method="POST" action="{{ route('nhanvien.admin.bai-viet.update', $baiViet) }}"
        enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.bai-viet._form')
    </form>
@endsection

@push('styles')
    <style>
        .bv-btn-preview {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 9px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none;
            transition: all .2s
        }

        .bv-btn-preview:hover {
            background: #1a3c5e;
            color: #fff
        }
    </style>
@endpush
