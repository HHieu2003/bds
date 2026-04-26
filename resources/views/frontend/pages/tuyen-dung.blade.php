@extends('frontend.layouts.master')

@section('title', 'Tuyển Dụng Nhân Tài - Thành Công Land')
@section('meta_description', 'Gia nhập Thành Công Land - Môi trường làm việc chuyên nghiệp, hoa hồng hấp dẫn nhất thị
    trường bất động sản. Khám phá các vị trí đang tuyển dụng.')

@section('content')

    {{-- 1. HERO SECTION --}}
    <section class="position-relative py-5"
        style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute inset-0 w-100 h-100"
            style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; opacity: 0.15;">
        </div>
        <div class="container position-relative z-1 text-center">
            <span class="badge px-3 py-2 rounded-pill mb-3"
                style="background-color: rgba(255, 140, 66, 0.2); color: #FF8C42; border: 1px solid #FF8C42;">Cơ hội nghề
                nghiệp {{ date('Y') }}</span>
            <h1 class="display-4 fw-bold text-white mb-3 serif-font">Kiến Tạo Sự Nghiệp <br> <span style="color: #FF8C42;">Bất
                    Động Sản Đỉnh Cao</span></h1>
            <p class="text-white-50 fs-5 mx-auto mb-4" style="max-width: 700px;">
                Tại Thành Công Land, chúng tôi không chỉ cung cấp cho bạn một công việc, chúng tôi trao cho bạn cơ hội làm
                chủ tài chính và phát triển bản thân không giới hạn.
            </p>
            <a href="#danh-sach-viec-lam" class="btn rounded-pill px-5 py-3 fw-bold text-white shadow hover-up"
                style="background-color: #FF8C42;">
                Xem Vị Trí Tuyển Dụng <i class="fas fa-arrow-down ms-2"></i>
            </a>
        </div>
    </section>

    {{-- 2. TẠI SAO CHỌN CHÚNG TÔI (CORE VALUES) --}}
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold serif-font text-dark mb-2">Đặc Quyền Của Bạn</h2>
                <div class="mx-auto bg-primary rounded"
                    style="width: 60px; height: 4px; background-color: #FF8C42 !important;"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #fff5ef; color: #FF8C42;">
                            <i class="fas fa-money-bill-wave fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Thu Nhập Đột Phá</h5>
                        <p class="text-muted small mb-0">Lương cứng hấp dẫn + Hoa hồng lũy tiến cao nhất thị trường lên đến
                            60%. Thưởng nóng từng giao dịch.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #eff6ff; color: #0068FF;">
                            <i class="fas fa-chalkboard-teacher fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Đào Tạo Chuyên Sâu</h5>
                        <p class="text-muted small mb-0">Được coaching trực tiếp bởi các Giám đốc dự án dày dặn kinh nghiệm.
                            Đào tạo Digital Marketing, kỹ năng chốt sale.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #ecfdf5; color: #10B981;">
                            <i class="fas fa-database fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Nguồn Hàng Độc Quyền</h5>
                        <p class="text-muted small mb-0">Sở hữu giỏ hàng độc quyền, quỹ căn đẹp nhất từ các chủ đầu tư lớn:
                            Vinhomes, Masterise, Sun Group...</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #f5f3ff; color: #8B5CF6;">
                            <i class="fas fa-chart-line fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Lộ Trình Thăng Tiến</h5>
                        <p class="text-muted small mb-0">Cơ hội thăng tiến rõ ràng sau 6 tháng lên Trưởng nhóm, Giám đốc
                            sàn. Phát triển năng lực quản lý toàn diện.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. DANH SÁCH VỊ TRÍ TUYỂN DỤNG (DYNAMIC) --}}
    <section id="danh-sach-viec-lam" class="py-5">
        <div class="container py-4">
            <div class="row align-items-center mb-5">
                <div class="col-md-8">
                    <h2 class="fw-bold serif-font text-dark mb-2">Vị Trí Đang Mở</h2>
                    <p class="text-muted mb-0">Hãy chọn vị trí phù hợp với thế mạnh của bạn và gia nhập đội ngũ tinh nhuệ
                        của chúng tôi.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge rounded-pill px-3 py-2 fw-bold"
                        style="background:#0F172A;color:#fff;font-size:.82rem">
                        <i class="fas fa-briefcase me-1"></i> {{ $tinTuyenDungs->count() }} vị trí đang tuyển
                    </span>
                </div>
            </div>

            <div class="row g-4">
                @forelse($tinTuyenDungs as $tin)
                    <div class="col-12">
                        <div class="card border border-light shadow-sm rounded-4 hover-up bg-white overflow-hidden p-0">
                            <div class="row g-0 align-items-stretch">
                                <div class="col-md-8 p-4 p-md-5 border-end">
                                    @if ($tin->tag)
                                        <span
                                            class="badge {{ $tin->tag_class }} px-3 py-2 rounded-pill mb-3 fw-semibold">{{ $tin->tag }}</span>
                                    @endif
                                    <h3 class="fw-bold text-dark mb-2">
                                        <a href="{{ route('frontend.tuyen-dung.show', $tin->slug) }}"
                                            class="text-dark text-decoration-none td-title-link">
                                            {{ $tin->tieu_de }}
                                        </a>
                                    </h3>

                                    <div class="d-flex flex-wrap gap-3 mb-3" style="font-size:.82rem;color:#6b7280">
                                        <span><i class="fas fa-briefcase me-1"
                                                style="color:#FF8C42"></i>{{ $tin->hinh_thuc_label }}</span>
                                        @if ($tin->dia_diem)
                                            <span><i class="fas fa-map-marker-alt me-1"
                                                    style="color:#FF8C42"></i>{{ $tin->dia_diem }}</span>
                                        @endif
                                        @if ($tin->phong_ban)
                                            <span><i class="fas fa-sitemap me-1"
                                                    style="color:#FF8C42"></i>{{ $tin->phong_ban }}</span>
                                        @endif
                                        <span><i class="fas fa-users me-1" style="color:#FF8C42"></i>Cần
                                            {{ $tin->so_luong }} người</span>
                                    </div>

                                    @if ($tin->mo_ta_ngan)
                                        <p class="text-muted mb-0" style="line-height:1.7;font-size:.9rem">
                                            {{ Str::limit($tin->mo_ta_ngan, 200) }}
                                        </p>
                                    @elseif($tin->mo_ta_cong_viec)
                                        <p class="text-muted mb-0" style="line-height:1.7;font-size:.9rem">
                                            {{ Str::limit(strip_tags($tin->mo_ta_cong_viec), 200) }}
                                        </p>
                                    @endif
                                </div>
                                <div
                                    class="col-md-4 p-4 text-center bg-light d-flex flex-column justify-content-center align-items-center">
                                    <h5 class="fw-bold mb-1" style="color: #FF8C42;">
                                        {{ $tin->muc_luong ?? 'Thỏa thuận' }}</h5>
                                    @if ($tin->han_nop && $tin->con_han)
                                        <p class="text-muted small mb-3">Hạn nộp:
                                            {{ $tin->han_nop->format('d/m/Y') }}</p>
                                    @else
                                        <p class="text-muted small mb-3">Tuyển liên tục</p>
                                    @endif
                                    <button class="btn w-100 rounded-pill text-white fw-bold py-3 shadow-sm btn-ung-tuyen"
                                        style="background-color: #0F172A;" data-tin-id="{{ $tin->id }}"
                                        data-tin-title="{{ $tin->tieu_de }}">
                                        Ứng Tuyển Ngay
                                    </button>
                                    <a href="{{ route('frontend.tuyen-dung.show', $tin->slug) }}"
                                        class="btn btn-link text-muted small mt-2 text-decoration-none td-detail-link">
                                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-3x mb-3" style="color:#d1d5db"></i>
                            <h5 class="text-muted">Hiện tại chưa có vị trí nào đang mở tuyển</h5>
                            <p class="text-muted small">Vui lòng quay lại sau hoặc gửi CV trực tiếp qua email.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- MODAL ỨNG TUYỂN --}}
    <div class="modal fade" id="modalUngTuyen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom-0 p-4 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold text-dark fs-4">Gửi Hồ Sơ Ứng Tuyển</h5>
                        <p class="text-muted small mb-0 mt-1" id="modalViTri"></p>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Để lại thông tin, Bộ phận Nhân sự sẽ liên hệ với bạn trong vòng 24h
                        làm việc.</p>

                    <form id="formUngTuyen" method="POST"
                        action="{{ route('frontend.tuyen-dung.ung-tuyen') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tin_tuyen_dung_id" id="inputTinId">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Họ và tên *</label>
                                <input type="text" name="ho_ten"
                                    class="form-control rounded-3 bg-light border-0 py-2 shadow-none" required
                                    placeholder="Nguyễn Văn A"
                                    value="{{ auth('customer')->user()?->ho_ten ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Email *</label>
                                <input type="email" name="email"
                                    class="form-control rounded-3 bg-light border-0 py-2 shadow-none" required
                                    placeholder="email@example.com"
                                    value="{{ auth('customer')->user()?->email ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Số điện thoại *</label>
                                <input type="text" name="so_dien_thoai"
                                    class="form-control rounded-3 bg-light border-0 py-2 shadow-none" required
                                    placeholder="09xx..."
                                    value="{{ auth('customer')->user()?->so_dien_thoai ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Năm sinh</label>
                                <input type="number" name="nam_sinh"
                                    class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                    placeholder="199x" min="1970" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Link CV (Google Drive,
                                    TopCV...)</label>
                                <input type="url" name="link_cv"
                                    class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                    placeholder="Dán đường link CV vào đây...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Hoặc tải file CV (PDF, DOC - tối
                                    đa 5MB)</label>
                                <input type="file" name="file_cv"
                                    class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                    accept=".pdf,.doc,.docx">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Giới thiệu bản thân</label>
                                <textarea name="gioi_thieu" class="form-control rounded-3 bg-light border-0 py-2 shadow-none" rows="3"
                                    placeholder="Hãy cho chúng tôi biết thêm về bạn..."></textarea>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmitUngTuyen"
                            class="btn w-100 rounded-pill py-3 text-white fw-bold shadow-sm mt-4"
                            style="background-color: #FF8C42;">
                            <span id="btnText">Nộp Hồ Sơ Ngay <i class="fas fa-paper-plane ms-2"></i></span>
                            <span id="btnLoading" style="display:none">
                                <span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS TÙY CHỈNH --}}
    <style>
        .hover-up {
            transition: all 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
        }

        .btn-ung-tuyen {
            transition: 0.3s;
        }

        .btn-ung-tuyen:hover {
            background-color: #FF8C42 !important;
            color: white !important;
            transform: translateY(-3px);
        }

        .td-title-link:hover {
            color: #FF8C42 !important;
        }

        .td-detail-link:hover {
            color: #FF8C42 !important;
        }
    </style>

    {{-- JS MODAL + AJAX SUBMIT --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const myModal = new bootstrap.Modal(document.getElementById('modalUngTuyen'));

                // Mở modal khi bấm nút ứng tuyển
                document.querySelectorAll('.btn-ung-tuyen').forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.getElementById('inputTinId').value = btn.dataset.tinId;
                        document.getElementById('modalViTri').textContent =
                            'Vị trí: ' + btn.dataset.tinTitle;
                        myModal.show();
                    });
                });

                // AJAX submit form
                const form = document.getElementById('formUngTuyen');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const btn = document.getElementById('btnSubmitUngTuyen');
                    const btnText = document.getElementById('btnText');
                    const btnLoading = document.getElementById('btnLoading');

                    btn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'inline';

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')?.content,
                            },
                            body: formData,
                        })
                        .then(r => r.json())
                        .then(data => {
                            myModal.hide();
                            form.reset();

                            // Hiện thông báo thành công
                            const toast = document.createElement('div');
                            toast.className = 'td-toast-success';
                            toast.innerHTML = `
                                <div class="td-toast-icon"><i class="fas fa-check-circle"></i></div>
                                <div class="td-toast-content">
                                    <div class="td-toast-title">Gửi hồ sơ thành công!</div>
                                    <div class="td-toast-msg">${data.message || 'Bộ phận Nhân sự sẽ liên hệ trong 24h.'}</div>
                                </div>
                            `;
                            document.body.appendChild(toast);
                            setTimeout(() => toast.classList.add('show'), 50);
                            setTimeout(() => {
                                toast.classList.remove('show');
                                setTimeout(() => toast.remove(), 400);
                            }, 5000);
                        })
                        .catch(err => {
                            alert('Có lỗi xảy ra, vui lòng thử lại!');
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btnText.style.display = 'inline';
                            btnLoading.style.display = 'none';
                        });
                });
            });
        </script>

        <style>
            .td-toast-success {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 99999;
                display: flex;
                align-items: center;
                gap: 12px;
                background: #fff;
                border-radius: 14px;
                padding: 16px 24px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
                border-left: 4px solid #10b981;
                transform: translateX(120%);
                transition: transform .4s cubic-bezier(.4, 0, .2, 1);
                max-width: 420px;
            }

            .td-toast-success.show {
                transform: translateX(0);
            }

            .td-toast-icon {
                font-size: 1.6rem;
                color: #10b981;
                flex-shrink: 0;
            }

            .td-toast-title {
                font-weight: 800;
                font-size: .92rem;
                color: #0F172A;
            }

            .td-toast-msg {
                font-size: .8rem;
                color: #6b7280;
                line-height: 1.5;
            }
        </style>
    @endpush

@endsection
