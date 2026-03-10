@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Đăng Tin Bất Động Sản Mới</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bat-dong-san.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                            <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de') }}" required placeholder="VD: Bán căn hộ 2PN view sông...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Thuộc Dự án</label>
                                <select name="du_an_id" class="form-control">
                                    <option value="">-- Chọn dự án (Nếu có) --</option>
                                    @foreach($du_ans as $da)
                                        <option value="{{ $da->id }}" {{ old('du_an_id') == $da->id ? 'selected' : '' }}>
                                            {{ $da->ten_du_an }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Loại hình</label>
                                <select name="loai_hinh" class="form-control">
                                    <option value="can_ho">Căn hộ chung cư</option>
                                    <option value="nha_pho">Nhà phố</option>
                                    <option value="biet_thu">Biệt thự</option>
                                    <option value="dat_nen">Đất nền</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
                            {{-- ID mo_ta dùng cho CKEditor --}}
                            <textarea name="mo_ta" id="mo_ta" class="form-control" rows="10">{{ old('mo_ta') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: THÔNG SỐ & HÌNH ẢNH --}}
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông số & Giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Mã căn</label>
                                <input type="text" name="ma_can" class="form-control" value="{{ old('ma_can') }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label>Tòa/Block</label>
                                <input type="text" name="toa" class="form-control" value="{{ old('toa') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-success">Giá bán (Tỷ VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="gia" class="form-control font-weight-bold" value="{{ old('gia') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Diện tích (m²)</label>
                                <input type="number" step="0.1" name="dien_tich" class="form-control" value="{{ old('dien_tich') }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label>Hướng cửa</label>
                                <select name="huong_cua" class="form-control">
                                    <option value="">-- Chọn --</option>
                                    <option value="Dong">Đông</option>
                                    <option value="Tay">Tây</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Bac">Bắc</option>
                                    <option value="Dong Nam">Đông Nam</option>
                                    <option value="Dong Bac">Đông Bắc</option>
                                    <option value="Tay Nam">Tây Nam</option>
                                    <option value="Tay Bac">Tây Bắc</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Phòng ngủ</label>
                                <input type="number" name="so_phong_ngu" class="form-control" value="{{ old('so_phong_ngu') }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label>Phòng tắm</label>
                                <input type="number" name="so_phong_tam" class="form-control" value="{{ old('so_phong_tam') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Hình ảnh (Chọn nhiều)</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Giữ Ctrl để chọn nhiều ảnh</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">Trạng thái</label>
                            <select name="trang_thai" class="form-control">
                                <option value="con_hang">Còn hàng</option>
                                <option value="da_ban">Đã bán</option>
                                <option value="ngung_ban">Ngừng kinh doanh</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="fas fa-save me-2"></i> Đăng Tin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT CKEDITOR --}}
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('mo_ta', {
        height: 400
    });
</script>
@endsection