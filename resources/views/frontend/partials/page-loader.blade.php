<style>
    #page-loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity .4s, visibility .4s;
    }

    #page-loader.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .loader-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .loader-logo {
        height: 48px;
        animation: loaderPulse 0.5s ease-in-out infinite;
    }

    @keyframes loaderPulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: .6;
            transform: scale(.95);
        }
    }

    .loader-bar {
        width: 160px;
        height: 3px;
        background: #f0e4da;
        border-radius: 2px;
        overflow: hidden;
    }

    .loader-bar::after {
        content: '';
        display: block;
        height: 100%;
        width: 40%;
        background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        border-radius: 2px;
        animation: loaderSlide 1.2s ease-in-out infinite;
    }

    @keyframes loaderSlide {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(350%);
        }
    }
</style>

{{-- HTML --}}
<div id="page-loader">
    <div class="loader-inner">
        <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" class="loader-logo"
            onerror="this.style.display='none'">
        <div class="loader-bar"></div>
    </div>
</div>

{{-- JS — ẩn loader sau khi trang tải xong --}}
<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            var loader = document.getElementById('page-loader');
            if (loader) loader.classList.add('hidden');
        }, 100);

    });
</script>
