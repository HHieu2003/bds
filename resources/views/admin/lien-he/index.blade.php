@extends('admin.layouts.master')
@section('title', 'Quản lý Yêu cầu Liên hệ (Leads)')

@push('styles')
<style>
    /* Chỉnh Dropdown Trạng thái trông giống Badge */
    .status-select {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 50px;
        border: 2px solid transparent;
        cursor: pointer;
        outline: none;
        transition: all 0.2s ease;
        appearance: none;
        text-align: center;
        width: 130px;
    }
    .status-select:focus { filter: brightness(0.9); }
    .status-wrapper { position: relative; display: inline-block; }
    .status-wrapper::after {
        content: '\f0d7';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.7rem;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-header-title"><i class="fas fa-headset text-primary me-2"></i> Pipeline: Yêu cầu Tư vấn</h1>
        <p class="text-muted mb-0">Hứng Data từ Web. Cập nhật trạng thái trực tiếp trên bảng để dễ dàng theo dõi.</p>
    </div>
</div>

@include('frontend.partials.flash-messages')

{{-- THỐNG KÊ NHANH --}}
<div class="row g-3 mb-4">
    @php
        $ttItems = array_merge(
            ['tat_ca' => ['label' => 'Tất cả Data', 'color' => '#1a3c5e', 'bg' => '#f8fafc', 'border' => 'border-secondary']],
            \App\Models\YeuCauLienHe::TRANG_THAI,
        );
        // Map lại màu border cho Bootstrap
        $borderMap = ['tat_ca' => 'border-dark', 'moi' => 'border-danger', 'dang_xu_ly' => 'border-warning', 'hoan_thanh' => 'border-success', 'that_bai' => 'border-secondary'];
    @endphp

    @foreach ($ttItems as $key => $info)
        <div class="col-6 col-md-3 col-lg-2">
            <a href="{{ request()->fullUrlWithQuery(['trang_thai' => $key === 'tat_ca' ? null : $key, 'page' => null]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm transition-hover {{ request('trang_thai') == $key || ($key === 'tat_ca' && !request('trang_thai')) ? 'border-bottom border-4 ' . ($borderMap[$key] ?? 'border-primary') : '' }}" style="background: {{ $info['bg'] }};">
                    <div class="card-body text-center py-3">
                        <h3 class="fw-bold mb-0" style="color: {{ $info['color'] }}">{{ $thongKe[$key] ?? 0 }}</h3>
                        <div class="small fw-bold text-uppercase text-muted mt-1" style="font-size: 0.7rem;">{{ $info['label'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- BỘ LỌC --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="🔍 Tên, SĐT, Email...">
            </div>
            <div class="col-6 col-md-2">
                <select name="trang_thai" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                        <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $info['label'] }}</option>
                    @endforeach
                </select>
            </div>
            @if(!$nhanVienAuth->isSale())
            <div class="col-6 col-md-3">
                <select name="nhan_vien" class="form-select">
                    <option value="">-- Tất cả Sale --</option>
                    @foreach ($nhanViens as $nv)
                        <option value="{{ $nv->id }}" @selected(request('nhan_vien') == $nv->id)>{{ $nv->ho_ten }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Lọc</button>
                <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="btn btn-light border"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- BẢNG DỮ LIỆU --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-uppercase text-muted small" style="width: 25%">Khách hàng</th>
                    <th class="text-uppercase text-muted small" style="width: 25%">Ngữ cảnh / BĐS</th>
                    <th class="text-uppercase text-muted small text-center" style="width: 15%">Trạng thái</th>
                    <th class="text-uppercase text-muted small" style="width: 18%">Phụ trách</th>
                    <th class="text-uppercase text-muted small text-end" style="width: 17%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lienHes as $lh)
                    @php $ttInfo = $lh->trang_thai_info ?? \App\Models\YeuCauLienHe::TRANG_THAI['moi']; @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">{{ $lh->ma_yeu_cau }}</span>
                                <strong class="text-dark">{{ $lh->ho_ten }}</strong>
                            </div>
                            <div><a href="tel:{{ $lh->so_dien_thoai }}" class="text-success fw-bold text-decoration-none small"><i class="fas fa-phone-alt me-1"></i>{{ $lh->so_dien_thoai }}</a></div>
                            <div class="small text-muted mt-1"><i class="far fa-clock me-1"></i>{{ $lh->thoi_diem_lien_he?->format('H:i d/m/Y') ?? $lh->created_at->format('H:i d/m/Y') }}</div>
                        </td>

                        <td>
                            @if ($lh->batDongSan)
                                <span class="badge bg-success-subtle text-success mb-1"><i class="fas fa-home"></i> Xem BĐS</span>
                                <a href="#" target="_blank" class="fw-bold text-primary text-decoration-none d-block small lh-sm" title="{{ $lh->batDongSan->ten_bat_dong_san }}">
                                    {{ \Illuminate\Support\Str::limit($lh->batDongSan->ten_bat_dong_san, 45) }}
                                </a>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary mb-1"><i class="fas fa-globe"></i> Liên hệ chung</span>
                                <div class="small text-muted fst-italic text-truncate" style="max-width: 200px;" title="{{ $lh->noi_dung }}">"{{ $lh->noi_dung }}"</div>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="status-wrapper">
                                <select class="status-select"
                                        style="background-color: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }};"
                                        onchange="capNhatNhanh({{ $lh->id }}, this)">
                                    @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                                        <option value="{{ $v }}" @selected($lh->trang_thai == $v)
                                                data-bg="{{ $info['bg'] }}" data-color="{{ $info['color'] }}">
                                            {{ $info['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td>
                            <div id="assignee-{{ $lh->id }}" class="fw-bold text-muted small">
                                @if ($lh->nhanVienPhuTrach)
                                    <i class="fas fa-user-circle text-primary me-1"></i>{{ $lh->nhanVienPhuTrach->ho_ten }}
                                @else
                                    <span class="text-warning"><i class="fas fa-user-times me-1"></i>Chưa nhận</span>
                                @endif
                            </div>
                        </td>

                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                                    Xử lý <i class="fas fa-angle-down ms-1"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><a class="dropdown-item fw-bold text-success" href="https://zalo.me/{{ $lh->so_dien_thoai }}" target="_blank"><i class="fas fa-comment-dots text-success me-2"></i> Chat Zalo ngay</a></li>
                                    <li><a class="dropdown-item text-primary" href="{{ route('nhanvien.admin.lien-he.show', $lh) }}"><i class="fas fa-info-circle text-primary me-2"></i> Xem chi tiết</a></li>
                                    <li><hr class="dropdown-divider"></li>

                                    @if($lh->trang_thai != 'hoan_thanh')
                                    <li>
                                        <form action="{{ route('nhanvien.admin.lien-he.chuyen-khach', $lh->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item fw-bold text-warning" onclick="return confirm('Chốt Lead này và tạo thành Data Khách Hàng CRM?')">
                                                <i class="fas fa-user-plus text-warning me-2"></i> Tạo Khách Hàng CRM
                                            </button>
                                        </form>
                                    </li>
                                    @endif

                                    <li>
                                        <form action="{{ route('nhanvien.admin.lien-he.destroy', $lh->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Xóa vĩnh viễn yêu cầu này?')"><i class="fas fa-trash me-2"></i> Xóa Lead</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-inbox fs-1 mb-3 opacity-50 d-block"></i> Chưa có yêu cầu liên hệ nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($lienHes->hasPages())
        <div class="card-footer bg-white p-3 d-flex justify-content-end">{{ $lienHes->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function capNhatNhanh(id, selectElement) {
        const newStatus = selectElement.value;
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        const bg = selectedOption.dataset.bg;
        const color = selectedOption.dataset.color;
        const oldVal = selectElement.dataset.old || newStatus;

        selectElement.disabled = true;

        fetch(`/nhan-vien/admin/lien-he/${id}/cap-nhat-nhanh`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ trang_thai: newStatus })
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                // Đổi màu UI lập tức
                selectElement.style.backgroundColor = data.info.bg;
                selectElement.style.color = data.info.color;
                selectElement.dataset.old = newStatus;

                // Nếu API trả về người nhận (Sale tự động nhận Lead)
                if(data.nguoi_nhan && data.nguoi_nhan !== 'Chưa có') {
                    const assigneeDiv = document.getElementById(`assignee-${id}`);
                    if(assigneeDiv && assigneeDiv.innerText.includes('Chưa nhận')) {
                        assigneeDiv.innerHTML = `<i class="fas fa-user-circle text-primary me-1"></i>${data.nguoi_nhan}`;
                    }
                }
            } else {
                selectElement.value = oldVal;
                alert('Lỗi cập nhật. Vui lòng F5 trang.');
            }
        })
        .catch(() => {
            selectElement.value = oldVal;
            alert('Lỗi kết nối mạng.');
        })
        .finally(() => {
            selectElement.disabled = false;
        });
    }
</script>
@endpush
