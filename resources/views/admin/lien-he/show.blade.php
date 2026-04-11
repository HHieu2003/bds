@extends('admin.layouts.master')
@section('title', 'Chi tiết Yêu cầu tư vấn')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="btn btn-sm btn-light border mb-2"><i
                    class="fas fa-arrow-left me-1"></i> Quay lại Danh sách</a>
            <h1 class="page-header-title"><i class="fas fa-id-card text-primary me-2"></i> Chi tiết Lead:
                {{ $lienHe->ma_yeu_cau }}</h1>
        </div>

        @if (is_null($lienHe->nhan_vien_phu_trach_id))
            <form action="{{ route('nhanvien.admin.lien-he.nhan-lead', $lienHe->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4 shadow-sm"
                    style="animation: pulse-animation 2s infinite;">
                    <i class="fas fa-hand-paper me-2"></i> NHẬN LEAD NÀY
                </button>
            </form>
        @elseif($lienHe->trang_thai != 'da_chot')
            <form action="{{ route('nhanvien.admin.lien-he.chuyen-khach', $lienHe->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm"
                    onclick="return confirm('Xác nhận chốt khách này?')">
                    <i class="fas fa-trophy me-2"></i> CHỐT! Chuyển vào CRM
                </button>
            </form>
        @endif
    </div>

    @include('frontend.partials.flash-messages')

    <div class="row g-4">
        {{-- Cột Trái: Thông tin khách & BĐS --}}
        <div class="col-lg-7">
            {{-- Thông tin Khách hàng --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Thông tin Khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Họ và tên:</div>
                        <div class="col-sm-8 fw-bold fs-5">{{ $lienHe->ho_ten }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Số điện thoại:</div>
                        <div class="col-sm-8">
                            <span class="fs-5 fw-bold text-success">{{ $lienHe->so_dien_thoai }}</span>
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $lienHe->so_dien_thoai) }}"
                                class="btn btn-sm btn-success rounded-circle ms-2"><i class="fas fa-phone-alt"></i></a>
                            <a href="https://zalo.me/{{ preg_replace('/[^0-9]/', '', $lienHe->so_dien_thoai) }}"
                                target="_blank" class="btn btn-sm btn-primary rounded-circle"
                                style="background:#0068FF; border:none;"><strong>Z</strong></a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Email:</div>
                        <div class="col-sm-8 fw-medium">{{ $lienHe->email ?: '—' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-muted">Nguồn tạo:</div>
                        <div class="col-sm-8 fw-medium"><span class="badge bg-light text-dark border"><i
                                    class="fas fa-sign-in-alt me-1"></i>
                                {{ \App\Models\YeuCauLienHe::NGUON[$lienHe->nguon_lien_he] ?? $lienHe->nguon_lien_he }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bối cảnh tư vấn --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold"><i class="fas fa-comment-dots me-2 text-primary"></i>Nhu cầu tư vấn
                    </h5>
                </div>
                <div class="card-body">
                    @if ($lienHe->batDongSan)
                        <div class="mb-3 p-3 bg-light rounded-3 border">
                            <span class="badge bg-success-subtle text-success mb-2"><i class="fas fa-home"></i> Khách đang
                                xem BĐS này</span>
                            <a href="{{ route('frontend.bat-dong-san.show', $lienHe->batDongSan->slug ?? '') }}"
                                target="_blank" class="fw-bold text-navy text-decoration-none d-block fs-6 mb-1">
                                {{ $lienHe->batDongSan->ten_bat_dong_san }}
                            </a>
                            <div class="text-danger fw-bold mb-1">{{ $lienHe->batDongSan->gia_hien_thi }}</div>
                            <div class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>
                                {{ $lienHe->batDongSan->dia_chi }}</div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="text-muted mb-1 d-block">Lời nhắn của khách:</label>
                        <div class="p-3 bg-light rounded-3 border fst-italic">
                            {!! $lienHe->noi_dung ? nl2br(e($lienHe->noi_dung)) : 'Không có lời nhắn.' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cột Phải: Xử lý và Ghi chú --}}
        <div class="col-lg-5">
            <form action="{{ route('nhanvien.admin.lien-he.update', $lienHe) }}" method="POST"
                class="card border-0 shadow-sm mb-4">
                @csrf @method('PUT')
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold"><i class="fas fa-tasks me-2 text-primary"></i>Trạng thái Xử lý</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Người phụ trách</label>
                        <select name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">-- Chưa có ai nhận --</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected($lienHe->nhan_vien_phu_trach_id == $nv->id)>{{ $nv->ho_ten }}
                                    ({{ $nv->vai_tro }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái Lead</label>
                        <select name="trang_thai" class="form-select fw-bold">
                            @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $k => $v)
                                <option value="{{ $k }}" @selected($lienHe->trang_thai == $k)>{{ $v['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Đánh giá tiềm năng</label>
                        <select name="muc_do_quan_tam" class="form-select">
                            <option value="">-- Chưa rõ --</option>
                            @foreach (\App\Models\YeuCauLienHe::MUC_DO as $k => $v)
                                <option value="{{ $k }}" @selected($lienHe->muc_do_quan_tam == $k)>{{ $v['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Cập nhật Trạng
                        thái</button>
                </div>
            </form>

            <form action="{{ route('nhanvien.admin.lien-he.update', $lienHe) }}" method="POST"
                class="card border-0 shadow-sm">
                @csrf @method('PUT')
                <input type="hidden" name="is_quick_update" value="1">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i>Lịch sử Chăm sóc
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-light p-3 rounded-3 border mb-3"
                            style="max-height: 250px; overflow-y: auto; font-size: 0.9rem;">
                            {!! $lienHe->ghi_chu_admin
                                ? nl2br(e($lienHe->ghi_chu_admin))
                                : '<span class="text-muted fst-italic">Chưa có lịch sử chăm sóc.</span>' !!}
                        </div>
                        <textarea name="ghi_chu_moi" rows="3" class="form-control" placeholder="Thêm lịch sử gọi điện mới..."
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-plus me-1"></i>Thêm
                        nhật ký</button>
                </div>
            </form>
        </div>
    </div>
@endsection
