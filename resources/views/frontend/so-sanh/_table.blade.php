{{-- AJAX partial — trả về bởi SoSanhController@modal --}}
<div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse;min-width:600px">

        {{-- Header: ảnh + tên + giá --}}
        <thead>
            <tr style="background:#f8f4f1">
                <th
                    style="width:140px;padding:12px 16px;font-size:.75rem;
                   color:#9ca3af;font-weight:700;text-transform:uppercase;
                   border-bottom:2px solid #f0ece8">
                    Tiêu chí
                </th>
                @foreach ($danhSachBds as $bds)
                    @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                    <th
                        style="padding:12px 14px;border-bottom:2px solid #f0ece8;
                   border-left:1px solid #f0ece8;vertical-align:top">
                        {{-- Nút xóa --}}
                        <div style="display:flex;justify-content:flex-end;margin-bottom:6px">
                            <button onclick="removeSoSanh({{ $bds->id }})"
                                style="background:#fee2e2;border:none;border-radius:6px;
                               padding:3px 8px;font-size:.7rem;color:#e74c3c;
                               cursor:pointer;font-weight:700"
                                title="Xóa khỏi so sánh">
                                <i class="fas fa-times"></i> Xóa
                            </button>
                        </div>
                        {{-- Ảnh --}}
                        @if ($anh)
                            <img src="{{ asset('storage/' . $anh) }}"
                                style="width:100%;height:120px;object-fit:cover;
                            border-radius:10px;margin-bottom:8px"
                                onerror="this.style.display='none'">
                        @else
                            <div
                                style="width:100%;height:80px;background:#f0ece8;
                            border-radius:10px;display:flex;align-items:center;
                            justify-content:center;margin-bottom:8px;color:#ccc">
                                <i class="fas fa-building" style="font-size:1.5rem"></i>
                            </div>
                        @endif
                        {{-- Tên --}}
                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug ?? $bds->id) }}"
                            style="font-size:.82rem;font-weight:800;color:#1a3c5e;
                      text-decoration:none;line-height:1.35;display:block">
                            {{ Str::limit($bds->tieu_de, 50) }}
                        </a>
                        {{-- Giá --}}
                        <div
                            style="font-size:.95rem;font-weight:900;
                        color:var(--primary);margin-top:4px">
                            {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>

        {{-- Body: từng tiêu chí --}}
        <tbody>
            @php
                $rows = [
                    [
                        'label' => 'Nhu cầu',
                        'icon' => 'fa-tag',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->nhu_cau == 'ban'
                                ? '<span style="color:#e74c3c;font-weight:700">🏷 Đang bán</span>'
                                : '<span style="color:#2d6a9f;font-weight:700">🔑 Cho thuê</span>',
                        ),
                    ],
                    [
                        'label' => 'Khu vực',
                        'icon' => 'fa-map-marker-alt',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->khuVuc->ten_khu_vuc ?? '<em style="color:#ccc">N/A</em>',
                        ),
                    ],
                    [
                        'label' => 'Dự án',
                        'icon' => 'fa-city',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->duAn->ten_du_an ?? '<em style="color:#ccc">BĐS Tự Do</em>',
                        ),
                    ],
                    [
                        'label' => 'Tòa',
                        'icon' => 'fa-building',
                        'values' => $danhSachBds->map(
                            fn($b) => $b->toa ? 'Tòa ' . $b->toa : '<em style="color:#ccc">—</em>',
                        ),
                    ],
                    [
                        'label' => 'Diện tích',
                        'icon' => 'fa-ruler-combined',
                        'values' => $danhSachBds->map(fn($b) => ($b->dien_tich ?? '—') . ' m²'),
                    ],
                    [
                        'label' => 'Phòng ngủ',
                        'icon' => 'fa-bed',
                        'values' => $danhSachBds->map(fn($b) => ($b->so_phong_ngu ?? '—') . ' phòng'),
                    ],
                    [
                        'label' => 'Phòng tắm',
                        'icon' => 'fa-bath',
                        'values' => $danhSachBds->map(fn($b) => ($b->so_phong_tam ?? '—') . ' WC'),
                    ],
                    [
                        'label' => 'Nội thất',
                        'icon' => 'fa-couch',
                        'values' => $danhSachBds->map(fn($b) => $b->noi_that ?? '<em style="color:#ccc">N/A</em>'),
                    ],
                    [
                        'label' => 'Hướng nhà',
                        'icon' => 'fa-compass',
                        'values' => $danhSachBds->map(fn($b) => $b->huong ?? '<em style="color:#ccc">N/A</em>'),
                    ],
                    [
                        'label' => 'Pháp lý',
                        'icon' => 'fa-file-contract',
                        'values' => $danhSachBds->map(fn($b) => $b->phap_ly ?? '<em style="color:#ccc">N/A</em>'),
                    ],
                ];
            @endphp

            @foreach ($rows as $i => $row)
                <tr style="background: {{ $i % 2 === 0 ? '#fff' : '#fdf8f5' }}">
                    <td
                        style="padding:10px 16px;font-size:.78rem;font-weight:700;
                   color:#6b7280;border-bottom:1px solid #f5f0eb;
                   white-space:nowrap">
                        <i class="fas {{ $row['icon'] }}" style="color:var(--primary);margin-right:6px;width:14px"></i>
                        {{ $row['label'] }}
                    </td>
                    @foreach ($row['values'] as $val)
                        <td
                            style="padding:10px 14px;font-size:.82rem;color:#333;
                   border-bottom:1px solid #f5f0eb;
                   border-left:1px solid #f5f0eb">
                            {!! $val !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach

            {{-- Hàng nút Xem chi tiết --}}
            <tr style="background:#f8f4f1">
                <td style="padding:12px 16px;font-size:.78rem;font-weight:700;color:#6b7280">
                    <i class="fas fa-link" style="color:var(--primary);margin-right:6px"></i>
                    Chi tiết
                </td>
                @foreach ($danhSachBds as $bds)
                    <td style="padding:12px 14px;border-left:1px solid #f0ece8">
                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug ?? $bds->id) }}"
                            style="display:inline-flex;align-items:center;gap:6px;
                      background:var(--navy);color:#fff;
                      padding:7px 14px;border-radius:8px;
                      font-size:.78rem;font-weight:700;text-decoration:none">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
