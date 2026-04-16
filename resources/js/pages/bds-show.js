function flash(message, type) {
    if (typeof window.showFlash === "function") {
        window.showFlash(message, type || "info");
    }
}

function initGalleryViewer() {
    const modalEl = document.getElementById("galleryViewerModal");
    const carouselEl = document.getElementById("galleryViewerCarousel");

    if (!modalEl || !carouselEl || typeof window.bootstrap === "undefined") {
        return;
    }

    const modal = new window.bootstrap.Modal(modalEl);
    const carousel = new window.bootstrap.Carousel(carouselEl, {
        interval: false,
        touch: true,
        wrap: true,
    });

    document.querySelectorAll(".js-open-gallery").forEach((el) => {
        el.addEventListener("click", (e) => {
            e.preventDefault();
            const idx = parseInt(
                el.getAttribute("data-gallery-index") || "0",
                10,
            );
            carousel.to(Number.isNaN(idx) ? 0 : idx);
            modal.show();
        });
    });
}

function buildHeaders() {
    if (typeof window.getCsrfHeaders === "function") {
        return window.getCsrfHeaders({
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        });
    }

    return {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        "X-CSRF-TOKEN":
            window.APP?.csrfToken || window.BDS_SHOW?.csrfToken || "",
    };
}

window.guiYeuCauGoiLai = function guiYeuCauGoiLai(e) {
    e.preventDefault();

    const btn = document.getElementById("btnCallBack");
    const form = document.getElementById("formCallBack");
    const errorBox = document.getElementById("callBackError");

    if (!btn || !form) {
        return;
    }

    const sdt =
        form.querySelector('[name="so_dien_thoai"]')?.value?.trim() || "";
    const hoTen = form.querySelector('[name="ho_ten"]')?.value?.trim() || "";

    if (errorBox) {
        errorBox.classList.add("d-none");
        errorBox.classList.remove("text-danger", "text-success");
        errorBox.textContent = "";
    }

    if (!sdt) {
        flash("Vui lòng nhập số điện thoại.", "warning");
        return;
    }

    if (!hoTen) {
        flash("Vui lòng nhập họ tên.", "warning");
        return;
    }

    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang gửi...';
    btn.disabled = true;

    const lienHeEndpoint =
        window.BDS_SHOW?.routes?.lienHeStore ||
        form.getAttribute("action") ||
        "";

    fetch(lienHeEndpoint, {
        method: "POST",
        headers: buildHeaders(),
        credentials: "same-origin",
        body: new FormData(form),
    })
        .then(async (r) => {
            let data = {};
            try {
                data = await r.json();
            } catch (_) {}

            if (!r.ok) {
                throw new Error(
                    data.message ||
                        (r.status === 429
                            ? "Bạn thao tác quá nhanh. Vui lòng thử lại sau 2 phút."
                            : "Có lỗi xảy ra, vui lòng thử lại."),
                );
            }

            return data;
        })
        .then((data) => {
            flash(
                data.message || "Yêu cầu đã gửi! Chúng tôi sẽ liên hệ sớm.",
                "success",
            );

            const defaultNoiDung =
                "Tôi quan tâm đến BĐS: " + (window.BDS_SHOW?.title || "");
            const noiDungEl = form.querySelector('[name="noi_dung"]');
            if (noiDungEl) noiDungEl.value = defaultNoiDung;

            if (!window.BDS_SHOW?.isCustomerLoggedIn) {
                form.reset();
                if (noiDungEl) noiDungEl.value = defaultNoiDung;
            }

            if (errorBox) {
                errorBox.classList.remove("d-none");
                errorBox.classList.add("text-success");
                errorBox.textContent =
                    data.message || "Yêu cầu đã được gửi thành công.";
            }
        })
        .catch((err) => {
            flash(err.message || "Có lỗi xảy ra, vui lòng thử lại.", "danger");
            if (errorBox) {
                errorBox.classList.remove("d-none");
                errorBox.classList.add("text-danger");
                errorBox.textContent =
                    err.message || "Có lỗi xảy ra, vui lòng thử lại.";
            }
        })
        .finally(() => {
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi Yêu Cầu';
            btn.disabled = false;
        });
};

function initDatLichForm() {
    const form = document.getElementById("frmDatLich");
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const btn = document.getElementById("btnSubmitDatLich");
        const errorBox = document.getElementById("datLichError");
        if (!btn) {
            return;
        }

        const originalText = btn.innerHTML;

        if (errorBox) {
            errorBox.classList.add("d-none");
            errorBox.textContent = "";
        }

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        btn.disabled = true;

        fetch(form.action, {
            method: "POST",
            headers: buildHeaders(),
            credentials: "same-origin",
            body: new FormData(form),
        })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(
                        data.message ||
                            (response.status === 429
                                ? "Bạn thao tác quá nhanh. Vui lòng thử lại sau 2 phút."
                                : "Có lỗi xảy ra, vui lòng kiểm tra lại thông tin."),
                    );
                }
                return data;
            })
            .then((data) => {
                if (data.status !== "success") {
                    throw new Error(
                        data.message || "Có lỗi xảy ra, vui lòng kiểm tra lại.",
                    );
                }

                const modalEl = document.getElementById("modalDatLich");
                if (modalEl && typeof window.bootstrap !== "undefined") {
                    const modal = window.bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }

                flash(
                    data.message || "Gửi yêu cầu đặt lịch thành công!",
                    "success",
                );

                const ghiChuEl = form.querySelector('[name="ghi_chu"]');
                const defaultGhiChu =
                    "Tôi muốn đặt lịch xem BĐS: " +
                    (window.BDS_SHOW?.title || "");

                if (!window.BDS_SHOW?.isCustomerLoggedIn) {
                    form.reset();
                }

                if (ghiChuEl) {
                    ghiChuEl.value = defaultGhiChu;
                }
            })
            .catch((error) => {
                flash(
                    error.message || "Có lỗi xảy ra, vui lòng thử lại.",
                    "warning",
                );
                if (errorBox) {
                    errorBox.classList.remove("d-none");
                    errorBox.textContent =
                        error.message || "Có lỗi xảy ra, vui lòng thử lại.";
                }
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    });
}

window.dangKyCanhBaoGia = function dangKyCanhBaoGia() {
    if (typeof window.openModalDangKy !== "function") {
        flash("Chưa thể mở form đăng ký nhận tin lúc này.", "danger");
        return;
    }

    const cfg = window.BDS_SHOW || {};
    window.openModalDangKy("", {
        batDongSanId: cfg.bdsId || null,
        batDongSanTitle: cfg.title || "",
        nhuCau: cfg.nhuCau || null,
        khuVucId: cfg.khuVucId ?? null,
        duAnId: cfg.duAnId ?? null,
        soPhongNgu: cfg.soPhongNgu ?? null,
    });
};

function initTracking() {
    const cfg = window.BDS_SHOW || {};
    const bdsId = cfg.bdsId;
    const trackTimeUrl = cfg.routes?.trackTime;

    if (!bdsId || !trackTimeUrl || !navigator.sendBeacon) {
        return;
    }

    const startTime = Date.now();
    window.addEventListener("beforeunload", function () {
        const seconds = Math.floor((Date.now() - startTime) / 1000);
        if (seconds < 3) return;

        navigator.sendBeacon(
            trackTimeUrl,
            JSON.stringify({
                bds_id: bdsId,
                seconds,
                _token: cfg.csrfToken || "",
            }),
        );
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initGalleryViewer();
    initDatLichForm();
    initTracking();
});
