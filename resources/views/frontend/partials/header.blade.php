<nav class="navbar navbar-expand-lg fixed-top p-0">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase fs-4 serif-font" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; margin-right: 10px;">

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('project.*') ? 'active' : '' }}" href="{{ route('project.index') }}">Dự án</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Mua bán</a></li>
                <li class="nav-item ms-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold btn-sm">Đăng nhập</a>
                </li>
            </ul>
        </div>
    </div>
</nav>