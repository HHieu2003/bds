@extends('frontend.layouts.master')
@section('title', 'Liên hệ với chúng tôi')

@section('content')
    <div style="background:#f8faff;min-height:60vh;padding:50px 0 70px">
        <div
            style="max-width:1100px;margin:0 auto;padding:0 16px;display:grid;grid-template-columns:1fr 420px;gap:40px;align-items:start">

            {{-- ── CỘT TRÁI: Thông tin ── --}}
            <div>
                <div
                    style="font-size:.8rem;color:#FF8C42;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">
                    Liên hệ
                </div>
                <h1 style="font-size:2rem;font-weight:800;color:#1a3c5e;margin:0 0 16px;line-height:1.3">
                    Chúng tôi luôn sẵn sàng<br>lắng nghe bạn
                </h1>
                <p style="color:#777;font-size:.95rem;line-height:1.7;margin:0 0 36px">
                    Đội ngũ chuyên viên tư vấn sẽ phản hồi trong vòng
                    <strong style="color:#FF8C42">2 giờ làm việc</strong>.
                </p>

                {{-- Thông tin liên hệ --}}
                @php
                    $contacts = [
                        [
                            'icon' => 'fas fa-phone',
                            'color' => '#27ae60',
                            'label' => 'Hotline tư vấn',
                            'val' => '0901 234 567',
                            'href' => 'tel:0901234567',
                        ],
                        [
                            'icon' => 'fas fa-envelope',
                            'color' => '#3498db',
                            'label' => 'Email',
                            'val' => 'info@batdongsan.vn',
                            'href' => 'mailto:info@batdongsan.vn',
                        ],
                        [
                            'icon' => 'fas fa-map-marker-alt',
                            'color' => '#e74c3c',
                            'label' => 'Văn phòng',
                            'val' => '123 Đường ABC, Hà Nội',
                            'href' => '#',
                        ],
                        [
                            'icon' => 'fas fa-clock',
                            'color' => '#f39c12',
                            'label' => 'Giờ làm việc',
                            'val' => 'T2-T7: 8:00 - 18:00',
                            'href' => null,
                        ],
                    ];
                @endphp

                <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:36px">
                    @foreach ($contacts as $c)
                        <div
                            style="display:flex;align-items:center;gap:16px;padding:16px 20px;background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.05);border:1.5px solid #f0f2f5">
                            <div
                                style="width:44px;height:44px;border-radius:12px;background:{{ $c['color'] }}18;color:{{ $c['color'] }};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                                <i class="{{ $c['icon'] }}"></i>
                            </div>
                            <div>
                                <div style="font-size:.72rem;color:#aaa;margin-bottom:2px">{{ $c['label'] }}</div>
                                @if ($c['href'])
                                    <a href="{{ $c['href'] }}"
                                        style="font-size:.95rem;font-weight:700;color:#1a3c5e;text-decoration:none">
                                        {{ $c['val'] }}
                                    </a>
                                @else
                                    <div style="font-size:.95rem;font-weight:700;color:#1a3c5e">{{ $c['val'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── CỘT PHẢI: Form ── --}}
            <div
                style="background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(0,0,0,.08);padding:36px;border:1.5px solid #f0f2f5;position:sticky;top:20px">

                @if (session('lien_he_success'))
                    <div style="background:#e8f8f0;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px">
                        <i class="fas fa-check-circle"
                            style="font-size:2rem;color:#27ae60;display:block;margin-bottom:10px"></i>
                        <div style="font-weight:700;color:#27ae60;margin-bottom:4px">Gửi thành công!</div>
                        <div style="font-size:.83rem;color:#555">Chúng tôi sẽ liên hệ bạn sớm nhất.</div>
                    </div>
                @endif

                <h2
                    style="font-size:1.15rem;font-weight:800;color:#1a3c5e;margin:0 0 20px;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-paper-plane" style="color:#FF8C42"></i>
                    Gửi yêu cầu tư vấn
                </h2>

                <form method="POST" action="{{ route('lien-he.store') }}" id="lienHeForm">
                    @csrf

                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:.8rem;font-weight:700;color:#555;margin-bottom:5px">
                            Họ và tên <span style="color:#e74c3c">*</span>
                        </label>
                        <input type="text" name="ho_ten"
                            value="{{ old('ho_ten', auth('customer')->user()?->ho_ten ?? '') }}" placeholder="Nguyễn Văn A"
                            style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('ho_ten') ? '#e74c3c' : '#e8e8e8' }};border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box">
                        @error('ho_ten')
                            <div style="font-size:.75rem;color:#e74c3c;margin-top:4px">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:.8rem;font-weight:700;color:#555;margin-bottom:5px">
                            Số điện thoại <span style="color:#e74c3c">*</span>
                        </label>
                        <input type="tel" name="so_dien_thoai"
                            value="{{ old('so_dien_thoai', auth('customer')->user()?->so_dien_thoai ?? '') }}"
                            placeholder="0901 234 567"
                            style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('so_dien_thoai') ? '#e74c3c' : '#e8e8e8' }};border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box">
                        @error('so_dien_thoai')
                            <div style="font-size:.75rem;color:#e74c3c;margin-top:4px">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div style="margin-bottom:14px">
                        <label
                            style="display:block;font-size:.8rem;font-weight:700;color:#555;margin-bottom:5px">Email</label>
                        <input type="email" name="email"
                            value="{{ old('email', auth('customer')->user()?->email ?? '') }}"
                            placeholder="email@example.com"
                            style="width:100%;padding:11px 14px;border:1.5px solid #e8e8e8;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box">
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:.8rem;font-weight:700;color:#555;margin-bottom:5px">Nội
                            dung</label>
                        <textarea name="noi_dung" rows="4"
                            placeholder="Bạn muốn tìm hiểu về sản phẩm nào? Chúng tôi có thể giúp gì cho bạn?"
                            style="width:100%;padding:11px 14px;border:1.5px solid #e8e8e8;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;resize:vertical">{{ old('noi_dung') }}</textarea>
                    </div>

                    <button type="submit"
                        style="width:100%;background:linear-gradient(135deg,#FF8C42,#f5a623);color:#fff;border:none;padding:14px;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(255,140,66,.35)">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu tư vấn
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
