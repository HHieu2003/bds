@extends('admin.layouts.master')

@section('title', 'Trung Tâm Hỗ Trợ Khách Hàng')

@section('content')
    <div class="chat-container-wrapper shadow-sm bg-white rounded-4 border overflow-hidden">
        <div class="row g-0 h-100">

            {{-- ==========================================
             CỘT TRÁI: DANH SÁCH KHÁCH HÀNG (30%)
        ========================================== --}}
            <div class="col-12 col-md-4 col-lg-3 d-flex flex-column border-end h-100 bg-light-gray">

                {{-- Header Sidebar --}}
                <div class="p-3 bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fab fa-facebook-messenger text-primary me-2"></i>Tin nhắn
                        </h5>
                        <span
                            class="badge bg-danger rounded-pill px-2 py-1">{{ $phienChats->where('trang_thai', 'dang_cho')->count() }}
                            chờ</span>
                    </div>
                    {{-- Thanh tìm kiếm (UI) --}}
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control bg-light border-start-0 shadow-none"
                            placeholder="Tìm tên khách hàng...">
                    </div>
                </div>

                {{-- Danh sách Chat --}}
                <div class="flex-grow-1 overflow-auto p-2 chat-sidebar-scroll">
                    @forelse($phienChats as $pc)
                        @php
                            $isActive = isset($activeChat) && $activeChat->id == $pc->id;
                            $lastMsg = $pc->tinNhan->last();
                            // Tạo màu ngẫu nhiên cho Avatar dựa trên ID
                            $colors = ['#FF8C42', '#0068FF', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444'];
                            $avatarColor = $colors[$pc->khach_hang_id % count($colors)];
                        @endphp

                        <a href="{{ route('nhanvien.admin.chat.show', $pc->id) }}"
                            class="text-decoration-none d-block p-3 mb-2 rounded-3 chat-list-item {{ $isActive ? 'active shadow-sm' : 'bg-white border-bottom' }}">

                            <div class="d-flex align-items-center">
                                {{-- Avatar --}}
                                <div class="position-relative me-3">
                                    <div class="avatar-circle text-white fw-bold d-flex align-items-center justify-content-center"
                                        style="width: 45px; height: 45px; border-radius: 50%; background-color: {{ $avatarColor }}; font-size: 1.2rem;">
                                        {{ mb_strtoupper(mb_substr($pc->khachHang->ho_ten ?? 'K', 0, 1)) }}
                                    </div>
                                    @if ($pc->trang_thai == 'dang_cho')
                                        <span
                                            class="position-absolute bottom-0 end-0 p-1 bg-danger border border-light rounded-circle"
                                            title="Đang chờ hỗ trợ"></span>
                                    @else
                                        <span
                                            class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"
                                            title="Đang hỗ trợ"></span>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 text-truncate {{ $isActive ? 'text-primary' : 'text-dark' }}"
                                            style="font-size: 0.95rem;">
                                            {{ $pc->khachHang->ho_ten ?? 'Khách Ẩn Danh' }}
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $pc->updated_at->format('H:i') }}
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-truncate text-muted" style="max-width: 80%; font-size: 0.85rem;">
                                            {{ $lastMsg ? $lastMsg->noi_dung : 'Bắt đầu trò chuyện...' }}
                                        </small>
                                        @if ($pc->chua_doc > 0)
                                            <span class="badge bg-danger rounded-pill"
                                                style="font-size: 0.7rem;">{{ $pc->chua_doc }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-muted mt-5 pt-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png"
                                style="width: 60px; opacity: 0.2;" class="mb-3">
                            <p class="small">Hộp thư trống.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ==========================================
             CỘT PHẢI: KHUNG CHAT CHI TIẾT (70%)
        ========================================== --}}
            <div class="col-12 col-md-8 col-lg-9 d-flex flex-column h-100 bg-chat-pattern position-relative">

                @if (isset($activeChat))
                    {{-- HEADER KHUNG CHAT CHI TIẾT --}}
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white shadow-sm z-1">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle text-white fw-bold d-flex align-items-center justify-content-center me-3"
                                style="width: 45px; height: 45px; border-radius: 50%; background-color: #0068FF; font-size: 1.2rem;">
                                {{ mb_strtoupper(mb_substr($activeChat->khachHang->ho_ten ?? 'K', 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">{{ $activeChat->khachHang->ho_ten ?? 'Khách Ẩn Danh' }}
                                </h5>
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <small class="text-muted"><i class="fas fa-phone-alt me-1 text-success"></i>
                                        {{ $activeChat->khachHang->so_dien_thoai ?? 'Chưa có SĐT' }}</small>
                                    <span
                                        class="badge {{ $activeChat->trang_thai == 'dang_chat' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border-0 px-2 py-1"
                                        style="font-size: 0.7rem;">
                                        <i class="fas fa-circle ms-0 me-1"
                                            style="font-size: 0.4rem; vertical-align: middle;"></i>
                                        {{ $activeChat->trang_thai == 'dang_chat' ? 'Đang hỗ trợ' : 'Đang chờ' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('nhanvien.admin.khach-hang.show', $activeChat->khach_hang_id ?? 0) }}"
                                target="_blank" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                                <i class="fas fa-id-card me-1"></i> Xem Hồ Sơ Khách
                            </a>
                        </div>
                    </div>

                    {{-- NỘI DUNG CHAT CHÍNH (AJAX LOAD VÀO ĐÂY) --}}
                    <div class="flex-grow-1 p-4 overflow-auto chat-box-admin d-flex flex-column" id="adminChatBody">
                        {{-- JS sẽ đổ HTML vào khu vực này --}}
                    </div>

                    {{-- FORM NHẬP TIN NHẮN --}}
                    <div class="p-3 bg-white border-top">
                        <form onsubmit="sendAdminMessage(event)">
                            <input type="hidden" id="adminPhienChatId" value="{{ $activeChat->id }}">
                            <div class="input-group input-group-lg bg-light rounded-pill p-1 border">
                                <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i
                                        class="far fa-smile"></i></span>
                                <input type="text" id="adminChatInput"
                                    class="form-control border-0 bg-transparent shadow-none px-2 fs-6"
                                    placeholder="Nhập câu trả lời của bạn..." autocomplete="off" autofocus>
                                <button type="submit" class="btn text-white rounded-pill px-4 fw-bold"
                                    style="background-color: #0068FF;">
                                    <i class="fas fa-paper-plane me-1"></i> Gửi
                                </button>
                            </div>
                        </form>
                        <div class="text-center mt-2">
                            <small class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-lock me-1"></i>Tin nhắn
                                được mã hóa nội bộ Thành Công Land</small>
                        </div>
                    </div>
                @else
                    {{-- MÀN HÌNH TRỐNG KHI CHƯA CHỌN KHÁCH HÀNG NÀO --}}
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 bg-white">
                        <img src="https://cdn-icons-png.flaticon.com/512/3068/3068340.png"
                            style="width: 150px; opacity: 0.5" class="mb-4">
                        <h4 class="fw-bold text-dark">Trung Tâm Trợ Giúp</h4>
                        <p class="text-muted text-center" style="max-width: 400px;">
                            Vui lòng chọn một cuộc hội thoại từ danh sách bên trái để bắt đầu tư vấn và hỗ trợ khách hàng
                            chốt sale.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ==========================================
     CSS ĐỘC QUYỀN CHO GIAO DIỆN CHAT ZALO 
========================================== --}}
    <style>
        /* Bọc toàn cục để căn chỉnh Layout */
        .chat-container-wrapper {
            height: calc(100vh - 100px);
            min-height: 600px;
        }

        .bg-light-gray {
            background-color: #f8fafc !important;
        }

        .bg-chat-pattern {
            background-color: #e5ecef;
        }

        /* Màu nền giống Zalo */

        /* Custom Scrollbar gọn gàng */
        .chat-sidebar-scroll::-webkit-scrollbar,
        .chat-box-admin::-webkit-scrollbar {
            width: 5px;
        }

        .chat-sidebar-scroll::-webkit-scrollbar-track,
        .chat-box-admin::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-sidebar-scroll::-webkit-scrollbar-thumb,
        .chat-box-admin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .chat-sidebar-scroll:hover::-webkit-scrollbar-thumb,
        .chat-box-admin:hover::-webkit-scrollbar-thumb {
            background: #94a3b8;
        }

        /* Hiệu ứng danh sách Sidebar */
        .chat-list-item {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .chat-list-item:hover {
            background-color: #f1f5f9 !important;
            cursor: pointer;
        }

        .chat-list-item.active {
            background-color: #ffffff !important;
            border-left: 3px solid #0068FF;
        }

        /* Bong bóng tin nhắn */
        .chat-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
            width: 100%;
        }

        .msg-bubble {
            max-width: 65%;
            padding: 10px 16px;
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
            word-wrap: break-word;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* System: Thông báo ở giữa */
        .msg-system {
            background-color: rgba(0, 0, 0, 0.05);
            color: #475569;
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
            align-self: center;
            text-align: center;
            max-width: 80%;
            box-shadow: none;
        }

        /* NhanVien (Sale): Xanh lam, bo góc phải dưới, nằm bên phải */
        .msg-sale {
            background-color: #0068FF;
            color: white;
            border-radius: 18px 18px 4px 18px;
            align-self: flex-end;
        }

        /* KhachHang: Trắng, bo góc trái dưới, nằm bên trái */
        .msg-customer {
            background-color: white;
            color: #0f172a;
            border-radius: 18px 18px 18px 4px;
            align-self: flex-start;
        }

        /* Thời gian tin nhắn */
        .msg-time {
            font-size: 0.7rem;
            margin-top: 4px;
            opacity: 0.7;
        }

        .msg-sale .msg-time {
            text-align: right;
            color: rgba(255, 255, 255, 0.8);
        }

        .msg-customer .msg-time {
            text-align: left;
            color: #94a3b8;
        }
    </style>

    {{-- ==========================================
     JAVASCRIPT XỬ LÝ CHAT THỜI GIAN THỰC
========================================== --}}
    @if (isset($activeChat))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const phienChatId = document.getElementById('adminPhienChatId').value;
                const chatBody = document.getElementById('adminChatBody');
                const inputField = document.getElementById('adminChatInput');

                // URL API
                const safeBaseUrl = '{{ url('/') }}'.replace(/\/$/, '');
                const apiUrl = safeBaseUrl + '/nhan-vien/admin/chat/' + phienChatId;

                // Trạng thái cờ để tránh giật cuộn chuột khi load lại nếu không có tin mới
                let lastMessageCount = 0;

                // 1. Load lần đầu ngay lập tức
                fetchAdminMessages();

                // 2. Bật Polling chạy ngầm mỗi 3 giây
                const chatPolling = setInterval(fetchAdminMessages, 3000);

                // Hàm gọi API lấy tin nhắn
                function fetchAdminMessages() {
                    fetch(apiUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                renderAdminMessages(data.tin_nhans);
                            }
                        })
                        .catch(err => console.error("Lỗi lấy dữ liệu chat:", err));
                }

                // Hàm render giao diện tin nhắn
                function renderAdminMessages(messages) {
                    if (!messages) return;

                    // Nếu không có tin nhắn mới thì không render lại để tránh giật chuột
                    if (messages.length === lastMessageCount) return;
                    lastMessageCount = messages.length;

                    chatBody.innerHTML = '';

                    messages.forEach(msg => {
                        let msgClass = '';
                        let alignmentClass = '';
                        let timeHtml = '';

                        // Định dạng thời gian (ví dụ: 14:30)
                        let dateObj = new Date(msg.created_at);
                        let timeString = ("0" + dateObj.getHours()).slice(-2) + ":" + ("0" + dateObj
                        .getMinutes()).slice(-2);

                        // Xác định đối tượng gửi
                        if (msg.nguoi_gui === 'hethong') {
                            msgClass = 'msg-system';
                            timeHtml = ''; // Tin hệ thống ko cần hiện giờ
                        } else if (msg.nguoi_gui === 'nhanvien') {
                            msgClass = 'msg-sale';
                            timeHtml = `<div class="msg-time">${timeString}</div>`;
                        } else {
                            // Khách hàng
                            msgClass = 'msg-customer';
                            timeHtml = `<div class="msg-time">${timeString}</div>`;
                        }

                        chatBody.innerHTML += `
                    <div class="chat-row">
                        <div class="msg-bubble ${msgClass}">
                            ${msg.noi_dung}
                            ${timeHtml}
                        </div>
                    </div>
                `;
                    });

                    // Tự động cuộn xuống tận cùng
                    scrollToBottom();
                }

                function scrollToBottom() {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }

                // Bắt sự kiện gửi form (Biến hàm này thành Global để thẻ form gọi được)
                window.sendAdminMessage = function(e) {
                    e.preventDefault();
                    const noiDung = inputField.value.trim();
                    if (!noiDung) return;

                    // Xóa ô nhập ngay lập tức tạo cảm giác nhanh chóng
                    inputField.value = '';

                    // (Tùy chọn) In tạm bong bóng ảo ra màn hình trước khi gửi xong
                    const now = new Date();
                    const timeString = ("0" + now.getHours()).slice(-2) + ":" + ("0" + now.getMinutes()).slice(-2);
                    chatBody.innerHTML += `
                <div class="chat-row">
                    <div class="msg-bubble msg-sale" style="opacity: 0.7;">
                        ${noiDung}
                        <div class="msg-time">${timeString} <i class="fas fa-clock ms-1"></i></div>
                    </div>
                </div>
            `;
                    scrollToBottom();

                    // Gửi API lên server
                    fetch(apiUrl + '/tra-loi', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                noi_dung: noiDung
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                fetchAdminMessages(); // Lấy lại list chuẩn từ CSDL
                            }
                        });
                };
            });
        </script>
    @endif
@endsection
