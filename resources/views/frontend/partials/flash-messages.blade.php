<style>
    /* Khung chứa — cố định góc phải */
    .flash-container {
        position: fixed;
        top: 1.2rem;
        right: 1.2rem;
        z-index: 9998;
        display: flex;
        flex-direction: column;
        gap: .6rem;
        max-width: 360px;
        width: calc(100% - 2.4rem);
        pointer-events: none;
    }

    /* Item chung */
    .flash-item {
        display: flex;
        align-items: flex-start;
        gap: .7rem;
        padding: .9rem 1.1rem;
        border-radius: var(--radius);
        font-size: .85rem;
        font-weight: 500;
        box-shadow: var(--shadow-lg);
        animation: flashIn .3s ease;
        pointer-events: all;
        cursor: default;
    }

    @keyframes flashIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* 4 loại màu */
    .flash-success {
        background: #f0fff4;
        border-left: 4px solid #27ae60;
        color: #276749;
    }

    .flash-error {
        background: #fff5f5;
        border-left: 4px solid #e74c3c;
        color: #c0392b;
    }

    .flash-info {
        background: #eff8ff;
        border-left: 4px solid #2d6a9f;
        color: #1a4971;
    }

    .flash-warning {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }

    .flash-item i {
        flex-shrink: 0;
        margin-top: .1rem;
    }

    .flash-item span {
        flex: 1;
    }

    /* Nút đóng thủ công */
    .flash-close {
        margin-left: auto;
        background: none;
        border: none;
        color: inherit;
        opacity: .5;
        cursor: pointer;
        font-size: .85rem;
        padding: 0;
        flex-shrink: 0;
        transition: opacity var(--transition);
    }

    .flash-close:hover {
        opacity: 1;
    }
</style>
<div class="flash-container" id="flashContainer">

    @if (session('success'))
        <div class="flash-item flash-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="flash-close" onclick="this.closest('.flash-item').remove()" title="Đóng">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="flash-item flash-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="flash-close" onclick="this.closest('.flash-item').remove()" title="Đóng">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('info'))
        <div class="flash-item flash-info">
            <i class="fas fa-info-circle"></i>
            <span>{{ session('info') }}</span>
            <button class="flash-close" onclick="this.closest('.flash-item').remove()" title="Đóng">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="flash-item flash-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('warning') }}</span>
            <button class="flash-close" onclick="this.closest('.flash-item').remove()" title="Đóng">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

</div>

{{-- JS: tự ẩn sau 4.5s + hàm showFlash() dùng được từ mọi trang --}}
<script>
    /* Tự ẩn flash từ session sau 4.5s */
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.flash-item').forEach(function(el) {
                el.style.transition = 'opacity .5s, transform .5s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(function() {
                    if (el.parentNode) el.remove();
                }, 500);
            });
        }, 4500);
    });

    /**
     * Gọi từ bất kỳ JS nào trong toàn site:
     * showFlash('Đã lưu thành công!', 'success')
     * showFlash('Có lỗi xảy ra', 'error')
     * showFlash('Thông tin thêm', 'info')
     * showFlash('Cảnh báo gì đó', 'warning')
     */
    window.showFlash = function(message, type) {
        type = type || 'success';
        var icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle',
        };
        var container = document.getElementById('flashContainer');
        if (!container) return;

        var el = document.createElement('div');
        el.className = 'flash-item flash-' + type;
        el.innerHTML =
            '<i class="fas ' + (icons[type] || icons.info) + '"></i>' +
            '<span>' + message + '</span>' +
            '<button class="flash-close" onclick="this.closest(\'.flash-item\').remove()" title="Đóng">' +
            '<i class="fas fa-times"></i>' +
            '</button>';
        container.appendChild(el);

        /* Tự ẩn sau 4s */
        setTimeout(function() {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateX(20px)';
            setTimeout(function() {
                if (el.parentNode) el.remove();
            }, 400);
        }, 4000);
    };
</script>
