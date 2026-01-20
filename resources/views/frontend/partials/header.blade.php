<nav class="navbar navbar-expand-lg fixed-top p-0">
    <div class="container" style="    max-width: 100%;padding: 5px 30px">
        <a class="navbar-brand fw-bold text-uppercase fs-4 serif-font" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; margin-right: 10px;">

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('du-an.*') ? 'active' : '' }}" href="{{ route('du-an.index') }}">Dự án</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a></li>
                <li class="nav-item">
    <a class="nav-link  {{ request()->routeIs('ky-gui.*') ? 'active' : '' }}" href="{{ route('ky-gui.create') }}">Ký Gửi Căn Hộ</a>
</li>
                <li class="nav-item"><a class="nav-link" href="#">Hàng Thuê</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Hàng Bán</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('bai-viet.*') ? 'active' : '' }}" href="{{ route('bai-viet.index') }}">Tin Tức</a></li>

                <li class="nav-item"><a class="nav-link" href="#">Tuyển Dụng</a></li>


                
<li class="nav-item">
    <a class="nav-link" href="{{ route('yeu-thich.index') }}" title="Tin đã lưu">
        <i class="fas fa-heart text-danger"></i> <span class="d-lg-none">Đã lưu</span>
    </a>
</li>

            </ul>
        </div>
    </div>
</nav>