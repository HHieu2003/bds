{{--
    ┌──────────────────────────────────────────────────────────────┐
    │  META SEO COMPONENT                                          │
    │  Tự động render đầy đủ thẻ meta cho SEO + Open Graph         │
    │                                                              │
    │  Sử dụng: @include('frontend.partials.meta-seo', [           │
    │      'seo_title'       => 'Tiêu đề trang',                  │
    │      'seo_description' => 'Mô tả trang',                    │
    │      'seo_image'       => 'https://...',                     │
    │      'seo_keywords'    => 'từ khóa 1, từ khóa 2',           │
    │      'seo_type'        => 'article',  // mặc định: website   │
    │      'seo_canonical'   => 'https://...',                     │
    │  ])                                                          │
    └──────────────────────────────────────────────────────────────┘
--}}

@php
    $siteName = 'Thành Công Land';

    // Tiêu đề: ưu tiên seo_title > fallback
    $_title = !empty($seo_title)
        ? $seo_title
        : 'Thành Công Land — Bất động sản Vinhomes Smart City';

    // Mô tả: ưu tiên seo_description > fallback
    $_description = !empty($seo_description)
        ? Str::limit(strip_tags($seo_description), 160)
        : 'Đơn vị phân phối bất động sản uy tín tại Vinhomes Smart City và khu vực phía Tây Hà Nội.';

    // Hình ảnh OG: ưu tiên seo_image > default
    $_image = !empty($seo_image)
        ? $seo_image
        : asset('images/og-default.jpg');

    // Keywords
    $_keywords = !empty($seo_keywords)
        ? $seo_keywords
        : 'bất động sản, vinhomes smart city, căn hộ hà nội, mua bán căn hộ, cho thuê căn hộ';

    // Loại OG
    $_type = !empty($seo_type) ? $seo_type : 'website';

    // Canonical URL
    $_canonical = !empty($seo_canonical) ? $seo_canonical : url()->current();

    // Robots
    $_robots = !empty($seo_robots) ? $seo_robots : 'index, follow';
@endphp

{{-- ═══ BASIC META ═══ --}}
<title>{{ $_title }}</title>
<meta name="description" content="{{ $_description }}">
<meta name="keywords" content="{{ $_keywords }}">
<meta name="robots" content="{{ $_robots }}">
<link rel="canonical" href="{{ $_canonical }}">
<meta name="author" content="{{ $siteName }}">

{{-- ═══ OPEN GRAPH (Facebook, Zalo, Messenger) ═══ --}}
<meta property="og:type" content="{{ $_type }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $_title }}">
<meta property="og:description" content="{{ $_description }}">
<meta property="og:image" content="{{ $_image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="vi_VN">
<meta property="og:site_name" content="{{ $siteName }}">

{{-- ═══ TWITTER CARD ═══ --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $_title }}">
<meta name="twitter:description" content="{{ $_description }}">
<meta name="twitter:image" content="{{ $_image }}">
