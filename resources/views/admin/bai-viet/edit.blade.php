{{-- resources/views/admin/bai-viet/edit.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Sửa: ' . Str::limit($baiViet->tieu_de, 40))

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <h1 class="fw-black mb-0" style="font-size:1.25rem;color:var(--navy);line-height:1.3">
                    <i class="fas fa-edit me-2" style="color:var(--primary)"></i>
                    {{ Str::limit($baiViet->tieu_de, 50) }}
                </h1>
                @php
                    $badgeClass = match ($baiViet->trang_thai) {
                        'hien_thi' => 'badge-active',
                        'an' => 'badge-inactive',
                        default => 'badge-pending',
                    };
                    $badgeText = match ($baiViet->trang_thai) {
                        'hien_thi' => 'Hiển thị',
                        'an' => 'Đã ẩn',
                        default => $baiViet->trang_thai,
                    };
                @endphp
                <span class="badge-status {{ $badgeClass }} flex-shrink-0">{{ $badgeText }}</span>
            </div>
            <nav style="font-size:.78rem;color:var(--text-sub)" aria-label="breadcrumb">
                <a href="{{ route('nhanvien.dashboard') }}" class="text-decoration-none" style="color:var(--text-sub)">
                    <i class="fas fa-home"></i>
                </a>
                <span class="mx-1" style="color:var(--text-muted)"><i class="fas fa-chevron-right"
                        style="font-size:.6rem"></i></span>
                <a href="{{ route('nhanvien.admin.bai-viet.index') }}" class="text-decoration-none"
                    style="color:var(--text-sub)" onmouseover="this.style.color='var(--primary)'"
                    onmouseout="this.style.color='var(--text-sub)'">Bài
                    viết</a>
                <span class="mx-1" style="color:var(--text-muted)"><i class="fas fa-chevron-right"
                        style="font-size:.6rem"></i></span>
                <span style="font-weight:700;color:var(--navy)">Chỉnh sửa</span>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-2 flex-shrink-0 flex-wrap">
            {{-- Stats nhanh --}}
            <div class="d-none d-md-flex align-items-center gap-3 pe-3 me-1"
                style="border-right:1px solid var(--border);font-size:.75rem;color:var(--text-sub)">
                <span><i class="fas fa-eye me-1" style="color:var(--blue)"></i>
                    <strong style="color:var(--navy)">{{ number_format($baiViet->luot_xem ?? 0) }}</strong> lượt xem</span>
                <span><i class="fas fa-clock me-1"></i>
                    {{ $baiViet->updated_at->diffForHumans() }}</span>
            </div>
            @if ($baiViet->trang_thai === 'hien_thi')
                <a href="{{ route('frontend.tin-tuc.show', $baiViet->slug) }}" target="_blank"
                    class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="d-none d-sm-inline">Xem bài</span>
                </a>
            @endif
            <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
                class="btn btn-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Quay lại</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
            <div>
                <div class="fw-bold mb-1">Vui lòng kiểm tra lại:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $err)
                        <li style="font-size:.83rem">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('nhanvien.admin.bai-viet.update', $baiViet) }}" method="POST" enctype="multipart/form-data"
        id="bvForm" novalidate>
        @csrf @method('PUT')
        @include('admin.bai-viet._form', ['baiViet' => $baiViet, 'isEdit' => true])
    </form>

@endsection
