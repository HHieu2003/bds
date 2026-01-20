

    @extends('admin.layout.master')

@section('content')
    <div class="card shadow-sm border-0">
       
    <div class="container bg-white p-4 rounded shadow-sm">
        <h2 class="mb-4 text-primary">Nhập Kho Hàng Mới</h2>

        {{-- --- PHẦN QUAN TRỌNG: HIỂN THỊ LỖI --- --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- --------------------------------------- --}}

        {{-- LƯU Ý: Phải có enctype="multipart/form-data" mới upload được ảnh --}}
        <form action="{{ route('admin.bat-dong-san.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-4">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Tiêu đề bài đăng (SEO Title) <span class="text-danger">*</span></label>
                    {{-- value="{{ old('...') }}" giúp giữ lại nội dung cũ nếu nhập sai --}}
                    <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de') }}" placeholder="VD: Bán gấp căn hộ 2PN Vinhomes view hồ..." required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Thuộc Dự Án <span class="text-danger">*</span></label>
                    <select name="du_an_id" class="form-select" required>
                        <option value="">-- Chọn Dự Án --</option>
                        @foreach($du_ans as $da)
                            <option value="{{ $da->id }}" {{ old('du_an_id') == $da->id ? 'selected' : '' }}>
                                {{ $da->ten_du_an }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mb-3">Thông số chi tiết</h5>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="fw-bold">Tòa <span class="text-danger">*</span></label>
                    <input type="text" name="toa" class="form-control" placeholder="VD: GS1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mã căn <span class="text-danger">*</span></label>
                    <input type="text" name="ma_can" class="form-control" value="{{ old('ma_can') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Diện tích (m2) <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="dien_tich" class="form-control" value="{{ old('dien_tich') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Phòng ngủ</label>
                    <input type="text" name="phong_ngu" class="form-control" value="{{ old('phong_ngu') }}" placeholder="VD: 2PN2VS">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hướng cửa</label>
                    <select name="huong_cua" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Đông" {{ old('huong_cua') == 'Đông' ? 'selected' : '' }}>Đông</option>
                        <option value="Tây" {{ old('huong_cua') == 'Tây' ? 'selected' : '' }}>Tây</option>
                        <option value="Nam" {{ old('huong_cua') == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Bắc" {{ old('huong_cua') == 'Bắc' ? 'selected' : '' }}>Bắc</option>
                        <option value="Đông Nam" {{ old('huong_cua') == 'Đông Nam' ? 'selected' : '' }}>Đông Nam</option>
                        <option value="Đông Bắc" {{ old('huong_cua') == 'Đông Bắc' ? 'selected' : '' }}>Đông Bắc</option>
                        <option value="Tây Nam" {{ old('huong_cua') == 'Tây Nam' ? 'selected' : '' }}>Tây Nam</option>
                        <option value="Tây Bắc" {{ old('huong_cua') == 'Tây Bắc' ? 'selected' : '' }}>Tây Bắc</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hướng ban công</label>
                    <select name="huong_ban_cong" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Đông" {{ old('huong_ban_cong') == 'Đông' ? 'selected' : '' }}>Đông</option>
                        <option value="Tây" {{ old('huong_ban_cong') == 'Tây' ? 'selected' : '' }}>Tây</option>
                        <option value="Nam" {{ old('huong_ban_cong') == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Bắc" {{ old('huong_ban_cong') == 'Bắc' ? 'selected' : '' }}>Bắc</option>
                        <option value="Đông Nam" {{ old('huong_ban_cong') == 'Đông Nam' ? 'selected' : '' }}>Đông Nam</option>
                        <option value="Đông Bắc" {{ old('huong_ban_cong') == 'Đông Bắc' ? 'selected' : '' }}>Đông Bắc</option>
                        <option value="Tây Nam" {{ old('huong_ban_cong') == 'Tây Nam' ? 'selected' : '' }}>Tây Nam</option>
                        <option value="Tây Bắc" {{ old('huong_ban_cong') == 'Tây Bắc' ? 'selected' : '' }}>Tây Bắc</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-success">Giá tiền (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" name="gia" class="form-control" value="{{ old('gia') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hình thức thanh toán</label>
                    <input type="text" name="hinh_thuc_thanh_toan" class="form-control" value="{{ old('hinh_thuc_thanh_toan') }}" placeholder="VD: 3 cọc 1">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Loại hình</label>
                    <select name="loai_hinh" class="form-select">
                        <option value="thue">Cho Thuê</option>
                        <option value="ban">Chuyển Nhượng</option>
                    </select>
                </div>
            </div>

            <div class="mb-4 bg-light p-3 rounded border">
                <label class="form-label fw-bold text-danger">Hình ảnh & Video căn hộ</label>
                <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*,video/*">
                <small class="text-muted">Giữ Ctrl để chọn nhiều file. Tối đa 50MB/file.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả thêm</label>
                <textarea name="mo_ta" class="form-control" rows="3">{{ old('mo_ta') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success btn-lg px-5">Đăng Tin</button>
            <a href="{{ route('admin.bat-dong-san.index') }}" class="btn btn-secondary btn-lg">Hủy</a>
        </form>
    </div>

    </div>
@endsection