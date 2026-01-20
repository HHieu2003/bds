@extends('admin.layout.master')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h2 class="mb-4 text-warning">✏️ Sửa tin: {{ $batDongSan->ma_can }}</h2>

            <form action="{{ route('admin.bat-dong-san.update', $batDongSan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Tiêu đề</label>
                        <input type="text" name="tieu_de" class="form-control" value="{{ $batDongSan->tieu_de }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Dự án</label>
                        <select name="du_an_id" class="form-select">
                            @foreach($du_ans as $da)
                                <option value="{{ $da->id }}" {{ $batDongSan->du_an_id == $da->id ? 'selected' : '' }}>
                                    {{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Mã căn</label>
                        <input type="text" name="ma_can" class="form-control" value="{{ $batDongSan->ma_can }}">
                    </div>
                    <div class="col-md-2">
                    <label class="fw-bold">Mã căn</label>
                    <input type="text" name="ma_can" class="form-control" value="{{ $batDongSan->ma_can }}">
                </div>
                    <div class="col-md-3">
                        <label class="form-label">Giá (VNĐ)</label>
                        <input type="number" name="gia" class="form-control" value="{{ $batDongSan->gia }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Diện tích</label>
                        <input type="number" name="dien_tich" class="form-control" value="{{ $batDongSan->dien_tich }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="con_hang" {{ $batDongSan->trang_thai == 'con_hang' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="da_chot" {{ $batDongSan->trang_thai == 'da_chot' ? 'selected' : '' }}>Đã chốt</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control" rows="3">{{ $batDongSan->mo_ta }}</textarea>
                </div>

                <div class="mb-4 bg-light p-3 rounded">
                    <label class="form-label fw-bold">Hình ảnh hiện có:</label>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @if($batDongSan->hinh_anh)
                            @foreach($batDongSan->hinh_anh as $img)
                                <img src="{{ asset('storage/' . $img) }}" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd;">
                            @endforeach
                        @endif
                    </div>
                    <label class="form-label text-primary small">Upload thêm ảnh mới (nếu muốn):</label>
                    <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                </div>

                <button type="submit" class="btn btn-warning px-4">Cập nhật tin</button>
                <a href="{{ route('admin.bat-dong-san.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection