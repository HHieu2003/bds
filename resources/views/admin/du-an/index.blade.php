@extends('admin.layouts.master')

@section('title', 'Quản lý Dự án')

@push('styles')
    <style>
        /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header-left h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header-left h1 i {
            color: #FF8C42;
        }

        .page-header-left p {
            color: #888;
            font-size: .85rem;
            margin: 0;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: .88rem;
            text-decoration: none;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(255, 140, 66, .3);
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #e67e22, #FF8C42);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(255, 140, 66, .4);
        }

        /* ══════════════════════════════════════
       STAT CARDS
    ══════════════════════════════════════ */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media(max-width:900px) {
            .stat-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:500px) {
            .stat-row {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            border: 1px solid #f0f0f0;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .1);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-icon.orange {
            background: #fff4ec;
            color: #FF8C42;
        }

        .stat-icon.blue {
            background: #e8f0ff;
            color: #1a3c5e;
        }

        .stat-icon.green {
            background: #e8f8f0;
            color: #27ae60;
        }

        .stat-icon.purple {
            background: #f3eeff;
            color: #8b5cf6;
        }

        .stat-info strong {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a3c5e;
            display: block;
            line-height: 1;
        }

        .stat-info span {
            font-size: .78rem;
            color: #999;
            margin-top: 4px;
            display: block;
        }

        /* ══════════════════════════════════════
       FILTER CARD
    ══════════════════════════════════════ */
        .filter-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            border: 1px solid #f0f0f0;
        }

        .filter-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-input {
            flex: 1;
            min-width: 200px;
            height: 40px;
            border: 1.5px solid #e8e8e8;
            border-radius: 9px;
            padding: 0 14px;
            font-size: .875rem;
            color: #333;
            outline: none;
            transition: border .2s;
            background: #fafafa;
        }

        .filter-input:focus {
            border-color: #FF8C42;
            background: #fff;
        }

        .filter-select {
            height: 40px;
            border: 1.5px solid #e8e8e8;
            border-radius: 9px;
            padding: 0 12px;
            font-size: .875rem;
            color: #333;
            outline: none;
            background: #fafafa;
            min-width: 150px;
            cursor: pointer;
            transition: border .2s;
        }

        .filter-select:focus {
            border-color: #FF8C42;
        }

        .btn-filter {
            height: 40px;
            padding: 0 20px;
            background: #1a3c5e;
            color: #fff;
            border: none;
            border-radius: 9px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .2s;
            white-space: nowrap;
        }

        .btn-filter:hover {
            background: #14304d;
        }

        .btn-reset {
            height: 40px;
            padding: 0 16px;
            background: #f5f5f5;
            color: #666;
            border: 1.5px solid #e0e0e0;
            border-radius: 9px;
            font-size: .875rem;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-reset:hover {
            background: #ffe9d8;
            border-color: #FF8C42;
            color: #FF8C42;
        }

        /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
        .table-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }

        .table-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-header h3 {
            font-size: .95rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-count {
            background: #fff4ec;
            color: #FF8C42;
            font-size: .75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
        }

        .admin-table thead th {
            background: #f8faff;
            color: #1a3c5e;
            font-weight: 700;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 12px 16px;
            border-bottom: 2px solid #eef2ff;
            white-space: nowrap;
        }

        .admin-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background .15s;
        }

        .admin-table tbody tr:last-child {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background: #fafcff;
        }

        .admin-table td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #444;
        }

        /* Thumbnail */
        .project-thumb {
            width: 68px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        .project-thumb-placeholder {
            width: 68px;
            height: 52px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e8f0ff, #f0f4ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c0cfe0;
            font-size: 1.2rem;
            border: 1px solid #eee;
        }

        /* Project name cell */
        .project-name {
            font-weight: 700;
            color: #1a3c5e;
            margin-bottom: 3px;
        }

        .project-addr {
            font-size: .78rem;
            color: #999;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Badges */
        .badge-kv {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            background: #e8f0ff;
            color: #1a3c5e;
            white-space: nowrap;
        }

        .badge-num {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
            min-width: 32px;
            text-align: center;
        }

        .badge-num.orange {
            background: #fff4ec;
            color: #FF8C42;
        }

        .badge-num.blue {
            background: #e8f0ff;
            color: #1a3c5e;
        }

        .badge-num.zero {
            background: #f5f5f5;
            color: #bbb;
        }

        /* Toggle hiển thị */
        .toggle-btn {
            width: 44px;
            height: 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            position: relative;
            transition: background .25s;
            outline: none;
            flex-shrink: 0;
        }

        .toggle-btn::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            top: 3px;
            left: 3px;
            transition: transform .25s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
        }

        .toggle-btn.on {
            background: #27ae60;
        }

        .toggle-btn.off {
            background: #ccc;
        }

        .toggle-btn.on::after {
            transform: translateX(20px);
        }

        /* Action buttons */
        .act-btns {
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
        }

        .act-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            transition: all .2s;
            text-decoration: none;
        }

        .act-btn.edit {
            background: #e8f0ff;
            color: #1a3c5e;
        }

        .act-btn.delete {
            background: #ffeaea;
            color: #e74c3c;
        }

        .act-btn.view {
            background: #e8f8f0;
            color: #27ae60;
        }

        .act-btn:hover {
            transform: scale(1.12);
        }

        .act-btn.edit:hover {
            background: #1a3c5e;
            color: #fff;
        }

        .act-btn.delete:hover {
            background: #e74c3c;
            color: #fff;
        }

        .act-btn.view:hover {
            background: #27ae60;
            color: #fff;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #bbb;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            display: block;
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0 0 16px;
        }

        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FF8C42;
            color: #fff;
            padding: 9px 20px;
            border-radius: 9px;
            font-weight: 600;
            text-decoration: none;
            font-size: .875rem;
            transition: background .2s;
        }

        .empty-state a:hover {
            background: #e67e22;
        }

        /* Pagination wrapper */
        .pagination-wrap {
            padding: 16px 20px;
            border-top: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-info {
            font-size: .82rem;
            color: #999;
        }

        .pagination-wrap .pagination {
            margin: 0;
        }

        .pagination-wrap .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            font-size: .82rem;
            color: #1a3c5e;
            border: 1.5px solid #eee;
        }

        .pagination-wrap .page-item.active .page-link {
            background: #FF8C42;
            border-color: #FF8C42;
            color: #fff;
        }

        /* Alert */
        .alert-success-custom {
            background: linear-gradient(135deg, #e8f8f0, #d5f2e3);
            border: 1px solid #a8e6c1;
            color: #1a7a40;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            font-size: .9rem;
        }

        .alert-success-custom i {
            font-size: 1rem;
            color: #27ae60;
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1><i class="fas fa-city"></i> Quản lý Dự án</h1>
            <p>Toàn bộ dự án bất động sản trên hệ thống</p>
        </div>
        <a href="{{ route('nhanvien.admin.du-an.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Thêm dự án mới
        </a>
    </div>

    {{-- ── Flash message ── --}}
    @if (session('success'))
        <div class="alert-success-custom">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Stat Cards ── --}}
    @php
        $tongDuAn = $duAns->total();
        $tongHienThi = $duAns->getCollection()->where('hien_thi', true)->count();
        $tongCanBan = $duAns->getCollection()->sum('so_can_ban');
        $tongCanThue = $duAns->getCollection()->sum('so_can_thue');
    @endphp
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-city"></i></div>
            <div class="stat-info">
                <strong>{{ $duAns->total() }}</strong>
                <span>Tổng dự án</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-eye"></i></div>
            <div class="stat-info">
                <strong>{{ \App\Models\DuAn::whereNull('deleted_at')->where('hien_thi', 1)->count() }}</strong>
                <span>Đang hiển thị</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-tag"></i></div>
            <div class="stat-info">
                <strong>{{ $tongCanBan }}</strong>
                <span>Căn đang bán</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-key"></i></div>
            <div class="stat-info">
                <strong>{{ $tongCanThue }}</strong>
                <span>Căn đang thuê</span>
            </div>
        </div>
    </div>

    {{-- ── Bộ lọc ── --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('nhanvien.admin.du-an.index') }}">
            <div class="filter-row">
                <input type="text" name="tukhoa" value="{{ request('tukhoa') }}" class="filter-input"
                    placeholder="🔍  Tìm tên dự án, địa chỉ...">

                <select name="khu_vuc_id" class="filter-select">
                    <option value="">📍 Tất cả khu vực</option>
                    @foreach ($khuVucs as $kv)
                        <option value="{{ $kv->id }}" {{ request('khu_vuc_id') == $kv->id ? 'selected' : '' }}>
                            {{ $kv->ten_khu_vuc }}
                        </option>
                    @endforeach
                </select>

                <select name="hien_thi" class="filter-select">
                    <option value="">👁 Tất cả trạng thái</option>
                    <option value="1" {{ request('hien_thi') === '1' ? 'selected' : '' }}>✅ Đang hiển thị</option>
                    <option value="0" {{ request('hien_thi') === '0' ? 'selected' : '' }}>🚫 Đang ẩn</option>
                </select>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Lọc
                </button>

                @if (request()->hasAny(['tukhoa', 'khu_vuc_id', 'hien_thi']))
                    <a href="{{ route('nhanvien.admin.du-an.index') }}" class="btn-reset">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Bảng danh sách ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <h3>
                <i class="fas fa-list" style="color:#FF8C42"></i>
                Danh sách dự án
            </h3>
            <span class="table-count">{{ $duAns->total() }} dự án</span>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th style="width:80px">Ảnh</th>
                        <th>Tên dự án / Địa chỉ</th>
                        <th>Khu vực</th>
                        <th>Chủ đầu tư</th>
                        <th class="text-center">Căn bán</th>
                        <th class="text-center">Căn thuê</th>
                        <th class="text-center">Thứ tự</th>
                        <th class="text-center">Hiển thị</th>
                        <th class="text-center" style="width:120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duAns as $i => $da)
                        <tr id="row-{{ $da->id }}">
                            {{-- STT --}}
                            <td class="text-muted" style="font-size:.78rem">
                                {{ ($duAns->currentPage() - 1) * $duAns->perPage() + $i + 1 }}
                            </td>

                            {{-- Ảnh --}}
                            <td>
                                @if ($da->hinh_anh)
                                    <img src="{{ Storage::url($da->hinh_anh) }}" class="project-thumb"
                                        alt="{{ $da->ten_du_an }}">
                                @else
                                    <div class="project-thumb-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Tên & Địa chỉ --}}
                            <td style="min-width:200px">
                                <div class="project-name">{{ $da->ten_du_an }}</div>
                                <div class="project-addr">
                                    <i class="fas fa-map-marker-alt" style="color:#FF8C42;font-size:.7rem"></i>
                                    {{ Str::limit($da->dia_chi, 55) }}
                                </div>
                            </td>

                            {{-- Khu vực --}}
                            <td>
                                <span class="badge-kv">
                                    <i class="fas fa-map-marker-alt me-1" style="font-size:.7rem"></i>
                                    {{ $da->khuVuc->ten_khu_vuc ?? '—' }}
                                </span>
                            </td>

                            {{-- Chủ đầu tư --}}
                            <td style="color:#666;font-size:.85rem">
                                {{ $da->chu_dau_tu ?: '—' }}
                            </td>

                            {{-- Căn bán --}}
                            <td class="text-center">
                                <span class="badge-num {{ $da->so_can_ban > 0 ? 'orange' : 'zero' }}">
                                    {{ $da->so_can_ban }}
                                </span>
                            </td>

                            {{-- Căn thuê --}}
                            <td class="text-center">
                                <span class="badge-num {{ $da->so_can_thue > 0 ? 'blue' : 'zero' }}">
                                    {{ $da->so_can_thue }}
                                </span>
                            </td>

                            {{-- Thứ tự --}}
                            <td class="text-center" style="color:#999;font-size:.85rem">
                                {{ $da->thu_tu_hien_thi ?? 0 }}
                            </td>

                            {{-- Toggle hiển thị --}}
                            <td class="text-center">
                                <div style="display:flex;justify-content:center">
                                    <button type="button" class="toggle-btn {{ $da->hien_thi ? 'on' : 'off' }}"
                                        data-id="{{ $da->id }}"
                                        data-url="{{ route('nhanvien.admin.du-an.toggle', $da) }}"
                                        title="{{ $da->hien_thi ? 'Đang hiển thị — Click để ẩn' : 'Đang ẩn — Click để hiện' }}">
                                    </button>
                                </div>
                            </td>

                            {{-- Thao tác --}}
                            <td>
                                <div class="act-btns">
                                    <a href="{{ route('nhanvien.admin.du-an.edit', $da) }}" class="act-btn edit"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('nhanvien.admin.du-an.destroy', $da) }}" method="POST"
                                        class="d-inline" onsubmit="return confirmDelete(this, '{{ $da->ten_du_an }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="fas fa-city"></i>
                                    <p>Chưa có dự án nào</p>
                                    <a href="{{ route('nhanvien.admin.du-an.create') }}">
                                        <i class="fas fa-plus"></i> Thêm dự án đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($duAns->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Hiển thị <strong>{{ $duAns->firstItem() }}–{{ $duAns->lastItem() }}</strong>
                    trong tổng số <strong>{{ $duAns->total() }}</strong> dự án
                </div>
                {{ $duAns->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        // Toggle hiển thị bằng AJAX
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.dataset.url;
                const self = this;

                fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.hien_thi) {
                            self.classList.replace('off', 'on');
                            self.title = 'Đang hiển thị — Click để ẩn';
                        } else {
                            self.classList.replace('on', 'off');
                            self.title = 'Đang ẩn — Click để hiện';
                        }
                    })
                    .catch(() => alert('Lỗi kết nối, thử lại!'));
            });
        });

        // Confirm xóa đẹp
        function confirmDelete(form, name) {
            return confirm(`⚠️ Xác nhận xóa dự án:\n"${name}"?\n\nHành động này không thể hoàn tác!`);
        }
    </script>
@endpush
