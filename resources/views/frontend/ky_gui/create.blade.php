@extends('frontend.layouts.master')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <h3 class="fw-bold mb-0 serif-font">Ký Gửi Nhà Đất</h3>
                    <p class="mb-0 opacity-75">Hãy để Thành Công Land giúp bạn bán nhà nhanh nhất!</p>
                </div>
                <div class="card-body p-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success rounded-3 mb-4">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('ky-gui.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <h5 class="text-secondary fw-bold mb-3 border-bottom pb-2">1. Thông tin Bất động sản</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại hình</label>
                                <select name="loai_hinh" class="form-select rounded-pill">
                                    <option value="chung_cu">Căn hộ chung cư</option>
                                    <option value="nha_dat">Nhà đất thổ cư</option>
                                    <option value="biet_thu">Biệt thự / Liền kề</option>
                                    <option value="dat_nen">Đất nền</option>
                                    <option value="cho_thue">Cho thuê</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá mong muốn (Tỷ/Triệu)</label>
                                <input type="number" step="0.01" name="gia_mong_muon" class="form-control rounded-pill" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Địa chỉ chi tiết</label>
                                <input type="text" name="dia_chi" class="form-control rounded-pill" placeholder="Số nhà, đường, phường, quận..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Diện tích (m2)</label>
                                <input type="number" step="0.1" name="dien_tich" class="form-control rounded-pill" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hình ảnh thực tế (Nếu có)</label>
                                <input type="file" name="hinh_anh_tham_khao" class="form-control rounded-pill">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Ghi chú thêm</label>
                                <textarea name="ghi_chu" class="form-control rounded-3" rows="3" placeholder="Ví dụ: Nhà hướng Đông Nam, để lại nội thất..."></textarea>
                            </div>
                        </div>

                        <h5 class="text-secondary fw-bold mb-3 border-bottom pb-2">2. Thông tin liên hệ</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Họ tên chủ nhà <span class="text-danger">*</span></label>
                                <input type="text" name="ho_ten_chu_nha" class="form-control rounded-pill" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="so_dien_thoai" class="form-control rounded-pill" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Email (Tùy chọn)</label>
                                <input type="email" name="email" class="form-control rounded-pill">
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold w-100">Gửi Yêu Cầu Ký Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection