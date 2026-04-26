@extends('admin.layouts.master')
@section('title', 'Quản lý Tuyển dụng')

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.3rem;color:var(--navy)">
                <i class="fas fa-user-plus me-2" style="color:var(--primary)"></i>Quản lý Tuyển dụng
            </h1>
            <div style="font-size:.78rem;color:var(--text-sub)">
                <span>{{ $tinTuyenDungs->total() }} vị trí</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('nhanvien.admin.tuyen-dung.don-ung-tuyen') }}"
                class="btn btn-navy d-flex align-items-center gap-2 flex-shrink-0">
                <i class="fas fa-inbox"></i>
                <span class="d-none d-sm-inline">Đơn ứng tuyển</span>
                @php $totalDonMoi = \App\Models\DonUngTuyen::where('trang_thai', 'moi')->count(); @endphp
                @if ($totalDonMoi > 0)
                    <span class="badge bg-danger rounded-pill">{{ $totalDonMoi }}</span>
                @endif
            </a>
            <a href="{{ route('nhanvien.admin.tuyen-dung.create') }}"
                class="btn btn-primary d-flex align-items-center gap-2 flex-shrink-0">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline">Thêm vị trí mới</span>
            </a>
        </div>
    </div>

    {{-- ── FILTER ── --}}
    <div class="filter-box mb-3">
        <form method="GET" action="{{ route('nhanvien.admin.tuyen-dung.index') }}">
            <div class="filter-box-row">
                <input type="text" name="search" class="filter-ctrl filter-ctrl-search"
                    value="{{ request('search') }}" placeholder="Tìm tiêu đề..." style="min-width:200px;flex:1">

                <select name="hinh_thuc" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả hình thức</option>
                    @foreach (\App\Models\TinTuyenDung::HINH_THUC as $key => $label)
                        <option value="{{ $key }}" {{ request('hinh_thuc') == $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>

                @if (request()->hasAny(['search', 'hinh_thuc']))
                    <a href="{{ route('nhanvien.admin.tuyen-dung.index') }}"
                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── TABLE ── --}}
    <div class="card">
        <div class="card-header">
            <span class="d-flex align-items-center gap-2">
                <i class="fas fa-list"></i> Danh sách vị trí tuyển dụng
            </span>
            @if ($tinTuyenDungs->total())
                <span class="result-info">
                    Hiển thị <strong>{{ $tinTuyenDungs->firstItem() }}–{{ $tinTuyenDungs->lastItem() }}</strong>
                    / <strong>{{ $tinTuyenDungs->total() }}</strong>
                </span>
            @endif
        </div>

        <div class="table-wrap tbl-desktop">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Vị trí</th>
                        <th style="width:120px">Hình thức</th>
                        <th style="width:100px">Mức lương</th>
                        <th style="width:80px;text-align:center">Số lượng</th>
                        <th style="width:80px;text-align:center">Đơn mới</th>
                        <th style="width:90px">Trạng thái</th>
                        <th style="width:100px">Hạn nộp</th>
                        <th style="width:100px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tinTuyenDungs as $tin)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('nhanvien.admin.tuyen-dung.edit', $tin) }}"
                                        class="fw-bold text-decoration-none"
                                        style="font-size:.84rem;color:var(--navy);transition:color .2s"
                                        onmouseover="this.style.color='var(--primary)'"
                                        onmouseout="this.style.color='var(--navy)'">
                                        {{ Str::limit($tin->tieu_de, 50) }}
                                    </a>
                                    @if ($tin->tag)
                                        <span class="badge {{ $tin->tag_class }} mt-1 align-self-start"
                                            style="font-size:.65rem;padding:.2rem .5rem;border-radius:20px">
                                            {{ $tin->tag }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span
                                    style="font-size:.78rem;color:var(--text-sub)">{{ $tin->hinh_thuc_label }}</span>
                            </td>
                            <td>
                                <span style="font-size:.8rem;font-weight:700;color:var(--primary)">
                                    {{ $tin->muc_luong ?? 'Thỏa thuận' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="count-chip">{{ $tin->so_luong }}</span>
                            </td>
                            <td class="text-center">
                                @php $donMoi = $tin->don_ung_tuyens_count ?? 0; @endphp
                                <a href="{{ route('nhanvien.admin.tuyen-dung.don-ung-tuyen', ['tin_id' => $tin->id]) }}"
                                    class="count-chip {{ $donMoi > 0 ? 'count-chip-orange' : '' }}"
                                    style="text-decoration:none">
                                    <i class="fas fa-inbox me-1" style="font-size:.6rem"></i>{{ $donMoi }}
                                </a>
                            </td>
                            <td>
                                <button type="button"
                                    class="badge-status {{ $tin->hien_thi ? 'badge-active' : 'badge-inactive' }}"
                                    onclick="toggleTinTD(this, '{{ route('nhanvien.admin.tuyen-dung.toggle', $tin) }}')"
                                    style="border:none;cursor:pointer">
                                    <i class="fas {{ $tin->hien_thi ? 'fa-check-circle' : 'fa-eye-slash' }}"
                                        style="font-size:.6rem"></i>
                                    {{ $tin->hien_thi ? 'Hiển thị' : 'Đã ẩn' }}
                                </button>
                            </td>
                            <td>
                                @if ($tin->han_nop)
                                    <div style="font-size:.79rem;font-weight:700;color:{{ $tin->con_han ? 'var(--navy)' : 'var(--red)' }}">
                                        {{ $tin->han_nop->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size:.7rem;color:var(--text-muted)">
                                        {{ $tin->con_han ? $tin->han_nop->diffForHumans() : 'Đã hết hạn' }}
                                    </div>
                                @else
                                    <span style="font-size:.76rem;color:var(--text-muted);font-style:italic">Không
                                        giới hạn</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('frontend.tuyen-dung.show', $tin->slug) }}" target="_blank"
                                        class="btn-action btn-action-view" data-tip="Xem trực tiếp">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.tuyen-dung.edit', $tin) }}"
                                        class="btn-action btn-action-edit" data-tip="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="del-td-{{ $tin->id }}" method="POST"
                                        action="{{ route('nhanvien.admin.tuyen-dung.destroy', $tin) }}"
                                        style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn-action btn-action-delete" data-tip="Xóa"
                                        data-confirm-delete="{{ addslashes($tin->tieu_de) }}"
                                        data-form-id="del-td-{{ $tin->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="table-empty">
                                    <div class="table-empty-icon"><i class="fas fa-user-plus"></i></div>
                                    <p>Chưa có vị trí tuyển dụng nào</p>
                                    <a href="{{ route('nhanvien.admin.tuyen-dung.create') }}"
                                        class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>Thêm vị trí đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tinTuyenDungs->hasPages())
            <div class="pagination-wrap">
                <nav>{{ $tinTuyenDungs->links() }}</nav>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function toggleTinTD(el, url) {
            el.disabled = true;
            fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json'
                    }
                })
                .then(() => location.reload())
                .catch(() => showAdminToast('Lỗi kết nối!', 'error'))
                .finally(() => el.disabled = false);
        }
    </script>
@endpush
