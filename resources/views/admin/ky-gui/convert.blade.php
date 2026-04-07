@extends('admin.layouts.master')
@section('title', 'Duyệt và Chuyển Đổi Ký Gửi')

@section('content')
    <div class="mb-4">
        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-sm btn-light border mb-3"><i
                class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        <h1 class="page-header-title text-success"><i class="fas fa-magic me-2"></i> Xác nhận Hợp Lệ & Đưa Vào Kho</h1>
        <p class="text-muted">Hệ thống đã tự động điền các thông tin từ form Ký gửi. Vui lòng kiểm tra lại với Chủ nhà và bổ
            sung thông tin trước khi Lưu BĐS.</p>
    </div>

    <form action="{{ route('nhanvien.admin.ky-gui.duyet', $kyGui->id) }}" method="POST">
        @csrf
        <div class="row g-4">

            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm border-top border-4 border-success mb-4">
                    <div class="card-header bg-white fw-bold py-3"><i class="fas fa-home text-success me-2"></i>Thông tin
                        Bất Động Sản (Lưu vào kho)</div>
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                            <input type="text" name="tieu_de" class="form-control form-control-lg text-primary fw-bold"
                                value="{{ old('tieu_de', 'Bán/Cho thuê ' . str_replace('_', ' ', $kyGui->loai_hinh) . ' - ' . $kyGui->dia_chi) }}"
                                required>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Nhu cầu <span class="text-danger">*</span></label>
                                <select name="nhu_cau" class="form-select border-danger fw-bold text-danger">
                                    <option value="ban" {{ $kyGui->nhu_cau == 'ban' ? 'selected' : '' }}>Bán</option>
                                    <option value="thue" {{ $kyGui->nhu_cau == 'thue' ? 'selected' : '' }}>Cho Thuê
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loại hình <span class="text-danger">*</span></label>
                                <select name="loai_hinh" class="form-select">
                                    @foreach (\App\Models\KyGui::LOAI_HINH as $k => $v)
                                        <option value="{{ $k }}" {{ $kyGui->loai_hinh == $k ? 'selected' : '' }}>
                                            {{ $v['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gán vào Dự án (Bỏ qua nếu nhà lẻ)</label>
                                <select name="du_an_id" class="form-select border-warning">
                                    <option value="">-- Không thuộc dự án --</option>
                                    @foreach ($duAns as $da)
                                        <option value="{{ $da->id }}">{{ $da->ten_du_an }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="dien_tich" class="form-control fw-bold"
                                    value="{{ $kyGui->dien_tich }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giá Bán (VNĐ)</label>
                                <input type="number" name="gia" class="form-control fw-bold"
                                    value="{{ $kyGui->nhu_cau == 'ban' ? $kyGui->gia_ban_mong_muon : '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giá Thuê (VNĐ/Tháng)</label>
                                <input type="number" name="gia_thue" class="form-control fw-bold"
                                    value="{{ $kyGui->nhu_cau == 'thue' ? $kyGui->gia_thue_mong_muon : '' }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-4 bg-light p-3 rounded">
                            <div class="col-md-3"><label class="form-label">Phòng ngủ</label><input type="number"
                                    name="so_phong_ngu" class="form-control" value="{{ $kyGui->so_phong_ngu }}"></div>
                            <div class="col-md-3"><label class="form-label">Phòng tắm</label><input type="number"
                                    name="so_phong_tam" class="form-control" value="{{ $kyGui->so_phong_tam }}"></div>
                            <div class="col-md-3"><label class="form-label">Hướng nhà</label><input type="text"
                                    name="huong_nha" class="form-control" value="{{ $kyGui->huong_nha }}"></div>
                            <div class="col-md-3"><label class="form-label">Pháp lý</label><input type="text"
                                    name="phap_ly" class="form-control" value="{{ $kyGui->phap_ly }}"></div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Mô tả / Thông tin cung cấp từ khách</label>
                            <textarea name="mo_ta" class="form-control" rows="5">Vị trí: {{ $kyGui->dia_chi }}&#13;&#10;Ghi chú: {{ $kyGui->ghi_chu }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm border-start border-4 border-info mb-4">
                    <div class="card-header bg-white fw-bold py-3"><i class="fas fa-user-tie text-info me-2"></i>Tạo Data
                        Chủ Nhà</div>
                    <div class="card-body bg-light">
                        <div class="alert alert-info text-dark" style="font-size: 0.8rem; padding: 10px;">
                            <i class="fas fa-info-circle"></i> Nếu SĐT đã có trên hệ thống, BĐS sẽ được tự động liên kết
                            với Chủ nhà cũ.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Họ tên Chủ nhà <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten_chu_nha" class="form-control fw-bold text-dark"
                                value="{{ $kyGui->ho_ten_chu_nha }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="so_dien_thoai" class="form-control fw-bold text-danger"
                                value="{{ $kyGui->so_dien_thoai }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $kyGui->email }}">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3"><i class="fas fa-images text-warning me-2"></i>Ảnh
                        khách đính kèm</div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        @if ($kyGui->hinh_anh_tham_khao && is_array($kyGui->hinh_anh_tham_khao))
                            @foreach ($kyGui->hinh_anh_tham_khao as $anh)
                                <a href="{{ asset('storage/' . $anh) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $anh) }}" class="img-thumbnail object-fit-cover"
                                        style="width: 100px; height: 100px;">
                                </a>
                            @endforeach
                            <p class="text-muted small mt-2 w-100"><i class="fas fa-check text-success"></i> Hệ thống sẽ
                                tự động Copy ảnh này sang BĐS.</p>
                        @else
                            <p class="text-muted w-100 text-center py-3">Khách không tải lên hình ảnh.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 text-end mb-5">
                <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-light border px-4 py-2 me-2">Trở
                    lại</a>
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold"
                    onclick="return confirm('Xác nhận đưa BĐS này vào Kho và tạo Data Chủ nhà?')">
                    <i class="fas fa-check-circle me-2"></i> LƯU VÀ ĐƯA VÀO KHO BĐS
                </button>
            </div>
        </div>
    </form>
@endsection
