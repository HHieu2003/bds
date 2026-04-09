<div class="lead-card" data-id="{{ $lead->id }}" data-status="{{ $lead->trang_thai }}">
    <span class="lead-time"><i class="far fa-clock me-1"></i> {{ $lead->created_at->format('H:i - d/m/Y') }}</span>

    <a href="tel:{{ $lead->so_dien_thoai }}" class="lead-phone" title="Nhấn để gọi"><i
            class="fas fa-phone-alt fs-6 me-1"></i> {{ $lead->so_dien_thoai }}</a>
    <div class="lead-name">{{ $lead->ho_ten ?? 'Khách chưa để lại tên' }}</div>

    <div class="lead-context">
        @if ($lead->batDongSan)
            <strong><i class="fas fa-home text-success"></i> Xem BĐS:</strong> <a
                href="{{ route('frontend.bat-dong-san.show', $lead->batDongSan->slug) }}" target="_blank"
                title="{{ $lead->batDongSan->tieu_de }}">{{ \Illuminate\Support\Str::limit($lead->batDongSan->tieu_de, 30) }}</a>
        @elseif($lead->duAn)
            <strong><i class="fas fa-building text-info"></i> Xem Dự án:</strong> <a
                href="{{ route('frontend.du-an.show', $lead->duAn->slug) }}"
                target="_blank">{{ $lead->duAn->ten_du_an }}</a>
        @else
            <strong><i class="fas fa-globe text-secondary"></i> Trang:</strong> Khác / Trang chủ
        @endif
    </div>

    <div class="lead-msg">"{{ $lead->noi_dung }}"</div>

    <div class="lead-footer">
        <div class="lead-assignee">
            @if ($lead->nhanVienPhuTrach)
                <i class="fas fa-user-circle text-primary"></i> Sale: {{ $lead->nhanVienPhuTrach->ho_ten }}
            @else
                <i class="fas fa-user-times text-warning"></i> Chưa ai nhận
            @endif
        </div>

        <div class="dropdown">
            <button class="btn btn-sm btn-light border py-0 px-2" type="button" data-bs-toggle="dropdown"><i
                    class="fas fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu shadow-sm" style="font-size: 0.85rem;">
                <li><a class="dropdown-item fw-bold text-success" href="https://zalo.me/{{ $lead->so_dien_thoai }}"
                        target="_blank"><i class="fas fa-comment-dots text-success me-2"></i> Chat Zalo ngay</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                {{-- Form Tạo Khách Hàng CRM (Chỉ hiện khi chưa Hoàn thành) --}}
                @if ($lead->trang_thai != 'hoan_thanh')
                    <li>
                        <form action="{{ route('nhanvien.admin.lien-he.convert', $lead->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item fw-bold text-primary"
                                onclick="return confirm('Chuyển Yêu cầu này thành Khách Hàng lưu vào danh bạ CRM?')">
                                <i class="fas fa-user-plus text-primary me-2"></i> Tạo Data Khách CRM
                            </button>
                        </form>
                    </li>
                @endif

                <li>
                    <form action="{{ route('nhanvien.admin.lien-he.destroy', $lead->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger"
                            onclick="return confirm('Xóa vĩnh viễn yêu cầu này?')"><i class="fas fa-trash me-2"></i> Xóa
                            Yêu Cầu</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
