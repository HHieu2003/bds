@extends('admin.layouts.master')
@section('title', 'Chi tiết Yêu cầu tư vấn')

@section('content')
@php $ttInfo = $lienHe->trang_thai_info ?? ['label' => $lienHe->trang_thai, 'color' => '#000', 'bg' => '#eee']; @endphp

<div class="mb-4">
    <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="btn btn-sm btn-light border mb-3"><i class="fas fa-arrow-left"></i> Quay lại Danh sách</a>
    <h1 class="page-header-title d-flex align-items-center gap-3">
        <span><i class="fas fa-user-headset text-primary me-2"></i> {{ $lienHe->ho_ten }}</span>
        <span class="badge rounded-pill px-3 py-2 fs-6" style="background:{{ $ttInfo['bg'] }}; color:{{ $ttInfo['color'] }}"><i class="{{ $ttInfo['icon'] ?? '' }} me-1"></i> {{ $ttInfo['label'] }}</span>
    </h1>
</div>

@include('frontend.partials.flash-messages')

<div class="row g-4">
    <div class="col-12 col-xl-8">

        <div class="card border-0 shadow-sm mb-4 border-top border-4 border-primary">
            <div class="card-header bg-white fw-bold py-3"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin Khách Hàng</div>
            <div class="card-body p-4 row g-4">
                <div class="col-md-6">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Họ tên</div>
                    <div class="fs-5 fw-bold text-dark">{{ $lienHe->ho_ten }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Số điện thoại</div>
                    <a href="tel:{{ $lienHe->so_dien_thoai }}" class="fs-5 fw-bold text-success text-decoration-none"><i class="fas fa-phone-alt me-1"></i>{{ $lienHe->so_dien_thoai }}</a>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Email</div>
                    <div class="text-dark fw-bold">{{ $lienHe->email ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Mã Yêu Cầu & Nguồn</div>
                    <div class="text-dark fw-bold"><span class="badge bg-secondary-subtle text-secondary me-2">{{ $lienHe->ma_yeu_cau }}</span> Nguồn: {{ \App\Models\YeuCauLienHe::NGUON[$lienHe->nguon_lien_he] ?? $lienHe->nguon_lien_he }}</div>
                </div>
            </div>
        </div>

        @if ($lienHe->batDongSan)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold py-3"><i class="fas fa-home text-success me-2"></i>Bất động sản đang xem</div>
            <div class="card-body p-4 d-flex gap-3 align-items-center">
                <img src="{{ $lienHe->batDongSan->anh_chinh ? asset('storage/' . $lienHe->batDongSan->anh_chinh) : asset('images/default-bds.jpg') }}" class="rounded shadow-sm" style="width:120px; height:80px; object-fit:cover;">
                <div>
                    <a href="#" target="_blank" class="fw-bold fs-5 text-primary text-decoration-none d-block mb-1">{{ $lienHe->batDongSan->ten_bat_dong_san }}</a>
                    <div class="text-muted small"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $lienHe->batDongSan->dia_chi }}</div>
                </div>
            </div>
        </div>
        @endif

        @if ($lienHe->noi_dung)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold py-3"><i class="fas fa-comment-dots text-info me-2"></i>Lời nhắn từ Khách</div>
            <div class="card-body p-4 bg-light text-dark fst-italic" style="font-size: 1.05rem; line-height: 1.6;">
                "{{ $lienHe->noi_dung }}"
            </div>
        </div>
        @endif
    </div>

    <div class="col-12 col-xl-4">
        <form method="POST" action="{{ route('nhanvien.admin.lien-he.update', $lienHe) }}" class="card border-0 shadow-sm mb-4 border-top border-4 border-warning">
            @csrf @method('PUT')
            <div class="card-header bg-white fw-bold py-3"><i class="fas fa-cog text-warning me-2"></i>Cập nhật Xử lý</div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Trạng thái (Status)</label>
                    <select name="trang_thai" class="form-select border-primary fw-bold text-primary">
                        @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                            <option value="{{ $v }}" @selected($lienHe->trang_thai == $v)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Mức độ quan tâm (Nhiệt độ)</label>
                    <select name="muc_do_quan_tam" class="form-select">
                        <option value="">— Chưa đánh giá —</option>
                        @foreach (\App\Models\YeuCauLienHe::MUC_DO as $v => $info)
                            <option value="{{ $v }}" @selected($lienHe->muc_do_quan_tam == $v)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Sale phụ trách</label>
                    <select name="nhan_vien_phu_trach_id" class="form-select">
                        <option value="">— Chưa có —</option>
                        @foreach ($nhanViens as $nv)
                            <option value="{{ $nv->id }}" @selected($lienHe->nhan_vien_phu_trach_id == $nv->id)>{{ $nv->ho_ten }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Ghi chú (Note)</label>
                    <textarea name="ghi_chu_admin" rows="4" class="form-control" placeholder="VD: Khách bảo trưa mai gọi lại...">{{ $lienHe->ghi_chu_admin }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark"><i class="fas fa-save me-1"></i> Lưu Tiến Trình</button>
            </div>
        </form>

        <div class="d-flex flex-column gap-2 mt-4">
            @if($lienHe->trang_thai != 'hoan_thanh')
            <form action="{{ route('nhanvien.admin.lien-he.chuyen-khach', $lienHe->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success w-100 fw-bold" onclick="return confirm('Chốt Lead này và tạo thành Data Khách Hàng CRM?')">
                    <i class="fas fa-user-plus me-1"></i> Tạo Data Khách CRM
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('nhanvien.admin.lien-he.destroy', $lienHe) }}" onsubmit="return confirm('Xác nhận xóa yêu cầu này vĩnh viễn?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100 fw-bold"><i class="fas fa-trash me-1"></i> Xóa Yêu Cầu</button>
            </form>
        </div>
    </div>
</div>
@endsection
