<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm p-1" style="transition: all 0.3s;">
    <div class="container mx-4" style="max-width: 100%" >
        <a class="navbar-brand fw-bold text-uppercase serif-font d-flex align-items-center" href="{{ route('home') }}">
            {{-- Thay đổi kích thước logo cho phù hợp --}}
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; margin-right: 12px;">
            
        </a>

        <button class="navbar-toggler border-0 focus-ring" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center fw-semibold">
                
                {{-- Trang chủ --}}
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active text-primary' : '' }}" href="{{ route('home') }}">
                        Trang chủ
                    </a>
                </li>

                {{-- Giới thiệu --}}
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('about') ? 'active text-primary' : '' }}" href="{{ route('about') }}">
                        Giới thiệu
                    </a>
                </li>

                {{-- DỰ ÁN (Dropdown theo yêu cầu) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link px-3 dropdown-toggle {{ request()->routeIs('du-an.*') ? 'active text-primary' : '' }}" 
                       href="{{ route('du-an.index') }}" 
                       id="duAnDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dự Án
                    </a>
                    <ul class="dropdown-menu border-0 shadow mt-2" aria-labelledby="duAnDropdown">
                        {{-- Mục nổi bật --}}
                        <li>
                            <a class="dropdown-item fw-bold text-primary py-2" href="{{ route('du-an.index') }}?khu_vuc=smart-city">
                                <i class="fas fa-city me-2"></i>Vinhomes Smart City
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        {{-- Các khu vực khác --}}
                        <li><a class="dropdown-item py-2" href="{{ route('du-an.index') }}?khu_vuc=khac">Khu vực Tây Mỗ</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('du-an.index') }}?khu_vuc=khac">Khu vực Đại Mỗ</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('du-an.index') }}">Xem tất cả dự án</a></li>
                    </ul>
                </li>

                {{-- Mua Bán (Tên mới thay cho Hàng Bán) --}}
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('tim-kiem') }}?loai=ban">
                        Mua Bán
                    </a>
                </li>

                {{-- Cho Thuê (Tên mới thay cho Hàng Thuê) --}}
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('tim-kiem') }}?loai=thue">
                        Cho Thuê
                    </a>
                </li>

                {{-- Tin tức --}}
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('bai-viet.*') ? 'active text-primary' : '' }}" href="{{ route('bai-viet.index') }}">
                        Tin Tức
                    </a>
                </li>

                {{-- NÚT KÝ GỬI (Nổi bật) --}}
                <li class="nav-item ms-lg-2 my-2 my-lg-0">
                    <a class="btn btn-primary text-white rounded-pill px-4 btn-sm shadow-sm hover-up {{ request()->routeIs('ky-gui.*') ? 'active' : '' }}" 
                       href="{{ route('ky-gui.create') }}">
                        <i class="fas fa-plus-circle me-1"></i> Ký Gửi
                    </a>
                </li>

                {{-- KHU VỰC TÀI KHOẢN & YÊU THÍCH --}}
                <li class="nav-item ms-lg-3 border-start ps-lg-3 d-flex align-items-center mt-3 mt-lg-0">
                    
                    {{-- Nút Yêu thích --}}
                    <a class="nav-link position-relative me-3" href="{{ route('yeu-thich.index') }}" title="Tin đã lưu">
                        <i class="far fa-heart fs-5"></i>
                    </a>

                    {{-- Logic Auth Customer --}}
                    @if(Auth::guard('customer')->check())
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center p-0 text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold border" style="width: 36px; height: 36px;">
                                    {{ substr(Auth::guard('customer')->user()->ho_ten ?? 'U', 0, 1) }}
                                </div>
                                <span class="ms-2 d-none d-lg-block small fw-bold">{{ Auth::guard('customer')->user()->ho_ten }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('yeu-thich.index') }}"><i class="fas fa-heart me-2 text-danger"></i>Tin đã lưu</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="#" class="fw-bold text-decoration-none small text-primary" data-bs-toggle="modal" data-bs-target="#modalQuickLogin">
                            Đăng nhập
                        </a>
                    @endif
                </li>

            </ul>
        </div>
    </div>
</nav>

{{-- Spacer để nội dung không bị Header che mất --}}
<div style="height: 76px;"></div>

<style>
    /* CSS nhỏ cho Header */
    .navbar-nav .nav-link { font-size: 0.95rem; color: #4B5563; transition: color 0.2s; }
    .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #0d6efd; } /* Màu primary */
    .hover-up:hover { transform: translateY(-2px); transition: transform 0.2s; }
    
    /* Dropdown hover trên Desktop (Optional - nếu muốn rê chuột là hiện) */
    @media (min-width: 992px) {
        .nav-item.dropdown:hover .dropdown-menu { display: block; margin-top: 0; animation: fadeIn 0.3s; }
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>