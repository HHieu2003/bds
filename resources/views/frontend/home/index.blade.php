@extends('frontend.layouts.master')
@section('title', 'Trang Chủ - Thành Công Land')

@section('content')

    {{-- 1. Banner & Form Tìm Kiếm --}}
    @include('frontend.home.sections.banner-tim-kiem')

    {{-- 2. Giới Thiệu Công Ty --}}
    @include('frontend.home.sections.gioi-thieu-cong-ty')

    {{-- 3. Carousel Dự Án Nổi Bật --}}
    @include('frontend.home.sections.du-an-noi-bat', ['du_ans' => $du_ans ?? ($duAnNoiBat ?? [])])

    {{-- 4. LƯỚI BẤT ĐỘNG SẢN NỔI BẬT --}}
    @include('frontend.home.sections.bds-noi-bat')

    {{-- 5. TAB NGUỒN HÀNG MỚI (BÁN & THUÊ) --}}
    @include('frontend.home.sections.nguon-hang-moi')

    {{-- 6. BANNER KÊU GỌI KÝ GỬI --}}
    @include('frontend.home.sections.cta-ky-gui')

    {{-- 7. TIN TỨC THỊ TRƯỜNG --}}
    @include('frontend.home.sections.tin-tuc-moi')

    {{-- 8. Logo Các Đối Tác Tin Cậy --}}
    @include('frontend.home.sections.doi-tac-tin-cay')

    @push('scripts')
        <script>
            window.chatContext = {
                type: 'general',
                id: null
            };
        </script>
    @endpush

@endsection
