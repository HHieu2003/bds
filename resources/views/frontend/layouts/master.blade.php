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
</body>
</html>