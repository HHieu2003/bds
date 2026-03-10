@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Cập Nhật Bất Động Sản: <small class="text-primary">{{ $batDongSan->ma_can }}</small></h1>
        <a href="{{ route('admin.bat-dong-san.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
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

    <form action="{{ route('admin.bat-dong-san.update', $batDongSan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- CỘT TRÁI --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin chi tiết</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold">Tiêu đề tin đăng</label>
                            <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de', $batDongSan->tieu_de) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Dự án</label>
                                <select name="du_an_id" class="form-control">
                                    <option value="">-- Chọn dự án --</option>
                                    @foreach($du_ans as $da)
                                        <option value="{{ $da->id }}" {{ $batDongSan->du_an_id == $da->id ? 'selected' : '' }}>
                                            {{ $da->ten_du_an }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Loại hình</label>
                                <select name="loai_hinh" class="form-control">
                                    <option value="can_ho" {{ $batDongSan->loai_hinh == 'can_ho' ? 'selected' : '' }}>Căn hộ chung cư</option>
                                    <option value="nha_pho" {{ $batDongSan->loai_hinh == 'nha_pho' ? 'selected' : '' }}>Nhà phố</option>
                                    <option value="biet_thu" {{ $batDongSan->loai_hinh == 'biet_thu' ? 'selected' : '' }}>Biệt thự</option>
                                    <option value="dat_nen" {{ $batDongSan->loai_hinh == 'dat_nen' ? 'selected' : '' }}>Đất nền</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Mô tả chi tiết</label>
                            <textarea name="mo_ta" id="mo_ta" class="form-control" rows="10">{{ old('mo_ta', $batDongSan->mo_ta) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI --}}
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông số & Hình ảnh</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Mã căn</label>
                                <input type="text" name="ma_can" class="form-control" value="{{ $batDongSan->ma_can }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label>Tòa/Block</label>
                                <input type="text" name="toa" class="form-control" value="{{ $batDongSan->toa }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-success">Giá bán (Tỷ)</label>
                            <input type="number" step="0.01" name="gia" class="form-control font-weight-bold" value="{{ $batDongSan->gia }}" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Diện tích (m²)</label>
                                <input type="number" step="0.1" name="dien_tich" class="form-control" value="{{ $batDongSan->dien_tich }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label>Hướng cửa</label>
                                <select name="huong_cua" class="form-control">
                                    <option value="">-- Chọn --</option>
                                    @foreach(['Dong', 'Tay', 'Nam', 'Bac', 'Dong Nam', 'Dong Bac', 'Tay Nam', 'Tay Bac'] as $h)
                                        <option value="{{ $h }}" {{ $batDongSan->huong_cua == $h ? 'selected' : '' }}>{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>PN</label>
                                <input type="number" name="so_phong_ngu" class="form-control" value="{{ $batDongSan->so_phong_ngu }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label>WC</label>
                                <input type="number" name="so_phong_tam" class="form-control" value="{{ $batDongSan->so_phong_tam }}">
                            </div>
                        </div>

                        {{-- Quản lý hình ảnh --}}
                        <div class="mb-3">
                            <label class="fw-bold">Hình ảnh hiện tại</label>
                            <div class="row g-2 mb-2">
                                @if($batDongSan->hinh_anh && is_array($batDongSan->hinh_anh))
                                    @foreach($batDongSan->hinh_anh as $img)
                                        <div class="col-4">
                                            <img src="{{ asset($img) }}" class="img-thumbnail" style="height: 60px; width: 100%; object-fit: cover;">
                                        </div>
                                    @endforeach
                                @else
                                    <p class="small text-muted ps-3">Chưa có hình ảnh</p>
                                @endif
                            </div>
                            
                            <label class="fw-bold mt-2">Thêm ảnh mới (Ghi đè hoặc nối thêm tùy logic Controller)</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">Trạng thái</label>
                            <select name="trang_thai" class="form-control">
                                <option value="con_hang" {{ $batDongSan->trang_thai == 'con_hang' ? 'selected' : '' }}>Còn hàng</option>
                                <option value="da_ban" {{ $batDongSan->trang_thai == 'da_ban' ? 'selected' : '' }}>Đã bán</option>
                                <option value="ngung_ban" {{ $batDongSan->trang_thai == 'ngung_ban' ? 'selected' : '' }}>Ngừng kinh doanh</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="fas fa-check-circle me-2"></i> Lưu Cập Nhật
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