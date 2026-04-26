@php
    $formatNoiThat = function ($value) {
        if (!$value) {
            return '<em class="text-muted small">Chưa cập nhật</em>';
        }

        return ucwords(str_replace('_', ' ', $value));
    };

    $formatHuongBanCong = function ($bds) {
        $huong = $bds->huong_ban_cong ?? null;
        if (!$huong) {
            return '<em class="text-muted small">Chưa cập nhật</em>';
        }

        return ucwords(str_replace('_', ' ', $huong));
    };
@endphp

<div class="table-responsive ss-table-wrapper">
    <table class="table align-middle mb-0 ss-table border-0" style="max-height:90%">

        {{-- ── HEADER: Ảnh + Tên + Giá + Nút Xóa ── --}}
        <thead>
            <tr class="ss-head-row">
                <th class="ss-th-label text-muted text-uppercase text-center align-middle"
                    style="width: 160px; border-right: 1px solid var(--border);">
                    <i class="fas fa-list-ul mb-1 d-block fs-5 text-primary-brand"></i>
                    Tiêu chí
                </th>

                @foreach ($danhSachBds as $bds)
                    @php
                        $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null;
                    @endphp
                    <th class="ss-th-item position-relative p-3"
                        style="border-right: 1px solid var(--border); min-width: 260px;">

                        {{-- Nút xóa (Góc phải trên) --}}
                        <div class="text-end mb-2">
                            <button
                                onclick="(typeof xoaVaTaiLai === 'function' ? xoaVaTaiLai({{ $bds->id }}) : removeSoSanh({{ $bds->id }}))"
                                class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 ss-btn-remove"
                                title="Xóa khỏi so sánh">
                                <i class="fas fa-times me-1"></i> Xóa
                            </button>
                        </div>

                        {{-- Ảnh đại diện --}}
                        @if ($anh)
                            <img src="{{ \Storage::disk('r2')->url($anh) }}" class="ss-item-img w-100 rounded-3 shadow-sm mb-3"
                                onerror="this.style.display='none'">
                        @else
                            <div
                                class="ss-item-img-placeholder w-100 rounded-3 shadow-sm mb-3 d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="fas fa-building fs-2 opacity-50"></i>
                            </div>
                        @endif

                        {{-- Tiêu đề BĐS --}}
                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug ?? $bds->id) }}"
                            class="card-title-link line-clamp-2 mb-2 d-block" title="{{ $bds->tieu_de }}">
                            {{ $bds->tieu_de }}
                        </a>

                        {{-- Giá tiền --}}
                        <div class="ss-price-text">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>

        {{-- ── BODY: Từng Tiêu Chí So Sánh ── --}}
        <tbody>
            @php
                $rows = [
                    [
                        'label' => 'Nhu cầu',
                        'icon' => 'fa-tag',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->nhu_cau == 'ban'
                                ? '<span class="badge badge-hot px-3 py-2"><i class="fas fa-tag me-1"></i> Đang Bán</span>'
                                : '<span class="badge badge-moi px-3 py-2"><i class="fas fa-key me-1"></i> Cho Thuê</span>',
                        ),
                    ],
                    [
                        'label' => 'Khu vực',
                        'icon' => 'fa-map-marker-alt',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->khuVuc->ten_khu_vuc ?? '<em class="text-muted small">Chưa cập nhật</em>',
                        ),
                    ],
                    [
                        'label' => 'Dự án',
                        'icon' => 'fa-city',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->duAn->ten_du_an ??
                                '<span class="fw-bold" style="color:var(--secondary)">BĐS Tự Do</span>',
                        ),
                    ],
                    [
                        'label' => 'Giá',
                        'icon' => 'fa-coins',
                        'values' => $danhSachBds->map(
                            fn($b) => '<span class="ss-price-text">' . ($b->gia_hien_thi ?? 'Thỏa thuận') . '</span>',
                        ),
                    ],
                    [
                        'label' => 'Tòa / Phân khu',
                        'icon' => 'fa-building',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->toa
                                ? '<span class="fw-bold">Tòa ' . $b->toa . '</span>'
                                : '<em class="text-muted">—</em>',
                        ),
                    ],
                    [
                        'label' => 'Diện tích',
                        'icon' => 'fa-ruler-combined',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->dien_tich
                                ? '<span class="fw-bold">' . $b->dien_tich . ' m²</span>'
                                : '<em class="text-muted">—</em>',
                        ),
                    ],
                    [
                        'label' => 'Phòng ngủ',
                        'icon' => 'fa-bed',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->so_phong_ngu ? $b->so_phong_ngu : '<em class="text-muted">—</em>',
                        ),
                    ],
                    [
                        'label' => 'Nội thất',
                        'icon' => 'fa-couch',
                        'values' => $danhSachBds->map(fn($b) => $formatNoiThat($b->noi_that)),
                    ],
                    [
                        'label' => 'Hướng ban công',
                        'icon' => 'fa-compass',
                        'values' => $danhSachBds->map(fn($b) => $formatHuongBanCong($b)),
                    ],
                    [
                        'label' => 'Pháp lý',
                        'icon' => 'fa-file-contract',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->nhu_cau === 'thue'
                                ? '<em class="text-muted small">Không áp dụng</em>'
                                : $b->phap_ly ?? '<em class="text-muted small">Chưa cập nhật</em>',
                        ),
                    ],
                ];
            @endphp

            @foreach ($rows as $i => $row)
                {{-- Xen kẽ màu nền để dễ đọc (Zebra striping) --}}
                <tr class="{{ $i % 2 === 0 ? 'ss-row-even' : 'ss-row-odd' }}">
                    <td class="ss-td-label fw-bold text-muted p-3"
                        style="border-right: 1px solid var(--border); font-size: 0.85rem;">
                        <i class="fas {{ $row['icon'] }} text-primary-brand me-2"
                            style="width: 20px; text-align: center;"></i>
                        {{ $row['label'] }}
                    </td>
                    @foreach ($row['values'] as $val)
                        <td class="ss-td-value p-3 text-center"
                            style="border-right: 1px solid var(--border); color: var(--text-body); font-size: 0.9rem;">
                            {!! $val !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach

            {{-- ── HÀNG CUỐI: Nút Xem Chi Tiết ── --}}
            <tr class="ss-row-odd border-top" style="border-top-width: 2px !important;">
                <td class="ss-td-label fw-bold text-muted p-3 text-center align-middle"
                    style="border-right: 1px solid var(--border);">
                    <i class="fas fa-link text-primary-brand d-block fs-5 mb-1"></i> Chi tiết
                </td>
                @foreach ($danhSachBds as $bds)
                    <td class="ss-td-value p-3 text-center" style="border-right: 1px solid var(--border);">
                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug ?? $bds->id) }}"
                            class="btn-secondary-theme rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 w-100 justify-content-center">
                            <i class="fas fa-eye"></i> Xem ngay
                        </a>
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

{{-- CSS Bổ sung cho bảng AJAX --}}
<style>
    .ss-table-wrapper {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--border);
        background: #fff;
    }

    .ss-head-row {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .ss-table th,
    .ss-table td {
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .ss-th-label {
        background: #f8fafc;
        color: #64748b !important;
        letter-spacing: 0.04em;
        font-size: 0.76rem;
    }

    .ss-th-item {
        background: #fff;
    }

    .ss-row-even {
        background: #ffffff;
    }

    .ss-row-odd {
        background: #f8fafc;
    }

    .ss-td-label {
        background: #f8fafc;
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .ss-td-value {
        font-weight: 500;
    }

    .ss-price-text {
        color: var(--primary);
        font-weight: 800;
        font-size: 1.04rem;
    }

    .ss-item-img {
        height: 160px;
        object-fit: cover;
        border: 2px solid #fff;
    }

    .ss-item-img-placeholder {
        height: 160px;
        border: 2px solid #fff;
    }

    .ss-btn-remove {
        transition: all 0.2s;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .ss-btn-remove:hover {
        background-color: var(--status-danger);
        color: #fff !important;
    }

    @media (max-width: 768px) {
        .ss-th-item {
            min-width: 220px !important;
        }

        .ss-item-img,
        .ss-item-img-placeholder {
            height: 130px;
        }
    }
</style>
