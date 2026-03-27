@extends('frontend.layouts.master')
@section('title', 'Ký gửi của tôi - Thành Công Land')

@section('content')
    <section class="py-5" style="background-color: var(--bg-main); min-height: 70vh;">
        <div class="container" style="max-width: 900px;">

            {{-- ── HEADER ── --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3" data-aos="fade-up">
                <div>
                    <h1 class="section-title mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-file-signature text-primary-brand"></i> Ký gửi của tôi
                    </h1>
                    <p class="text-muted mb-0">
                        Tổng cộng <strong class="text-primary-brand">{{ $kyGuis->total() ?? 0 }}</strong> yêu cầu đã gửi
                    </p>
                </div>
                <a href="{{ route('frontend.ky-gui.create') }}" class="btn-primary-brand rounded-pill px-4 py-2">
                    <i class="fas fa-plus me-1"></i> Ký gửi mới
                </a>
            </div>

            {{-- ── DANH SÁCH KÝ GỬI ── --}}
            <div class="ky-gui-list">
                @forelse($kyGuis as $kg)
                    @php
                        // Khởi tạo các mảng mặc định để chống lỗi nếu bị thiếu dữ liệu Models
                        $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? [
                            'label' => 'Đang chờ',
                            'color' => '#6c757d',
                            'bg' => '#f8f9fa',
                            'icon' => 'fas fa-clock',
                        ];
                        $lhInfo = \App\Models\KyGui::LOAI_HINH[$kg->loai_hinh] ?? [
                            'label' => 'Khác',
                            'icon' => 'fas fa-home',
                            'color' => 'var(--secondary)',
                        ];
                        $ncInfo = \App\Models\KyGui::NHU_CAU[$kg->nhu_cau] ?? [
                            'label' => 'Bán/Cho thuê',
                            'color' => 'var(--primary)',
                            'bg' => 'var(--primary-light)',
                        ];
                    @endphp

                    <div class="card border-0 rounded-4 shadow-sm mb-4 hover-up overflow-hidden" data-aos="fade-up"
                        data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="card-body p-4 p-md-4">
                            <div class="row align-items-start g-3">

                                {{-- Cột Trái: Thông tin chính --}}
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                        <span class="badge fw-bold px-3 py-2"
                                            style="background-color: var(--bg-alt); color: {{ $lhInfo['color'] }}; border: 1px solid currentColor;">
                                            <i class="{{ $lhInfo['icon'] }} me-1"></i> {{ $lhInfo['label'] }}
                                        </span>
                                        <span class="badge fw-bold px-3 py-2"
                                            style="background-color: {{ $ncInfo['bg'] }}; color: {{ $ncInfo['color'] }};">
                                            {{ $ncInfo['label'] }}
                                        </span>
                                        <span class="badge bg-light text-muted border px-3 py-2"
                                            style="font-family: monospace;">
                                            <i
                                                class="fas fa-hashtag icon-muted"></i>KG-{{ str_pad($kg->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>

                                    @if ($kg->dia_chi)
                                        <p class="mb-3 fw-semibold" style="color: var(--text-heading); font-size: 0.95rem;">
                                            <i class="fas fa-map-marker-alt text-primary-brand me-1"></i>
                                            {{ $kg->dia_chi }}
                                        </p>
                                    @endif

                                    <div class="d-flex flex-wrap gap-4 text-muted small fw-semibold">
                                        <span><i class="fas fa-ruler-combined icon-muted me-1"></i> {{ $kg->dien_tich }}
                                            m²</span>
                                        @if ($kg->so_phong_ngu)
                                            <span><i class="fas fa-bed icon-muted me-1"></i> {{ $kg->so_phong_ngu }}
                                                PN</span>
                                        @endif
                                        <span><i class="far fa-calendar-alt icon-muted me-1"></i>
                                            {{ $kg->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                {{-- Cột Phải: Giá & Trạng thái --}}
                                <div class="col-md-4 text-md-end border-md-start border-0 ps-md-4">
                                    <div class="price-text mb-2" style="font-size: 1.4rem;">{{ $kg->gia_hien_thi }}</div>
                                    <span class="badge rounded-pill px-3 py-2 fw-bold"
                                        style="background-color: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }}; font-size: 0.8rem;">
                                        <i class="{{ $ttInfo['icon'] }} me-1"></i> {{ $ttInfo['label'] }}
                                    </span>

                                    @if ($kg->thoi_diem_xu_ly)
                                        <div class="text-muted small mt-2 fw-medium">
                                            Xử lý: {{ $kg->thoi_diem_xu_ly->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Phản hồi từ Admin (Thiết kế khối Highlight Luxury) --}}
                            @if ($kg->phan_hoi_cua_admin)
                                <div class="mt-4 p-3 rounded-3"
                                    style="background-color: var(--primary-light); border-left: 4px solid var(--primary);">
                                    <div class="small fw-bold text-uppercase mb-1"
                                        style="color: var(--primary); letter-spacing: 0.5px;">
                                        <i class="fas fa-comment-dots me-1"></i> Phản hồi từ Thành Công Land
                                    </div>
                                    <div style="color: var(--text-heading); font-size: 0.9rem; line-height: 1.6;">
                                        {{ $kg->phan_hoi_cua_admin }}
                                    </div>
                                </div>
                            @endif

                            {{-- Hình ảnh tham khảo --}}
                            @if ($kg->hinh_anh_tham_khao && is_array($kg->hinh_anh_tham_khao) && count($kg->hinh_anh_tham_khao) > 0)
                                <div class="d-flex gap-2 mt-3 flex-wrap">
                                    @foreach (array_slice($kg->hinh_anh_tham_khao, 0, 4) as $idx => $img)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $img) }}" alt="Ảnh ký gửi"
                                                class="rounded-3 hover-up"
                                                style="width: 75px; height: 75px; object-fit: cover; border: 1.5px solid var(--border); transition: transform 0.2s;">
                                            @if ($idx === 3 && count($kg->hinh_anh_tham_khao) > 4)
                                                <div class="position-absolute inset-0 rounded-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                                    style="background: rgba(28, 18, 9, 0.6); top:0; left:0; right:0; bottom:0;">
                                                    +{{ count($kg->hinh_anh_tham_khao) - 4 }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                @empty
                    {{-- Giao diện Trống (Empty State) --}}
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm" data-aos="fade-up">
                        <div class="mb-4">
                            <i class="fas fa-folder-open" style="font-size: 4rem; color: var(--border);"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--text-heading);">Chưa có yêu cầu ký gửi</h4>
                        <p class="text-muted mb-4">Bạn chưa gửi yêu cầu ký gửi bán/cho thuê bất động sản nào.</p>
                        <a href="{{ route('frontend.ky-gui.create') }}" class="btn-primary-brand rounded-pill px-5 py-3">
                            <i class="fas fa-plus me-2"></i> Tạo yêu cầu ký gửi đầu tiên
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- ── PHÂN TRANG ── --}}
            @if (isset($kyGuis) && $kyGuis->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $kyGuis->links() }}
                </div>
            @endif

        </div>
    </section>
@endsection
