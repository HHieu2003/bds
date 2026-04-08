@extends('frontend.layouts.master')

@section('title', 'Lịch sử xem nhà — Thành Công Land')

@push('styles')
    <style>
        .tk-page {
            background-color: var(--bs-gray-100);
            min-height: 80vh;
            padding: 3rem 0 5rem;
        }

        /* Lịch Hẹn Card */
        .lh-card {
            background: var(--bs-body-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--bs-box-shadow-sm);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid var(--bs-border-color-translucent);
        }

        .lh-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--bs-box-shadow);
        }

        .lh-header {
            padding: 1.2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--bs-border-color-translucent);
        }

        .lh-time {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--bs-heading-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .lh-body {
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .lh-img {
            width: 160px;
            height: 110px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: var(--bs-box-shadow-sm);
        }

        .lh-info {
            flex: 1;
            min-width: 250px;
        }

        .lh-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--bs-heading-color);
            text-decoration: none;
            display: block;
            margin-bottom: 0.6rem;
            transition: color 0.2s;
            line-height: 1.4;
        }

        .lh-title:hover {
            color: var(--bs-primary);
        }

        .lh-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.95rem;
            color: var(--bs-body-color);
        }

        .lh-meta i {
            width: 20px;
            text-align: center;
        }

        .lh-footer {
            padding: 1rem 1.5rem;
            background: var(--bs-gray-100);
            border-top: 1px solid var(--bs-border-color-translucent);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .sale-box {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bs-body-bg);
            padding: 8px 18px;
            border-radius: 50px;
            border: 1px solid var(--bs-border-color);
            box-shadow: var(--bs-box-shadow-sm);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .lh-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }

            .lh-body {
                flex-direction: column;
                gap: 1.2rem;
            }

            .lh-img {
                width: 100%;
                height: 180px;
            }

            .lh-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .sale-box {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="tk-page">
        <div class="container">

            {{-- HEADER TRANG --}}
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-900 text-dark mb-3"><i class="fas fa-calendar-check text-primary me-2"></i> Lịch trình xem
                        nhà của bạn</h2>
                    <p class="text-muted fs-6">Quản lý các cuộc hẹn xem bất động sản, theo dõi trạng thái và liên hệ trực
                        tiếp với chuyên viên hỗ trợ.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">

                    @include('frontend.partials.flash-messages')

                    @forelse($lichHens as $lh)
                        @php
                            // Tận dụng hoàn toàn bộ class màu (subtle) có sẵn của Bootstrap 5
                            $statusData = [
                                'moi_dat' => [
                                    'icon' => 'fas fa-spinner fa-spin',
                                    'text' => 'Chờ tiếp nhận',
                                    'class' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                ],
                                'cho_xac_nhan' => [
                                    'icon' => 'fas fa-hourglass-half',
                                    'text' => 'Đang chờ xác nhận',
                                    'class' => 'bg-info-subtle text-info border border-info-subtle',
                                ],
                                'da_xac_nhan' => [
                                    'icon' => 'fas fa-check-circle',
                                    'text' => 'Đã chốt lịch',
                                    'class' => 'bg-success-subtle text-success border border-success-subtle',
                                ],
                                'hoan_thanh' => [
                                    'icon' => 'fas fa-flag-checkered',
                                    'text' => 'Đã xem xong',
                                    'class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                ],
                                'tu_choi' => [
                                    'icon' => 'fas fa-times-circle',
                                    'text' => 'Bị từ chối',
                                    'class' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                ],
                                'huy' => [
                                    'icon' => 'fas fa-ban',
                                    'text' => 'Đã hủy',
                                    'class' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                ],
                            ];
                            $st = $statusData[$lh->trang_thai] ?? $statusData['moi_dat'];

                            // Hình ảnh BĐS
                            $anhBds = asset('images/default-bds.jpg');
                            if ($lh->batDongSan && $lh->batDongSan->album_anh) {
                                $album = is_string($lh->batDongSan->album_anh)
                                    ? json_decode($lh->batDongSan->album_anh, true)
                                    : $lh->batDongSan->album_anh;
                                if (is_array($album) && count($album) > 0) {
                                    $anhBds = asset('storage/' . $album[0]);
                                }
                            }
                        @endphp

                        <div class="lh-card">
                            <div class="lh-header">
                                <div class="lh-time">
                                    <i class="far fa-clock text-primary"></i>
                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}
                                    <span class="fw-normal text-muted fs-6 ms-2">ngày
                                        {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</span>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 {{ $st['class'] }}">
                                    <i class="{{ $st['icon'] }} me-1"></i> {{ $st['text'] }}
                                </span>
                            </div>

                            <div class="lh-body">
                                <img src="{{ $anhBds }}" alt="Thumb" class="lh-img">
                                <div class="lh-info">
                                    @if ($lh->batDongSan)
                                        <a href="{{ route('frontend.bat-dong-san.show', $lh->batDongSan->slug) }}"
                                            class="lh-title">{{ $lh->batDongSan->tieu_de }}</a>
                                        <div class="lh-meta">
                                            <span><i class="fas fa-map-marker-alt text-primary"></i>
                                                {{ $lh->batDongSan->dia_chi ?? 'Khu vực chưa xác định' }}</span>
                                            <span><i class="fas fa-tag text-primary"></i> Giá: <strong
                                                    class="text-danger">{{ $lh->batDongSan->gia_hien_thi ?? 'Thỏa thuận' }}</strong></span>
                                            @if ($lh->dia_diem_hen)
                                                <span class="text-primary fw-bold"><i
                                                        class="fas fa-location-arrow text-primary"></i> Điểm gặp mặt:
                                                    {{ $lh->dia_diem_hen }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <h5 class="fw-bold text-muted">Bất động sản không còn tồn tại</h5>
                                    @endif

                                    @if ($lh->trang_thai == 'tu_choi' || $lh->trang_thai == 'huy')
                                        <div class="alert alert-danger p-3 mt-3 mb-0 small border-danger">
                                            <strong><i class="fas fa-exclamation-circle me-1"></i> Lời nhắn từ hệ
                                                thống:</strong>
                                            {{ $lh->ly_do_tu_choi ?? 'Lịch hẹn đã bị hủy.' }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="lh-footer">
                                <div class="sale-box">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:36px; height:36px;">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Hỗ trợ viên:
                                            {{ optional($lh->nhanVienSale)->ho_ten ?? 'Đang sắp xếp...' }}</div>
                                        @if ($lh->nhanVienSale)
                                            <a href="tel:{{ $lh->nhanVienSale->so_dien_thoai }}"
                                                class="text-success text-decoration-none fw-bold"
                                                style="font-size: 0.85rem;">
                                                <i class="fas fa-phone-alt me-1"></i>
                                                {{ $lh->nhanVienSale->so_dien_thoai }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Nút Hủy Lịch --}}
                                @if (in_array($lh->trang_thai, ['moi_dat', 'cho_xac_nhan', 'da_xac_nhan']))
                                    <button type="button"
                                        class="btn btn-outline-danger fw-bold rounded-pill px-4 py-2 btn-huy-lich"
                                        data-id="{{ $lh->id }}" data-bs-toggle="modal"
                                        data-bs-target="#modalHuyLich">
                                        <i class="fas fa-times me-1"></i> Báo Hủy Lịch
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 border shadow-sm mt-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty"
                                style="width: 140px; opacity: 0.5;" class="mb-4">
                            <h4 class="fw-bold text-dark mb-2">Bạn chưa có lịch hẹn nào</h4>
                            <p class="text-muted mb-4">Hãy khám phá các bất động sản hấp dẫn và đặt lịch xem nhà ngay nhé.
                            </p>
                            <a href="{{ route('frontend.bat-dong-san.index') }}"
                                class="btn btn-primary btn-lg rounded-pill fw-bold px-5 shadow-sm">TÌM KIẾM NHÀ ĐẸP NGAY</a>
                        </div>
                    @endforelse

                    <div class="d-flex justify-content-center mt-5">
                        {{ $lichHens->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHuyLich" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="frmHuyLichKhach" method="POST" action="" class="modal-content border-0 shadow-lg"
                style="border-radius: 16px; overflow: hidden;">
                @csrf
                <div class="modal-header border-0 bg-danger text-white p-4">
                    <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Xác nhận hủy lịch hẹn
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-dark fs-6 mb-3">Bạn có chắc chắn muốn hủy lịch hẹn xem nhà này không?</p>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-danger">Lý do hủy (Giúp chúng tôi hỗ trợ bạn tốt hơn):</label>
                        <textarea name="ly_do" class="form-control bg-light border-danger-subtle" rows="3"
                            placeholder="Ví dụ: Tôi bận việc đột xuất, Tôi đã chốt căn khác..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4"><i class="fas fa-check me-1"></i> Chắc chắn
                        Hủy</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnHuys = document.querySelectorAll('.btn-huy-lich');
            const frmHuy = document.getElementById('frmHuyLichKhach');

            btnHuys.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    frmHuy.action = `/tai-khoan/lich-hen/${id}/huy`;
                });
            });
        });
    </script>
@endpush
