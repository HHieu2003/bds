@extends('admin.layouts.master')
@section('title', 'Chi tiết lịch hẹn #' . $lichHen->id)
@section('page_title', 'Chi tiết Lịch hẹn')

@push('styles')
<style>
.timeline { position:relative;padding-left:2rem; }
.timeline::before { content:'';position:absolute;left:.6rem;top:0;bottom:0;width:2px;background:#e5e7eb; }
.tl-step { position:relative;margin-bottom:1.5rem; }
.tl-dot  { position:absolute;left:-1.9rem;top:.1rem;width:1.1rem;height:1.1rem;border-radius:50%;border:2px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;font-size:.55rem; }
.tl-dot.done { background:#10b981;border-color:#10b981;color:#fff; }
.tl-dot.pend { background:#3b82f6;border-color:#3b82f6;color:#fff; }
.tl-dot.fail { background:#ef4444;border-color:#ef4444;color:#fff; }
.tl-dot.wait { background:#f59e0b;border-color:#f59e0b;color:#fff; }
.tl-label { font-size:.8rem;font-weight:700;color:var(--navy); }
.tl-sub   { font-size:.72rem;color:var(--text-sub); }
.info-row { display:flex;gap:1rem;margin-bottom:.65rem;font-size:.85rem; }
.info-lbl { min-width:140px;color:var(--text-sub);font-weight:600;flex-shrink:0; }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
<div class="row justify-content-center">
<div class="col-lg-9">

    {{-- Header card --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="fw-800 mb-1">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        Lịch hẹn #{{ $lichHen->id }}
                    </h5>
                    <span class="badge bg-secondary-subtle text-secondary">
                        {{ $lichHen->thoi_gian_hen->format('H:i — d/m/Y') }}
                    </span>
                </div>
                @php
                $badgeMap = [
                    'moi_dat'       => ['warning', 'Mới đặt'],
                    'cho_xac_nhan'  => ['primary', 'Chờ xác nhận'],
                    'da_xac_nhan'   => ['success', 'Đã xác nhận'],
                    'hoan_thanh'    => ['success', 'Hoàn thành'],
                    'tu_choi'       => ['danger',  'Từ chối'],
                    'huy'           => ['secondary','Đã hủy'],
                ];
                [$badgeCls, $badgeLbl] = $badgeMap[$lichHen->trang_thai] ?? ['secondary','?'];
                @endphp
                <span class="badge bg-{{ $badgeCls }} fs-6">{{ $badgeLbl }}</span>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Cột trái: Thông tin chi tiết --}}
        <div class="col-md-7">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="fas fa-user me-2"></i><strong>Thông tin khách hàng</strong></div>
                <div class="card-body">
                    <div class="info-row"><span class="info-lbl">Họ tên</span><span>{{ $lichHen->ten_khach_hang }}</span></div>
                    <div class="info-row"><span class="info-lbl">SĐT</span><span><a href="tel:{{ $lichHen->sdt_khach_hang }}">{{ $lichHen->sdt_khach_hang }}</a></span></div>
                    @if($lichHen->email_khach_hang)
                    <div class="info-row"><span class="info-lbl">Email</span><span><a href="mailto:{{ $lichHen->email_khach_hang }}">{{ $lichHen->email_khach_hang }}</a></span></div>
                    @endif
                    @if($lichHen->khachHang)
                    <div class="info-row"><span class="info-lbl">Tài khoản</span><a href="{{ route('nhanvien.admin.khach-hang.show', $lichHen->khachHang) }}">{{ $lichHen->khachHang->ho_ten }}</a></div>
                    @endif
                    <div class="info-row">
                        <span class="info-lbl">Nguồn đặt</span>
                        @php $srcMap=['website'=>'Website','sale'=>'Sale','phone'=>'Điện thoại','chat'=>'Chat']; @endphp
                        <span class="badge bg-info-subtle text-info">{{ $srcMap[$lichHen->nguon_dat_lich] ?? $lichHen->nguon_dat_lich }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="fas fa-building me-2"></i><strong>Bất động sản</strong></div>
                <div class="card-body">
                    @if($lichHen->batDongSan)
                    <div class="info-row"><span class="info-lbl">Tên BDS</span><span class="fw-600">{{ $lichHen->batDongSan->ten_bat_dong_san }}</span></div>
                    <div class="info-row"><span class="info-lbl">Mã căn</span><span>{{ $lichHen->batDongSan->ma_can }}</span></div>
                    @if($lichHen->batDongSan->khuVuc)
                    <div class="info-row"><span class="info-lbl">Khu vực</span><span>{{ $lichHen->batDongSan->khuVuc->ten_khu_vuc }}</span></div>
                    @endif
                    @endif
                    <div class="info-row"><span class="info-lbl">Thời gian hẹn</span><span class="fw-700 text-primary">{{ $lichHen->thoi_gian_hen->format('H:i d/m/Y') }}</span></div>
                    @if($lichHen->dia_diem_hen)
                    <div class="info-row"><span class="info-lbl">Địa điểm</span><span>{{ $lichHen->dia_diem_hen }}</span></div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header"><i class="fas fa-users-cog me-2"></i><strong>Nhân viên phụ trách</strong></div>
                <div class="card-body">
                    <div class="info-row"><span class="info-lbl">Sale</span><span>{{ optional($lichHen->nhanVienSale)->ho_ten ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-lbl">Nguồn hàng</span><span>{{ optional($lichHen->nhanVienNguonHang)->ho_ten ?? '—' }}</span></div>
                    @if($lichHen->ghi_chu_sale)
                    <div class="info-row"><span class="info-lbl">Ghi chú Sale</span><span>{{ $lichHen->ghi_chu_sale }}</span></div>
                    @endif
                    @if($lichHen->ghi_chu_nguon_hang)
                    <div class="info-row"><span class="info-lbl">Ghi chú NHàng</span><span>{{ $lichHen->ghi_chu_nguon_hang }}</span></div>
                    @endif
                    @if($lichHen->ly_do_tu_choi)
                    <div class="info-row"><span class="info-lbl">Lý do từ chối</span><span class="text-danger">{{ $lichHen->ly_do_tu_choi }}</span></div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Cột phải: Timeline + Actions --}}
        <div class="col-md-5">

            {{-- Timeline trạng thái --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="fas fa-stream me-2"></i><strong>Timeline</strong></div>
                <div class="card-body">
                    <div class="timeline">

                        <div class="tl-step">
                            <div class="tl-dot done"><i class="fas fa-plus"></i></div>
                            <div class="tl-label">Khách đặt lịch</div>
                            <div class="tl-sub">{{ $lichHen->created_at->format('H:i d/m/Y') }}</div>
                        </div>

                        @if(in_array($lichHen->trang_thai, ['cho_xac_nhan','da_xac_nhan','hoan_thanh']))
                        <div class="tl-step">
                            <div class="tl-dot done"><i class="fas fa-check"></i></div>
                            <div class="tl-label">Sale tiếp nhận</div>
                            <div class="tl-sub">Đã assign Nguồn hàng</div>
                        </div>
                        @elseif($lichHen->trang_thai === 'moi_dat')
                        <div class="tl-step">
                            <div class="tl-dot wait"><i class="fas fa-clock"></i></div>
                            <div class="tl-label">Chờ Sale tiếp nhận</div>
                            <div class="tl-sub">Chưa assign Nguồn hàng</div>
                        </div>
                        @endif

                        @if(in_array($lichHen->trang_thai, ['da_xac_nhan','hoan_thanh']))
                        <div class="tl-step">
                            <div class="tl-dot done"><i class="fas fa-check"></i></div>
                            <div class="tl-label">Nguồn hàng xác nhận</div>
                            <div class="tl-sub">{{ optional($lichHen->xac_nhan_at)->format('H:i d/m/Y') }}</div>
                        </div>
                        @elseif($lichHen->trang_thai === 'cho_xac_nhan')
                        <div class="tl-step">
                            <div class="tl-dot pend"><i class="fas fa-clock"></i></div>
                            <div class="tl-label">Chờ Nguồn hàng xác nhận</div>
                            <div class="tl-sub">Đang chờ phản hồi</div>
                        </div>
                        @elseif($lichHen->trang_thai === 'tu_choi')
                        <div class="tl-step">
                            <div class="tl-dot fail"><i class="fas fa-times"></i></div>
                            <div class="tl-label">Nguồn hàng từ chối</div>
                            <div class="tl-sub">{{ optional($lichHen->tu_choi_at)->format('H:i d/m/Y') }}</div>
                        </div>
                        @elseif($lichHen->trang_thai === 'huy')
                        <div class="tl-step">
                            <div class="tl-dot fail"><i class="fas fa-ban"></i></div>
                            <div class="tl-label">Lịch hẹn bị hủy</div>
                            <div class="tl-sub">{{ optional($lichHen->huy_at)->format('H:i d/m/Y') }}</div>
                        </div>
                        @endif

                        @if($lichHen->trang_thai === 'hoan_thanh')
                        <div class="tl-step">
                            <div class="tl-dot done"><i class="fas fa-flag"></i></div>
                            <div class="tl-label">Hoàn thành</div>
                            <div class="tl-sub">{{ optional($lichHen->hoan_thanh_at)->format('H:i d/m/Y') }}</div>
                        </div>
                        @elseif($lichHen->trang_thai === 'da_xac_nhan')
                        <div class="tl-step">
                            <div class="tl-dot wait"><i class="fas fa-hourglass"></i></div>
                            <div class="tl-label">Chờ diễn ra</div>
                            <div class="tl-sub">{{ $lichHen->thoi_gian_hen->format('H:i d/m/Y') }}</div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Actions theo role & trạng thái --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header"><i class="fas fa-bolt me-2"></i><strong>Thao tác</strong></div>
                <div class="card-body d-flex flex-column gap-2">

                    @if($lichHen->trang_thai === 'cho_xac_nhan' && $nhanVien->hasRole(['admin','nguon_hang']))
                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lichHen) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-success w-100"
                            onclick="return confirm('Xác nhận lịch hẹn này?')">
                            <i class="fas fa-check me-2"></i>Xác nhận lịch hẹn
                        </button>
                    </form>
                    <button class="btn btn-outline-danger w-100"
                        onclick="openTuChoiModal()">
                        <i class="fas fa-times me-2"></i>Từ chối
                    </button>
                    @endif

                    @if($lichHen->trang_thai === 'da_xac_nhan' && $nhanVien->hasRole(['admin','sale']))
                    <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lichHen) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-success w-100"
                            onclick="return confirm('Đánh dấu hoàn thành?')">
                            <i class="fas fa-flag-checkered me-2"></i>Đánh dấu Hoàn thành
                        </button>
                    </form>
                    @endif

                    @if(!in_array($lichHen->trang_thai,['hoan_thanh','huy']) && $nhanVien->hasRole(['admin','sale']))
                    <button class="btn btn-outline-secondary w-100" onclick="openHuyModal()">
                        <i class="fas fa-ban me-2"></i>Hủy lịch hẹn
                    </button>
                    @endif

                    <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                    </a>

                </div>
            </div>

        </div>
    </div>

</div>
</div>
</div>

{{-- Modal Từ chối (show page) --}}
<div class="modal fade" id="modalTuChoiShow" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('nhanvien.admin.lich-hen.tu-choi', $lichHen) }}" method="POST">
      @csrf @method('PATCH')
      <div class="modal-content">
        <div class="modal-header bg-danger-subtle">
          <h5 class="modal-title"><i class="fas fa-times-circle me-2 text-danger"></i>Từ chối lịch hẹn</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-0">
            <label class="form-label fw-600">Lý do từ chối <span class="text-danger">*</span></label>
            <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                      placeholder="Không có khả năng, lịch bị trùng..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger fw-700">Xác nhận từ chối</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Hủy (show page) --}}
<div class="modal fade" id="modalHuyShow" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <form action="{{ route('nhanvien.admin.lich-hen.huy', $lichHen) }}" method="POST">
      @csrf @method('PATCH')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hủy lịch hẹn</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <textarea name="ly_do" class="form-control" rows="2" placeholder="Lý do hủy..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Không</button>
          <button type="submit" class="btn btn-danger btn-sm">Hủy lịch</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function openTuChoiModal() { new bootstrap.Modal(document.getElementById('modalTuChoiShow')).show(); }
function openHuyModal()    { new bootstrap.Modal(document.getElementById('modalHuyShow')).show(); }
</script>
@endpush
