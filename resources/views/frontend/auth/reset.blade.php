@extends('frontend.layouts.master')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh; padding: 40px 0;">
    <div class="card shadow-lg" style="width: 100%; max-width: 450px; border-radius: 16px; border: none;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #FF8C42, #FF5722); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 1.5rem; box-shadow: 0 4px 15px rgba(255,140,66,0.4);">
                    <i class="fas fa-unlock-alt"></i>
                </div>
                <h4 style="color: #1a3c5e; font-weight: 800;">Tạo Mật Khẩu Mới</h4>
                <p class="text-muted" style="font-size: 0.85rem;">Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
            </div>

            <form action="{{ route('khach-hang.reset.post') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group mb-3">
                    <label style="font-weight: 600; font-size: 0.85rem; color: #444;">Mật khẩu mới <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự" style="border-radius: 8px; padding: 10px 15px;" required>
                    @error('password')
                        <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label style="font-weight: 600; font-size: 0.85rem; color: #444;">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" style="border-radius: 8px; padding: 10px 15px;" required>
                </div>

                <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #FF8C42, #FF5722); color: #fff; font-weight: 700; padding: 12px; border-radius: 8px; transition: transform 0.2s, box-shadow 0.2s;">
                    <i class="fas fa-save me-1"></i> Lưu mật khẩu & Đăng nhập
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
