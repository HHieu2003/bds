@extends('admin.layout.master')

@section('content')
    <h2 class="text-primary fw-bold mb-4">📞 Danh Sách Khách Hàng (Leads)</h2>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Ngày nhận</th>
                        <th>Khách hàng</th>
                        <th>Căn hộ quan tâm</th>
                        <th>Lời nhắn</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dsLienHe as $lh)
                    <tr>
                        <td>{{ $lh->created_at->format('H:i d/m/Y') }}</td>
                        <td>
                            <div class="fw-bold text-danger">{{ $lh->so_dien_thoai }}</div>
                        </td>
                        <td>
                            <a href="{{ route('home.show', $lh->batDongSan->slug) }}" target="_blank" class="text-decoration-none">
                                <span class="badge bg-info text-dark">{{ $lh->batDongSan->ma_can }}</span>
                                <small class="d-block text-muted">{{ $lh->batDongSan->duAn->ten_du_an }}</small>
                            </a>
                        </td>
                        <td class="text-muted fst-italic">"{{ Str::limit($lh->loi_nhan, 50) }}"</td>
                        <td>
                            @if($lh->trang_thai == 'chua_xu_ly')
                                <span class="badge bg-warning text-dark">Mới</span>
                            @else
                                <span class="badge bg-success">Đã xử lý</span>
                            @endif
                        </td>
                        <td>
                            <a href="tel:{{ $lh->so_dien_thoai }}" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-phone"></i> Gọi ngay
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection