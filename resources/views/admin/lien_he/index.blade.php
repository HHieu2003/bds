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
                    @foreach($lien_hes as $lh)
                    <tr>
                        <td>{{ $lh->created_at->format('H:i d/m/Y') }}</td>
                        <td>
                            <div class="fw-bold text-danger">{{ $lh->so_dien_thoai }}</div>
                        </td>
                        <td>
                            <a href="{{ route('bat-dong-san.show', $lh->batDongSan->id) }}" target="_blank" class="text-decoration-none">
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

                        <td>
    <select onchange="updateCRMStatus({{ $lh->id }}, this.value)" 
            class="form-control form-control-sm 
            {{ $lh->trang_thai == 'moi' ? 'border-danger text-danger' : 
               ($lh->trang_thai == 'da_chot' ? 'border-success text-success' : 'border-primary text-primary') }}">
        <option value="moi" {{ $lh->trang_thai=='moi'?'selected':'' }}>🔥 Mới tiếp nhận</option>
        <option value="dang_tu_van" {{ $lh->trang_thai=='dang_tu_van'?'selected':'' }}>📞 Đang tư vấn</option>
        <option value="da_xem" {{ $lh->trang_thai=='da_xem'?'selected':'' }}>👀 Đã dẫn khách xem</option>
        <option value="da_chot" {{ $lh->trang_thai=='da_chot'?'selected':'' }}>💰 Đã chốt cọc</option>
        <option value="khong_nhu_cau" {{ $lh->trang_thai=='khong_nhu_cau'?'selected':'' }}>❌ Không nhu cầu</option>
    </select>
</td>

<script>
function updateCRMStatus(id, status) {
    fetch(`/admin/lien-he/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ trang_thai: status })
    })
    .then(res => res.json())
    .then(data => {
        alert('Đã cập nhật tiến độ khách hàng!');
        // Có thể đổi màu ô select ngay tại đây nếu muốn đẹp hơn
    });
}
</script>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection