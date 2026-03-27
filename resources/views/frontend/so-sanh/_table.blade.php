{{-- AJAX partial — trả về bởi SoSanhController@modal --}}

<div class="table-responsive ss-table-wrapper">
    <table class="table align-middle mb-0 ss-table border">

        {{-- ── HEADER: Ảnh + Tên + Giá + Nút Xóa ── --}}
        <thead>
            <tr class="bg-alt-section">
                <th class="ss-th-label text-muted text-uppercase text-center align-middle"
                    style="width: 140px; border-right: 1px solid var(--border);">
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
                            <button onclick="removeSoSanh({{ $bds->id }})"
                                class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 ss-btn-remove"
                                title="Xóa khỏi so sánh">
                                <i class="fas fa-times me-1"></i> Xóa
                            </button>
                        </div>

                        {{-- Ảnh đại diện --}}
                        @if ($anh)
                            <img src="{{ asset('storage/' . $anh) }}" class="ss-item-img w-100 rounded-3 shadow-sm mb-3"
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
                        <div class="price-text">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</div>
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
                            fn($b) => $b->so_phong_ngu ? $b->so_phong_ngu . ' PN' : '<em class="text-muted">—</em>',
                        ),
                    ],
                    [
                        'label' => 'Phòng tắm',
                        'icon' => 'fa-bath',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->so_phong_tam ? $b->so_phong_tam . ' WC' : '<em class="text-muted">—</em>',
                        ),
                    ],
                    [
                        'label' => 'Nội thất',
                        'icon' => 'fa-couch',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->noi_that ?? '<em class="text-muted small">Chưa cập nhật</em>',
                        ),
                    ],
                    [
                        'label' => 'Hướng nhà',
                        'icon' => 'fa-compass',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->huong
                                ? '<span class="fw-semibold">' . $b->huong . '</span>'
                                : '<em class="text-muted small">Chưa cập nhật</em>',
                        ),
                    ],
                    [
                        'label' => 'Pháp lý',
                        'icon' => 'fa-file-contract',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->phap_ly ?? '<em class="text-muted small">Chưa cập nhật</em>',
                        ),
                    ],
                ];
            @endphp

            @foreach ($rows as $i => $row)
                {{-- Xen kẽ màu nền để dễ đọc (Zebra striping) --}}
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-alt-section' }}">
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
            <tr class="bg-alt-section border-top" style="border-top-width: 2px !important;">
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
        border-radius: 12px;
        overflow: hidden;
    }

    .ss-table th,
    .ss-table td {
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
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
</style>
