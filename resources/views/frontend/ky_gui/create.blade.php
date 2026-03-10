@extends('frontend.layouts.master')

@section('title', 'Ký Gửi Bất Động Sản')

@section('content')

{{-- CSS Riêng cho trang này --}}
<style>
    /* Hiệu ứng chuyển động khi load trang */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* Nền và Card */
    .ky-gui-section {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 60px 0;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    /* Input Styling */
    .form-floating > .form-control {
        border: 2px solid #eef2f7;
        border-radius: 12px;
        height: 55px;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus {
        border-color: #3b82f6; /* Màu chủ đạo */
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-floating > label {
        color: #9ca3af;
    }

    /* Custom Select */
    .form-select {
        border: 2px solid #eef2f7;
        border-radius: 12px;
        height: 55px;
        transition: all 0.3s ease;
    }

    /* Upload Box đẹp */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .upload-zone:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .upload-zone i {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 15px;
        transition: color 0.3s;
    }

    .upload-zone:hover i {
        color: #3b82f6;
    }

    .upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Button Gradient */
    .btn-gradient {
        background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(37, 99, 235, 0.3);
        background: linear-gradient(90deg, #1d4ed8 0%, #1e40af 100%);
    }

    /* Typography */
    .section-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        position: relative;
        padding-left: 15px;
    }

    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 25px;
        background: #3b82f6;
        border-radius: 2px;
    }
</style>

<div class="ky-gui-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="text-center mb-5 animate-fade-up">
                    <h1 class="fw-bold display-5 mb-3 text-dark">Ký Gửi Nhà Đất</h1>
                    <p class="text-muted fs-5">Điền thông tin bất động sản của bạn. Chúng tôi sẽ hỗ trợ bán nhanh nhất với giá tốt nhất.</p>
                </div>

                <div class="card form-card animate-fade-up" style="animation-delay: 0.2s;">
                    <div class="card-body p-5">
                        
                        {{-- Hiển thị thông báo --}}
                        @if(session('success'))
                            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-4 rounded-3">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4 rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('ky-gui.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-5">
                                <h4 class="section-title">Thông Tin Liên Hệ</h4>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="ho_ten" name="ho_ten" placeholder="Họ và tên" value="{{ old('ho_ten', Auth::guard('customer')->check() ? Auth::guard('customer')->user()->ho_ten : '') }}" required>
                                            <label for="ho_ten"><i class="fas fa-user me-2 text-muted"></i>Họ và tên (*)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" placeholder="Số điện thoại" value="{{ old('so_dien_thoai', Auth::guard('customer')->check() ? Auth::guard('customer')->user()->so_dien_thoai : '') }}" required>
                                            <label for="so_dien_thoai"><i class="fas fa-phone-alt me-2 text-muted"></i>Số điện thoại (*)</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', Auth::guard('customer')->check() ? Auth::guard('customer')->user()->email : '') }}">
                                            <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Email (Nếu có)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5 text-muted opacity-25">

                            <div class="mb-5">
                                <h4 class="section-title">Thông Tin Bất Động Sản</h4>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="tieu_de" name="tieu_de" placeholder="Tiêu đề tin đăng" value="{{ old('tieu_de') }}" required>
                                            <label for="tieu_de"><i class="fas fa-heading me-2 text-muted"></i>Tiêu đề tin đăng (VD: Bán nhà mặt phố Quận 1...)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="loai_hinh" name="loai_hinh">
                                                <option value="can_ho">Căn hộ chung cư</option>
                                                <option value="nha_pho">Nhà phố</option>
                                                <option value="biet_thu">Biệt thự</option>
                                                <option value="dat_nen">Đất nền</option>
                                                <option value="van_phong">Văn phòng</option>
                                            </select>
                                            <label for="loai_hinh"><i class="fas fa-building me-2 text-muted"></i>Loại hình</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="huong_cua" name="huong_cua">
                                                <option value="">-- Chọn hướng --</option>
                                                <option value="Dong">Đông</option>
                                                <option value="Tay">Tây</option>
                                                <option value="Nam">Nam</option>
                                                <option value="Bac">Bắc</option>
                                                <option value="Dong Nam">Đông Nam</option>
                                                <option value="Dong Bac">Đông Bắc</option>
                                                <option value="Tay Nam">Tây Nam</option>
                                                <option value="Tay Bac">Tây Bắc</option>
                                            </select>
                                            <label for="huong_cua"><i class="fas fa-compass me-2 text-muted"></i>Hướng cửa</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="dien_tich" name="dien_tich" placeholder="Diện tích (m2)" required>
                                            <label for="dien_tich"><i class="fas fa-ruler-combined me-2 text-muted"></i>Diện tích (m²)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="gia" name="gia" placeholder="Giá mong muốn" required>
                                            <label for="gia"><i class="fas fa-tag me-2 text-muted"></i>Giá mong muốn (VNĐ)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="so_phong_ngu" name="so_phong_ngu" placeholder="Phòng ngủ">
                                            <label for="so_phong_ngu"><i class="fas fa-bed me-2 text-muted"></i>Số phòng ngủ</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="so_phong_tam" name="so_phong_tam" placeholder="Phòng tắm">
                                            <label for="so_phong_tam"><i class="fas fa-bath me-2 text-muted"></i>Số phòng tắm</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="dia_chi" name="dia_chi" placeholder="Địa chỉ chi tiết" required>
                                            <label for="dia_chi"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Địa chỉ chi tiết bất động sản</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="mo_ta" name="mo_ta" style="height: 150px" placeholder="Mô tả"></textarea>
                                            <label for="mo_ta"><i class="fas fa-align-left me-2 text-muted"></i>Mô tả chi tiết (Vị trí, tiện ích, nội thất...)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <h4 class="section-title">Hình Ảnh Thực Tế</h4>
                                <div class="upload-zone" id="uploadZone">
                                    <input type="file" name="hinh_anh[]" multiple class="upload-input" id="fileInput" accept="image/*" onchange="previewImages()">
                                    <div class="upload-content">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <h5 class="fw-bold text-dark">Kéo thả ảnh vào đây hoặc click để chọn</h5>
                                        <p class="text-muted small mb-0">Hỗ trợ: JPG, PNG, JPEG (Tối đa 5MB/ảnh)</p>
                                    </div>
                                </div>
                                {{-- Khu vực hiển thị ảnh preview --}}
                                <div id="imagePreview" class="row g-3 mt-3"></div>
                            </div>

                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-gradient w-100 py-3">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi Yêu Cầu Ký Gửi
                                </button>
                                <p class="text-muted mt-3 small">
                                    <i class="fas fa-lock me-1"></i> Thông tin của bạn được bảo mật tuyệt đối.
                                </p>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Script xử lý xem trước ảnh --}}
<script>
    function previewImages() {
        var preview = document.getElementById('imagePreview');
        var files = document.getElementById('fileInput').files;
        
        preview.innerHTML = ''; // Xóa ảnh cũ

        if (files) {
            [].forEach.call(files, function(file) {
                var reader = new FileReader();
                
                reader.onloadend = function () {
                    var div = document.createElement("div");
                    div.className = "col-6 col-md-3 animate-fade-up";
                    div.innerHTML = `
                        <div class="position-relative ratio ratio-1x1 rounded-3 overflow-hidden shadow-sm border">
                            <img src="${reader.result}" class="object-fit-cover w-100 h-100" alt="Preview">
                        </div>
                    `;
                    preview.appendChild(div);
                }
                
                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        }
    }
</script>

@endsection