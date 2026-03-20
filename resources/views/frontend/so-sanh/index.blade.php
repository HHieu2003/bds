@extends('frontend.layouts.master')

@section('title', 'So Sánh Bất Động Sản - Thành Công Land')

@section('content')
    <section class="bg-light pt-4 pb-3 border-bottom">
        <div class="container">
            <h2 class="fw-bold serif-font mb-0" style="color: #0F172A;">
                <i class="fas fa-balance-scale me-2" style="color: #FF8C42;"></i>Bảng So Sánh Bất Động Sản
            </h2>
        </div>
    </section>

    <section class="py-5" style="min-height: 60vh; background-color: #F8FAFC;">
        <div class="container">

            @if ($danhSachBds->count() == 0)
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Trống"
                        style="width: 100px; opacity: 0.3;" class="mb-3">
                    <h5 class="text-muted fw-bold">Chưa có bất động sản nào để so sánh!</h5>
                    <p class="text-muted small">Bạn hãy tìm kiếm BĐS và bấm nút "Thêm vào so sánh" để xem đối chiếu tại đây
                        nhé.</p>
                    <a href="{{ route('frontend.bat-dong-san.index') }}"
                        class="btn rounded-pill px-4 mt-2 text-white fw-bold" style="background-color: #0F172A;">
                        <i class="fas fa-search me-1"></i> Tìm BĐS Ngay
                    </a>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center mb-0" style="min-width: 800px;">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 15%; background-color: #f1f5f9; color: #64748B;"
                                        class="text-start p-4 fs-5 serif-font">Tiêu chí</th>

                                    @foreach ($danhSachBds as $bds)
                                        <th style="width: {{ 85 / $danhSachBds->count() }}%; padding: 1.5rem 1rem;">
                                            <div class="position-relative">
                                                {{-- Nút Xóa Khỏi So Sánh (Reload lại trang) --}}
                                                <button class="btn btn-sm btn-danger rounded-circle position-absolute"
                                                    style="top: -10px; right: -10px; z-index: 10;"
                                                    onclick="xoaVaTaiLai({{ $bds->id }})" title="Xóa khỏi bảng">
                                                    <i class="fas fa-times"></i>
                                                </button>

                                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                    class="d-block overflow-hidden rounded-3 mb-3 bg-light"
                                                    style="height: 160px;">
                                                    @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                                        class="w-100 h-100" style="object-fit: cover;">
                                                </a>

                                                <h6 class="fw-bold mb-2 text-dark line-clamp-2">{{ $bds->tieu_de }}</h6>
                                                <h5 class="fw-bold mb-0" style="color: #FF8C42;">
                                                    {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h5>
                                            </div>
                                        </th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Nhu cầu</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3"><span
                                                class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-danger' : 'bg-success' }} px-3 py-2 rounded-pill">{{ $bds->nhu_cau == 'ban' ? 'Mua Bán' : 'Cho Thuê' }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Khu vực</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3 fw-semibold">{{ $bds->khuVuc->ten_khu_vuc ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Dự án</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3 text-primary fw-bold">{{ $bds->duAn->ten_du_an ?? 'BĐS Tự Do' }}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Diện tích</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3 fw-bold fs-5">{{ $bds->dien_tich }} m²</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Phòng ngủ / Tắm</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3">{{ $bds->so_phong_ngu }} PN <span
                                                class="text-muted mx-1">|</span> {{ $bds->so_phong_tam }} WC</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Hướng nhà</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3">{{ $bds->huong ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light">Pháp lý</td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-3">{{ $bds->phap_ly ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold text-start text-muted p-3 bg-light"></td>
                                    @foreach ($danhSachBds as $bds)
                                        <td class="p-4">
                                            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                class="btn btn-outline-dark rounded-pill fw-bold w-100">
                                                Xem Chi Tiết
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #cbd5e1 !important;
        }

        table th,
        table td {
            vertical-align: middle;
            border-color: #e2e8f0;
        }
    </style>

    @push('scripts')
        <script>
            // Hàm tiện ích: Khi xóa trong bảng so sánh, tải lại trang để mất cột đó đi
            function xoaVaTaiLai(id) {
                removeSoSanh(id); // Gọi hàm xóa localStorage có sẵn ở master.blade.php

                // Lấy lại danh sách mới và render lại URL
                var ids = soSanhList.map(function(item) {
                    return item.id;
                }).join(',');
                if (ids === '') {
                    window.location.href = APP.routes.soSanhIndex;
                } else {
                    window.location.href = APP.routes.soSanhIndex + '?ids=' + ids;
                }
            }
        </script>
    @endpush
@endsection
