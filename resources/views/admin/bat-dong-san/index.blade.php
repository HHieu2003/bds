@extends('admin.layouts.master')
@section('title', 'Quản lý Bất động sản')

@section('content')
    {{-- ══ HEADER ══ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-building text-primary"></i> Bất động sản</h1>
            <p class="page-header-sub mb-0">Quản lý toàn bộ danh sách bất động sản</p>
        </div>
        <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm BĐS mới
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ══ THỐNG KÊ ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon blue bg-opacity-10"><i class="fas fa-building"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['tong']) }}</div>
                    <div class="stat-label">Tổng BĐS</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon green bg-opacity-10"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['con_hang']) }}</div>
                    <div class="stat-label">Còn hàng</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon navy bg-opacity-10"><i class="fas fa-key"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['dang_thue']) }}</div>
                    <div class="stat-label">Đang cho thuê</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon orange bg-opacity-10"><i class="fas fa-handshake"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['dat_coc']) }}</div>
                    <div class="stat-label">Đặt cọc</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon red bg-opacity-10"><i class="fas fa-times-circle"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['da_ban']) }}</div>
                    <div class="stat-label">Đã giao dịch</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BỘ LỌC ══ --}}
    <div class="filter-box mb-4">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tukhoa') }}" placeholder="🔍 Tìm tiêu đề, mã BĐS...">
                </div>
                <div class="col-6 col-md-2">
                    <select name="nhu_cau" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Nhu cầu</option>
                        <option value="ban" @selected(request('nhu_cau') == 'ban')>🏷 Bán</option>
                        <option value="thue" @selected(request('nhu_cau') == 'thue')>🔑 Cho thuê</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="loai_hinh" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Loại hình</option>
                        @foreach (['can_ho' => 'Căn hộ', 'nha_pho' => 'Nhà phố', 'biet_thu' => 'Biệt thự', 'dat_nen' => 'Đất nền', 'shophouse' => 'Shophouse'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('loai_hinh') == $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="trang_thai" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Trạng thái</option>
                        @foreach (['con_hang' => '✅ Còn hàng', 'dat_coc' => '🤝 Đặt cọc', 'da_ban' => '❌ Đã bán', 'dang_thue' => '🔑 Đang thuê', 'da_thue' => '📦 Đã thuê', 'tam_an' => '⏸ Tạm ẩn'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="du_an_id" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Tất cả dự án</option>
                        @foreach ($duAns as $da)
                            <option value="{{ $da->id }}" @selected(request('du_an_id') == $da->id)>
                                {{ Str::limit($da->ten_du_an, 30) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="sapxep" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>📅 Mới nhất</option>
                        <option value="gia_tang" @selected(request('sapxep') == 'gia_tang')>💰 Giá tăng dần</option>
                        <option value="gia_giam" @selected(request('sapxep') == 'gia_giam')>💰 Giá giảm dần</option>
                        <option value="luot_xem" @selected(request('sapxep') == 'luot_xem')>👁 Lượt xem nhiều</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto ms-auto d-flex gap-2">
                    <button type="submit" class="btn btn-navy"><i class="fas fa-search"></i> Lọc</button>
                    @if (request()->hasAny(['tukhoa', 'nhu_cau', 'loai_hinh', 'trang_thai', 'du_an_id', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn btn-danger"><i
                                class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU & MOBILE CARD ══ --}}
    @php
        $vaiTroHienTai = auth('nhanvien')->check()
            ? \App\Models\NhanVien::normalizeVaiTro((string) (auth('nhanvien')->user()->vai_tro ?? ''))
            : null;
        $canDeleteBds = in_array($vaiTroHienTai, ['admin', 'nguon_hang'], true);
        $ttMap = [
            'con_hang' => ['label' => 'Còn hàng', 'color' => '#27ae60', 'bg' => '#e8f8f0', 'icon' => '✅'],
            'dat_coc' => ['label' => 'Đặt cọc', 'color' => '#e67e22', 'bg' => '#fff3e0', 'icon' => '🤝'],
            'da_ban' => ['label' => 'Đã bán', 'color' => '#e74c3c', 'bg' => '#ffeaea', 'icon' => '❌'],
            'dang_thue' => ['label' => 'Đang cho thuê', 'color' => '#2d6a9f', 'bg' => '#e8f4fd', 'icon' => '🔑'],
            'da_thue' => ['label' => 'Đã cho thuê', 'color' => '#8e44ad', 'bg' => '#f5eeff', 'icon' => '📦'],
            'tam_an' => ['label' => 'Tạm ẩn', 'color' => '#6c757d', 'bg' => '#f8f9fa', 'icon' => '⏸'],
        ];
        $loaiMap = [
            'can_ho' => 'Căn hộ',
            'nha_pho' => 'Nhà phố',
            'biet_thu' => 'Biệt thự',
            'dat_nen' => 'Đất nền',
            'shophouse' => 'Shophouse',
        ];
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fw-normal" style="font-size: 0.85rem">
                @if ($batDongSans->total() > 0)
                    Hiển thị <strong>{{ $batDongSans->firstItem() }}–{{ $batDongSans->lastItem() }}</strong> /
                    <strong>{{ number_format($batDongSans->total()) }}</strong> BĐS
                @else
                    Không có kết quả nào
                @endif
            </span>
        </div>

        {{-- Bảng Desktop - Đã tối ưu kích thước cột để không bị scroll ngang --}}
        <div class="table-responsive tbl-desktop" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0"
                style="table-layout: fixed; width: 100%; word-wrap: break-word;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 40px">#</th>
                        <th>Bất động sản</th>
                        <th style="width: 12%">Loại / Nhu cầu</th>
                        <th style="width: 14%">Giá</th>
                        <th style="width: 10%">Diện tích</th>
                        <th style="width: 13%">Trạng thái</th>
                        <th class="text-center" style="width: 70px">Hiển thị</th>
                        <th class="text-center" style="width: 90px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batDongSans as $i => $bds)
                        @php $tt = $ttMap[$bds->trang_thai] ?? ['label' => $bds->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => '']; @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $batDongSans->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-start gap-2">
                                    <div class="position-relative flex-shrink-0">
                                        @if ($bds->hinh_anh)
                                            <img src="{{ asset('storage/' . $bds->hinh_anh) }}" class="rounded border"
                                                style="width: 60px; height: 45px; object-fit: cover;" alt="">
                                        @else
                                            <div class="rounded border bg-light text-muted d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 45px;"><i class="fas fa-image"></i></div>
                                        @endif
                                        @if ($bds->noi_bat)
                                            <span class="badge bg-danger position-absolute top-0 start-0 translate-middle"
                                                style="font-size: 0.5rem; padding: 0.25em 0.4em;">HOT</span>
                                        @endif
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-normal">#{{ $bds->ma_bat_dong_san }}</span>
                                    </div>
                                    <div style="min-width: 0; flex: 1;">
                                        {{-- Tiêu đề tự động rớt 2 dòng nếu dài, không phá vỡ chiều rộng bảng --}}
                                        <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                            class="fw-bold text-navy text-decoration-none mb-1"
                                            style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.85rem;"
                                            title="{{ $bds->tieu_de }}">{{ $bds->tieu_de }}</a>
                                        <div class="d-flex flex-wrap gap-1 align-items-center" style="font-size: 0.7rem">

                                            @if ($bds->duAn)
                                                <span class="text-muted text-truncate" style="max-width: 150px;"
                                                    title="{{ $bds->duAn->ten_du_an }}"><i
                                                        class="fas fa-city me-1"></i>{{ $bds->duAn->ten_du_an }}</span>
                                            @endif
                                            @if ($bds->nhanVienPhuTrach)
                                                <span class="text-muted ms-1"><i
                                                        class="fas fa-user-tie me-1"></i>{{ $bds->nhanVienPhuTrach->ho_ten }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border mb-1 d-inline-block"
                                    style="white-space: normal; text-align: left;">{{ $loaiMap[$bds->loai_hinh] ?? $bds->loai_hinh }}</span><br>
                                <span
                                    class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-warning text-dark' : 'bg-info text-dark' }}">{{ $bds->nhu_cau == 'ban' ? '🏷 Bán' : '🔑 Thuê' }}</span>
                            </td>
                            <td>
                                @if ($bds->nhu_cau == 'ban' && $bds->gia)
                                    <div class="fw-bold text-danger">{{ number_format($bds->gia / 1e9, 2) }} tỷ</div>
                                @elseif($bds->nhu_cau == 'thue' && $bds->gia_thue)
                                    <div class="fw-bold text-primary">{{ number_format($bds->gia_thue / 1e6, 1) }} tr/th
                                    </div>
                                @else
                                    <span class="text-muted fst-italic" style="font-size: 0.8rem">Thỏa thuận</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ (float) $bds->dien_tich }} m²</div>
                                @if ($bds->so_phong_ngu !== null)
                                    <div class="text-muted" style="font-size: 0.8rem"><i
                                            class="fas fa-bed me-1"></i>{{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu . ' PN' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{-- Dropdown Đổi Trạng Thái Bootstrap --}}
                                <div class="dropdown">
                                    <span class="badge cursor-pointer dropdown-toggle" data-bs-toggle="dropdown"
                                        style="background: {{ $tt['bg'] }}; color: {{ $tt['color'] }}; font-size: 0.75rem; white-space: normal; text-align: left;">
                                        {{ $tt['label'] }}
                                    </span>
                                    <ul class="dropdown-menu shadow-sm" style="font-size: 0.85rem">
                                        @foreach ($ttMap as $val => $item)
                                            <li><a class="dropdown-item tt-update-btn" href="javascript:void(0)"
                                                    data-id="{{ $bds->id }}"
                                                    data-val="{{ $val }}">{{ $item['icon'] }}
                                                    {{ $item['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <label class="toggle-sw">
                                    <input type="checkbox"
                                        data-toggle-url="/nhan-vien/admin/bat-dong-san/{{ $bds->id }}/toggle"
                                        {{ $bds->hien_thi ? 'checked' : '' }}>
                                    <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                                </label>
                                <div class="text-muted mt-1" style="font-size: 0.7rem" title="Lượt xem"><i
                                        class="fas fa-eye"></i> {{ number_format($bds->luot_xem) }}</div>
                            </td>
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                        class="btn-action btn-action-edit" title="Sửa"><i class="fas fa-pen"></i></a>
                                    @if ($canDeleteBds)
                                        <form id="frmDel_{{ $bds->id }}"
                                            action="{{ route('nhanvien.admin.bat-dong-san.destroy', $bds) }}"
                                            method="POST" class="d-none">@csrf @method('DELETE')</form>
                                        <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                            data-id="{{ $bds->id }}" data-name="{{ $bds->ma_bat_dong_san }}"
                                            title="Xóa"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-building text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Không tìm thấy bất động sản nào</p>
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}"
                                        class="btn btn-sm btn-outline-primary">Thêm BĐS đầu tiên</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Danh sách Mobile Card --}}
        <div class="mobile-card-list">
            @foreach ($batDongSans as $bds)
                @php $tt = $ttMap[$bds->trang_thai] ?? ['label' => $bds->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5']; @endphp
                <div class="mobile-card">
                    <div class="mobile-card-top align-items-start">
                        @if ($bds->hinh_anh)
                            <img src="{{ asset('storage/' . $bds->hinh_anh) }}" class="rounded border me-2"
                                style="width: 70px; height: 50px; object-fit: cover;" alt="">
                        @else
                            <div class="rounded border bg-light text-muted d-flex align-items-center justify-content-center me-2"
                                style="width: 70px; height: 50px;"><i class="fas fa-image"></i></div>
                        @endif
                        <div style="flex: 1; min-width: 0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-normal"
                                    style="font-size: 0.7rem">#{{ $bds->ma_bat_dong_san }}</span>
                                <span class="badge"
                                    style="background: {{ $tt['bg'] }}; color: {{ $tt['color'] }}">{{ $tt['label'] }}</span>
                            </div>
                            <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                class="fw-bold text-navy text-decoration-none d-block text-truncate mb-1">{{ $bds->tieu_de }}</a>
                        </div>
                    </div>
                    <div class="mobile-card-meta">
                        <div>
                            @if ($bds->nhu_cau == 'ban')
                                <span class="fw-bold text-danger">{{ number_format($bds->gia / 1e9, 2) }} tỷ</span>
                            @else
                                <span class="fw-bold text-primary">{{ number_format($bds->gia_thue / 1e6, 1) }}
                                    tr/th</span>
                            @endif
                        </div>
                        <div><i class="fas fa-vector-square text-muted"></i> {{ (float) $bds->dien_tich }}m²</div>
                        @if ($bds->so_phong_ngu !== null)
                            <div><i class="fas fa-bed text-muted"></i>
                                {{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu . 'PN' }}</div>
                        @endif
                    </div>
                    <div class="mobile-card-foot">
                        <label class="toggle-sw">
                            <input type="checkbox"
                                data-toggle-url="/nhan-vien/admin/bat-dong-san/{{ $bds->id }}/toggle"
                                {{ $bds->hien_thi ? 'checked' : '' }}>
                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                        </label>
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                            @if ($canDeleteBds)
                                <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                    data-id="{{ $bds->id }}" data-name="{{ $bds->ma_bat_dong_san }}"><i
                                        class="fas fa-trash"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Phân trang Bootstrap --}}
        @if ($batDongSans->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $batDongSans->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;

            // Xóa BĐS bằng admin.js confirm
            document.querySelectorAll('.btn-delete-bds').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name;
                    const id = this.dataset.id;
                    confirmDelete('bất động sản #' + name, function() {
                        document.getElementById('frmDel_' + id).submit();
                    });
                });
            });

            // AJAX Update Trạng Thái từ Dropdown
            document.querySelectorAll('.tt-update-btn').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const val = this.dataset.val;

                    fetch(`/nhan-vien/admin/bat-dong-san/${id}/trang-thai`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                trang_thai: val
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) window.location.reload();
                            else showAdminToast('Lỗi cập nhật', 'error');
                        }).catch(() => showAdminToast('Lỗi kết nối', 'error'));
                });
            });
        });
    </script>
@endpush
