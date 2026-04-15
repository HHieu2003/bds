{{-- MODAL ĐỔI GIỜ --}}
@if (in_array($lh->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']))
    <div class="modal fade" id="modalBaoLai{{ $lh->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.bao-lai-gio', $lh->id) }}" method="POST"
                class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                @csrf @method('PATCH')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-history me-2"></i> Báo lại giờ cho Sale
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border border-warning text-dark small mb-4 shadow-sm">
                        Chủ nhà đi vắng hoặc không tiện tiếp khách vào lúc
                        <strong
                            class="text-danger">{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i d/m/Y') }}</strong>?<br>
                        Hãy điền giờ chủ nhà hẹn lại vào đây.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giờ mới chủ nhà hẹn:</label>
                        <input type="datetime-local" name="thoi_gian_hen" class="form-control form-control-lg fw-bold"
                            value="{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Lý do / Ghi chú cho Sale:</label>
                        <textarea name="ghi_chu_nguon_hang" class="form-control bg-light" rows="3"
                            placeholder="Ví dụ: Chủ đi làm, hẹn lại 4h chiều nay mới có người ở nhà nhé..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-dark fw-bold"><i class="fas fa-paper-plane me-1"></i> Báo
                        Sale</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- MODAL HỦY --}}
@if ($lh->trang_thai != 'cho_xac_nhan')
    <div class="modal fade text-start" id="modalHuyNguon{{ $lh->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST"
                class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                @csrf @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Báo hủy lịch đột
                        xuất</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold">Lý do Hủy (Sale sẽ thấy):</label>
                    <textarea name="ly_do" class="form-control bg-danger bg-opacity-10 border-danger" rows="3"
                        placeholder="VD: Chủ nhà đã chốt bán cho khách khác..." required></textarea>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger fw-bold"><i class="fas fa-trash-alt me-1"></i> Xác nhận
                        Hủy</button>
                </div>
            </form>
        </div>
    </div>
@endif
