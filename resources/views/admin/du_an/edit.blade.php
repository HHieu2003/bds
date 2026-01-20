@extends('admin.layout.master')

@section('content')
    <div class="card shadow-sm border-0" style="max-width: 800px;">
        <div class="card-body p-4">
            <h3 class="mb-4 text-warning">Sửa Dự Án: {{ $duAn->ten_du_an }}</h3>

            <form action="{{ route('admin.du-an.update', $duAn->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tên dự án <span class="text-danger">*</span></label>
                        <input type="text" name="ten_du_an" class="form-control" value="{{ $duAn->ten_du_an }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ảnh đại diện</label>
                        @if($duAn->hinh_anh)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $duAn->hinh_anh) }}" width="100" class="rounded border">
                            </div>
                        @endif
                        <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control" value="{{ $duAn->dia_chi }}">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Chủ đầu tư</label>
                        <input type="text" name="chu_dau_tu" class="form-control" value="{{ $duAn->chu_dau_tu }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Đơn vị thi công</label>
                        <input type="text" name="don_vi_thi_cong" class="form-control" value="{{ $duAn->don_vi_thi_cong }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả dự án</label>
                    <textarea name="mo_ta" class="form-control" rows="5">{{ $duAn->mo_ta }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning px-4">Cập nhật</button>
                <a href="{{ route('admin.du-an.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection