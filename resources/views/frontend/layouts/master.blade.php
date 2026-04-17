<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Thành Công Land — Bất động sản Vinhomes Smart City')</title>
    <meta name="description" content="@yield('meta_description', 'Đơn vị phân phối bất động sản uy tín tại Vinhomes Smart City và khu vực phía Tây Hà Nội.')">
    <meta name="keywords" content="@yield('meta_keywords', 'bất động sản, vinhomes smart city, căn hộ hà nội, mua bán căn hộ, cho thuê căn hộ')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Thành Công Land')">
    <meta property="og:description" content="@yield('og_description', 'Bất động sản Vinhomes Smart City')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="Thành Công Land">

    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <meta name="author" content="Thành Công Land">

    @vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
    @stack('styles')

</head>

<body>
    @include('frontend.partials.page-loader')
    @include('frontend.partials.flash-messages')
    @include('frontend.partials.header')

    @hasSection('breadcrumb')
        <div class="breadcrumb-wrap">
            <div class="container-fluid px-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"><i class="fas fa-home"></i></a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>
    @endif

    <main id="main-content">
        @yield('content')
    </main>
    @include('frontend.partials.modal-dang-ky-nhan-tin')
    @include('frontend.partials.footer')
    @include('frontend.partials.contact-panel')

    @include('frontend.partials.chat-widget', [
        'chat_loai_ngu_canh' => $chat_loai_ngu_canh ?? '',
        'chat_ngu_canh_id' => $chat_ngu_canh_id ?? '',
        'chat_ten_ngu_canh' => $chat_ten_ngu_canh ?? '',
    ])


    @include('frontend.partials.auth-modal')
    @include('frontend.partials.so-sanh-bar')
    @include('frontend.partials.modal-khach-hang')
    @include('frontend.partials.modal-quan-ly-dang-ky-nhan-tin')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- CẤU HÌNH ROUTING JS --}}
    <script>
        window.APP = {
            csrfToken: '{{ csrf_token() }}',
            baseUrl: '{{ url('/') }}',
            isLoggedIn: {{ Auth::guard('customer')->check() ? 'true' : 'false' }},
            chatContext: {
                loai: @json($chat_loai_ngu_canh ?? ''),
                id: @json($chat_ngu_canh_id ?? ''),
                ten: @json($chat_ten_ngu_canh ?? '')
            },
            user: @if (Auth::guard('customer')->check())
                {
                    id: {{ Auth::guard('customer')->id() }},
                    name: @json(Auth::guard('customer')->user()->ho_ten)
                }
            @else
                null
            @endif ,
            chatFaq: [{
                    q: 'Lịch làm việc của văn phòng như thế nào?',
                    a: 'Văn phòng làm việc từ 8:00 đến 18:00 các ngày trong tuần, hỗ trợ xem nhà ngoài giờ theo lịch hẹn trước.'
                },
                {
                    q: 'Dự án này hiện còn những loại căn nào?',
                    a: 'Hiện tại còn nhiều lựa chọn theo nhu cầu ở thực và đầu tư. Anh/chị để lại nhu cầu cụ thể, em sẽ gửi giỏ hàng phù hợp ngay.'
                },
                {
                    q: 'Giá căn hộ hiện tại khoảng bao nhiêu?',
                    a: 'Giá phụ thuộc diện tích, tầng, view và nội thất. Em có thể gửi bảng giá mới nhất theo đúng phân khúc anh/chị quan tâm.'
                },
                {
                    q: 'Chi phí sang tên và thuế phí gồm những gì?',
                    a: 'Thông thường gồm thuế, lệ phí trước bạ và phí công chứng theo quy định. Em sẽ hỗ trợ ước tính chi tiết theo từng giao dịch cụ thể.'
                },
                {
                    q: 'Có hỗ trợ vay ngân hàng không?',
                    a: 'Bên em có hỗ trợ kết nối ngân hàng và tư vấn phương án vay phù hợp khả năng tài chính của anh/chị.'
                }
            ],
            routes: {
                login: '{{ route('khach-hang.login') }}',
                loginPost: '{{ route('khach-hang.login.post') }}',
                registerPost: '{{ route('khach-hang.register.post') }}',
                sendOtp: '{{ route('khach-hang.send-otp') }}',
                verifyOtp: '{{ route('khach-hang.verify-otp') }}',
                logout: '{{ route('khach-hang.logout') }}',
                forgotPost: '{{ route('khach-hang.forgot.post') }}',
                resetPost: '{{ route('khach-hang.reset.post') }}',
                profileUpdate: '{{ route('khach-hang.profile.update') }}',
                changePassword: '{{ route('khach-hang.change-password') }}',
                yeuThichToggle: '{{ route('frontend.yeu-thich.toggle', [], false) }}',
                chatKhoiTao: '{{ route('frontend.chat.khoi-tao', [], false) }}',
                chatGui: '{{ route('frontend.chat.gui', [], false) }}',
                chatLichSu: '{{ route('frontend.chat.lich-su', ['id' => '__ID__'], false) }}',
                chatChuyenNV: '{{ route('frontend.chat.chuyen-nv', [], false) }}',
                chatChuyenBot: '{{ route('frontend.chat.chuyen-bot', [], false) }}',
                chatLongPoll: '{{ route('frontend.chat.long-poll', ['id' => '__ID__'], false) }}',
                soSanhIndex: '{{ route('frontend.so-sanh.index') }}',

                soSanhThem: '{{ url('/so-sanh/them') }}',
                soSanhXoa: '{{ url('/so-sanh/xoa') }}',
                soSanhModal: '{{ route('frontend.so-sanh.modal', [], false) }}',
                dangKyNhanTinDestroy: '{{ route('frontend.dang-ky-nhan-tin.destroy', ['id' => '__ID__'], false) }}'
            }
        };
    </script>


    @stack('scripts')
</body>

</html>
