@extends('admin.layouts.master')
@section('title', 'Đơn ứng tuyển')

@section('content')

    <div class="d-flex align-items-start justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.3rem;color:var(--navy)">
                <i class="fas fa-inbox me-2" style="color:var(--primary)"></i>Đơn ứng tuyển
            </h1>
            <div style="font-size:.78rem;color:var(--text-sub)">
                <span>{{ $dons->total() }} đơn ứng tuyển</span>
            </div>
        </div>
        <a href="{{ route('nhanvien.admin.tuyen-dung.index') }}"
            class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i> Quản lý tin tuyển dụng
        </a>
    </div>

    {{-- ── FILTER ── --}}
    <div class="filter-box mb-3">
        <form method="GET" action="{{ route('nhanvien.admin.tuyen-dung.don-ung-tuyen') }}">
            <div class="filter-box-row">
                <input type="text" name="search" class="filter-ctrl filter-ctrl-search"
                    value="{{ request('search') }}" placeholder="Tìm tên, email, SĐT..." style="min-width:200px;flex:1">

                <select name="trang_thai" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả trạng thái</option>
                    @foreach (\App\Models\TinTuyenDung::TRANG_THAI_DON as $key => $info)
                        <option value="{{ $key }}" {{ request('trang_thai') == $key ? 'selected' : '' }}>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="tin_id" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả vị trí</option>
                    @foreach ($tinTuyenDungs as $tin)
                        <option value="{{ $tin->id }}" {{ request('tin_id') == $tin->id ? 'selected' : '' }}>
                            {{ Str::limit($tin->tieu_de, 40) }}
                        </option>
                    @endforeach
                </select>

                @if (request()->hasAny(['search', 'trang_thai', 'tin_id']))
                    <a href="{{ route('nhanvien.admin.tuyen-dung.don-ung-tuyen') }}"
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
            <span class="d-flex align-items-center gap-2"><i class="fas fa-list"></i> Danh sách đơn</span>
        </div>

        <div class="table-wrap tbl-desktop">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Ứng viên</th>
                        <th>Vị trí</th>
                        <th style="width:110px">Trạng thái</th>
                        <th style="width:100px">CV</th>
                        <th style="width:110px">Ngày nộp</th>
                        <th style="width:100px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dons as $don)
                        @php $info = $don->trang_thai_info; @endphp
                        <tr>
                            <td>
                                <div class="fw-bold" style="font-size:.84rem;color:var(--navy)">{{ $don->ho_ten }}
                                </div>
                                <div style="font-size:.72rem;color:var(--text-muted)">
                                    <i class="fas fa-phone me-1"></i>{{ $don->so_dien_thoai }}
                                    &nbsp;·&nbsp;
                                    <i class="fas fa-envelope me-1"></i>{{ $don->email }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size:.8rem;color:var(--text-sub)">
                                    {{ Str::limit($don->tinTuyenDung->tieu_de ?? 'N/A', 35) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status"
                                    style="background:{{ $info['bg'] }};color:{{ $info['color'] }}">
                                    <i class="{{ $info['icon'] }}" style="font-size:.6rem"></i>
                                    {{ $info['label'] }}
                                </span>
                            </td>
                            <td>
                                @if ($don->link_cv)
                                    <a href="{{ $don->link_cv }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary rounded-pill"
                                        style="font-size:.72rem;padding:.2rem .6rem">
                                        <i class="fas fa-link me-1"></i>Link
                                    </a>
                                @endif
                                @if ($don->file_cv)
                                    <a href="{{ $don->file_cv_url }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill"
                                        style="font-size:.72rem;padding:.2rem .6rem">
                                        <i class="fas fa-file-pdf me-1"></i>File
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:.79rem;font-weight:700;color:var(--navy)">
                                    {{ $don->created_at->format('d/m/Y') }}
                                </div>
                                <div style="font-size:.7rem;color:var(--text-muted)">
                                    {{ $don->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                <div class="btn-actions-group justify-content-center">
                                    <button type="button" class="btn-action btn-action-view" data-tip="Xem chi tiết"
                                        data-bs-toggle="modal" data-bs-target="#donModal{{ $don->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form id="del-don-{{ $don->id }}" method="POST"
                                        action="{{ route('nhanvien.admin.tuyen-dung.don.xoa', $don) }}"
                                        style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn-action btn-action-delete" data-tip="Xóa"
                                        data-confirm-delete="{{ addslashes($don->ho_ten) }}"
                                        data-form-id="del-don-{{ $don->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="table-empty">
                                    <div class="table-empty-icon"><i class="fas fa-inbox"></i></div>
                                    <p>Chưa có đơn ứng tuyển nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dons->hasPages())
            <div class="pagination-wrap">
                <nav>{{ $dons->links() }}</nav>
            </div>
        @endif
    </div>

    {{-- ── MODALS CHI TIẾT ĐƠN ── --}}
    @foreach ($dons as $don)
        @php $info = $don->trang_thai_info; @endphp
        <div class="modal fade" id="donModal{{ $don->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden">
                    <div class="modal-header" style="background:var(--navy);border:none;padding:1.2rem 1.5rem">
                        <h5 class="modal-title text-white fw-bold">
                            <i class="fas fa-user me-2" style="color:var(--primary)"></i>{{ $don->ho_ten }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:.2rem">
                                    Email</div>
                                <div style="font-size:.88rem;font-weight:600">{{ $don->email }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:.2rem">
                                    Số điện thoại</div>
                                <div style="font-size:.88rem;font-weight:600">{{ $don->so_dien_thoai }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:.2rem">
                                    Năm sinh</div>
                                <div style="font-size:.88rem;font-weight:600">{{ $don->nam_sinh ?? '—' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:.2rem">
                                    Vị trí ứng tuyển</div>
                                <div style="font-size:.88rem;font-weight:600">
                                    {{ $don->tinTuyenDung->tieu_de ?? 'N/A' }}</div>
                            </div>
                        </div>

                        @if ($don->gioi_thieu)
                            <div class="mb-4 p-3 rounded-3" style="background:var(--bg)">
                                <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;margin-bottom:.4rem">
                                    Giới thiệu bản thân</div>
                                <p class="mb-0" style="font-size:.88rem;line-height:1.7">{{ $don->gioi_thieu }}
                                </p>
                            </div>
                        @endif

                        <div class="d-flex gap-2 mb-4">
                            @if ($don->link_cv)
                                <a href="{{ $don->link_cv }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="fas fa-link me-1"></i> Xem CV (Link)
                                </a>
                            @endif
                            @if ($don->file_cv)
                                <a href="{{ $don->file_cv_url }}" target="_blank"
                                    class="btn btn-outline-success btn-sm rounded-pill">
                                    <i class="fas fa-file-download me-1"></i> Tải CV (File)
                                </a>
                            @endif
                        </div>

                        <hr>

                        {{-- Form cập nhật trạng thái --}}
                        <form method="POST"
                            action="{{ route('nhanvien.admin.tuyen-dung.don.trang-thai', $don) }}">
                            @csrf @method('PATCH')
                            <div class="row g-3">
                                <div class="col-sm-5">
                                    <label class="form-label fw-bold" style="font-size:.82rem">Cập nhật trạng
                                        thái</label>
                                    <select name="trang_thai" class="form-select form-select-sm">
                                        @foreach (\App\Models\TinTuyenDung::TRANG_THAI_DON as $key => $stInfo)
                                            <option value="{{ $key }}"
                                                {{ $don->trang_thai == $key ? 'selected' : '' }}>
                                                {{ $stInfo['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-7">
                                    <label class="form-label fw-bold" style="font-size:.82rem">Ghi chú nội bộ</label>
                                    <textarea name="ghi_chu_admin" class="form-control form-control-sm" rows="2"
                                        placeholder="Ghi chú cho nội bộ...">{{ $don->ghi_chu_admin }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
