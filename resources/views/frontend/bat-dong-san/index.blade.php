@extends('frontend.layouts.master')

@php
    $nhuCau = request('nhu_cau', 'ban');
    $title = $nhuCau == 'ban' ? 'Mua Bán Căn Hộ' : 'Cho Thuê Căn Hộ';
    $isBan = $nhuCau == 'ban';

    $activeFilters = array_filter([
        'timkiem' => request('timkiem'),
        'khu_vuc' => request('khu_vuc'),
        'du_an' => request('du_an'),
        'toa' => request('toa'),
        'gia_tu' => request('gia_tu'),
        'gia_den' => request('gia_den'),
        'sophongngu' => request('sophongngu'),
        'noithat' => request('noithat'),
    ]);
@endphp

@section('title', $title . ' Vinhomes Smart City — Thành Công Land')
@section('meta_description',
    'Tìm kiếm căn hộ ' .
    strtolower($title) .
    ' tại Vinhomes Smart City theo dự án, khu vực,
    tòa nhà. Cập nhật mới nhất từ Thành Công Land.')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@push('styles')
    <style>
        /* ══════════════════ LAYOUT ══════════════════ */
        .bds-page {
            background: var(--bg-alt);
            min-height: 80vh;
        }

        /* ══ TOPBAR ══ */
        .bds-topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0;
            position: sticky;
            top: 68px;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
        }

        /* ── Toolbar row 1 ── */
        .bds-toolbar {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 0;
            flex-wrap: nowrap;
            overflow: visible;
        }

        /* Tabs Mua / Thuê */
        .bds-nhu-cau-tabs {
            display: flex;
            background: var(--bg-alt);
            border-radius: 9px;
            padding: 3px;
            gap: 2px;
            flex-shrink: 0;
            border: 1px solid var(--border);
        }

        .bds-nhu-cau-tab {
            padding: 6px 14px;
            border-radius: 7px;
            font-size: .8rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--text-muted);
            transition: all var(--transition);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .bds-nhu-cau-tab.active {
            background: #fff;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .bds-nhu-cau-tab.active.thue {
            color: var(--secondary);
        }

        /* Search */
        .bds-quick-search {
            flex: 1;
            min-width: 140px;
            max-width: 200px;
            position: relative;
        }

        .bds-quick-search input {
            width: 100%;
            padding: 8px 12px 8px 32px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: .82rem;
            outline: none;
            font-family: inherit;
            background: var(--bg-alt);
            transition: border-color var(--transition), background var(--transition);
            color: var(--text-body);
        }

        .bds-quick-search input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .bds-quick-search i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: .8rem;
            pointer-events: none;
        }

        /* Filter pills */
        .bds-filter-pills {
            display: flex;
            align-items: center;
            gap: 5px;
            flex: 1;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .bds-filter-pills::-webkit-scrollbar {
            display: none;
        }

        .bds-qs {
            padding: 7px 10px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-body);
            background: #fff;
            cursor: pointer;
            outline: none;
            font-family: inherit;
            white-space: nowrap;
            flex-shrink: 0;
            max-width: 155px;
            transition: border-color var(--transition), color var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath fill='%23aaa' d='M4 5L0 0h8z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 24px;
        }

        .bds-qs:focus,
        .bds-qs.has-value {
            border-color: var(--primary);
            color: var(--primary);
            background-color: var(--primary-light);
        }

        /* Sort */
        .bds-sort {
            padding: 7px 24px 7px 10px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-body);
            background: #fff;
            cursor: pointer;
            outline: none;
            font-family: inherit;
            flex-shrink: 0;
            white-space: nowrap;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath fill='%23aaa' d='M4 5L0 0h8z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            transition: border-color var(--transition);
        }

        .bds-sort:focus {
            border-color: var(--primary);
        }

        /* View buttons */
        .bds-view-wrap {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .bds-view-btn {
            width: 33px;
            height: 33px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: .85rem;
            transition: all var(--transition);
            flex-shrink: 0;
        }

        .bds-view-btn.active {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .bds-view-btn:hover:not(.active) {
            border-color: var(--text-muted);
            color: var(--text-body);
        }

        /* ── Row 2: active chips ── */
        .bds-active-filters {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            padding: 5px 0 8px;
            border-top: 1px solid var(--border);
        }

        .bds-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--primary-light);
            border: 1px solid rgba(192, 102, 42, .25);
            color: var(--primary);
            padding: 3px 8px 3px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
        }

        .bds-filter-chip button {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            padding: 0;
            font-size: .65rem;
            opacity: .7;
            display: flex;
            align-items: center;
            transition: opacity var(--transition);
        }

        .bds-filter-chip button:hover {
            opacity: 1;
        }

        .bds-clear-all {
            font-size: .72rem;
            font-weight: 700;
            color: var(--status-danger);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 3px 0;
            white-space: nowrap;
        }

        .bds-result-count {
            font-size: .78rem;
            color: var(--text-muted);
            margin-left: auto;
            white-space: nowrap;
        }

        /* Mobile sidebar btn */
        .bds-sidebar-toggle-btn {
            display: none;
            width: 100%;
            padding: 8px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            background: #fff;
            font-weight: 700;
            font-size: .82rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--secondary);
            margin: 6px 0;
            font-family: inherit;
            transition: all var(--transition);
        }

        .bds-sidebar-toggle-btn:hover {
            border-color: var(--primary);
        }

        /* ══════════════════ LAYOUT MAIN ══════════════════ */
        .bds-main {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 20px;
            padding: 20px 0 40px;
        }

        /* ══════════════════ SIDEBAR ══════════════════ */
        .bds-sidebar {
            position: sticky;
            top: 130px;
            height: fit-content;
            max-height: calc(100vh - 150px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .bds-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .bds-sidebar::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .sidebar-card {
            background: #fff;
            border-radius: 14px;
            border: 1.5px solid var(--border);
            overflow: hidden;
            margin-bottom: 12px;
            transition: box-shadow var(--transition);
        }

        .sidebar-card:hover {
            box-shadow: var(--shadow-sm);
        }

        .sidebar-card-header {
            padding: 12px 16px;
            font-size: .78rem;
            font-weight: 800;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: .6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            user-select: none;
            border-bottom: 1px solid var(--border);
            transition: background var(--transition);
        }

        .sidebar-card-header:hover {
            background: var(--bg-alt);
        }

        .sidebar-card-header i.label-icon {
            color: var(--primary);
            margin-right: 6px;
        }

        .sidebar-card-header .toggle-icon {
            color: var(--text-muted);
            font-size: .7rem;
            transition: transform var(--transition);
        }

        .sidebar-card-header.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        .sidebar-card-body {
            padding: 12px 14px;
        }

        /* Sidebar inputs */
        .sb-input {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: .82rem;
            outline: none;
            font-family: inherit;
            color: var(--text-body);
            transition: border-color var(--transition), box-shadow var(--transition);
            background: var(--bg-alt);
            box-sizing: border-box;
            appearance: none;
        }

        .sb-input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        select.sb-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='5'%3E%3Cpath fill='%23aaa' d='M4 5L0 0h8z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: var(--bg-alt);
            padding-right: 28px;
            cursor: pointer;
        }

        /* Phòng ngủ buttons */
        .sb-btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .sb-option-btn {
            padding: 6px 12px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 700;
            color: var(--text-muted);
            background: #fff;
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
        }

        .sb-option-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .sb-option-btn.active {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Giá range */
        .sb-price-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .sb-price-label {
            font-size: .72rem;
            color: var(--text-muted);
            margin-bottom: 4px;
            font-weight: 600;
        }

        /* Checkbox / radio */
        .sb-check-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sb-check-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: background var(--transition);
            font-size: .82rem;
            color: var(--text-body);
            font-weight: 500;
        }

        .sb-check-item:hover {
            background: var(--bg-alt);
        }

        .sb-check-item input[type="radio"],
        .sb-check-item input[type="checkbox"] {
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
        }

        .sb-check-item.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
        }

        /* Tòa chips */
        .sb-toa-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .sb-toa-chip {
            padding: 5px 12px;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            color: var(--text-muted);
            background: #fff;
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
        }

        .sb-toa-chip:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .sb-toa-chip.active {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Apply btn */
        .sb-apply-btn {
            width: 100%;
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 800;
            font-size: .9rem;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 4px 16px rgba(192, 102, 42, .3);
        }

        .sb-apply-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-gold);
        }

        .sb-clear-link {
            display: block;
            text-align: center;
            font-size: .78rem;
            color: var(--text-muted);
            text-decoration: none;
            margin-top: 8px;
            padding: 6px;
            transition: color var(--transition);
        }

        .sb-clear-link:hover {
            color: var(--status-danger);
        }

        /* Sub-label trong sidebar */
        .sb-sub-label {
            font-size: .7rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ══════════════════ CARDS BĐS ══════════════════ */
        .bds-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .bds-grid.view-2col {
            grid-template-columns: repeat(2, 1fr);
        }

        .bds-grid.view-list {
            grid-template-columns: 1fr;
        }

        .bds-card {
            background: #fff;
            border-radius: 14px;
            border: 1.5px solid var(--border);
            overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition);
            display: flex;
            flex-direction: column;
        }

        .bds-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, .1);
            border-color: rgba(192, 102, 42, .2);
        }

        /* Thumb */
        .bds-thumb {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: var(--bg-alt);
            flex-shrink: 0;
        }

        .bds-grid.view-list .bds-thumb {
            aspect-ratio: 16/9;
            max-width: 280px;
            width: 280px;
        }

        .bds-grid.view-list .bds-card {
            flex-direction: row;
        }

        .bds-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .bds-card:hover .bds-thumb img {
            transform: scale(1.06);
        }

        .bds-thumb-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--text-muted);
            gap: 6px;
        }

        /* Badges */
        .bds-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .bds-badge {
            padding: 3px 9px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .bds-badge-ban {
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
        }

        .bds-badge-thue {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: #fff;
        }

        .bds-badge-noibat {
            background: var(--status-danger);
            color: #fff;
        }

        .bds-badge-moi {
            background: var(--status-success);
            color: #fff;
        }

        /* Actions (yêu thích + so sánh) */
        .bds-card-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 2;
        }

        .bds-action-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .92);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .88rem;
            color: var(--text-muted);
            transition: all var(--transition);
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
            text-decoration: none;
        }

        .bds-action-btn:hover {
            transform: scale(1.12);
        }

        .bds-action-btn.yt-btn:hover,
        .bds-action-btn.yt-btn.liked {
            color: #e53e3e;
            background: #fff;
        }

        .bds-action-btn.ss-btn:hover {
            color: var(--secondary);
            background: #fff;
        }

        .bds-action-btn.ss-btn.active {
            color: var(--secondary);
            background: rgba(27, 58, 107, .1);
        }

        /* Giá trên ảnh */
        .bds-price-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(27, 58, 107, .88);
            color: #fff;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: .88rem;
            font-weight: 800;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .bds-price-badge.ban {
            background: rgba(192, 102, 42, .92);
        }

        .bds-price-badge.thue {
            background: rgba(27, 58, 107, .88);
        }

        /* Card body */
        .bds-card-body {
            padding: 13px 14px 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .bds-card-title {
            font-size: .88rem;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1.4;
            margin: 0 0 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-decoration: none;
            transition: color var(--transition);
        }

        .bds-card-title:hover {
            color: var(--primary);
        }

        .bds-card-loc {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .75rem;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .bds-card-loc i {
            color: var(--primary);
            font-size: .7rem;
            flex-shrink: 0;
        }

        /* Tags */
        .bds-card-tags {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .bds-card-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: .68rem;
            font-weight: 700;
        }

        .bds-card-tag.du-an {
            background: rgba(27, 58, 107, .08);
            color: var(--secondary);
        }

        .bds-card-tag.toa {
            background: var(--primary-light);
            color: var(--primary);
        }

        .bds-card-tag.loai {
            background: rgba(46, 125, 50, .08);
            color: var(--status-success);
        }

        /* Specs */
        .bds-card-specs {
            display: flex;
            gap: 0;
            border-top: 1px solid var(--border);
            margin-top: auto;
            padding-top: 10px;
        }

        .bds-spec {
            flex: 1;
            text-align: center;
            font-size: .75rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .bds-spec:not(:last-child) {
            border-right: 1px solid var(--border);
        }

        .bds-spec i {
            color: var(--primary);
            font-size: .8rem;
        }

        .bds-spec strong {
            font-size: .85rem;
            color: var(--text-heading);
            font-weight: 700;
        }

        /* ══ Empty state ══ */
        .bds-empty {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
            background: #fff;
            border-radius: 16px;
            border: 2px dashed var(--border);
        }

        .bds-empty i {
            font-size: 3.5rem;
            display: block;
            margin-bottom: 14px;
            opacity: .3;
        }

        .bds-empty h3 {
            font-size: 1.1rem;
            color: var(--text-body);
            margin: 0 0 8px;
        }

        .bds-empty p {
            font-size: .875rem;
            margin: 0 0 20px;
        }

        /* ══ Toast ══ */
        .bds-toast {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--secondary);
            color: #fff;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: .82rem;
            font-weight: 700;
            white-space: nowrap;
            z-index: 9999;
            opacity: 0;
            transition: opacity .25s, transform .25s;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
        }

        .bds-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .bds-toast i {
            color: var(--primary);
        }

        /* ══ Result header ══ */
        .bds-result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .bds-result-h1 {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--secondary);
            margin: 0 0 2px;
        }

        .bds-result-sub {
            font-size: .78rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ══════════════════ RESPONSIVE ══════════════════ */
        @media (max-width: 1199px) {
            .bds-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 991px) {

            .bds-filter-pills,
            .bds-sort {
                display: none;
            }

            .bds-view-wrap .bds-view-btn:not(#btnGrid3):not(#btnGrid2) {
                display: none;
            }

            .bds-quick-search {
                max-width: 100%;
                flex: 1;
            }

            .bds-sidebar-toggle-btn {
                display: flex !important;
            }

            .bds-main {
                grid-template-columns: 1fr;
            }

            .bds-sidebar {
                position: static;
                max-height: none;
            }

            .bds-sidebar-wrapper {
                display: none;
            }

            .bds-sidebar-wrapper.open {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .bds-topbar {
                top: 60px;
            }

            .bds-toolbar {
                gap: 5px;
            }

            .bds-nhu-cau-tab {
                padding: 5px 10px;
                font-size: .75rem;
            }

            .bds-nhu-cau-tab i {
                display: none;
            }

            .bds-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ══ TOPBAR ══ --}}
    <div class="bds-topbar">
        <div class="container-fluid px-4">
            <form method="GET" action="{{ route('frontend.bat-dong-san.index') }}" id="formFilter">
                <input type="hidden" name="nhu_cau" value="{{ $nhuCau }}">

                <div class="bds-toolbar">

                    {{-- Tabs Mua / Thuê --}}
                    <div class="bds-nhu-cau-tabs">
                        <a href="{{ route('frontend.bat-dong-san.index', array_merge(request()->except(['nhu_cau', 'page']), ['nhu_cau' => 'ban'])) }}"
                            class="bds-nhu-cau-tab {{ $isBan ? 'active' : '' }}">
                            <i class="fas fa-tag"></i> Mua
                        </a>
                        <a href="{{ route('frontend.bat-dong-san.index', array_merge(request()->except(['nhu_cau', 'page']), ['nhu_cau' => 'thue'])) }}"
                            class="bds-nhu-cau-tab thue {{ !$isBan ? 'active thue' : '' }}">
                            <i class="fas fa-key"></i> Thuê
                        </a>
                    </div>

                    {{-- Tìm kiếm --}}
                    <div class="bds-quick-search">
                        <i class="fas fa-search"></i>
                        <input type="text" name="timkiem" value="{{ request('timkiem') }}"
                            placeholder="Tìm tiêu đề, địa chỉ..." id="inputTimkiem">
                    </div>

                    {{-- Filter pills --}}
                    <div class="bds-filter-pills">
                        <select name="khu_vuc" class="bds-qs {{ request('khu_vuc') ? 'has-value' : '' }}"
                            onchange="this.form.submit()" id="qsKhuVuc">
                            <option value="">🏘 Khu vực</option>
                            @foreach ($khuVucs ?? [] as $kv)
                                <option value="{{ $kv->id }}" {{ request('khu_vuc') == $kv->id ? 'selected' : '' }}>
                                    {{ $kv->ten_khu_vuc }}
                                </option>
                            @endforeach
                        </select>

                        <select name="du_an" class="bds-qs {{ request('du_an') ? 'has-value' : '' }}"
                            onchange="loadToa(this.value); this.form.submit()" id="qsDuAn">
                            <option value="">🏢 Dự án</option>
                            @foreach ($duAns ?? [] as $da)
                                <option value="{{ $da->id }}" data-khu-vuc="{{ $da->khu_vuc_id ?? '' }}"
                                    {{ request('du_an') == $da->id ? 'selected' : '' }}>
                                    {{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>

                        <select name="toa" class="bds-qs {{ request('toa') ? 'has-value' : '' }}" id="qsToa"
                            onchange="this.form.submit()" style="{{ !request('du_an') ? 'display:none' : '' }}">
                            <option value="">🏬 Tất cả tòa</option>
                            @foreach ($toaList ?? [] as $toa)
                                <option value="{{ $toa }}" {{ request('toa') == $toa ? 'selected' : '' }}>
                                    Tòa {{ $toa }}
                                </option>
                            @endforeach
                        </select>

                        <select name="sophongngu" class="bds-qs {{ request('sophongngu') ? 'has-value' : '' }}"
                            onchange="this.form.submit()">
                            <option value="">🛏 Phòng ngủ</option>
                            <option value="studio" {{ request('sophongngu') == 'studio' ? 'selected' : '' }}>Studio
                            </option>
                            <option value="1" {{ request('sophongngu') == '1' ? 'selected' : '' }}>1 PN</option>
                            <option value="2" {{ request('sophongngu') == '2' ? 'selected' : '' }}>2 PN</option>
                            <option value="3" {{ request('sophongngu') == '3' ? 'selected' : '' }}>3 PN+</option>
                        </select>

                        <select name="muc_gia" class="bds-qs {{ request('muc_gia') ? 'has-value' : '' }}"
                            onchange="this.form.submit()">
                            <option value="">💰 Mức giá</option>
                            @if ($isBan)
                                <option value="duoi2ty" {{ request('muc_gia') == 'duoi2ty' ? 'selected' : '' }}>Dưới 2 tỷ
                                </option>
                                <option value="2-5ty" {{ request('muc_gia') == '2-5ty' ? 'selected' : '' }}>2 – 5 tỷ
                                </option>
                                <option value="5-10ty" {{ request('muc_gia') == '5-10ty' ? 'selected' : '' }}>5 – 10 tỷ
                                </option>
                                <option value="tren10ty" {{ request('muc_gia') == 'tren10ty' ? 'selected' : '' }}>Trên 10
                                    tỷ</option>
                            @else
                                <option value="duoi10tr" {{ request('muc_gia') == 'duoi10tr' ? 'selected' : '' }}>Dưới
                                    10tr/tháng</option>
                                <option value="10-20tr" {{ request('muc_gia') == '10-20tr' ? 'selected' : '' }}>10 –
                                    20tr/tháng</option>
                                <option value="20-50tr" {{ request('muc_gia') == '20-50tr' ? 'selected' : '' }}>20 –
                                    50tr/tháng</option>
                                <option value="tren50tr" {{ request('muc_gia') == 'tren50tr' ? 'selected' : '' }}>Trên
                                    50tr/tháng</option>
                            @endif
                        </select>
                    </div>

                    {{-- Sort --}}
                    <select name="sap_xep" class="bds-sort" onchange="this.form.submit()">
                        <option value="moinhat" {{ request('sap_xep', 'moinhat') == 'moinhat' ? 'selected' : '' }}>Mới nhất
                        </option>
                        <option value="giatang" {{ request('sap_xep') == 'giatang' ? 'selected' : '' }}>Giá thấp → cao
                        </option>
                        <option value="giagiam" {{ request('sap_xep') == 'giagiam' ? 'selected' : '' }}>Giá cao → thấp
                        </option>
                        <option value="dientich" {{ request('sap_xep') == 'dientich' ? 'selected' : '' }}>Diện tích lớn
                        </option>
                    </select>

                    {{-- View toggle --}}
                    <div class="bds-view-wrap">
                        <button type="button" class="bds-view-btn active" id="btnGrid3" onclick="setView('grid3')"
                            title="3 cột">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="bds-view-btn" id="btnGrid2" onclick="setView('grid2')"
                            title="2 cột">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="bds-view-btn d-none d-lg-flex" id="btnList"
                            onclick="setView('list')" title="Danh sách">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>

                </div>

                {{-- Active filter chips --}}
                @if (count($activeFilters) > 0)
                    <div class="bds-active-filters">
                        <span
                            style="font-size:.7rem;color:var(--text-muted);font-weight:700;white-space:nowrap;flex-shrink:0">
                            <i class="fas fa-filter" style="font-size:.65rem"></i> Đang lọc:
                        </span>

                        @if (request('timkiem'))
                            <span class="bds-filter-chip">
                                🔍 "{{ Str::limit(request('timkiem'), 20) }}"
                                <button type="button" onclick="removeFilter('timkiem')"><i
                                        class="fas fa-times"></i></button>
                            </span>
                        @endif
                        @if (request('khu_vuc'))
                            <span class="bds-filter-chip">
                                🏘
                                {{ ($khuVucs ?? collect())->firstWhere('id', request('khu_vuc'))?->ten_khu_vuc ?? 'Khu vực' }}
                                <button type="button" onclick="removeFilter('khu_vuc')"><i
                                        class="fas fa-times"></i></button>
                            </span>
                        @endif
                        @if (request('du_an'))
                            <span class="bds-filter-chip">
                                🏢
                                {{ Str::limit(($duAns ?? collect())->firstWhere('id', request('du_an'))?->ten_du_an ?? 'Dự án', 20) }}
                                <button type="button" onclick="removeFilter('du_an', true)"><i
                                        class="fas fa-times"></i></button>
                            </span>
                        @endif
                        @if (request('toa'))
                            <span class="bds-filter-chip">
                                🏬 Tòa {{ request('toa') }}
                                <button type="button" onclick="removeFilter('toa')"><i
                                        class="fas fa-times"></i></button>
                            </span>
                        @endif
                        @if (request('sophongngu'))
                            <span class="bds-filter-chip">
                                🛏 {{ request('sophongngu') == 'studio' ? 'Studio' : request('sophongngu') . ' PN' }}
                                <button type="button" onclick="removeFilter('sophongngu')"><i
                                        class="fas fa-times"></i></button>
                            </span>
                        @endif
                        @if (request('muc_gia'))
                            <span class="bds-filter-chip">
                                💰 Đã chọn mức giá
                                <button type="button" onclick="removeFilter('muc_gia')"><i
                                        class="fas fa-times"></i></button>
                            </span>
                        @endif

                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $nhuCau]) }}"
                            class="bds-clear-all">
                            <i class="fas fa-times-circle"></i> Xóa tất cả
                        </a>
                        <span class="bds-result-count">
                            <strong style="color:var(--secondary)">{{ $batDongSans->total() }}</strong> kết quả
                        </span>
                    </div>
                @endif

            </form>

            {{-- Mobile: nút bộ lọc nâng cao --}}
            <button class="bds-sidebar-toggle-btn" id="btnOpenSidebar">
                <i class="fas fa-sliders-h" style="color:var(--primary)"></i>
                Bộ lọc nâng cao
                @if (count($activeFilters) > 0)
                    <span style="background:var(--primary);color:#fff;border-radius:10px;padding:1px 7px;font-size:.7rem;">
                        {{ count($activeFilters) }}
                    </span>
                @endif
            </button>

        </div>
    </div>

    {{-- ══ MAIN ══ --}}
    <div class="bds-page">
        <div class="container-fluid px-4">
            <div class="bds-main">

                {{-- ════ SIDEBAR ════ --}}
                <div class="bds-sidebar-wrapper" id="sidebarWrapper">
                    <div class="bds-sidebar">
                        <form method="GET" action="{{ route('frontend.bat-dong-san.index') }}" id="formSidebar">
                            <input type="hidden" name="nhu_cau" value="{{ $nhuCau }}">
                            @foreach (request()->except(['timkiem', 'khu_vuc', 'du_an', 'toa', 'sophongngu', 'muc_gia', 'noibat', 'noithat', 'page', 'sidebar']) as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach

                            {{-- Tìm kiếm --}}
                            <div class="sidebar-card">
                                <div class="sidebar-card-header">
                                    <span><i class="fas fa-search label-icon"></i> Tìm kiếm</span>
                                </div>
                                <div class="sidebar-card-body">
                                    <input type="text" name="timkiem" class="sb-input"
                                        value="{{ request('timkiem') }}" placeholder="Tiêu đề, địa chỉ, từ khóa...">
                                </div>
                            </div>

                            {{-- Vị trí --}}
                            <div class="sidebar-card">
                                <div class="sidebar-card-header">
                                    <span><i class="fas fa-map-marked-alt label-icon"></i> Vị trí</span>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-card-body" style="display:flex;flex-direction:column;gap:10px">

                                    <div>
                                        <div class="sb-sub-label">Khu vực</div>
                                        <select name="khu_vuc" class="sb-input" id="sbKhuVuc"
                                            onchange="filterDuAnByKhuVuc(this.value)">
                                            <option value="">— Tất cả khu vực —</option>
                                            @foreach ($khuVucs ?? [] as $kv)
                                                <option value="{{ $kv->id }}"
                                                    {{ request('khu_vuc') == $kv->id ? 'selected' : '' }}>
                                                    {{ $kv->ten_khu_vuc }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <div class="sb-sub-label">Dự án</div>
                                        <select name="du_an" class="sb-input" id="sbDuAn"
                                            onchange="loadToaSidebar(this.value)">
                                            <option value="">— Tất cả dự án —</option>
                                            @foreach ($duAns ?? [] as $da)
                                                <option value="{{ $da->id }}"
                                                    data-khu-vuc="{{ $da->khu_vuc_id ?? '' }}"
                                                    {{ request('du_an') == $da->id ? 'selected' : '' }}>
                                                    {{ $da->ten_du_an }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="sbToaWrap" style="{{ !request('du_an') ? 'display:none' : '' }}">
                                        <div class="sb-sub-label">
                                            <i class="fas fa-building" style="color:var(--primary)"></i>
                                            Chọn tòa nhà
                                        </div>
                                        <div class="sb-toa-chips" id="sbToaChips">
                                            @foreach ($toaList ?? [] as $toa)
                                                <button type="button"
                                                    class="sb-toa-chip {{ request('toa') == $toa ? 'active' : '' }}"
                                                    onclick="selectToa('{{ $toa }}')">
                                                    {{ $toa }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="toa" id="sbToaInput"
                                            value="{{ request('toa') }}">
                                    </div>

                                </div>
                            </div>

                            {{-- Phòng ngủ --}}
                            <div class="sidebar-card">
                                <div class="sidebar-card-header">
                                    <span><i class="fas fa-bed label-icon"></i> Phòng ngủ</span>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-card-body">
                                    @php $pn = request('sophongngu',''); @endphp
                                    <div class="sb-btn-group">
                                        <button type="button" class="sb-option-btn {{ $pn == '' ? 'active' : '' }}"
                                            onclick="setSBOption('sophongngu','',this)">Tất cả</button>
                                        <button type="button"
                                            class="sb-option-btn {{ $pn == 'studio' ? 'active' : '' }}"
                                            onclick="setSBOption('sophongngu','studio',this)">Studio</button>
                                        <button type="button" class="sb-option-btn {{ $pn == '1' ? 'active' : '' }}"
                                            onclick="setSBOption('sophongngu','1',this)">1 PN</button>
                                        <button type="button" class="sb-option-btn {{ $pn == '2' ? 'active' : '' }}"
                                            onclick="setSBOption('sophongngu','2',this)">2 PN</button>
                                        <button type="button" class="sb-option-btn {{ $pn == '3' ? 'active' : '' }}"
                                            onclick="setSBOption('sophongngu','3',this)">3 PN+</button>
                                    </div>
                                    <input type="hidden" name="sophongngu" id="sbPNInput"
                                        value="{{ request('sophongngu') }}">
                                </div>
                            </div>

                            {{-- Mức giá --}}
                            <div class="sidebar-card">
                                <div class="sidebar-card-header">
                                    <span><i class="fas fa-tag label-icon"></i> Mức giá</span>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-card-body">
                                    @php $mg = request('muc_gia',''); @endphp
                                    <div class="sb-check-list">
                                        <label class="sb-check-item {{ $mg == '' ? 'active' : '' }}">
                                            <input type="radio" name="muc_gia" value=""
                                                {{ $mg == '' ? 'checked' : '' }}> Tất cả mức giá
                                        </label>
                                        @if ($isBan)
                                            <label class="sb-check-item {{ $mg == 'duoi2ty' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="duoi2ty"
                                                    {{ $mg == 'duoi2ty' ? 'checked' : '' }}> Dưới 2 tỷ</label>
                                            <label class="sb-check-item {{ $mg == '2-5ty' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="2-5ty"
                                                    {{ $mg == '2-5ty' ? 'checked' : '' }}> 2 – 5 tỷ</label>
                                            <label class="sb-check-item {{ $mg == '5-10ty' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="5-10ty"
                                                    {{ $mg == '5-10ty' ? 'checked' : '' }}> 5 – 10 tỷ</label>
                                            <label class="sb-check-item {{ $mg == 'tren10ty' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="tren10ty"
                                                    {{ $mg == 'tren10ty' ? 'checked' : '' }}> Trên 10 tỷ</label>
                                        @else
                                            <label class="sb-check-item {{ $mg == 'duoi10tr' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="duoi10tr"
                                                    {{ $mg == 'duoi10tr' ? 'checked' : '' }}> Dưới 10 triệu/tháng</label>
                                            <label class="sb-check-item {{ $mg == '10-20tr' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="10-20tr"
                                                    {{ $mg == '10-20tr' ? 'checked' : '' }}> 10 – 20 triệu/tháng</label>
                                            <label class="sb-check-item {{ $mg == '20-50tr' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="20-50tr"
                                                    {{ $mg == '20-50tr' ? 'checked' : '' }}> 20 – 50 triệu/tháng</label>
                                            <label class="sb-check-item {{ $mg == 'tren50tr' ? 'active' : '' }}"><input
                                                    type="radio" name="muc_gia" value="tren50tr"
                                                    {{ $mg == 'tren50tr' ? 'checked' : '' }}> Trên 50 triệu/tháng</label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Nội thất --}}
                            <div class="sidebar-card">
                                <div class="sidebar-card-header">
                                    <span><i class="fas fa-couch label-icon"></i> Nội thất</span>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-card-body">
                                    @php $nt = request('noithat',''); @endphp
                                    <div class="sb-check-list">
                                        <label class="sb-check-item {{ $nt == '' ? 'active' : '' }}"><input
                                                type="radio" name="noithat" value=""
                                                {{ $nt == '' ? 'checked' : '' }}> Tất cả</label>
                                        <label class="sb-check-item {{ $nt == 'full' ? 'active' : '' }}"><input
                                                type="radio" name="noithat" value="full"
                                                {{ $nt == 'full' ? 'checked' : '' }}> Full nội thất</label>
                                        <label class="sb-check-item {{ $nt == 'co-ban' ? 'active' : '' }}"><input
                                                type="radio" name="noithat"
                                                value="co-ban"{{ $nt == 'co-ban' ? 'checked' : '' }}> Nội thất cơ
                                            bản</label>
                                        <label class="sb-check-item {{ $nt == 'tho' ? 'active' : '' }}"><input
                                                type="radio" name="noithat" value="tho"
                                                {{ $nt == 'tho' ? 'checked' : '' }}> Nhà thô</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Lọc thêm --}}
                            <div class="sidebar-card">
                                <div class="sidebar-card-header">
                                    <span><i class="fas fa-sliders-h label-icon"></i> Lọc thêm</span>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                <div class="sidebar-card-body">
                                    <div class="sb-check-list">
                                        <label class="sb-check-item">
                                            <input type="checkbox" name="noibat" value="1"
                                                {{ request('noibat') ? 'checked' : '' }}>
                                            ⭐ Chỉ căn hộ nổi bật
                                        </label>
                                        <label class="sb-check-item">
                                            <input type="checkbox" name="vao_o_ngay" value="1"
                                                {{ request('vao_o_ngay') ? 'checked' : '' }}>
                                            🔑 Vào ở ngay
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="sb-apply-btn">
                                <i class="fas fa-filter"></i> Áp dụng bộ lọc
                            </button>
                            <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $nhuCau]) }}"
                                class="sb-clear-link">
                                <i class="fas fa-times"></i> Xóa tất cả bộ lọc
                            </a>

                        </form>
                    </div>
                </div>

                {{-- ════ KẾT QUẢ ════ --}}
                <div>

                    {{-- Tiêu đề kết quả --}}
                    <div class="bds-result-header">
                        <div>
                            <h1 class="bds-result-h1">
                                {{ $title }}
                                @if (request('du_an'))
                                    —
                                    {{ Str::limit(($duAns ?? collect())->firstWhere('id', request('du_an'))?->ten_du_an ?? '', 30) }}
                                @endif
                                @if (request('toa'))
                                    <span style="color:var(--primary)">— Tòa {{ request('toa') }}</span>
                                @endif
                            </h1>
                            <p class="bds-result-sub">
                                Tìm thấy <strong style="color:var(--secondary)">{{ $batDongSans->total() }}</strong> bất
                                động sản
                            </p>
                        </div>
                    </div>

                    {{-- Grid BĐS --}}
                    <div class="bds-grid" id="bdsGrid">

                        @forelse($batDongSans as $bds)
                            @php
                                $anhBia =
                                    is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null;
                                $isNew = $bds->created_at?->diffInDays(now()) <= 7;
                            @endphp

                            <div class="bds-card">
                                <div class="bds-thumb">
                                    @if ($anhBia)
                                        <img src="{{ asset('storage/' . $anhBia) }}" alt="{{ $bds->tieu_de }}"
                                            loading="lazy"
                                            onerror="this.parentElement.innerHTML='<div class=\'bds-thumb-placeholder\'><i class=\'fas fa-image\' style=\'font-size:2rem\'></i><span style=\'font-size:.75rem\'>Chưa có ảnh</span></div>'">
                                    @else
                                        <div class="bds-thumb-placeholder">
                                            <i class="fas fa-building" style="font-size:2.5rem"></i>
                                            <span style="font-size:.75rem">Chưa có ảnh</span>
                                        </div>
                                    @endif

                                    {{-- Badges --}}
                                    <div class="bds-badges">
                                        <span
                                            class="bds-badge {{ $bds->nhu_cau == 'ban' ? 'bds-badge-ban' : 'bds-badge-thue' }}">
                                            {{ $bds->nhu_cau == 'ban' ? '🏷 Đang bán' : '🔑 Cho thuê' }}
                                        </span>
                                        @if ($bds->noi_bat)
                                            <span class="bds-badge bds-badge-noibat">⭐ Nổi bật</span>
                                        @endif
                                        @if ($isNew)
                                            <span class="bds-badge bds-badge-moi">🆕 Mới</span>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="bds-card-actions">
                                        <button type="button"
                                            class="bds-action-btn yt-btn heart-icon-{{ $bds->id }} {{ $bds->isYeuThich ?? false ? 'liked' : '' }}"
                                            onclick="toggleYeuThich(this, {{ $bds->id }})"
                                            title="{{ $bds->isYeuThich ?? false ? 'Bỏ yêu thích' : 'Yêu thích' }}">
                                            <i class="{{ $bds->isYeuThich ?? false ? 'fas' : 'far' }} fa-heart"></i>
                                        </button>
                                        <button type="button" class="bds-action-btn ss-btn"
                                            id="ss-btn-{{ $bds->id }}"
                                            onclick="themVaSoSanh({{ $bds->id }}, '{{ addslashes(Str::limit($bds->tieu_de, 40)) }}')"
                                            title="So sánh căn hộ này">
                                            <i class="fas fa-balance-scale"></i>
                                        </button>
                                    </div>

                                    {{-- Giá --}}
                                    <div class="bds-price-badge {{ $bds->nhu_cau == 'ban' ? 'ban' : 'thue' }}">
                                        {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}
                                    </div>
                                </div>

                                {{-- Body --}}
                                <div class="bds-card-body">
                                    <a href="{{ route('frontend.bat-dong-san.show', $bds->slug ?? $bds->id) }}"
                                        class="bds-card-title">
                                        {{ $bds->tieu_de }}
                                    </a>

                                    <div class="bds-card-loc">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $bds->duAn?->ten_du_an ?? Str::limit($bds->dia_chi, 40) }}
                                    </div>

                                    <div class="bds-card-tags">
                                        @if ($bds->duAn)
                                            <span class="bds-card-tag du-an">
                                                <i class="fas fa-city" style="font-size:.65rem"></i>
                                                {{ Str::limit($bds->duAn->ten_du_an, 18) }}
                                            </span>
                                        @endif
                                        @if (!empty($bds->toa))
                                            <span class="bds-card-tag toa">
                                                <i class="fas fa-building" style="font-size:.65rem"></i>
                                                Tòa {{ $bds->toa }}
                                            </span>
                                        @endif
                                        @if (!empty($bds->loai_hinh))
                                            <span class="bds-card-tag loai">{{ $bds->loai_hinh }}</span>
                                        @endif
                                    </div>

                                    <div class="bds-card-specs">
                                        <div class="bds-spec">
                                            <i class="fas fa-ruler-combined"></i>
                                            <strong>{{ $bds->dien_tich ?? '—' }}</strong>
                                            <span>m²</span>
                                        </div>
                                        <div class="bds-spec">
                                            <i class="fas fa-bed"></i>
                                            <strong>{{ $bds->so_phong_ngu ?? '—' }}</strong>
                                            <span>Phòng ngủ</span>
                                        </div>
                                        <div class="bds-spec">
                                            <i class="fas fa-bath"></i>
                                            <strong>{{ $bds->so_phong_tam ?? '—' }}</strong>
                                            <span>Toilet</span>
                                        </div>
                                        <div class="bds-spec">
                                            <i class="fas fa-couch"></i>
                                            <strong
                                                style="font-size:.72rem">{{ !empty($bds->noi_that) ? Str::limit($bds->noi_that, 6) : '—' }}</strong>
                                            <span>Nội thất</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="bds-empty">
                                <i class="fas fa-search"></i>
                                <h3>Không tìm thấy bất động sản nào</h3>
                                <p>Thử thay đổi bộ lọc hoặc mở rộng tiêu chí tìm kiếm</p>
                                <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $nhuCau]) }}"
                                    class="btn btn-primary-brand rounded-3 px-4 py-2 fw-bold">
                                    <i class="fas fa-redo me-2"></i>Xem tất cả căn hộ
                                </a>
                            </div>
                        @endforelse

                    </div>

                    {{-- Phân trang --}}
                    @if ($batDongSans->hasPages())
                        <div style="margin-top:24px;display:flex;justify-content:center">
                            {{ $batDongSans->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>{{-- .bds-main --}}
        </div>
    </div>

    {{-- Toast --}}
    <div class="bds-toast" id="bdsToast">
        <i class="fas fa-check-circle"></i>
        <span id="bdsToastMsg"></span>
    </div>

@endsection

@push('scripts')
    <script>
        const ALL_DU_ANS = @json($duAns ?? []);
        const ALL_TOA = @json($toaList ?? []);

        /* ── View toggle ── */
        function setView(mode) {
            const grid = document.getElementById('bdsGrid');
            grid.className = 'bds-grid';
            if (mode === 'grid2') grid.classList.add('view-2col');
            if (mode === 'list') grid.classList.add('view-list');

            ['btnGrid3', 'btnGrid2', 'btnList'].forEach(id => document.getElementById(id)?.classList.remove('active'));
            const map = {
                grid3: 'btnGrid3',
                grid2: 'btnGrid2',
                list: 'btnList'
            };
            document.getElementById(map[mode])?.classList.add('active');
            localStorage.setItem('bds_view', mode);
        }
        const savedView = localStorage.getItem('bds_view');
        if (savedView) setView(savedView);

        /* ── Remove filter chip ── */
        function removeFilter(key, clearToa = false) {
            const url = new URL(window.location.href);
            url.searchParams.delete(key);
            url.searchParams.delete('page');
            if (clearToa) url.searchParams.delete('toa');
            window.location.href = url.toString();
        }

        /* ── Cascade: Khu vực → Dự án ── */
        function filterDuAnByKhuVuc(khuVucId) {
            const sbDuAn = document.getElementById('sbDuAn');
            if (!sbDuAn) return;
            sbDuAn.querySelectorAll('option:not([value=""])').forEach(opt => {
                opt.style.display = (!khuVucId || opt.dataset.khuVuc == khuVucId) ? '' : 'none';
            });
            sbDuAn.value = '';
            document.getElementById('sbToaWrap').style.display = 'none';
        }

        /* ── Cascade: Dự án → Tòa ── */
        function loadToaSidebar(duAnId) {
            const wrap = document.getElementById('sbToaWrap');
            const chips = document.getElementById('sbToaChips');
            const input = document.getElementById('sbToaInput');
            if (!duAnId) {
                wrap.style.display = 'none';
                input.value = '';
                return;
            }

            fetch(`/api/toa-by-du-an/${duAnId}`)
                .then(r => r.json())
                .then(data => {
                    chips.innerHTML = '';
                    if (data.length === 0) {
                        wrap.style.display = 'none';
                        return;
                    }
                    data.forEach(toa => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'sb-toa-chip';
                        btn.textContent = toa;
                        btn.onclick = () => selectToa(toa);
                        chips.appendChild(btn);
                    });
                    wrap.style.display = 'block';
                })
                .catch(() => {
                    chips.innerHTML = '';
                    ALL_TOA.forEach(toa => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'sb-toa-chip';
                        btn.textContent = toa;
                        btn.onclick = () => selectToa(toa);
                        chips.appendChild(btn);
                    });
                    if (ALL_TOA.length > 0) wrap.style.display = 'block';
                });
        }

        function loadToa(duAnId) {
            const sel = document.getElementById('qsToa');
            if (!sel) return;
            sel.style.display = duAnId ? '' : 'none';
        }

        function selectToa(toa) {
            const input = document.getElementById('sbToaInput');
            document.querySelectorAll('#sbToaChips .sb-toa-chip').forEach(c => c.classList.toggle('active', c
                .textContent === toa));
            input.value = (input.value === toa) ? '' : toa;
            if (input.value === '') document.querySelectorAll('#sbToaChips .sb-toa-chip').forEach(c => c.classList.remove(
                'active'));
        }

        /* ── Phòng ngủ ── */
        function setSBOption(inputName, val, btn) {
            document.getElementById('sbPNInput').value = val;
            btn.closest('.sb-btn-group').querySelectorAll('.sb-option-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        /* ── Radio active ── */
        document.querySelectorAll('.sb-check-item input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                this.closest('.sb-check-list').querySelectorAll('.sb-check-item').forEach(li => li.classList
                    .remove('active'));
                this.closest('.sb-check-item').classList.add('active');
            });
        });

        /* ── Sidebar collapsible ── */
        document.querySelectorAll('.sidebar-card-header').forEach(header => {
            header.addEventListener('click', function() {
                const body = this.nextElementSibling;
                body.style.display = body.style.display === 'none' ? '' : 'none';
                this.classList.toggle('collapsed');
            });
        });

        /* ── Mobile sidebar toggle ── */
        document.getElementById('btnOpenSidebar')?.addEventListener('click', function() {
            const sw = document.getElementById('sidebarWrapper');
            sw.classList.toggle('open');
            this.innerHTML = sw.classList.contains('open') ?
                '<i class="fas fa-times" style="color:var(--primary)"></i> Đóng bộ lọc' :
                '<i class="fas fa-sliders-h" style="color:var(--primary)"></i> Bộ lọc nâng cao';
        });

        /* ── So sánh ── */
        function themVaSoSanh(id, ten) {
            id = parseInt(id);
            const btn = document.getElementById('ss-btn-' + id);
            const daCoTrongList = soSanhList.find(x => x.id === id);
            if (daCoTrongList) {
                removeSoSanh(id);
                btn?.classList.remove('active');
                showFlash('Đã bỏ khỏi danh sách so sánh.', 'info');
                return;
            }
            addSoSanh(id, ten);
            btn?.classList.add('active');
            if (soSanhList.length >= 2) {
                openSoSanhModal();
            } else {
                showFlash('Chọn thêm ' + (2 - soSanhList.length) + ' căn hộ nữa để so sánh.', 'info');
            }
        }

        /* ── Khởi tạo ── */
        document.addEventListener('DOMContentLoaded', function() {
            soSanhList.forEach(item => {
                document.getElementById('ss-btn-' + item.id)?.classList.add('active');
            });
        });
    </script>
@endpush
