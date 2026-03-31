@extends('admin.layouts.master')
@section('title', 'Chat Tư vấn trực tuyến')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-comments text-info"></i> Tư vấn Khách hàng</h1>
            <p class="page-header-sub mb-0">Hỗ trợ trực tuyến và Quản lý tương tác</p>
        </div>
    </div>

    {{-- GIAO DIỆN CHAT 3 CỘT (Dùng Flexbox để lấp đầy chiều cao) --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="height: calc(100vh - 140px); min-height: 500px;">
        <div class="row g-0 h-100">

            {{-- ════ CỘT 1: DANH SÁCH PHIÊN CHAT (Bên Trái) ════ --}}
            <div class="col-12 col-md-4 col-lg-3 border-end d-flex flex-column bg-white h-100">
                {{-- Header + Tìm kiếm --}}
                <div class="p-3 border-bottom bg-light bg-opacity-50">
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 shadow-none"
                            placeholder="Tìm tên, SĐT...">
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-pill cursor-pointer px-3 py-2">Tất cả</span>
                        <span class="badge bg-light text-dark border rounded-pill cursor-pointer px-3 py-2">Chưa đọc <span
                                class="text-danger fw-bold ms-1">2</span></span>
                    </div>
                </div>

                {{-- Danh sách chat (Scrollable) --}}
                <div class="chat-list flex-grow-1 overflow-auto">
                    {{-- Ví dụ Chat Item 1 (Đang chọn) --}}
                    <a href="javascript:void(0)"
                        class="chat-list-item active d-flex align-items-center p-3 border-bottom text-decoration-none">
                        <div class="position-relative me-3 flex-shrink-0">
                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                style="width: 45px; height: 45px;">
                                T
                            </div>
                            <span
                                class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">Trần Văn B</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">10:25</small>
                            </div>
                            <div class="text-muted text-truncate" style="font-size: 0.8rem;">Bạn: Vâng, để em gửi thông
                                tin...</div>
                        </div>
                    </a>

                    {{-- Ví dụ Chat Item 2 (Có tin nhắn chưa đọc) --}}
                    <a href="javascript:void(0)"
                        class="chat-list-item d-flex align-items-center p-3 border-bottom text-decoration-none">
                        <div class="position-relative me-3 flex-shrink-0">
                            <img src="{{ asset('images/default-avatar.png') }}" class="rounded-circle object-fit-cover"
                                style="width: 45px; height: 45px;" alt="">
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">Lê Thị C</h6>
                                <small class="text-primary fw-bold" style="font-size: 0.7rem;">Hôm qua</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-dark fw-bold text-truncate" style="font-size: 0.8rem;">Dự án này còn căn
                                    góc không em?</div>
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">1</span>
                            </div>
                        </div>
                    </a>

                    {{-- Ví dụ Chat Item 3 (Khách vãng lai) --}}
                    <a href="javascript:void(0)"
                        class="chat-list-item d-flex align-items-center p-3 border-bottom text-decoration-none">
                        <div class="position-relative me-3 flex-shrink-0">
                            <div class="avatar bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                style="width: 45px; height: 45px;">
                                <i class="fas fa-user-secret"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">Khách vãng lai
                                    #8912</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">T2</small>
                            </div>
                            <div class="text-muted text-truncate" style="font-size: 0.8rem;">Cho mình hỏi giá căn hộ</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ════ CỘT 2: KHUNG CHAT CHÍNH (Ở Giữa) ════ --}}
            <div class="col-12 col-md-8 col-lg-6 d-flex flex-column h-100 position-relative">

                {{-- Chat Header --}}
                <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center shadow-sm z-1">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                            style="width: 40px; height: 40px;">T</div>
                        <div>
                            <h5 class="mb-0 fw-bold text-navy" style="font-size: 1rem;">Trần Văn B</h5>
                            <span class="text-success" style="font-size: 0.75rem;"><i class="fas fa-circle"
                                    style="font-size: 0.5rem; vertical-align: middle;"></i> Đang hoạt động</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light text-primary border rounded-circle" data-bs-toggle="tooltip"
                            title="Gọi điện"><i class="fas fa-phone-alt"></i></button>
                        <button class="btn btn-light text-warning border rounded-circle" data-bs-toggle="tooltip"
                            title="Gắn sao"><i class="fas fa-star"></i></button>
                    </div>
                </div>

                {{-- Nơi chứa tin nhắn (Bong bóng chat) --}}
                <div class="chat-messages flex-grow-1 p-4 overflow-auto bg-light"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ced4da\' fill-opacity=\'0.15\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">

                    {{-- Dấu thời gian --}}
                    <div class="text-center mb-4">
                        <span class="badge bg-secondary bg-opacity-10 text-muted fw-normal px-3 py-1">10:15 Hôm nay</span>
                    </div>

                    {{-- Nhận (Khách hàng) --}}
                    <div class="d-flex align-items-start mb-4">
                        <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-2"
                            style="width: 35px; height: 35px; font-size: 0.8rem;">T</div>
                        <div>
                            <div class="chat-bubble chat-bubble-received">
                                Chào admin, mình đang quan tâm đến dự án Vinhomes Smart City. Bên mình còn căn 2 phòng ngủ
                                nào view hồ không ạ?
                            </div>
                            <div class="chat-time text-muted mt-1" style="font-size: 0.7rem; margin-left: 5px;">10:16</div>
                        </div>
                    </div>

                    {{-- Gửi (Admin/Nhân viên) --}}
                    <div class="d-flex align-items-start flex-row-reverse mb-4">
                        <div>
                            <div class="chat-bubble chat-bubble-sent shadow-sm">
                                Chào anh Trần Văn B, cảm ơn anh đã quan tâm. Hiện tại dự án bên em đang có 2 quỹ căn 2PN
                                view hồ cực đẹp ở phân khu Tonkin ạ.
                            </div>
                            <div class="chat-time text-muted mt-1 text-end" style="font-size: 0.7rem; margin-right: 5px;">
                                10:18 <i class="fas fa-check-double text-primary ms-1"></i></div>
                        </div>
                    </div>

                    {{-- Nhận (Khách gửi kèm ảnh BĐS đang xem) --}}
                    <div class="d-flex align-items-start mb-4">
                        <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-2"
                            style="width: 35px; height: 35px; font-size: 0.8rem;">T</div>
                        <div>
                            <div class="chat-bubble chat-bubble-received p-2">
                                <div class="d-flex align-items-center bg-white rounded p-2 border mb-1">
                                    <img src="{{ asset('images/default-bds.jpg') }}"
                                        style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-2">
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.8rem;">Căn hộ 2PN Tonkin</div>
                                        <div class="text-danger fw-bold" style="font-size: 0.75rem;">3.2 tỷ</div>
                                    </div>
                                </div>
                                Mình xem trên web thấy căn này, có thể gửi thông tin chi tiết cho mình qua Zalo số
                                0901234567 được không?
                            </div>
                            <div class="chat-time text-muted mt-1" style="font-size: 0.7rem; margin-left: 5px;">10:20
                            </div>
                        </div>
                    </div>

                    {{-- Gửi (Admin trả lời) --}}
                    <div class="d-flex align-items-start flex-row-reverse mb-4">
                        <div>
                            <div class="chat-bubble chat-bubble-sent shadow-sm">
                                Vâng, để em gửi thông tin mặt bằng và chính sách qua Zalo cho anh ngay nhé!
                            </div>
                            <div class="chat-time text-muted mt-1 text-end" style="font-size: 0.7rem; margin-right: 5px;">
                                10:25 <i class="fas fa-check text-muted ms-1"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Khung nhập tin nhắn --}}
                <div class="p-3 bg-white border-top z-1">
                    <form id="chatForm">
                        <div class="d-flex align-items-end gap-2">
                            <button type="button" class="btn btn-light text-secondary rounded-circle flex-shrink-0"><i
                                    class="fas fa-image"></i></button>
                            <button type="button" class="btn btn-light text-secondary rounded-circle flex-shrink-0"><i
                                    class="fas fa-paperclip"></i></button>
                            <textarea class="form-control bg-light border-0 shadow-none" rows="1" placeholder="Nhập tin nhắn..."
                                style="resize: none; border-radius: 20px; padding: 10px 15px;"></textarea>
                            <button type="submit" class="btn btn-primary rounded-circle flex-shrink-0"
                                style="width: 40px; height: 40px;"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ════ CỘT 3: THÔNG TIN KHÁCH HÀNG / CRM (Bên Phải) ════ --}}
            <div class="col-lg-3 d-none d-lg-flex flex-column border-start bg-white h-100">
                <div class="p-3 border-bottom bg-light bg-opacity-50 text-center">
                    <h6 class="mb-0 fw-bold text-navy">Thông tin Khách hàng</h6>
                </div>

                <div class="p-4 flex-grow-1 overflow-auto">
                    {{-- Avatar to ở giữa --}}
                    <div class="text-center mb-4">
                        <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-2"
                            style="width: 80px; height: 80px; font-size: 2rem;">T</div>
                        <h5 class="fw-bold text-dark mb-0">Trần Văn B</h5>
                        <span class="badge bg-warning text-dark mt-1"><i class="fas fa-fire me-1"></i>Tiềm năng
                            Nóng</span>
                    </div>

                    {{-- Box liên hệ --}}
                    <div class="card border-0 bg-light rounded-3 mb-4 p-3 shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone-alt text-success me-3" style="width: 15px;"></i>
                            <span class="fw-bold">0901 234 567</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-secondary me-3" style="width: 15px;"></i>
                            <span>tranvanb@gmail.com</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tag text-info me-3" style="width: 15px;"></i>
                            <span>Nhu cầu: <strong class="text-info">Mua Căn hộ</strong></span>
                        </div>
                    </div>

                    {{-- Thao tác nhanh CRM --}}
                    <h6 class="fw-bold text-muted mb-3" style="font-size: 0.8rem; text-transform: uppercase;">Thao tác
                        nhanh CRM</h6>

                    <a href="#"
                        class="btn btn-outline-primary w-100 mb-2 text-start d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-id-card me-2"></i>Xem hồ sơ CRM</span> <i class="fas fa-chevron-right"
                            style="font-size: 0.7rem;"></i>
                    </a>

                    <a href="#"
                        class="btn btn-outline-danger w-100 mb-2 text-start d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar-plus me-2"></i>Tạo lịch hẹn xem nhà</span> <i
                            class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                    </a>

                    <div class="mt-4">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Ghi chú nội bộ</label>
                        <textarea class="form-control bg-light" rows="3" placeholder="Thêm ghi chú nhanh..."
                            style="font-size: 0.85rem;">Khách quan tâm phân khu Tonkin, có khả năng chốt trong tháng này.</textarea>
                        <button class="btn btn-sm btn-navy mt-2 w-100">Lưu ghi chú</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- CSS ĐỘC QUYỀN CHO MODULE CHAT --}}
    <style>
        /* Tinh chỉnh danh sách */
        .chat-list-item {
            transition: 0.2s ease;
            cursor: pointer;
        }

        .chat-list-item:hover {
            background-color: #f8f9fa;
        }

        .chat-list-item.active {
            background-color: #e8f4fd;
            border-left: 3px solid var(--bs-primary);
        }

        /* Bong bóng chat (Giống Zalo/Messenger) */
        .chat-bubble {
            max-width: 85%;
            padding: 10px 15px;
            font-size: 0.9rem;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .chat-bubble-received {
            background-color: #ffffff;
            color: #1a1a1a;
            border: 1px solid #e0e0e0;
            border-radius: 15px 15px 15px 4px;
            /* Vuông góc dưới trái */
        }

        .chat-bubble-sent {
            background-color: var(--bs-primary);
            color: #ffffff;
            border-radius: 15px 15px 4px 15px;
            /* Vuông góc dưới phải */
        }

        /* Tùy chỉnh thanh cuộn cho đẹp */
        .chat-list::-webkit-scrollbar,
        .chat-messages::-webkit-scrollbar {
            width: 5px;
        }

        .chat-list::-webkit-scrollbar-thumb,
        .chat-messages::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Khởi tạo Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto cuộn xuống cuối khung chat
            const chatMessages = document.querySelector('.chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Xử lý auto-resize cho textarea nhập tin nhắn
            const tx = document.querySelector('textarea');
            tx.setAttribute('style', 'height:' + (tx.scrollHeight) +
                'px; overflow-y:hidden; resize:none; border-radius:20px; padding:10px 15px;');
            tx.addEventListener("input", OnInput, false);

            function OnInput() {
                this.style.height = 'auto';
                let newHeight = this.scrollHeight;
                if (newHeight > 100) newHeight = 100; // Max height
                this.style.height = newHeight + 'px';
                if (newHeight === 100) this.style.overflowY = 'auto';
            }
        });
    </script>
@endpush
