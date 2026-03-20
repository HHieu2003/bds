{{-- edit.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Sửa ký gửi — ' . $kyGui->ho_ten_chu_nha)
@section('content')

    <div style="margin-bottom:20px">
        <div style="font-size:.8rem;color:#aaa;margin-bottom:6px;display:flex;align-items:center;gap:6px">
            <a href="{{ route('nhanvien.admin.ky-gui.index') }}" style="color:#aaa;text-decoration:none">
                <i class="fas fa-file-signature"></i> Ký gửi
            </a>
            <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
            <a href="{{ route('nhanvien.admin.ky-gui.show', $kyGui) }}" style="color:#aaa;text-decoration:none">
                {{ $kyGui->ho_ten_chu_nha }}
            </a>
            <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
            <span>Chỉnh sửa</span>
        </div>
        <h1 style="font-size:1.3rem;font-weight:700;color:#1a3c5e;margin:0;display:flex;align-items:center;gap:8px">
            <i class="fas fa-edit" style="color:#FF8C42"></i> Chỉnh sửa ký gửi
        </h1>
    </div>

    <form method="POST" action="{{ route('nhanvien.admin.ky-gui.update', $kyGui) }}" enctype="multipart/form-data"
        id="kgAdminForm">
        @csrf @method('PUT')
        @include('admin.ky-gui._form', ['kyGui' => $kyGui])
    </form>

@endsection
