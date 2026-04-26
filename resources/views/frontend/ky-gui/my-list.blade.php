@extends('frontend.layouts.master')
@section('title', 'Ký gửi của tôi - Thành Công Land')

@section('content')
    <section class="py-5 kgm-page">
        <div class="container" style="max-width: 900px;">

            {{-- ── HEADER ── --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3 kgm-head" data-aos="fade-up">
                <div>
                    <h1 class="section-title mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-file-signature text-primary-brand"></i> Ký gửi của tôi
                    </h1>
                    <p class="text-muted mb-0">
                        Tổng cộng <strong class="text-primary-brand">{{ $kyGuis->total() ?? 0 }}</strong> yêu cầu đã gửi
                    </p>
                </div>
                <a href="{{ route('frontend.ky-gui.create') }}" class="btn-primary-brand rounded-pill px-4 py-2 kgm-btn-new">
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

                    <div class="card border-0 rounded-4 shadow-sm mb-4 hover-up overflow-hidden kgm-card" data-aos="fade-up"
                        data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="card-body p-4 p-md-4">
                            <div class="row align-items-start g-3">

                                {{-- Cột Trái: Thông tin chính --}}
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap kgm-badges">
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
                                        <p class="mb-3 fw-semibold kgm-address"
                                            style="color: var(--text-heading); font-size: 0.95rem;">
                                            <i class="fas fa-map-marker-alt text-primary-brand me-1"></i>
                                            {{ $kg->dia_chi }}
                                        </p>
                                    @endif

                                    @if ($kg->du_an || $kg->ma_can)
                                        <div class="d-flex flex-wrap gap-3 mb-3 text-muted small fw-semibold">
                                            @if ($kg->du_an)
                                                <span><i class="fas fa-city icon-muted me-1"></i>{{ $kg->du_an }}</span>
                                            @endif
                                            @if ($kg->ma_can)
                                                <span><i class="fas fa-hashtag icon-muted me-1"></i>Mã căn:
                                                    {{ $kg->ma_can }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="d-flex flex-wrap gap-4 text-muted small fw-semibold kgm-meta">
                                        <span><i class="fas fa-ruler-combined icon-muted me-1"></i> {{ $kg->dien_tich }}
                                            m²</span>
                                        @if ($kg->so_phong_ngu)
                                            <span><i class="fas fa-bed icon-muted me-1"></i> {{ $kg->so_phong_ngu }}</span>
                                        @endif
                                        @if ($kg->tang)
                                            <span><i class="fas fa-layer-group icon-muted me-1"></i>
                                                {{ $kg->tang }}</span>
                                        @endif
                                        <span><i class="far fa-calendar-alt icon-muted me-1"></i>
                                            {{ $kg->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                {{-- Cột Phải: Giá & Trạng thái --}}
                                <div class="col-md-4 text-md-end border-md-start border-0 ps-md-4">
                                    <div class="price-text mb-2 kgm-price" style="font-size: 1.4rem;">
                                        {{ $kg->gia_hien_thi }}</div>
                                    <span class="badge rounded-pill px-3 py-2 fw-bold"
                                        style="background-color: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }}; font-size: 0.8rem;">
                                        <i class="{{ $ttInfo['icon'] }} me-1"></i> {{ $ttInfo['label'] }}
                                    </span>

                                    @if (in_array($kg->trang_thai, ['cho_duyet', 'dang_tham_dinh']))
                                        <form action="{{ route('frontend.ky-gui.huy', $kg) }}" method="POST"
                                            class="mt-3" id="kgCancelForm{{ $kg->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm rounded-pill px-3 kgm-btn-cancel"
                                                onclick="openKyGuiCancelModal('kgCancelForm{{ $kg->id }}', 'KG-{{ str_pad($kg->id, 5, '0', STR_PAD_LEFT) }}')">
                                                <i class="fas fa-times me-1"></i> Hủy ký gửi
                                            </button>
                                        </form>
                                    @endif

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
                                            <img src="{{ \Storage::disk('r2')->url($img) }}" alt="Ảnh ký gửi"
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

            <div class="modal fade" id="kgCancelConfirmModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 kgm-confirm-modal">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold"><i class="fas fa-triangle-exclamation text-danger me-2"></i>Xác
                                nhận hủy ký gửi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body pt-2">
                            <p class="mb-2">Bạn chắc chắn muốn hủy yêu cầu <strong id="kgCancelCode">KG-00000</strong>?
                            </p>
                            <p class="text-muted small mb-0">Hành động này sẽ ẩn yêu cầu khỏi danh sách của bạn. Bạn vẫn có
                                thể tạo ký gửi mới bất cứ lúc nào.</p>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Giữ
                                lại</button>
                            <button type="button" class="btn btn-danger rounded-pill px-3" id="kgConfirmCancelBtn">
                                <i class="fas fa-trash-alt me-1"></i> Xác nhận hủy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('styles')
    <style>
        .kgm-page {
            background:
                radial-gradient(circle at 100% 0, rgba(255, 140, 66, .06), transparent 30%),
                radial-gradient(circle at 0 100%, rgba(27, 58, 107, .06), transparent 28%),
                var(--bg-main);
            min-height: 70vh;
        }

        .kgm-head {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem 1.2rem;
            box-shadow: 0 2px 14px rgba(15, 23, 42, .05);
        }

        .kgm-btn-new {
            box-shadow: 0 8px 20px rgba(255, 140, 66, .2);
        }

        .kgm-card {
            border: 1px solid #eef2f7;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .kgm-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, .08) !important;
        }

        .kgm-address {
            border-left: 3px solid rgba(255, 140, 66, .35);
            padding-left: .55rem;
        }

        .kgm-meta {
            border-top: 1px dashed #e2e8f0;
            padding-top: .6rem;
        }

        .kgm-price {
            color: #d9480f;
            font-weight: 800;
        }

        .kgm-btn-cancel {
            border: 1px solid #fecaca;
            color: #b91c1c;
            background: #fff5f5;
            font-weight: 700;
        }

        .kgm-btn-cancel:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .kgm-confirm-modal {
            box-shadow: 0 30px 70px rgba(2, 8, 23, .22);
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentKyGuiCancelFormId = null;

        function openKyGuiCancelModal(formId, code) {
            currentKyGuiCancelFormId = formId;
            const codeEl = document.getElementById('kgCancelCode');
            if (codeEl) {
                codeEl.textContent = code;
            }

            const modalEl = document.getElementById('kgCancelConfirmModal');
            if (!modalEl || typeof bootstrap === 'undefined') return;

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const confirmBtn = document.getElementById('kgConfirmCancelBtn');
            if (!confirmBtn) return;

            confirmBtn.addEventListener('click', function() {
                if (!currentKyGuiCancelFormId) return;

                const form = document.getElementById(currentKyGuiCancelFormId);
                if (!form) return;

                form.submit();
            });
        });
    </script>
@endpush
