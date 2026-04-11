{{-- resources/views/admin/lien-he/_card.blade.php --}}

@php
    $statusConfig = [
        'moi' => ['label' => 'Mới', 'class' => 'bg-primary', 'icon' => 'fa-star'],
        'dang_xu_ly' => ['label' => 'Đang xử lý', 'class' => 'bg-warning', 'icon' => 'fa-spinner'],
        'hoan_thanh' => ['label' => 'Hoàn thành', 'class' => 'bg-success', 'icon' => 'fa-check-circle'],
        'huy' => ['label' => 'Đã huỷ', 'class' => 'bg-secondary', 'icon' => 'fa-ban'],
    ];
    $status = $statusConfig[$lead->trang_thai] ?? [
        'label' => $lead->trang_thai,
        'class' => 'bg-dark',
        'icon' => 'fa-circle',
    ];
    $initials = strtoupper(mb_substr($lead->ho_ten ?? '?', 0, 1));

    $avatarColors = ['#4361ee', '#3a86ff', '#7209b7', '#f72585', '#06d6a0', '#fb8500'];
    $avatarBg = $avatarColors[abs(crc32($lead->so_dien_thoai ?? '')) % count($avatarColors)];
@endphp

<div class="lead-card" data-id="{{ $lead->id }}" data-status="{{ $lead->trang_thai }}">

    {{-- ── HEADER ── --}}
    <div class="lc-header">
        {{-- Avatar --}}
        <div class="lc-avatar" style="background:{{ $avatarBg }}">{{ $initials }}</div>

        {{-- Tên + thời gian --}}
        <div class="lc-meta">
            <div class="lc-name">{{ $lead->ho_ten ?? 'Khách ẩn danh' }}</div>
            <div class="lc-time">
                <i class="far fa-clock me-1"></i>{{ $lead->created_at->format('H:i · d/m/Y') }}
            </div>
        </div>

        {{-- Badge trạng thái --}}
        <span class="badge {{ $status['class'] }} lc-badge">
            <i class="fas {{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
        </span>
    </div>

    {{-- ── SỐ ĐIỆN THOẠI ── --}}
    <a href="tel:{{ $lead->so_dien_thoai }}" class="lc-phone">
        <i class="fas fa-phone-alt me-2"></i>{{ $lead->so_dien_thoai }}
    </a>

    {{-- ── NGUỒN (BĐS / Dự án / Trang chủ) ── --}}
    <div class="lc-source">
        @if ($lead->batDongSan)
            <span class="lc-source-icon text-success"><i class="fas fa-home"></i></span>
            <span class="lc-source-label">BĐS:</span>
            <a href="{{ route('frontend.bat-dong-san.show', $lead->batDongSan->slug) }}" target="_blank"
                class="lc-source-link">
                {{ \Illuminate\Support\Str::limit($lead->batDongSan->tieu_de, 32) }}
            </a>
        @elseif ($lead->duAn)
            <span class="lc-source-icon text-info"><i class="fas fa-building"></i></span>
            <span class="lc-source-label">Dự án:</span>
            <a href="{{ route('frontend.du-an.show', $lead->duAn->slug) }}" target="_blank" class="lc-source-link">
                {{ $lead->duAn->ten_du_an }}
            </a>
        @else
            <span class="lc-source-icon text-secondary"><i class="fas fa-globe"></i></span>
            <span class="lc-source-label text-muted">Trang chủ / Khác</span>
        @endif
    </div>

    {{-- ── NỘI DUNG TIN NHẮN ── --}}
    @if ($lead->noi_dung)
        <div class="lc-msg">
            <i class="fas fa-quote-left lc-quote-icon"></i>
            {{ $lead->noi_dung }}
        </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="lc-footer">

        {{-- Nhân viên phụ trách --}}
        <div class="lc-assignee">
            @if ($lead->nhanVienPhuTrach)
                <i class="fas fa-user-circle text-primary me-1"></i>
                <span>{{ $lead->nhanVienPhuTrach->ho_ten }}</span>
            @else
                <i class="fas fa-user-clock text-warning me-1"></i>
                <span class="text-warning fst-italic">Chưa có người nhận</span>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="lc-actions">
            {{-- Zalo --}}
            <a href="https://zalo.me/{{ $lead->so_dien_thoai }}" target="_blank" class="lc-btn lc-btn-zalo"
                title="Chat Zalo">
                <i class="fas fa-comment-dots"></i>
            </a>

            {{-- Chuyển CRM --}}
            @if ($lead->trang_thai != 'hoan_thanh')
                <form action="{{ route('nhanvien.admin.lien-he.convert', $lead->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="lc-btn lc-btn-crm" title="Tạo khách hàng CRM"
                        onclick="return confirm('Chuyển yêu cầu này thành Khách Hàng CRM?')">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </form>
            @endif

            {{-- Xoá --}}
            <form action="{{ route('nhanvien.admin.lien-he.destroy', $lead->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="lc-btn lc-btn-del" title="Xoá yêu cầu"
                    onclick="return confirm('Xoá vĩnh viễn yêu cầu này?')">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>

    </div>
</div>


{{-- ══════════════════════════════════════════
     CSS — dán vào <style> trong layout hoặc file CSS riêng
════════════════════════════════════════════ --}}
@once
    @push('styles')
        <style>
            /* ── Lead Card ─────────────────────────────── */
            .lead-card {
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e8eaf0;
                padding: 16px;
                margin-bottom: 12px;
                box-shadow: 0 2px 8px rgba(67, 97, 238, .07);
                transition: transform .18s ease, box-shadow .18s ease;
                position: relative;
                overflow: hidden;
            }

            .lead-card::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 4px;
                background: linear-gradient(180deg, #4361ee, #7209b7);
                border-radius: 14px 0 0 14px;
            }

            .lead-card[data-status="hoan_thanh"]::before {
                background: linear-gradient(180deg, #06d6a0, #1b9965);
            }

            .lead-card[data-status="dang_xu_ly"]::before {
                background: linear-gradient(180deg, #fb8500, #e63946);
            }

            .lead-card[data-status="huy"]::before {
                background: #adb5bd;
            }

            .lead-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(67, 97, 238, .13);
            }

            /* ── Header ──────────────────────────────── */
            .lc-header {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }

            .lc-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 16px;
                color: #fff;
                flex-shrink: 0;
                letter-spacing: .5px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
            }

            .lc-meta {
                flex: 1;
                min-width: 0;
            }

            .lc-name {
                font-weight: 600;
                font-size: .92rem;
                color: #1a1a2e;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .lc-time {
                font-size: .75rem;
                color: #8d99ae;
                margin-top: 2px;
            }

            .lc-badge {
                font-size: .72rem;
                white-space: nowrap;
                border-radius: 20px;
                padding: 4px 9px;
            }

            /* ── Phone ───────────────────────────────── */
            .lc-phone {
                display: inline-flex;
                align-items: center;
                background: #f0f4ff;
                color: #4361ee;
                font-weight: 700;
                font-size: .88rem;
                padding: 6px 13px;
                border-radius: 20px;
                text-decoration: none;
                margin-bottom: 10px;
                transition: background .15s;
                border: 1px solid #d0d9ff;
            }

            .lc-phone:hover {
                background: #4361ee;
                color: #fff;
            }

            /* ── Source ──────────────────────────────── */
            .lc-source {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: .8rem;
                color: #495057;
                background: #f8f9fa;
                border-radius: 8px;
                padding: 5px 10px;
                margin-bottom: 10px;
                border: 1px solid #eee;
            }

            .lc-source-label {
                font-weight: 600;
                color: #555;
            }

            .lc-source-link {
                color: #4361ee;
                text-decoration: none;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 180px;
                display: inline-block;
                vertical-align: bottom;
            }

            .lc-source-link:hover {
                text-decoration: underline;
            }

            /* ── Message ─────────────────────────────── */
            .lc-msg {
                font-size: .82rem;
                color: #6c757d;
                background: #fafafa;
                border-left: 3px solid #dee2e6;
                border-radius: 0 8px 8px 0;
                padding: 7px 10px 7px 12px;
                margin-bottom: 12px;
                line-height: 1.5;
                position: relative;
            }

            .lc-quote-icon {
                color: #ced4da;
                font-size: .7rem;
                margin-right: 4px;
                vertical-align: middle;
            }

            /* ── Footer ──────────────────────────────── */
            .lc-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-top: 10px;
                border-top: 1px solid #f1f3f5;
                gap: 8px;
            }

            .lc-assignee {
                font-size: .8rem;
                color: #495057;
                display: flex;
                align-items: center;
                gap: 3px;
                flex: 1;
                min-width: 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .lc-actions {
                display: flex;
                gap: 6px;
                flex-shrink: 0;
            }

            /* ── Action Buttons ──────────────────────── */
            .lc-btn {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: .82rem;
                cursor: pointer;
                transition: all .18s;
                text-decoration: none;
            }

            .lc-btn-zalo {
                background: #e8f5e9;
                color: #2e7d32;
            }

            .lc-btn-zalo:hover {
                background: #2e7d32;
                color: #fff;
            }

            .lc-btn-crm {
                background: #e3f0ff;
                color: #1565c0;
            }

            .lc-btn-crm:hover {
                background: #1565c0;
                color: #fff;
            }

            .lc-btn-del {
                background: #fce4e4;
                color: #c62828;
            }

            .lc-btn-del:hover {
                background: #c62828;
                color: #fff;
            }
        </style>
    @endpush
@endonce
