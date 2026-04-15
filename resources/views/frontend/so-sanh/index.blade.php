@extends('frontend.layouts.master')

@section('title', 'So Sánh Bất Động Sản - Thành Công Land')

@section('content')
    <section class="ss-page-hero pt-4 pb-3 border-bottom">
        <div class="container">
            <h2 class="fw-bold serif-font mb-0 ss-page-title">
                <i class="fas fa-balance-scale me-2"></i>Bảng So Sánh Bất Động Sản
            </h2>
        </div>
    </section>

    <section class="py-5 ss-page-wrap">
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
                <div class="ss-page-summary mb-3">
                    <div class="ss-page-summary-item">
                        <i class="fas fa-circle-check"></i>
                        So sánh tối đa 3 bất động sản cùng tệp khách hàng
                    </div>
                    <div class="ss-page-summary-item">
                        <i class="fas fa-circle-info"></i>
                        Dữ liệu hiển thị theo trạng thái đăng hiện tại
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    @include('frontend.so-sanh._table', ['danhSachBds' => $danhSachBds])
                </div>
            @endif

        </div>
    </section>

    <style>
        .ss-page-hero {
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }

        .ss-page-title {
            color: #0f172a;
        }

        .ss-page-title i {
            color: var(--primary);
        }

        .ss-page-wrap {
            min-height: 60vh;
            background-color: #f8fafc;
        }

        .ss-page-summary {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .ss-page-summary-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
        }

        .ss-page-summary-item i {
            color: var(--primary);
            margin-right: 6px;
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #cbd5e1 !important;
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
