@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản Lý Ký Gửi (Nguồn Hàng)</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Ngày gửi</th>
                            <th>Chủ nhà</th>
                            <th>BĐS Ký gửi</th>
                            <th>Ảnh</th>
                            <th>Trạng thái</th>
                            <th width="200">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ky_guis as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $item->ho_ten_chu_nha }}</strong><br>
                                <span class="text-primary">{{ $item->so_dien_thoai }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $item->loai_hinh }}</span><br>
                                {{ $item->dia_chi }}<br>
                                <strong>Giá: {{ number_format($item->gia_mong_muon, 2) }} tỷ</strong> - {{ $item->dien_tich }}m2
                            </td>
                            <td>
                                @if($item->hinh_anh_tham_khao)
                                    <a href="{{ asset($item->hinh_anh_tham_khao) }}" target="_blank">
                                        <img src="{{ asset($item->hinh_anh_tham_khao) }}" width="60" class="rounded">
                                    </a>
                                @else
                                    <span class="text-muted small">Không có</span>
                                @endif
                            </td>
                            <td>
                                @if($item->trang_thai == 'cho_duyet')
                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                @elseif($item->trang_thai == 'da_lien_he')
                                    <span class="badge bg-primary">Đã liên hệ</span>
                                @elseif($item->trang_thai == 'da_nhan')
                                    <span class="badge bg-success">Đã nhận về kho</span>
                                @else
                                    <span class="badge bg-secondary">Từ chối</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.ky-gui.update_status', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm mb-1">
                                        <select name="trang_thai" class="form-control">
                                            <option value="da_lien_he" {{ $item->trang_thai=='da_lien_he'?'selected':'' }}>Đã LH</option>
                                            <option value="da_nhan" {{ $item->trang_thai=='da_nhan'?'selected':'' }}>Đã Nhận</option>
                                            <option value="tu_choi" {{ $item->trang_thai=='tu_choi'?'selected':'' }}>Từ chối</option>
                                        </select>
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i></button>
                                    </div>
                                    <textarea name="phan_hoi_cua_admin" class="form-control form-control-sm" placeholder="Ghi chú nội bộ..." rows="1">{{ $item->phan_hoi_cua_admin }}</textarea>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $ky_guis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection