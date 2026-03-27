/* =========================================================
   JAVASCRIPT DÙNG CHUNG - THÀNH CÔNG LAND
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    // 1. Navbar Sticky Effect
    const navbar = document.getElementById("mainNavbar");
    window.addEventListener("scroll", () =>
        navbar?.classList.toggle("scrolled", window.scrollY > 50),
    );

    // 2. Mobile Hamburger Menu
    const hamburger = document.getElementById("hamburger");
    const navMenu = document.getElementById("navMenu");
    const navOverlay = document.getElementById("navOverlay");

    function closeMenu() {
        hamburger?.classList.remove("open");
        navMenu?.classList.remove("open");
        navOverlay?.classList.remove("show");
        document.body.style.overflow = "";
    }

    hamburger?.addEventListener("click", () => {
        hamburger.classList.toggle("open");
        navMenu.classList.toggle("open");
        navOverlay.classList.toggle("show");
        document.body.style.overflow = navMenu.classList.contains("open")
            ? "hidden"
            : "";
    });
    navOverlay?.addEventListener("click", closeMenu);

    // Xử lý mở Menu con trên Mobile
    document
        .querySelectorAll(".nav-item.has-dropdown > .nav-link")
        .forEach((link) => {
            link.addEventListener("click", function (e) {
                if (window.innerWidth >= 1200) return;
                e.preventDefault();
                const item = this.closest(".nav-item");
                const isOpen = item.classList.contains("mobile-open");
                document
                    .querySelectorAll(".nav-item.has-dropdown.mobile-open")
                    .forEach((el) => el.classList.remove("mobile-open"));
                if (!isOpen) item.classList.add("mobile-open");
            });
        });

    // 3. Dropdown Profile Khách Hàng
    let _khOpen = false;
    document.getElementById("khAvatarBtn")?.addEventListener("click", (e) => {
        e.stopPropagation();
        _khOpen = !_khOpen;
        document
            .getElementById("khProfileWrap")
            .classList.toggle("open", _khOpen);
    });

    document.addEventListener("click", (e) => {
        const wrap = document.getElementById("khProfileWrap");
        if (wrap && !wrap.contains(e.target)) {
            _khOpen = false;
            wrap.classList.remove("open");
        }
    });
});

/* =========================================================
   AUTH MODAL LOGIC (ĐĂNG NHẬP / ĐĂNG KÝ / OTP)
========================================================= */
let _otpEmail = "",
    _resetEmail = "",
    _otpTimer = null;

const AUTH_STEPS = {
    login: {
        title: "Đăng nhập",
        sub: "Chào mừng bạn trở lại!",
        icon: "fa-user",
    },
    register: {
        title: "Tạo tài khoản",
        sub: "Đăng ký miễn phí ngay hôm nay",
        icon: "fa-user-plus",
    },
    otp: {
        title: "Xác thực OTP",
        sub: "Kiểm tra hộp thư của bạn",
        icon: "fa-shield-alt",
    },
    forgot: {
        title: "Quên mật khẩu",
        sub: "Lấy lại quyền truy cập",
        icon: "fa-key",
    },
    resetConfirm: {
        title: "Tạo mật khẩu mới",
        sub: "Bảo mật tài khoản của bạn",
        icon: "fa-unlock-alt",
    },
};

window.openAuthModal = function (step = "login") {
    const backdrop = document.getElementById("authModalBackdrop");
    const modal = document.getElementById("authModal");
    if (backdrop && modal) {
        backdrop.classList.add("show");
        modal.classList.add("show");
        document.body.style.overflow = "hidden";
        switchAuthStep(step);
        // Reset form nếu đó là bước login
        if (step === "login") {
            document.getElementById("formAuthLogin")?.reset();
            document.getElementById("errLoginGeneral").innerHTML = "";
            document.getElementById("errLoginGeneral").style.display = "none";
        }
    }
};

window.closeAuthModal = function () {
    const backdrop = document.getElementById("authModalBackdrop");
    const modal = document.getElementById("authModal");
    if (backdrop && modal) {
        backdrop.classList.remove("show");
        modal.classList.remove("show");
        document.body.style.overflow = "";
    }
};

window.switchAuthStep = function (step) {
    ["login", "register", "otp", "forgot", "resetConfirm"].forEach((s) => {
        const el = document.getElementById(
            "authStep" + s.charAt(0).toUpperCase() + s.slice(1),
        );
        if (el) el.style.display = s === step ? "block" : "none";
    });
    const cfg = AUTH_STEPS[step];
    if (document.getElementById("authModalTitle"))
        document.getElementById("authModalTitle").textContent = cfg.title;
    if (document.getElementById("authModalSub"))
        document.getElementById("authModalSub").textContent = cfg.sub;
    if (document.getElementById("authModalIconI"))
        document.getElementById("authModalIconI").className = "fas " + cfg.icon;
    clearAuthErrors();
};

function clearAuthErrors() {
    document.querySelectorAll('[id^="err"]').forEach((el) => {
        el.innerHTML = "";
        el.style.display = "none";
    });
    document
        .querySelectorAll(".kh-field-input")
        .forEach((el) => el.classList.remove("error"));
}

function showAuthErrors(errors) {
    Object.entries(errors).forEach(([key, msgs]) => {
        const maps = {
            ho_ten: "errRegHoTen",
            email: "errRegEmail",
            password: "errRegPassword",
            so_dien_thoai: "errRegSoDienThoai",
            password_confirmation: "errRegPasswordConfirmation",
        };
        const elId = maps[key];
        if (elId) {
            const el = document.getElementById(elId);
            if (el) {
                el.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${Array.isArray(msgs) ? msgs[0] : msgs}`;
                el.style.display = "flex";
            }
        }
    });
}

async function authPost(url, data, btn, origHtml) {
    if (!window.APP)
        return { success: false, message: "Lỗi cấu hình hệ thống." };
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang xử lý...';
    try {
        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.APP.csrfToken,
                Accept: "application/json",
            },
            body: JSON.stringify(data),
        });
        return await res.json();
    } catch {
        return {
            success: false,
            message: "Lỗi kết nối, vui lòng thử lại.",
        };
    } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
    }
}

// ĐĂNG NHẬP
document
    .getElementById("formAuthLogin")
    ?.addEventListener("submit", async function (e) {
        e.preventDefault();
        clearAuthErrors();
        const btn = document.getElementById("btnAuthLogin");
        const data = await authPost(
            window.APP.routes.loginPost,
            {
                email: document.getElementById("loginEmail").value.trim(),
                password: document.getElementById("loginPassword").value,
            },
            btn,
            '<i class="fas fa-sign-in-alt"></i> Đăng nhập',
        );
        if (data.success) location.reload();
        else if (data.errors) {
            if (data.errors.email) {
                document.getElementById("errLoginEmail").innerHTML =
                    `<i class="fas fa-exclamation-circle"></i> ${data.errors.email[0]}`;
                document.getElementById("errLoginEmail").style.display = "flex";
            }
            if (data.errors.password) {
                document.getElementById("errLoginPassword").innerHTML =
                    `<i class="fas fa-exclamation-circle"></i> ${data.errors.password[0]}`;
                document.getElementById("errLoginPassword").style.display =
                    "flex";
            }
        } else {
            document.getElementById("errLoginGeneral").innerHTML =
                `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
            document.getElementById("errLoginGeneral").style.color =
                "var(--status-danger)";
            document.getElementById("errLoginGeneral").style.display = "block";
        }
    });

// ĐĂNG KÝ
document
    .getElementById("formAuthRegister")
    ?.addEventListener("submit", async function (e) {
        e.preventDefault();
        clearAuthErrors();
        const btn = document.getElementById("btnAuthRegister");
        const data = await authPost(
            window.APP.routes.registerPost,
            {
                ho_ten: document.getElementById("regHoTen").value.trim(),
                email: document.getElementById("regEmail").value.trim(),
                so_dien_thoai: document.getElementById("regSdt").value.trim(),
                password: document.getElementById("regPassword").value,
                password_confirmation:
                    document.getElementById("regPasswordConfirm").value,
            },
            btn,
            '<i class="fas fa-user-plus"></i> Đăng ký',
        );
        if (data.success) {
            _otpEmail =
                data.email || document.getElementById("regEmail").value.trim();
            document.getElementById("otpEmailDisplay").textContent = _otpEmail;
            switchAuthStep("otp");
            startOtpCountdown();
            setTimeout(
                () => document.querySelector(".reg-otp-box")?.focus(),
                100,
            );
        } else if (data.errors) showAuthErrors(data.errors);
    });

// OTP KÍCH HOẠT
document.querySelectorAll(".reg-otp-box").forEach((inp, i, all) => {
    inp.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, "");
        if (this.value && i < all.length - 1) all[i + 1].focus();
        if ([...all].every((b) => b.value)) submitOtp();
    });
    inp.addEventListener("keydown", function (e) {
        if (e.key === "Backspace" && !this.value && i > 0) all[i - 1].focus();
    });
    inp.addEventListener("paste", function (e) {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData)
            .getData("text")
            .replace(/\D/g, "");
        [...all].forEach((box, idx) => {
            box.value = text[idx] || "";
        });
        if (text.length >= 6) submitOtp();
    });
});

document.getElementById("btnVerifyOtp")?.addEventListener("click", submitOtp);
async function submitOtp() {
    const otp = [...document.querySelectorAll(".reg-otp-box")]
        .map((b) => b.value)
        .join("");
    if (otp.length !== 6) return;
    const btn = document.getElementById("btnVerifyOtp");
    const data = await authPost(
        window.APP.routes.verifyOtp,
        { email: _otpEmail, otp },
        btn,
        '<i class="fas fa-check-circle"></i> Xác thực ngay',
    );
    if (data.success) {
        clearInterval(_otpTimer);
        btn.innerHTML =
            '<i class="fas fa-check-circle"></i> Thành công! Đang tải...';
        btn.style.background = "var(--status-success)";
        setTimeout(() => location.reload(), 800);
    } else {
        document.getElementById("errOtp").innerHTML =
            `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
        document.getElementById("errOtp").style.display = "block";
        document.querySelectorAll(".reg-otp-box").forEach((b) => {
            b.value = "";
        });
        document.querySelector(".reg-otp-box")?.focus();
    }
}

document
    .getElementById("btnResendOtp")
    ?.addEventListener("click", async function (e) {
        e.preventDefault();
        this.style.display = "none";
        document.getElementById("otpCountdownWrap").style.display = "inline";
        await fetch(window.APP.routes.sendOtp, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.APP.csrfToken,
            },
            body: JSON.stringify({ email: _otpEmail }),
        });
        startOtpCountdown();
    });

function startOtpCountdown() {
    clearInterval(_otpTimer);
    let s = 60;
    document.getElementById("otpTimer").textContent = s;
    document.getElementById("otpCountdownWrap").style.display = "inline";
    document.getElementById("btnResendOtp").style.display = "none";
    document.getElementById("errOtp").style.display = "none";
    _otpTimer = setInterval(() => {
        s--;
        document.getElementById("otpTimer").textContent = s;
        if (s <= 0) {
            clearInterval(_otpTimer);
            document.getElementById("otpCountdownWrap").style.display = "none";
            document.getElementById("btnResendOtp").style.display = "inline";
        }
    }, 1000);
}

// QUÊN MẬT KHẨU
document
    .getElementById("formAuthForgot")
    ?.addEventListener("submit", async function (e) {
        e.preventDefault();
        clearAuthErrors();
        const btn = document.getElementById("btnAuthForgot");
        _resetEmail = document.getElementById("forgotEmail").value.trim();
        const data = await authPost(
            window.APP.routes.forgotPost,
            { email: _resetEmail },
            btn,
            '<i class="fas fa-paper-plane"></i> Nhận mã OTP',
        );
        if (data.success) {
            document.getElementById("resetEmailDisplay").textContent =
                _resetEmail;
            switchAuthStep("resetConfirm");
        } else {
            if (data.errors?.email) {
                document.getElementById("errForgotEmail").innerHTML =
                    `<i class="fas fa-exclamation-circle"></i> ${data.errors.email[0]}`;
                document.getElementById("errForgotEmail").style.display =
                    "flex";
            } else if (data.message) {
                document.getElementById("errForgotGeneral").innerHTML =
                    `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
                document.getElementById("errForgotGeneral").style.color =
                    "var(--status-danger)";
                document.getElementById("errForgotGeneral").style.display =
                    "block";
            }
        }
    });

// CẬP NHẬT MẬT KHẨU
document
    .getElementById("formAuthResetConfirm")
    ?.addEventListener("submit", async function (e) {
        e.preventDefault();
        clearAuthErrors();
        const btn = document.getElementById("btnAuthResetConfirm");
        const data = await authPost(
            window.APP.routes.resetPost,
            {
                email: _resetEmail,
                token: document.getElementById("resetOtp").value.trim(),
                password: document.getElementById("resetNewPassword").value,
                password_confirmation: document.getElementById(
                    "resetNewPasswordConfirm",
                ).value,
            },
            btn,
            '<i class="fas fa-save"></i> Cập nhật mật khẩu',
        );
        if (data.success) {
            btn.innerHTML =
                '<i class="fas fa-check-circle"></i> Đặt lại thành công!';
            btn.style.background = "var(--status-success)";
            setTimeout(() => {
                switchAuthStep("login");
                document.getElementById("loginEmail").value = _resetEmail;
                document.getElementById("errLoginGeneral").innerHTML =
                    `<i class="fas fa-check-circle"></i> Đặt lại mật khẩu thành công! Vui lòng đăng nhập.`;
                document.getElementById("errLoginGeneral").style.color =
                    "var(--status-success)";
                document.getElementById("errLoginGeneral").style.display =
                    "block";
                btn.innerHTML = '<i class="fas fa-save"></i> Cập nhật mật khẩu';
                btn.style.background = "";
            }, 1200);
        } else {
            if (data.errors) {
                if (data.errors.password) {
                    document.getElementById("errResetNewPassword").innerHTML =
                        `<i class="fas fa-exclamation-circle"></i> ${data.errors.password[0]}`;
                    document.getElementById(
                        "errResetNewPassword",
                    ).style.display = "flex";
                }
                if (data.errors.token) {
                    document.getElementById("errResetOtp").innerHTML =
                        `<i class="fas fa-exclamation-circle"></i> ${data.errors.token[0]}`;
                    document.getElementById("errResetOtp").style.display =
                        "flex";
                }
            } else {
                document.getElementById("errResetConfirmGeneral").innerHTML =
                    `<i class="fas fa-exclamation-circle"></i> ${data.message}`;
                document.getElementById("errResetConfirmGeneral").style.color =
                    "var(--status-danger)";
                document.getElementById(
                    "errResetConfirmGeneral",
                ).style.display = "block";
            }
        }
    });

window.toggleAuthEye = function (id, btn) {
    const inp = document.getElementById(id);
    const ico = btn.querySelector("i");
    const show = inp.type === "password";
    inp.type = show ? "text" : "password";
    ico.className = show ? "far fa-eye-slash" : "far fa-eye";
};

// =========================================================
// BACK TO TOP & NEWSLETTER (FOOTER)
// =========================================================
const btnTop = document.getElementById("backToTop");
window.addEventListener("scroll", () => {
    if (btnTop) {
        if (window.scrollY > 400) {
            btnTop.classList.add("show");
        } else {
            btnTop.classList.remove("show");
        }
    }
});

btnTop?.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});

window.handleNewsletter = function (e) {
    e.preventDefault();
    const input = e.target.querySelector('input[type="email"]');
    const btn = e.target.querySelector('button[type="submit"]');
    if (!input?.value?.trim()) return false;

    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> <span>Đã đăng ký!</span>';
    btn.style.background = "var(--status-success)";
    input.value = "";

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.style.background = "";
    }, 3000);

    return false;
};

/* =========================================================
   CONTACT PANEL LOGIC
========================================================= */
let _cpOpen = false;
window.toggleContactPanel = function () {
    _cpOpen = !_cpOpen;
    const panel = document.getElementById("contactPanel");
    const btn = document.getElementById("contactToggleBtn");
    const icon = document.getElementById("contactBtnIcon");
    if (!panel || !btn) return;

    if (_cpOpen) {
        panel.classList.add("show");
        btn.classList.add("active");
        icon.className = "fas fa-times";
    } else {
        panel.classList.remove("show");
        btn.classList.remove("active");
        icon.className = "fas fa-headset";
    }
};
document.addEventListener("click", (e) => {
    if (!_cpOpen) return;
    const panel = document.getElementById("contactPanel");
    const btn = document.getElementById("contactToggleBtn");
    if (panel && btn && !panel.contains(e.target) && !btn.contains(e.target)) {
        window.toggleContactPanel();
    }
});

/* =========================================================
   CHAT WIDGET LOGIC
========================================================= */
let feChatPolling = null;
let feLastMessageCount = 0;

window.toggleChatWindow = function () {
    const win = document.getElementById("chatWindow");
    const btn = document.getElementById("chatWidgetBtn");
    const badge = document.getElementById("chatUnreadBadge");
    if (!win) return;

    if (win.classList.contains("show")) {
        win.classList.remove("show");
        btn.classList.remove("active");
        clearInterval(feChatPolling);
    } else {
        win.classList.add("show");
        btn.classList.add("active");
        if (badge) badge.style.display = "none";
        initFrontendChat();
    }
};

function initFrontendChat() {
    if (!window.APP || !window.APP.isLoggedIn) {
        const guestEl = document.getElementById("chatKhachHangChuaDangNhap");
        if (guestEl) guestEl.style.display = "flex";
        return;
    }
    document.getElementById("chatKhachHangChuaDangNhap").style.display = "none";
    if (feLastMessageCount === 0)
        document.getElementById("chatBody").innerHTML =
            '<div class="text-center mt-4"><div class="spinner-grow text-primary spinner-grow-sm"></div><p class="text-muted small mt-2">Đang kết nối...</p></div>';

    fetch(window.APP.routes.chatKhoiTao, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.APP.csrfToken,
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                document.getElementById("currentPhienChatId").value =
                    data.phien_chat_id;
                document.getElementById("chatInput").disabled = false;
                document.getElementById("chatSendBtn").disabled = false;
                fetchFrontendMessages();
                clearInterval(feChatPolling);
                feChatPolling = setInterval(fetchFrontendMessages, 3000);
            }
        })
        .catch(() => {
            document.getElementById("chatBody").innerHTML =
                '<div class="text-center mt-4 text-danger"><i class="fas fa-exclamation-triangle mb-2"></i><p>Lỗi kết nối.</p></div>';
        });
}

function fetchFrontendMessages() {
    const phienChatId = document.getElementById("currentPhienChatId")?.value;
    if (!phienChatId) return;
    fetch(
        `${window.APP.baseUrl.replace(/\/$/, "")}/chat/lich-su/${phienChatId}`,
        { headers: { Accept: "application/json" } },
    )
        .then((res) => res.json())
        .then((data) => {
            if (data.success) renderFrontendMessages(data.tin_nhans);
        });
}

function renderFrontendMessages(messages) {
    const body = document.getElementById("chatBody");
    if (messages.length === feLastMessageCount && feLastMessageCount > 0)
        return;
    feLastMessageCount = messages.length;
    body.innerHTML = "";
    messages.forEach((msg) => {
        const t = new Date(msg.created_at).toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });
        let cls =
            msg.nguoi_gui === "hethong"
                ? "fe-msg-system"
                : msg.nguoi_gui === "nhanvien"
                  ? "fe-msg-admin"
                  : "fe-msg-customer";
        let time =
            msg.nguoi_gui === "hethong"
                ? ""
                : `<div class="fe-msg-time">${t} ${msg.nguoi_gui === "khachhang" ? '<i class="fas fa-check-double ms-1"></i>' : ""}</div>`;
        body.innerHTML += `<div class="fe-chat-row"><div class="fe-msg-bubble ${cls}">${msg.noi_dung}${time}</div></div>`;
    });
    body.scrollTo({ top: body.scrollHeight, behavior: "smooth" });
}

window.sendFrontendMessage = function (e) {
    e.preventDefault();
    const input = document.getElementById("chatInput");
    const phienId = document.getElementById("currentPhienChatId").value;
    const noiDung = input.value.trim();
    if (!noiDung || !phienId) return;
    input.value = "";

    const body = document.getElementById("chatBody");
    const t = new Date().toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
    });
    body.innerHTML += `<div class="fe-chat-row"><div class="fe-msg-bubble fe-msg-customer" style="opacity:.7">${noiDung}<div class="fe-msg-time">${t} <i class="far fa-clock ms-1"></i></div></div></div>`;
    body.scrollTo({ top: body.scrollHeight, behavior: "smooth" });

    fetch(window.APP.routes.chatGui, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.APP.csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({ phien_chat_id: phienId, noi_dung: noiDung }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) fetchFrontendMessages();
        });
};

/* =========================================================
   SO SÁNH BẤT ĐỘNG SẢN LOGIC
========================================================= */
if (typeof window.soSanhList === "undefined") {
    var soSanhList = JSON.parse(localStorage.getItem("tcl_sosanh") || "[]");
} else {
    var soSanhList = window.soSanhList;
}

window.addSoSanh = function (id, ten) {
    id = parseInt(id);
    if (soSanhList.find((x) => x.id === id)) {
        showFlash("BĐS này đã có trong danh sách.", "info");
        return;
    }
    if (soSanhList.length >= 3) {
        showFlash("Tối đa 3 BĐS.", "warning");
        return;
    }
    soSanhList.push({ id, ten });
    localStorage.setItem("tcl_sosanh", JSON.stringify(soSanhList));
    renderSoSanhBar();
    showFlash("Đã thêm vào so sánh.", "success");
};

window.removeSoSanh = function (id) {
    soSanhList = soSanhList.filter((x) => x.id !== parseInt(id));
    localStorage.setItem("tcl_sosanh", JSON.stringify(soSanhList));
    renderSoSanhBar();
    const modalEl = document.getElementById("soSanhModal");
    if (modalEl && modalEl.classList.contains("show")) {
        if (soSanhList.length >= 2) openSoSanhModal();
        else bootstrap.Modal.getInstance(modalEl)?.hide();
    }
};

window.clearSoSanh = function () {
    soSanhList = [];
    localStorage.setItem("tcl_sosanh", "[]");
    renderSoSanhBar();
    const modalEl = document.getElementById("soSanhModal");
    if (modalEl && modalEl.classList.contains("show"))
        bootstrap.Modal.getInstance(modalEl)?.hide();
    showFlash("Đã xóa toàn bộ.", "info");
};

function renderSoSanhBar() {
    const bar = document.getElementById("so-sanh-bar");
    if (!bar) return;
    if (soSanhList.length === 0) {
        bar.style.display = "none";
        return;
    }
    bar.style.display = "block";
    if (document.getElementById("ssSanhCount"))
        document.getElementById("ssSanhCount").textContent = soSanhList.length;

    const itemsEl = document.getElementById("ssSanhItems");
    if (itemsEl) {
        itemsEl.innerHTML = soSanhList
            .map((item) => {
                const shortName =
                    item.ten.length > 22
                        ? item.ten.substring(0, 22) + "…"
                        : item.ten;
                return `<span class="ss-item-chip">${shortName} <button class="ss-item-remove" onclick="removeSoSanh(${item.id})"><i class="fas fa-times"></i></button></span>`;
            })
            .join("");
    }
}

window.openSoSanhModal = function () {
    if (soSanhList.length === 0) return;
    const modalEl = document.getElementById("soSanhModal");
    const modalBody = document.getElementById("soSanhModalBody");
    if (!modalEl) return;
    if (document.getElementById("soSanhModalCount"))
        document.getElementById("soSanhModalCount").textContent =
            soSanhList.length + " BĐS";

    if (modalBody)
        modalBody.innerHTML =
            '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted fw-bold">Đang tải...</p></div>';
    bootstrap.Modal.getOrCreateInstance(modalEl).show();

    const ids = soSanhList.map((item) => item.id).join(",");
    fetch(`${window.APP.routes.soSanhModal}?ids=${ids}`)
        .then((r) => r.text())
        .then((html) => {
            if (modalBody) modalBody.innerHTML = html;
        })
        .catch(() => {
            if (modalBody)
                modalBody.innerHTML =
                    '<div class="text-center text-danger py-5">Lỗi tải dữ liệu.</div>';
        });
};

window.moTrangSoSanh = function () {
    if (soSanhList.length === 0) {
        showFlash("Chưa có BĐS nào.", "warning");
        return;
    }
    window.open(
        `${window.APP.routes.soSanhIndex}?ids=${soSanhList.map((x) => x.id).join(",")}`,
        "_blank",
    );
};

/* =========================================================
   MODAL HỒ SƠ KHÁCH HÀNG
========================================================= */
window.openModalHoSo = function (tab) {
    document.getElementById("khModalBackdrop")?.classList.add("show");
    document.getElementById("modalHoSo")?.classList.add("show");
    document.body.style.overflow = "hidden";
    switchModalTab(tab || "thong-tin");
};

window.closeModalHoSo = function () {
    document.getElementById("khModalBackdrop")?.classList.remove("show");
    document.getElementById("modalHoSo")?.classList.remove("show");
    document.body.style.overflow = "";
    document
        .querySelectorAll(".kh-field-err")
        .forEach((el) => (el.textContent = ""));
    document
        .querySelectorAll(".kh-field-input")
        .forEach((el) => el.classList.remove("error"));
};

window.switchModalTab = function (tab) {
    const isTT = tab === "thong-tin";
    document.getElementById("tabThongTin")?.classList.toggle("active", isTT);
    document.getElementById("tabMatKhau")?.classList.toggle("active", !isTT);
    document.getElementById("tabBtnThongTin")?.classList.toggle("active", isTT);
    document.getElementById("tabBtnMatKhau")?.classList.toggle("active", !isTT);
};

window.submitThongTin = function (e) {
    e.preventDefault();
    const btn = document.getElementById("btnSubmitThongTin");
    const orig = '<i class="fas fa-save"></i> Lưu thay đổi';
    postKhachHang(
        window.APP.routes.profileUpdate,
        {
            ho_ten: document.getElementById("f_ho_ten").value.trim(),
            so_dien_thoai: document
                .getElementById("f_so_dien_thoai")
                .value.trim(),
            email: document.getElementById("f_email").value.trim(),
        },
        btn,
        orig,
        () => closeModalHoSo(),
    );
};

window.submitMatKhau = function (e) {
    e.preventDefault();
    const pw1 = document.getElementById("f_mat_khau_moi").value;
    const pw2 = document.getElementById("f_mat_khau_moi_confirmation").value;
    if (pw1 !== pw2) return;
    const btn = document.getElementById("btnSubmitMk");
    postKhachHang(
        window.APP.routes.changePassword,
        {
            mat_khau_cu: document.getElementById("f_mat_khau_cu").value,
            mat_khau_moi: pw1,
            mat_khau_moi_confirmation: pw2,
        },
        btn,
        '<i class="fas fa-shield-alt"></i> Cập nhật',
        () => {
            document.getElementById("formMatKhau").reset();
            closeModalHoSo();
        },
    );
};

function postKhachHang(url, data, btn, orig, onSuccess) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.APP.csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((r) => r.json())
        .then((res) => {
            if (res.success) {
                showFlash(res.message || "Cập nhật thành công!", "success");
                if (onSuccess) onSuccess();
            } else {
                showFlash(res.message || "Có lỗi xảy ra", "danger");
                if (res.errors) {
                    Object.entries(res.errors).forEach(([key, msgs]) => {
                        const el = document.getElementById("err" + key);
                        if (el) {
                            el.textContent = Array.isArray(msgs)
                                ? msgs[0]
                                : msgs;
                            el.style.display = "block";
                        }
                    });
                }
            }
        })
        .catch(() => showFlash("Lỗi kết nối", "danger"))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = orig;
        });
}

/* =========================================================
   UTILITIES
========================================================= */
function showFlash(message, type = "info") {
    const palette = {
        success: {
            bg: "#ecfdf3",
            border: "#16a34a",
            text: "#166534",
            icon: "fa-check-circle",
        },
        info: {
            bg: "#eff6ff",
            border: "#2563eb",
            text: "#1e40af",
            icon: "fa-info-circle",
        },
        warning: {
            bg: "#fffbeb",
            border: "#d97706",
            text: "#92400e",
            icon: "fa-exclamation-triangle",
        },
        danger: {
            bg: "#fef2f2",
            border: "#dc2626",
            text: "#991b1b",
            icon: "fa-times-circle",
        },
    };
    const tone = palette[type] || palette.info;

    let root = document.getElementById("flashToastRoot");
    if (!root) {
        root = document.createElement("div");
        root.id = "flashToastRoot";
        root.style.position = "fixed";
        root.style.top = "18px";
        root.style.right = "18px";
        root.style.zIndex = "99999";
        root.style.display = "flex";
        root.style.flexDirection = "column";
        root.style.gap = "10px";
        root.style.maxWidth = "calc(100vw - 36px)";
        document.body.appendChild(root);
    }

    const toast = document.createElement("div");
    toast.style.display = "flex";
    toast.style.alignItems = "center";
    toast.style.gap = "10px";
    toast.style.padding = "11px 13px";
    toast.style.borderRadius = "12px";
    toast.style.borderLeft = `4px solid ${tone.border}`;
    toast.style.background = tone.bg;
    toast.style.color = tone.text;
    toast.style.boxShadow = "0 10px 30px rgba(0,0,0,.12)";
    toast.style.fontWeight = "700";
    toast.style.fontSize = "14px";
    toast.style.lineHeight = "1.35";
    toast.style.opacity = "0";
    toast.style.transform = "translateY(-8px)";
    toast.style.transition = "all .25s ease";
    toast.innerHTML = `<i class="fas ${tone.icon}"></i><span>${message}</span>`;
    root.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = "1";
        toast.style.transform = "translateY(0)";
    });

    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(-8px)";
        setTimeout(() => toast.remove(), 260);
    }, 2400);
}

function toggleYeuThich(btn, batDongSanId) {
    if (!window.APP || !window.APP.isLoggedIn) {
        window.openAuthModal("login");
        showFlash("Vui lòng đăng nhập để lưu yêu thích.", "info");
        return;
    }
    var icon = btn.querySelector("i");
    var wasLiked = btn.classList.contains("liked");
    btn.classList.toggle("liked", !wasLiked);
    if (icon) icon.className = wasLiked ? "far fa-heart" : "fas fa-heart";

    fetch(window.APP.routes.yeuThichToggle, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.APP.csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({ bat_dong_san_id: batDongSanId }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                btn.classList.toggle("liked", data.is_liked);
                if (icon)
                    icon.className = data.is_liked
                        ? "fas fa-heart"
                        : "far fa-heart";
                btn.title = data.is_liked ? "Bỏ yêu thích" : "Yêu thích";
                if (typeof showFlash === "function")
                    showFlash(
                        data.is_liked
                            ? "Đã lưu tin vào mục yêu thích."
                            : "Đã bỏ lưu tin yêu thích.",
                        data.is_liked ? "success" : "info",
                    );
            } else {
                btn.classList.toggle("liked", wasLiked);
                if (icon)
                    icon.className = wasLiked ? "fas fa-heart" : "far fa-heart";
                showFlash(
                    data.message || "Không thể lưu tin lúc này.",
                    "warning",
                );
            }
        })
        .catch(() => {
            btn.classList.toggle("liked", wasLiked);
            if (icon)
                icon.className = wasLiked ? "fas fa-heart" : "far fa-heart";
            showFlash("Lỗi kết nối. Vui lòng thử lại.", "danger");
        });
}
