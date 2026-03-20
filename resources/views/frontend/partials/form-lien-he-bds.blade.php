{{-- Dùng @include('frontend.partials.form-lien-he-bds', ['bds' => $batDongSan]) --}}
<div
    style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,.08);padding:24px;border:1.5px solid #f0f2f5">
    <div style="font-size:1rem;font-weight:800;color:#1a3c5e;margin-bottom:16px;display:flex;align-items:center;gap:8px">
        <i class="fas fa-headset" style="color:#FF8C42"></i>
        Đăng ký xem nhà / tư vấn
    </div>

    <div id="lhBdsSuccess"
        style="display:none;background:#e8f8f0;border-radius:10px;padding:16px;text-align:center;margin-bottom:16px">
        <i class="fas fa-check-circle" style="color:#27ae60;font-size:1.4rem;display:block;margin-bottom:6px"></i>
        <div style="font-weight:700;color:#27ae60;font-size:.9rem">Gửi thành công!</div>
        <div style="font-size:.78rem;color:#555;margin-top:2px">Chuyên viên sẽ liên hệ sớm nhất.</div>
    </div>

    <form id="lhBdsForm">
        @csrf
        <input type="hidden" name="bat_dong_san_id" value="{{ $bds->id ?? '' }}">
        <input type="hidden" name="muc_do_quan_tam" value="quan_tam">

        <div style="margin-bottom:10px">
            <input type="text" name="ho_ten" value="{{ auth('customer')->user()?->ho_ten ?? '' }}"
                placeholder="Họ và tên *" required
                style="width:100%;padding:10px 13px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;box-sizing:border-box">
        </div>
        <div style="margin-bottom:10px">
            <input type="tel" name="so_dien_thoai" value="{{ auth('customer')->user()?->so_dien_thoai ?? '' }}"
                placeholder="Số điện thoại *" required
                style="width:100%;padding:10px 13px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;box-sizing:border-box">
        </div>
        <div style="margin-bottom:14px">
            <textarea name="noi_dung" rows="3" placeholder="Nội dung muốn tư vấn..."
                style="width:100%;padding:10px 13px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;box-sizing:border-box;resize:none"></textarea>
        </div>

        <button type="submit" id="lhBdsBtn"
            style="width:100%;background:linear-gradient(135deg,#FF8C42,#f5a623);color:#fff;border:none;padding:12px;border-radius:10px;font-weight:700;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 12px rgba(255,140,66,.3)">
            <i class="fas fa-paper-plane"></i> Gửi yêu cầu tư vấn
        </button>

        <div id="lhBdsError" style="display:none;margin-top:8px;font-size:.78rem;color:#e74c3c;text-align:center"></div>
    </form>
</div>

<script>
    document.getElementById('lhBdsForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('lhBdsBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';

        const formData = new FormData(this);

        try {
            const res = await fetch('{{ route('frontend.lien-he.store') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                document.getElementById('lhBdsSuccess').style.display = 'block';
                this.style.display = 'none';
            } else {
                document.getElementById('lhBdsError').style.display = 'block';
                document.getElementById('lhBdsError').textContent = 'Có lỗi xảy ra, vui lòng thử lại.';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi yêu cầu tư vấn';
            }
        } catch (err) {
            document.getElementById('lhBdsError').style.display = 'block';
            document.getElementById('lhBdsError').textContent = 'Lỗi kết nối, vui lòng thử lại.';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi yêu cầu tư vấn';
        }
    });
</script>
