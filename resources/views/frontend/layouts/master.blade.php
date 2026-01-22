<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thành Công Land')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0F172A;      /* Xanh Navy Đậm */
            --accent: #B99044;       /* Vàng Gold */
            --light-bg: #F8FAFC;
            --text-gray: #64748B;
        }
        body { font-family: 'Manrope', sans-serif; color: #334155; background-color: var(--light-bg); overflow-x: hidden; }
        h1, h2, h3, h4, .serif-font { font-family: 'Playfair Display', serif; }
        
        /* Navbar Effect */
        .navbar { transition: 0.4s; padding: 15px 0; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .nav-link { font-weight: 600; color: var(--primary) !important; font-size: 0.95rem; margin: 0 10px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: var(--accent) !important; }
    </style>
    
    @stack('styles')
</head>
<body>

    @include('frontend.partials.header')

    <main style="padding-top: 76px; min-height: 80vh;">
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    @include('frontend.partials.chat_widget')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, easing: 'ease-out-cubic', once: true, offset: 50 });
    </script>
    
    @stack('scripts')



    @if(Auth::guard('customer')->check() && !Auth::guard('customer')->user()->isVerified())
    <div class="alert alert-warning text-center mb-0 p-2 fixed-top w-100" style="z-index: 9999; top: 0;">
        Xin chào <b>{{ Auth::guard('customer')->user()->ho_ten }}</b>! 
        Bạn đang dùng SĐT/Email: <b>{{ Auth::guard('customer')->user()->getContactInfo() }}</b> (Chưa xác thực). 
        <a href="#" onclick="openVerifyModal()" class="fw-bold text-dark text-decoration-underline">Xác thực ngay</a> 
        để bảo vệ tài khoản.
    </div>
    <style>body { padding-top: 50px; }</style>
@endif

<div class="modal fade" id="modalQuickLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin liên hệ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Nhập thông tin để Chat hoặc Lưu tin ngay (Không cần mật khẩu).</p>
                <div class="mb-3">
                    <label class="form-label">Email hoặc Số điện thoại (*)</label>
                    <input type="text" id="ql-contact" class="form-control" placeholder="vd: 0912xxx hoặc abc@gmail.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên của bạn</label>
                    <input type="text" id="ql-name" class="form-control" placeholder="Nhập tên để xưng hô">
                </div>
                <button class="btn btn-primary w-100" onclick="submitQuickLogin()">Hoàn tất & Tiếp tục</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerifyOtp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác thực tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Hệ thống sẽ gửi mã OTP đến: <b>{{ Auth::guard('customer')->user()?->getContactInfo() }}</b></p>
                
                <div id="verify-step-1">
                    <button class="btn btn-primary" onclick="sendVerifyOtp()">Gửi mã OTP</button>
                </div>

                <div id="verify-step-2" style="display:none;" class="mt-3">
                    <input type="text" id="verify-otp-input" class="form-control mb-2 text-center" placeholder="Nhập mã 6 số">
                    <button class="btn btn-success" onclick="confirmVerifyOtp()">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Biến lưu hành động đang dở dang (để thực hiện tiếp sau khi login xong)
    let pendingAction = null; 

    // --- LOGIC 1: QUICK LOGIN (ĐĂNG NHẬP NHANH) ---
    function submitQuickLogin() {
        let contact = $('#ql-contact').val();
        let name = $('#ql-name').val();

        if(!contact) { alert('Vui lòng nhập Email hoặc SĐT'); return; }

        $.post("{{ route('customer.quick_login') }}", {
            contact: contact,
            name: name,
            _token: '{{ csrf_token() }}'
        }, function(res) {
            if(res.status === 'success') {
                // Ẩn modal login
                bootstrap.Modal.getInstance(document.getElementById('modalQuickLogin')).hide();
                
                // Kiểm tra xem user đang làm gì dở dang thì làm tiếp
                if (pendingAction && pendingAction.type === 'favorite') {
                    toggleFavorite(pendingAction.id); // Gọi lại hàm like
                    alert('Đăng nhập thành công! Hãy xác thực SĐT trên thanh menu khi rảnh.');
                    location.reload(); // Reload để hiện thanh Header Warning
                } else if (pendingAction && pendingAction.type === 'chat') {
                    // Nếu là chat, reload lại trang để Widget chat tự nhận diện user mới
                    location.reload(); 
                } else {
                    location.reload();
                }
            }
        }).fail(function(err) {
            alert('Lỗi: ' + err.responseJSON.message);
        });
    }

    // --- LOGIC 2: YÊU THÍCH (Gắn vào nút Tim) ---
    function toggleFavorite(id) {
        $.ajax({
            url: '/yeu-thich/toggle/' + id,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.status === 'require_login') {
                    // Server báo chưa login -> Lưu hành động lại -> Hiện popup
                    pendingAction = { type: 'favorite', id: id };
                    new bootstrap.Modal(document.getElementById('modalQuickLogin')).show();
                } else if (res.status === 'added') {
                    alert('Đã lưu tin!');
                    // Code đổi màu icon tim ở đây (Frontend của bạn)
                    $('.heart-icon-' + id).addClass('active');
                } else if (res.status === 'removed') {
                    alert('Đã bỏ lưu!');
                    $('.heart-icon-' + id).removeClass('active');
                }
            }
        });
    }

    // --- LOGIC 3: CHAT (Gắn vào nút Chat ngay) ---
    function checkChatLogin() {
        // Gọi thử API start chat để xem đã login chưa
        $.post("{{ route('chat.start') }}", {
            _token: '{{ csrf_token() }}'
        }, function(res) {
            if (res.status === 'require_login') {
                pendingAction = { type: 'chat' };
                new bootstrap.Modal(document.getElementById('modalQuickLogin')).show();
            } else if (res.status === 'success') {
                // Nếu đã login -> Mở widget chat lên
                // Giả sử hàm mở chat của bạn là openChatWidget()
                if(typeof openChatWidget === 'function') openChatWidget(res);
            }
        });
    }

    // --- LOGIC 4: XÁC THỰC SAU (Verify Later) ---
    function openVerifyModal() {
        new bootstrap.Modal(document.getElementById('modalVerifyOtp')).show();
    }

    function sendVerifyOtp() {
        $.post("{{ route('customer.send_otp') }}", {
            _token: '{{ csrf_token() }}'
        }, function(res) {
            alert(res.message);
            $('#verify-step-1').hide();
            $('#verify-step-2').show();
        });
    }

    function confirmVerifyOtp() {
        $.post("{{ route('customer.confirm_otp') }}", {
            otp: $('#verify-otp-input').val(),
            _token: '{{ csrf_token() }}'
        }, function(res) {
            alert(res.message);
            if(res.status === 'success') location.reload(); // Ẩn thanh warning đi
        }).fail(function(err) {
            alert('Lỗi: ' + err.responseJSON.message);
        });
    }
</script>
</body>
</html>