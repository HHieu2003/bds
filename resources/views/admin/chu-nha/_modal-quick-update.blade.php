<div class="modal fade" id="modalQuickUpdate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="quickUpdateForm" class="modal-content border-0 shadow" method="POST" action="#">
            @csrf
            @method('PUT')

            {{-- BIẾN CỜ XÁC NHẬN ĐÂY LÀ HÀNH ĐỘNG CẬP NHẬT NHANH --}}
            <input type="hidden" name="is_quick_update" value="1">

            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-headset text-success me-2"></i>Báo cáo Tương tác /
                    Check-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4">
                    <i class="fas fa-info-circle me-1"></i> Đang thao tác với Chủ nhà: <strong id="qu_chu_nha_name"
                        class="text-primary"></strong><br>
                    <small>Lưu lại sẽ tự động làm mới <span class="fw-bold text-danger">Ngày cập nhật</span> để đẩy chủ
                        nhà này lên trên cùng trong bộ lọc, giúp bạn biết là vừa gọi xong.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Tình trạng / Kết quả cuộc gọi mới nhất là gì?</label>
                    <textarea id="ghi_chu_moi" name="ghi_chu_moi" class="form-control" rows="4"
                        placeholder="Ví dụ: Khách vừa báo giảm giá xuống còn 3 tỷ, hẹn cuối tuần xem nhà..." required></textarea>
                    <div class="form-text text-muted">Hệ thống sẽ tự động ghép ngày giờ hiện tại vào dòng ghi chú này và
                        nối tiếp với các ghi chú cũ của Chủ nhà.</div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-secondary bg-white text-dark border"
                    data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-2"></i>Lưu tương
                    tác</button>
            </div>
        </form>
    </div>
</div>
