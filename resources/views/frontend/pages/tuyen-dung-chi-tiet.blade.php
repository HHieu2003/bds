@extends('frontend.layouts.master')

@section('title', $tin->tieu_de . ' - Tuyển Dụng - Thành Công Land')
@section('meta_description', Str::limit(strip_tags($tin->mo_ta_ngan ?? $tin->mo_ta_cong_viec), 160))
@section('og_title', $tin->tieu_de . ' - Tuyển Dụng')
@section('og_description', Str::limit(strip_tags($tin->mo_ta_ngan ?? $tin->mo_ta_cong_viec), 160))

@section('content')

    {{-- HERO --}}
    <section class="position-relative py-5"
        style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); min-height: 320px; display: flex; align-items: center;">
        <div class="container position-relative z-1">

            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0" style="font-size:.82rem">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"
                            style="color:rgba(255,255,255,.65);text-decoration:none"><i
                                class="fas fa-home me-1"></i>Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.tuyen-dung') }}"
                            style="color:rgba(255,255,255,.65);text-decoration:none">Tuyển dụng</a></li>
                    <li class="breadcrumb-item" style="color:#FF8C42">{{ Str::limit($tin->tieu_de, 40) }}</li>
                </ol>
            </nav>

            @if ($tin->tag)
                <span class="badge {{ $tin->tag_class }} px-3 py-2 rounded-pill mb-3 fw-semibold"
                    style="font-size:.78rem">{{ $tin->tag }}</span>
            @endif

            <h1 class="text-white fw-bold mb-3" style="font-size:clamp(1.5rem,3vw,2.2rem);line-height:1.3">
                {{ $tin->tieu_de }}
            </h1>

            <div class="d-flex flex-wrap gap-3" style="font-size:.85rem;color:rgba(255,255,255,.7)">
                <span><i class="fas fa-briefcase me-1" style="color:#FF8C42"></i>{{ $tin->hinh_thuc_label }}</span>
                @if ($tin->dia_diem)
                    <span><i class="fas fa-map-marker-alt me-1" style="color:#FF8C42"></i>{{ $tin->dia_diem }}</span>
                @endif
                @if ($tin->phong_ban)
                    <span><i class="fas fa-sitemap me-1" style="color:#FF8C42"></i>{{ $tin->phong_ban }}</span>
                @endif
                <span><i class="fas fa-users me-1" style="color:#FF8C42"></i>Cần {{ $tin->so_luong }} người</span>
                @if ($tin->han_nop)
                    <span><i class="fas fa-clock me-1"
                            style="color:#FF8C42"></i>{{ $tin->con_han ? 'Hạn: ' . $tin->han_nop->format('d/m/Y') : 'Đã hết hạn' }}</span>
                @endif
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="py-5" style="background:#f4f6f9">
        <div class="container">
            <div class="row g-5">

                {{-- CỘT TRÁI --}}
                <div class="col-lg-8">

                    @if ($tin->muc_luong)
                        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-3"
                                    style="width:48px;height:48px;background:linear-gradient(135deg,#FF8C42,#FF5722)">
                                    <i class="fas fa-money-bill-wave text-white fs-5"></i>
                                </div>
                                <div>
                                    <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;font-weight:700">
                                        Mức lương</div>
                                    <div style="font-size:1.3rem;font-weight:900;color:#FF8C42">
                                        {{ $tin->muc_luong }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($tin->mo_ta_cong_viec)
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3" style="color:#0F172A">
                                    <i class="fas fa-tasks me-2" style="color:#FF8C42"></i>Mô tả công việc
                                </h5>
                                <div class="article-content" style="font-size:.92rem;line-height:1.85;color:#475569">
                                    {!! nl2br(e($tin->mo_ta_cong_viec)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($tin->yeu_cau)
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3" style="color:#0F172A">
                                    <i class="fas fa-clipboard-check me-2" style="color:#FF8C42"></i>Yêu cầu ứng viên
                                </h5>
                                <div class="article-content" style="font-size:.92rem;line-height:1.85;color:#475569">
                                    {!! nl2br(e($tin->yeu_cau)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($tin->quyen_loi)
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3" style="color:#0F172A">
                                    <i class="fas fa-gift me-2" style="color:#FF8C42"></i>Quyền lợi
                                </h5>
                                <div class="article-content" style="font-size:.92rem;line-height:1.85;color:#475569">
                                    {!! nl2br(e($tin->quyen_loi)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Vị trí khác --}}
                    @if ($tinKhac->count() > 0)
                        <div class="mt-5">
                            <h5 class="fw-bold mb-4" style="color:#0F172A">
                                <span
                                    style="display:inline-block;width:4px;height:20px;background:#FF8C42;border-radius:2px;margin-right:.6rem;vertical-align:middle"></span>
                                Vị Trí Tuyển Dụng Khác
                            </h5>
                            <div class="row g-3">
                                @foreach ($tinKhac as $tk)
                                    <div class="col-md-6 col-lg-4">
                                        <a href="{{ route('frontend.tuyen-dung.show', $tk->slug) }}"
                                            class="card border-0 shadow-sm rounded-4 p-3 h-100 text-decoration-none"
                                            style="transition:all .3s"
                                            onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(0,0,0,.1)'"
                                            onmouseout="this.style.transform='';this.style.boxShadow=''">
                                            @if ($tk->tag)
                                                <span class="badge {{ $tk->tag_class }} mb-2"
                                                    style="font-size:.65rem;align-self:flex-start">{{ $tk->tag }}</span>
                                            @endif
                                            <h6 class="fw-bold mb-2" style="color:#0F172A;font-size:.88rem">
                                                {{ $tk->tieu_de }}</h6>
                                            <div style="font-size:.75rem;color:#6b7280">
                                                <span><i class="fas fa-briefcase me-1"
                                                        style="color:#FF8C42"></i>{{ $tk->hinh_thuc_label }}</span>
                                            </div>
                                            <div class="mt-auto pt-2" style="font-size:.82rem;font-weight:700;color:#FF8C42">
                                                {{ $tk->muc_luong ?? 'Thỏa thuận' }}
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- CỘT PHẢI: SIDEBAR --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top:100px">

                        {{-- Card Ứng tuyển --}}
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="p-4" style="background:linear-gradient(135deg,#0F172A,#1A2948)">
                                <div class="text-center text-white">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                                        style="width:56px;height:56px;background:linear-gradient(135deg,#FF8C42,#FF5722)">
                                        <i class="fas fa-paper-plane fs-4"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Bạn phù hợp?</h6>
                                    <p class="small opacity-75 mb-0">Gửi hồ sơ ngay hôm nay!</p>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <form id="sidebarFormUngTuyen" method="POST"
                                    action="{{ route('frontend.tuyen-dung.ung-tuyen') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="tin_tuyen_dung_id" value="{{ $tin->id }}">
                                    <div class="mb-3">
                                        <input type="text" name="ho_ten" class="form-control rounded-3 bg-light border-0 py-2"
                                            required placeholder="Họ và tên *"
                                            value="{{ auth('customer')->user()?->ho_ten ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control rounded-3 bg-light border-0 py-2"
                                            required placeholder="Email *"
                                            value="{{ auth('customer')->user()?->email ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="so_dien_thoai"
                                            class="form-control rounded-3 bg-light border-0 py-2" required
                                            placeholder="Số điện thoại *"
                                            value="{{ auth('customer')->user()?->so_dien_thoai ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="url" name="link_cv" class="form-control rounded-3 bg-light border-0 py-2"
                                            placeholder="Link CV (Google Drive, TopCV...)">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" name="file_cv"
                                            class="form-control rounded-3 bg-light border-0 py-2"
                                            accept=".pdf,.doc,.docx">
                                        <div class="form-text">PDF, DOC, DOCX — tối đa 5MB</div>
                                    </div>
                                    <button type="submit" id="sidebarBtnSubmit"
                                        class="btn w-100 rounded-pill py-3 text-white fw-bold"
                                        style="background:#FF8C42">
                                        <span class="btn-text">Nộp Hồ Sơ <i class="fas fa-paper-plane ms-1"></i></span>
                                        <span class="btn-spin" style="display:none">
                                            <span class="spinner-border spinner-border-sm me-1"></span>Đang gửi...
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Thông tin liên hệ --}}
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h6 class="fw-bold mb-3" style="color:#0F172A">
                                <i class="fas fa-headset me-2" style="color:#FF8C42"></i>Liên hệ Nhân sự
                            </h6>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-flex align-items-center justify-content-center rounded-3"
                                    style="width:40px;height:40px;background:#fff5ef;flex-shrink:0">
                                    <i class="fas fa-phone" style="color:#FF8C42"></i>
                                </div>
                                <div>
                                    <div style="font-size:.72rem;color:#6b7280">Hotline tuyển dụng</div>
                                    <a href="tel:0336123130" class="fw-bold text-dark text-decoration-none"
                                        style="font-size:.92rem">0336 123 130</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-3"
                                    style="width:40px;height:40px;background:#eff6ff;flex-shrink:0">
                                    <i class="fas fa-envelope" style="color:#0068FF"></i>
                                </div>
                                <div>
                                    <div style="font-size:.72rem;color:#6b7280">Email tuyển dụng</div>
                                    <a href="mailto:tuyendung@thanhcongland.com"
                                        class="fw-bold text-dark text-decoration-none"
                                        style="font-size:.88rem">tuyendung@thanhcongland.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('sidebarFormUngTuyen');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('sidebarBtnSubmit');
                btn.disabled = true;
                btn.querySelector('.btn-text').style.display = 'none';
                btn.querySelector('.btn-spin').style.display = 'inline';

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        },
                        body: new FormData(form),
                    })
                    .then(r => r.json())
                    .then(data => {
                        form.reset();
                        const toast = document.createElement('div');
                        toast.style.cssText =
                            'position:fixed;top:20px;right:20px;z-index:99999;display:flex;align-items:center;gap:12px;background:#fff;border-radius:14px;padding:16px 24px;box-shadow:0 10px 40px rgba(0,0,0,.15);border-left:4px solid #10b981;transform:translateX(120%);transition:transform .4s;max-width:420px';
                        toast.innerHTML =
                            `<div style="font-size:1.6rem;color:#10b981;flex-shrink:0"><i class="fas fa-check-circle"></i></div><div><div style="font-weight:800;font-size:.92rem;color:#0F172A">Gửi hồ sơ thành công!</div><div style="font-size:.8rem;color:#6b7280">${data.message || 'Nhân sự sẽ liên hệ trong 24h.'}</div></div>`;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.style.transform = 'translateX(0)', 50);
                        setTimeout(() => {
                            toast.style.transform = 'translateX(120%)';
                            setTimeout(() => toast.remove(), 400);
                        }, 5000);
                    })
                    .catch(() => alert('Có lỗi xảy ra, vui lòng thử lại!'))
                    .finally(() => {
                        btn.disabled = false;
                        btn.querySelector('.btn-text').style.display = 'inline';
                        btn.querySelector('.btn-spin').style.display = 'none';
                    });
            });
        });
    </script>
@endpush
