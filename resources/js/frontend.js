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
    // Xử lý mở Menu con trên Mobile - ACCORDION
    document
        .querySelectorAll(".nav-item.has-dropdown > .nav-link")
        .forEach((link) => {
            link.addEventListener("click", function (e) {
                if (window.innerWidth >= 1200) return;
                e.preventDefault();

                const item = this.closest(".nav-item");
                const isOpen = item.classList.contains("mobile-open");

                // Đóng tất cả dropdown khác
                document
                    .querySelectorAll(".nav-item.has-dropdown.mobile-open")
                    .forEach((el) => el.classList.remove("mobile-open"));

                // Toggle cái hiện tại
                if (!isOpen) {
                    item.classList.add("mobile-open");

                    // Scroll menu để item vừa mở không bị khuất
                    setTimeout(() => {
                        const navMenu = document.getElementById("navMenu");
                        const itemTop = item.offsetTop;
                        navMenu.scrollTo({
                            top: itemTop - 60,
                            behavior: "smooth",
                        });
                    }, 50);
                }
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

function readCookie(name) {
    const escaped = name.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const match = document.cookie.match(
        new RegExp("(?:^|; )" + escaped + "=([^;]*)"),
    );
    return match ? decodeURIComponent(match[1]) : "";
}

function getCsrfToken() {
    const meta = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    if (meta) return meta;
    return window.APP?.csrfToken || "";
}

function getCsrfHeaders(extraHeaders = {}) {
    const token = getCsrfToken();
    const xsrf = readCookie("XSRF-TOKEN");
    return {
        ...extraHeaders,
        ...(token ? { "X-CSRF-TOKEN": token } : {}),
        ...(xsrf ? { "X-XSRF-TOKEN": xsrf } : {}),
    };
}

window.getCsrfToken = getCsrfToken;
window.getCsrfHeaders = getCsrfHeaders;

const originalFetch = window.fetch;
window.fetch = async function (...args) {
    let res = await originalFetch.apply(this, args);
    if (res.status === 419) {
        try {
            const refreshRes = await originalFetch(window.location.href, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const html = await refreshRes.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const newToken = doc
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");
            if (newToken) {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute("content", newToken);
                if (window.APP) window.APP.csrfToken = newToken;

                if (args.length >= 2 && args[1] && args[1].headers) {
                    if (args[1].headers instanceof Headers) {
                        args[1].headers.set("X-CSRF-TOKEN", newToken);
                    } else {
                        args[1].headers["X-CSRF-TOKEN"] = newToken;
                    }
                }

                return await originalFetch.apply(this, args);
            }
        } catch (e) {
            console.error("CSRF refresh failed", e);
        }
        window.location.reload();
    }
    return res;
};

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

function setAuthFieldError(errorId, inputId, message) {
    const errEl = document.getElementById(errorId);
    const inputEl = document.getElementById(inputId);
    if (errEl) {
        errEl.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errEl.style.display = "flex";
    }
    if (inputEl) {
        inputEl.classList.add("error");
        inputEl.focus();
    }
    showFlash(message, "warning");
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i.test(String(email || "").trim());
}

function normalizePhone(phone) {
    return String(phone || "").replace(/\D/g, "");
}

function isValidVnPhone(phone) {
    return /^0\d{7,12}$/.test(phone);
}

async function authPost(url, data, btn, origHtml) {
    if (!window.APP)
        return { success: false, message: "Lỗi cấu hình hệ thống." };
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang xử lý...';
    try {
        const res = await fetch(url, {
            method: "POST",
            headers: getCsrfHeaders({
                "Content-Type": "application/json",
                Accept: "application/json",
            }),
            credentials: "same-origin",
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
        const email = document.getElementById("loginEmail").value.trim();
        const password = document.getElementById("loginPassword").value;

        if (!email) {
            setAuthFieldError(
                "errLoginEmail",
                "loginEmail",
                "Vui lòng nhập email.",
            );
            return;
        }
        if (!isValidEmail(email)) {
            setAuthFieldError(
                "errLoginEmail",
                "loginEmail",
                "Email không đúng định dạng.",
            );
            return;
        }
        if (!password) {
            setAuthFieldError(
                "errLoginPassword",
                "loginPassword",
                "Vui lòng nhập mật khẩu.",
            );
            return;
        }

        const btn = document.getElementById("btnAuthLogin");
        const data = await authPost(
            window.APP.routes.loginPost,
            {
                email,
                password,
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

        const hoTen = document.getElementById("regHoTen").value.trim();
        const email = document.getElementById("regEmail").value.trim();
        const soDienThoaiRaw = document.getElementById("regSdt").value.trim();
        const soDienThoai = normalizePhone(soDienThoaiRaw);
        const password = document.getElementById("regPassword").value;
        const passwordConfirmation =
            document.getElementById("regPasswordConfirm").value;

        if (!hoTen) {
            setAuthFieldError(
                "errRegHoTen",
                "regHoTen",
                "Vui lòng nhập họ và tên.",
            );
            return;
        }
        if (!email) {
            setAuthFieldError(
                "errRegEmail",
                "regEmail",
                "Vui lòng nhập email.",
            );
            return;
        }
        if (!isValidEmail(email)) {
            setAuthFieldError(
                "errRegEmail",
                "regEmail",
                "Email không đúng định dạng.",
            );
            return;
        }
        if (soDienThoaiRaw && !isValidVnPhone(soDienThoai)) {
            setAuthFieldError(
                "errRegSoDienThoai",
                "regSdt",
                "Số điện thoại sai định dạng.",
            );
            return;
        }
        if (!password || password.length < 6) {
            setAuthFieldError(
                "errRegPassword",
                "regPassword",
                "Mật khẩu phải có ít nhất 6 ký tự.",
            );
            return;
        }
        if (password !== passwordConfirmation) {
            setAuthFieldError(
                "errRegPasswordConfirmation",
                "regPasswordConfirm",
                "Xác nhận mật khẩu không khớp.",
            );
            return;
        }

        const btn = document.getElementById("btnAuthRegister");
        const data = await authPost(
            window.APP.routes.registerPost,
            {
                ho_ten: hoTen,
                email,
                so_dien_thoai: soDienThoaiRaw ? soDienThoai : "",
                password,
                password_confirmation: passwordConfirmation,
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
        } else if (data.errors) {
            showAuthErrors(data.errors);
            if (
                !data.errors.ho_ten &&
                !data.errors.email &&
                !data.errors.password &&
                !data.errors.so_dien_thoai &&
                !data.errors.password_confirmation
            ) {
                const errGeneral = document.getElementById("errRegGeneral");
                if (errGeneral) {
                    errGeneral.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message || "Đăng ký thất bại, vui lòng thử lại."}`;
                    errGeneral.style.color = "var(--status-danger)";
                    errGeneral.style.display = "block";
                }
            }
        } else {
            const errGeneral = document.getElementById("errRegGeneral");
            if (errGeneral) {
                errGeneral.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message || "Không thể đăng ký lúc này, vui lòng thử lại."}`;
                errGeneral.style.color = "var(--status-danger)";
                errGeneral.style.display = "block";
            }
        }
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
            headers: getCsrfHeaders({
                "Content-Type": "application/json",
            }),
            credentials: "same-origin",
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

        if (!_resetEmail) {
            setAuthFieldError(
                "errForgotEmail",
                "forgotEmail",
                "Vui lòng nhập email để nhận OTP.",
            );
            return;
        }
        if (!isValidEmail(_resetEmail)) {
            setAuthFieldError(
                "errForgotEmail",
                "forgotEmail",
                "Email không đúng định dạng.",
            );
            return;
        }

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
        const otp = document.getElementById("resetOtp").value.trim();
        const password = document.getElementById("resetNewPassword").value;
        const passwordConfirmation = document.getElementById(
            "resetNewPasswordConfirm",
        ).value;

        if (!_resetEmail || !isValidEmail(_resetEmail)) {
            setAuthFieldError(
                "errResetConfirmGeneral",
                "resetOtp",
                "Phiên đặt lại mật khẩu không hợp lệ. Vui lòng thử lại từ bước Quên mật khẩu.",
            );
            return;
        }
        if (!/^\d{6}$/.test(otp)) {
            setAuthFieldError(
                "errResetOtp",
                "resetOtp",
                "OTP phải gồm đúng 6 chữ số.",
            );
            return;
        }
        if (!password || password.length < 6) {
            setAuthFieldError(
                "errResetNewPassword",
                "resetNewPassword",
                "Mật khẩu mới phải có ít nhất 6 ký tự.",
            );
            return;
        }
        if (password !== passwordConfirmation) {
            setAuthFieldError(
                "errResetConfirmGeneral",
                "resetNewPasswordConfirm",
                "Xác nhận mật khẩu không khớp.",
            );
            return;
        }

        const btn = document.getElementById("btnAuthResetConfirm");
        const data = await authPost(
            window.APP.routes.resetPost,
            {
                email: _resetEmail,
                token: otp,
                password,
                password_confirmation: passwordConfirmation,
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
let feLastMessageId = 0;
let fePollingInFlight = false;
let feChatOpened = false;
let feLongPollController = null;
let feAiThinkingVisible = false;
let feAiThinkingTimer = null;
let feChatMode = "faq";
let feChatFaqCache = null;
let feAiQuickQuestions = [];
let feQuickRepliesSignature = "";
const FE_CHAT_POLL_INTERVAL_MS = 1200;

function escapeHtml(value) {
    return String(value || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function normalizeChatText(value) {
    const escaped = escapeHtml(value || "");
    return escaped.replace(/\n/g, "<br>");
}

function setAiThinking(show, mode = "bot") {
    const el = document.getElementById("chatAiThinking");
    if (!el) return;

    if (feAiThinkingTimer) {
        clearTimeout(feAiThinkingTimer);
        feAiThinkingTimer = null;
    }

    const label = el.querySelector(".chat-ai-thinking-label");
    if (label) {
        label.textContent =
            mode === "send" ? "Dang gui tin nhan" : "Gemini dang suy nghi";
    }

    feAiThinkingVisible = Boolean(show);
    el.style.display = show ? "block" : "none";

    if (show) {
        feAiThinkingTimer = setTimeout(() => {
            setAiThinking(false);
        }, 18000);
    }

    if (show) {
        const body = document.getElementById("chatBody");
        if (body) body.scrollTop = body.scrollHeight;
    }
}

function renderChatError(message) {
    const body = document.getElementById("chatBody");
    if (!body) return;
    body.innerHTML = `<div class="text-center mt-4 text-danger"><i class="fas fa-exclamation-triangle mb-2"></i><p>${message}</p></div>`;
}

function normalizeSenderClass(sender) {
    if (sender === "he_thong") return "fe-msg-system";
    if (sender === "nhan_vien" || sender === "bot") return "fe-msg-admin";
    return "fe-msg-customer";
}

function formatChatTime(dateLike) {
    if (!dateLike) return "";
    return new Date(dateLike).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
    });
}

function buildChatMessageHtml(msg) {
    const cls = normalizeSenderClass(msg.nguoi_gui);
    const safeContent = normalizeChatText(msg.noi_dung || "");
    let mediaHtml = "";
    if (msg.tep_dinh_kem) {
        const fileUrl = `/storage/${String(msg.tep_dinh_kem).replace(/^\/+/, "")}`;
        if (msg.loai_tin_nhan === "hinh_anh") {
            mediaHtml = `<div class="mt-1"><a href="${fileUrl}" target="_blank" rel="noopener noreferrer"><img src="${fileUrl}" alt="Hinh dinh kem" style="max-width:220px; max-height:220px; border-radius:10px; object-fit:cover;"></a></div>`;
        } else if (msg.loai_tin_nhan === "video") {
            mediaHtml = `<div class="mt-1"><video controls preload="metadata" style="max-width:220px; max-height:220px; border-radius:10px;"><source src="${fileUrl}"></video></div>`;
        }
    }
    const time =
        msg.nguoi_gui === "he_thong"
            ? ""
            : `<div class="fe-msg-time">${formatChatTime(msg.created_at)} ${
                  msg.nguoi_gui === "khach_hang"
                      ? '<i class="fas fa-check-double ms-1"></i>'
                      : ""
              }</div>`;
    return `<div class="fe-chat-row" data-msg-id="${msg.id || ""}"><div class="fe-msg-bubble ${cls}">${safeContent}${mediaHtml}${time}</div></div>`;
}

function appendMessages(messages, scrollToBottom = true) {
    const body = document.getElementById("chatBody");
    if (!body || !Array.isArray(messages) || messages.length === 0) return;

    const frag = document.createDocumentFragment();
    const wrap = document.createElement("div");
    let appended = 0;

    messages.forEach((msg) => {
        if (!msg || !msg.id) return;
        if (Number(msg.id) <= feLastMessageId) return;
        wrap.innerHTML = buildChatMessageHtml(msg);
        frag.appendChild(wrap.firstChild);
        feLastMessageId = Number(msg.id);
        appended++;

        if (msg.nguoi_gui === "bot" || msg.nguoi_gui === "nhan_vien") {
            setAiThinking(false);
        }
    });

    if (appended > 0) {
        body.appendChild(frag);
        feLastMessageCount += appended;
        if (scrollToBottom) body.scrollTop = body.scrollHeight;
    }
}

function appendOptimisticCustomerMessage(content) {
    const body = document.getElementById("chatBody");
    if (!body || !content) return;

    const now = new Date();
    const html = `<div class="fe-chat-row" data-msg-id="tmp-${now.getTime()}"><div class="fe-msg-bubble fe-msg-customer">${normalizeChatText(content)}<div class="fe-msg-time">${formatChatTime(now.toISOString())}</div></div></div>`;
    body.insertAdjacentHTML("beforeend", html);
    body.scrollTop = body.scrollHeight;
}

function resetPollingState() {
    if (feLongPollController) {
        feLongPollController.abort();
        feLongPollController = null;
    }
    fePollingInFlight = false;
}

function getChatContext() {
    const loai =
        document.getElementById("chatContextLoai")?.value ||
        window.APP?.chatContext?.loai ||
        "";
    const id =
        document.getElementById("chatContextId")?.value ||
        window.APP?.chatContext?.id ||
        "";
    const ten =
        document.getElementById("chatContextTen")?.value ||
        window.APP?.chatContext?.ten ||
        "";

    return {
        loai_ngu_canh: loai || null,
        ngu_canh_id: id ? parseInt(id, 10) : null,
        ten_ngu_canh: ten || null,
    };
}

function chatHistoryUrl(phienChatId) {
    return `${window.APP.routes.chatLichSu.replace("__ID__", phienChatId)}?limit=80`;
}

function chatLongPollUrl(phienChatId, sauId) {
    return `${window.APP.routes.chatLongPoll.replace("__ID__", phienChatId)}?sau_id=${sauId || 0}`;
}

function extractJsonPayload(rawText) {
    if (!rawText) return null;

    const startCandidates = [rawText.indexOf("{"), rawText.indexOf("[")]
        .filter((idx) => idx >= 0)
        .sort((a, b) => a - b);

    if (startCandidates.length === 0) return null;

    const start = startCandidates[0];
    const openChar = rawText[start];
    const closeChar = openChar === "{" ? "}" : "]";
    let depth = 0;
    let inString = false;
    let escaped = false;

    for (let i = start; i < rawText.length; i++) {
        const ch = rawText[i];

        if (inString) {
            if (escaped) {
                escaped = false;
            } else if (ch === "\\") {
                escaped = true;
            } else if (ch === '"') {
                inString = false;
            }
            continue;
        }

        if (ch === '"') {
            inString = true;
            continue;
        }

        if (ch === openChar) depth++;
        if (ch === closeChar) depth--;

        if (depth === 0) {
            return rawText.slice(start, i + 1);
        }
    }

    return null;
}

function getChatFaqItems() {
    if (Array.isArray(feChatFaqCache)) {
        return feChatFaqCache;
    }

    const fromDb = Array.isArray(window.APP?.chatFaq) ? window.APP.chatFaq : [];
    const normalized = fromDb
        .map((item) => ({
            question: String(item?.q || item?.question || "").trim(),
            answer: String(item?.a || item?.answer || "").trim(),
        }))
        .filter((item) => item.question && item.answer)
        .slice(0, 8);

    feChatFaqCache =
        normalized.length > 0
            ? normalized
            : [
                  {
                      question: "Lịch làm việc của văn phòng như thế nào?",
                      answer: "Văn phòng làm việc từ 8:00 đến 18:00 các ngày trong tuần, hỗ trợ xem nhà ngoài giờ theo lịch hẹn trước.",
                  },
                  {
                      question: "Dự án này hiện còn những loại căn nào?",
                      answer: "Hiện tại còn nhiều lựa chọn theo nhu cầu ở thực và đầu tư. Anh/chị để lại nhu cầu cụ thể, em sẽ gửi giỏ hàng phù hợp ngay.",
                  },
                  {
                      question: "Giá căn hộ hiện tại khoảng bao nhiêu?",
                      answer: "Giá phụ thuộc diện tích, tầng, view và nội thất. Em có thể gửi bảng giá mới nhất theo đúng phân khúc anh/chị quan tâm.",
                  },
                  {
                      question: "Chi phí sang tên và thuế phí gồm những gì?",
                      answer: "Thông thường gồm thuế, lệ phí trước bạ và phí công chứng theo quy định. Em sẽ hỗ trợ ước tính chi tiết theo từng giao dịch cụ thể.",
                  },
                  {
                      question: "Có hỗ trợ vay ngân hàng không?",
                      answer: "Bên em có hỗ trợ kết nối ngân hàng và tư vấn phương án vay phù hợp khả năng tài chính của anh/chị.",
                  },
              ];

    return feChatFaqCache;
}

function hideQuickReplies() {
    const wrap = document.getElementById("chatQuickReplies");
    if (!wrap) return;
    wrap.innerHTML = "";
    wrap.style.display = "none";
    feQuickRepliesSignature = "";
}

function appendLocalBotMessage(content) {
    const body = document.getElementById("chatBody");
    if (!body || !content) return;

    const html = `<div class="fe-chat-row" data-msg-id="faq-${Date.now()}"><div class="fe-msg-bubble fe-msg-admin">${normalizeChatText(content)}<div class="fe-msg-time">${formatChatTime(new Date().toISOString())}</div></div></div>`;
    body.insertAdjacentHTML("beforeend", html);
    body.scrollTop = body.scrollHeight;
}

function renderCollapsedMenu() {
    const wrap = document.getElementById("chatQuickReplies");
    if (!wrap) return;

    wrap.innerHTML = "";
    wrap.style.animation = "none";

    const expandBtn = document.createElement("button");
    expandBtn.type = "button";
    expandBtn.className = "chat-qr-btn is-expand";
    expandBtn.innerHTML =
        '<i class="fas fa-chevron-up me-1"></i> Mở rộng gợi ý';
    expandBtn.onclick = (e) => {
        e.preventDefault();
        feQuickRepliesSignature = "";
        if (feChatMode === "faq") {
            renderFaqMenu();
        } else if (feChatMode === "ai") {
            renderAiModeActions();
        }
    };
    wrap.appendChild(expandBtn);
    wrap.style.display = "flex";
}

function renderFaqMenu() {
    const wrap = document.getElementById("chatQuickReplies");
    if (!wrap) return;

    const faqItems = getChatFaqItems();
    const nextSignature = `faq:${faqItems.map((item) => item.question).join("|")}`;
    if (
        feQuickRepliesSignature === nextSignature &&
        wrap.style.display === "flex" &&
        wrap.childElementCount > 0
    ) {
        return;
    }

    feQuickRepliesSignature = nextSignature;
    wrap.innerHTML = "";
    wrap.style.animation = "none";

    faqItems.forEach((item, index) => {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "chat-qr-btn";
        btn.textContent = item.question;
        btn.style.animationDelay = `${index * 0.04}s`;
        btn.onclick = (e) => {
            e.preventDefault();
            appendOptimisticCustomerMessage(item.question);
            appendLocalBotMessage(item.answer);
        };
        wrap.appendChild(btn);
    });

    const aiBtn = document.createElement("button");
    aiBtn.type = "button";
    aiBtn.className = "chat-qr-btn is-ai";
    aiBtn.textContent = "Chat với AI";
    aiBtn.onclick = (e) => {
        e.preventDefault();
        feChatMode = "ai";
        setChatInputsEnabled(true);
        renderAiModeActions();
    };
    wrap.appendChild(aiBtn);

    const supportBtn = document.createElement("button");
    supportBtn.type = "button";
    supportBtn.className = "chat-qr-btn is-support";
    supportBtn.textContent = "Yêu cầu nhân viên hỗ trợ";
    supportBtn.onclick = (e) => {
        e.preventDefault();
        transferToAgent();
    };
    wrap.appendChild(supportBtn);

    const collapseBtn = document.createElement("button");
    collapseBtn.type = "button";
    collapseBtn.className = "chat-qr-btn is-collapse";
    collapseBtn.innerHTML = '<i class="fas fa-chevron-down me-1"></i> Thu gọn';
    collapseBtn.onclick = (e) => {
        e.preventDefault();
        renderCollapsedMenu();
    };
    wrap.appendChild(collapseBtn);

    wrap.style.display = "flex";
    setTimeout(() => {
        wrap.style.animation = "slideUp 0.3s ease-out";
    }, 10);
}

function renderAiModeActions(questions = null) {
    const wrap = document.getElementById("chatQuickReplies");
    if (!wrap) return;

    const quickQuestions = Array.isArray(questions)
        ? questions
        : feAiQuickQuestions;
    const nextSignature = `ai:${quickQuestions.join("|")}`;
    if (
        feQuickRepliesSignature === nextSignature &&
        wrap.style.display === "flex" &&
        wrap.childElementCount > 0
    ) {
        return;
    }

    feQuickRepliesSignature = nextSignature;

    wrap.innerHTML = "";
    wrap.style.animation = "none";

    if (Array.isArray(quickQuestions) && quickQuestions.length > 0) {
        quickQuestions.slice(0, 5).forEach((q, index) => {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "chat-qr-btn is-ai-suggest";
            btn.textContent = q;
            btn.style.animationDelay = `${index * 0.04}s`;
            btn.onclick = (e) => {
                e.preventDefault();
                document.getElementById("chatInput").value = q;
                sendFrontendMessage();
            };
            wrap.appendChild(btn);
        });
    }

    const supportBtn = document.createElement("button");
    supportBtn.type = "button";
    supportBtn.className = "chat-qr-btn is-support";
    supportBtn.textContent = "Yêu cầu nhân viên hỗ trợ";
    supportBtn.onclick = (e) => {
        e.preventDefault();
        transferToAgent();
    };
    wrap.appendChild(supportBtn);

    const collapseBtn = document.createElement("button");
    collapseBtn.type = "button";
    collapseBtn.className = "chat-qr-btn is-collapse";
    collapseBtn.innerHTML = '<i class="fas fa-chevron-down me-1"></i> Thu gọn';
    collapseBtn.onclick = (e) => {
        e.preventDefault();
        renderCollapsedMenu();
    };
    wrap.appendChild(collapseBtn);

    wrap.style.display = "flex";
    setTimeout(() => {
        wrap.style.animation = "slideUp 0.3s ease-out";
    }, 10);
}

// ── Hiển thị câu hỏi gợi ý ──
function renderQuickReplies(questions) {
    if (feChatMode !== "ai") {
        return;
    }
    feAiQuickQuestions = Array.isArray(questions) ? questions.slice(0, 5) : [];
    renderAiModeActions(feAiQuickQuestions);
}

// ── Sửa hàm xử lý response sau khi gửi tin nhắn ──
// Trong hàm nhận response từ server (longPoll hoặc sau guiTinNhan),
// tìm chỗ append tin nhắn bot và thêm:

function handleBotMessage(tinNhan, botData) {
    appendMessage(tinNhan); // hàm render tin nhắn cũ của bạn

    // Hiện câu hỏi gợi ý
    if (botData && botData.cau_hoi_goi_y) {
        renderQuickReplies(botData.cau_hoi_goi_y);
    }
}

async function parseApiJsonResponse(res) {
    const text = await res.text();
    const payload = (text || "").trim();

    if (!payload) {
        return { success: false, message: "May chu khong tra du lieu." };
    }

    try {
        return JSON.parse(payload);
    } catch (_) {
        const extracted = extractJsonPayload(payload);
        if (extracted) {
            try {
                return JSON.parse(extracted);
            } catch (_) {
                // Fall through to friendly error mapping below.
            }
        }

        const contentType = res.headers.get("content-type") || "";
        const isHtmlLike =
            contentType.includes("text/html") ||
            payload.startsWith("<!DOCTYPE") ||
            payload.startsWith("<html") ||
            payload.startsWith("<");

        if (isHtmlLike) {
            if (res.status === 419) {
                return {
                    success: false,
                    message:
                        "Phien het han. Vui long tai lai trang va thu lai.",
                };
            }

            return {
                success: false,
                message:
                    "May chu tra ve du lieu khong dung dinh dang JSON. Vui long thu lai.",
            };
        }

        return { success: false, message: "Phan hoi API khong hop le." };
    }
}

function setChatInputsEnabled(enabled) {
    const input = document.getElementById("chatInput");
    const sendBtn = document.getElementById("chatSendBtn");
    const attachBtn = document.getElementById("chatAttachBtn");
    if (input) input.disabled = !enabled;
    if (sendBtn) sendBtn.disabled = !enabled;
    if (attachBtn) attachBtn.disabled = !enabled;
}

window.openChatFilePicker = function () {
    const fileInput = document.getElementById("chatFileInput");
    if (fileInput && !fileInput.disabled) {
        fileInput.click();
    }
};

window.sendChatSelectedFile = function () {
    sendFrontendMessage();
};

function updateTransferButtonsByState(trangThai, dangBotXuLy) {
    const isClosed = trangThai === "da_dong";
    const isBotMode = Boolean(dangBotXuLy) || trangThai === "dang_bot";
    const isWaitingForAgent = trangThai === "dang_cho";

    if (isClosed) {
        feChatMode = "closed";
        setChatInputsEnabled(false);
        hideQuickReplies();
        return;
    }

    if (isWaitingForAgent || !isBotMode) {
        feChatMode = "agent";
        setChatInputsEnabled(true);
        hideQuickReplies();
        return;
    }

    if (feChatMode === "ai") {
        setChatInputsEnabled(true);
        renderAiModeActions();
        return;
    }

    // faq mode: always enable input so user can type freely
    feChatMode = "faq";
    setChatInputsEnabled(true);
    renderFaqMenu();
}

function syncChatExpandUi() {
    const win = document.getElementById("chatWindow");
    const backdrop = document.getElementById("chatWindowBackdrop");
    const icon = document.getElementById("chatExpandIcon");
    const btn = document.getElementById("chatExpandBtn");
    const expanded = win?.classList.contains("is-expanded");

    if (backdrop) {
        backdrop.classList.toggle("show", Boolean(expanded));
    }
    if (icon) {
        icon.className = expanded ? "fas fa-compress" : "fas fa-expand";
    }
    if (btn) {
        const title = expanded ? "Thu nhỏ khung chat" : "Phóng to khung chat";
        btn.setAttribute("title", title);
        btn.setAttribute("aria-label", title);
    }
}

window.toggleChatExpand = function (forceExpand) {
    const win = document.getElementById("chatWindow");
    if (!win || !win.classList.contains("show")) return;

    if (typeof forceExpand === "boolean") {
        win.classList.toggle("is-expanded", forceExpand);
    } else {
        win.classList.toggle("is-expanded");
    }

    syncChatExpandUi();
};

window.toggleChatWindow = function () {
    const win = document.getElementById("chatWindow");
    const btn = document.getElementById("chatWidgetBtn");
    const badge = document.getElementById("chatUnreadBadge");
    if (!win) return;

    if (win.classList.contains("show")) {
        win.classList.remove("is-expanded");
        syncChatExpandUi();
        win.classList.remove("show");
        btn.classList.remove("active");
        feChatOpened = false;
        setAiThinking(false);
        resetPollingState();
        clearInterval(feChatPolling);
    } else {
        win.classList.add("show");
        syncChatExpandUi();
        btn.classList.add("active");
        feChatOpened = true;
        if (badge) badge.style.display = "none";
        initFrontendChat();
    }
};

document.addEventListener("keydown", (e) => {
    if (e.key !== "Escape") return;
    const win = document.getElementById("chatWindow");
    if (!win?.classList.contains("is-expanded")) return;
    win.classList.remove("is-expanded");
    syncChatExpandUi();
});

function startPolling(phienChatId) {
    clearInterval(feChatPolling);

    const tick = () => {
        if (!feChatOpened || fePollingInFlight) return;
        fePollingInFlight = true;
        feLongPollController = new AbortController();

        fetch(chatLongPollUrl(phienChatId, feLastMessageId), {
            headers: { Accept: "application/json" },
            signal: feLongPollController.signal,
        })
            .then((res) => parseApiJsonResponse(res))
            .then((data) => {
                if (!data.success || !Array.isArray(data.tin_nhans)) return;
                updateTransferButtonsByState(
                    data.trang_thai,
                    data.dang_bot_xu_ly,
                );
                if (!Boolean(data.dang_bot_xu_ly)) {
                    setAiThinking(false);
                }
                appendMessages(data.tin_nhans, true);
            })
            .catch(() => {})
            .finally(() => {
                feLongPollController = null;
                fePollingInFlight = false;
            });
    };

    tick();
    feChatPolling = setInterval(() => {
        tick();
    }, FE_CHAT_POLL_INTERVAL_MS);
}

function initChatSession(payload = {}) {
    if (feLastMessageCount === 0) {
        document.getElementById("chatBody").innerHTML =
            '<div class="text-center mt-4"><div class="spinner-grow text-primary spinner-grow-sm"></div><p class="text-muted small mt-2">Đang kết nối...</p></div>';
    }

    fetch(window.APP.routes.chatKhoiTao, {
        method: "POST",
        headers: getCsrfHeaders({
            "Content-Type": "application/json",
            Accept: "application/json",
        }),
        credentials: "same-origin",
        body: JSON.stringify({
            ...getChatContext(),
            ...payload,
        }),
    })
        .then(async (res) => {
            const data = await parseApiJsonResponse(res);
            if (!res.ok || !data.success) {
                throw new Error(data.message || "Không thể khởi tạo chat.");
            }
            return data;
        })
        .then((data) => {
            document.getElementById("chatKhachHangChuaDangNhap").style.display =
                "none";
            document.getElementById("currentPhienChatId").value =
                data.phien_chat_id;
            feLastMessageId = 0;
            feLastMessageCount = 0;
            feChatMode = "faq";
            feAiQuickQuestions = [];
            // Enable input immediately so user can type straight away
            setChatInputsEnabled(true);
            setAiThinking(false);
            updateTransferButtonsByState("dang_bot", true);
            fetchFrontendMessages();
            startPolling(data.phien_chat_id);
        })
        .catch((err) => {
            renderChatError(err.message || "Lỗi kết nối.");
            setChatInputsEnabled(false);
        });
}

function initFrontendChat() {
    const guestEl = document.getElementById("chatKhachHangChuaDangNhap");
    if (!window.APP) {
        renderChatError("Không tải được cấu hình chat.");
        setAiThinking(false);
        return;
    }

    // Always hide the guest gate — all visitors can chat immediately
    if (guestEl) guestEl.style.display = "none";
    initChatSession();
}

window.startGuestChat = function (e) {
    if (e?.preventDefault) {
        e.preventDefault();
    }

    initChatSession();
};

function fetchFrontendMessages() {
    const phienChatId = document.getElementById("currentPhienChatId")?.value;
    if (!phienChatId) return;

    fetch(chatHistoryUrl(phienChatId), {
        headers: { Accept: "application/json" },
    })
        .then((res) => parseApiJsonResponse(res))
        .then((data) => {
            if (!data.success || !Array.isArray(data.tin_nhans)) return;
            updateTransferButtonsByState(data.trang_thai, data.dang_bot_xu_ly);
            renderFrontendMessages(data.tin_nhans);
        })
        .catch(() => {}); // Bỏ qua lỗi mạng, polling sẽ tự thử lại
}
function renderFrontendMessages(messages) {
    const body = document.getElementById("chatBody");
    if (!body || !Array.isArray(messages)) return;

    feLastMessageId = 0;
    feLastMessageCount = 0;
    body.innerHTML = "";
    appendMessages(messages, true);
}

window.sendFrontendMessage = function (e) {
    if (e?.preventDefault) {
        e.preventDefault();
    }

    const phienChatId = document.getElementById("currentPhienChatId")?.value;
    const noiDung = document.getElementById("chatInput")?.value?.trim();
    const fileInput = document.getElementById("chatFileInput");
    const selectedFile = fileInput?.files?.[0] || null;
    const sendBtn = document.getElementById("chatSendBtn");

    // ✅ Kiểm tra trước khi gửi
    if (!phienChatId || (!noiDung && !selectedFile)) return;

    if (noiDung) {
        appendOptimisticCustomerMessage(noiDung);
    }
    document.getElementById("chatInput").value = "";
    setAiThinking(true, "send");
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
    }

    const formData = new FormData();
    formData.append("phien_chat_id", String(phienChatId));
    if (noiDung) formData.append("noi_dung", noiDung);
    if (selectedFile) formData.append("tep_tin", selectedFile);

    fetch(window.APP.routes.chatGui, {
        method: "POST",
        headers: getCsrfHeaders({
            Accept: "application/json",
        }),
        credentials: "same-origin",
        body: formData,
    })
        .then(async (res) => {
            const data = await parseApiJsonResponse(res);
            if (!res.ok || !data.success) {
                throw new Error(data.message || "Gửi tin nhắn thất bại.");
            }
            return data;
        })
        .then((data) => {
            resetPollingState();
            fetchFrontendMessages();
            setTimeout(fetchFrontendMessages, 700);
            if (data.da_chuyen_nhan_vien) {
                showFlash(
                    "Đã chuyển cuộc trò chuyện sang nhân viên kinh doanh.",
                    "success",
                );
            }

            // Nếu có câu hỏi gợi ý (bình thường hoặc fallback) → hiển thị
            if (data.cau_hoi_goi_y && Array.isArray(data.cau_hoi_goi_y)) {
                setTimeout(() => {
                    renderQuickReplies(data.cau_hoi_goi_y);
                }, 300);
            }
        })
        .catch((err) => {
            showFlash(err.message || "Không gửi được tin nhắn.", "danger");
            setAiThinking(false);
            fetchFrontendMessages();
        })
        .finally(() => {
            if (fileInput) {
                fileInput.value = "";
            }
            if (sendBtn) {
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            }
        });
};

document.getElementById("chatFileInput")?.addEventListener("change", () => {
    const fileInput = document.getElementById("chatFileInput");
    const file = fileInput?.files?.[0] || null;
    if (!file) return;

    const okType = /^(image\/.+|video\/.+)$/.test(file.type || "");
    if (!okType) {
        showFlash("Chi ho tro anh hoac video.", "warning");
        fileInput.value = "";
        return;
    }

    if (file.size > 20 * 1024 * 1024) {
        showFlash("Tep vuot qua 20MB.", "warning");
        fileInput.value = "";
        return;
    }

    sendFrontendMessage();
});

window.transferToAgent = function () {
    const phienChatId = document.getElementById("currentPhienChatId")?.value;
    if (!phienChatId) {
        showFlash(
            "Vui lòng bắt đầu chat trước khi chuyển cho nhân viên.",
            "warning",
        );
        return;
    }

    fetch(window.APP.routes.chatChuyenNV, {
        method: "POST",
        headers: getCsrfHeaders({
            "Content-Type": "application/json",
            Accept: "application/json",
        }),
        credentials: "same-origin",
        body: JSON.stringify({ phien_chat_id: phienChatId }),
    })
        .then((res) => parseApiJsonResponse(res))
        .then((data) => {
            if (data.success) {
                feChatMode = "agent";
                updateTransferButtonsByState("dang_cho", false);
                hideQuickReplies();
                showFlash(
                    "Yêu cầu đã được chuyển cho nhân viên kinh doanh.",
                    "success",
                );
                fetchFrontendMessages();
                return;
            }
            showFlash(
                data.message || "Không thể chuyển cho nhân viên.",
                "warning",
            );
        })
        .catch(() => showFlash("Không thể chuyển cho nhân viên.", "danger"));
};

window.transferBackToBot = function () {
    const phienChatId = document.getElementById("currentPhienChatId")?.value;
    if (!phienChatId) {
        showFlash("Vui lòng bắt đầu chat trước.", "warning");
        return;
    }

    fetch(window.APP.routes.chatChuyenBot, {
        method: "POST",
        headers: getCsrfHeaders({
            "Content-Type": "application/json",
            Accept: "application/json",
        }),
        credentials: "same-origin",
        body: JSON.stringify({ phien_chat_id: phienChatId }),
    })
        .then((res) => parseApiJsonResponse(res))
        .then((data) => {
            if (!data.success) {
                showFlash(
                    data.message || "Không thể chuyển lại AI.",
                    "warning",
                );
                return;
            }

            feChatMode = "faq";
            updateTransferButtonsByState("dang_bot", true);
            showFlash("Đã chuyển lại cho trợ lý AI.", "success");
            fetchFrontendMessages();
        })
        .catch(() => showFlash("Không thể chuyển lại AI.", "danger"));
};

/* =========================================================
   SO SÁNH BẤT ĐỘNG SẢN LOGIC
========================================================= */
// 1. Lấy dữ liệu từ LocalStorage chuẩn 1 key duy nhất
window.soSanhList = JSON.parse(localStorage.getItem("tcl_sosanh") || "[]");

window.addSoSanh = function (id, ten) {
    id = parseInt(id);
    if (window.soSanhList.find((x) => x.id === id)) {
        showFlash("BĐS này đã có trong danh sách so sánh.", "info");
        return;
    }
    if (window.soSanhList.length >= 3) {
        showFlash("Chỉ so sánh tối đa 3 BĐS cùng lúc.", "warning");
        return;
    }
    window.soSanhList.push({ id, ten });
    localStorage.setItem("tcl_sosanh", JSON.stringify(window.soSanhList));

    window.renderSoSanhBar();
    showFlash("Đã thêm vào so sánh.", "success");
};

window.removeSoSanh = function (id) {
    window.soSanhList = window.soSanhList.filter((x) => x.id !== parseInt(id));
    localStorage.setItem("tcl_sosanh", JSON.stringify(window.soSanhList));

    window.renderSoSanhBar();
    const modalEl = document.getElementById("soSanhModal");
    if (modalEl && modalEl.classList.contains("show")) {
        if (window.soSanhList.length >= 2) window.openSoSanhModal();
        else bootstrap.Modal.getInstance(modalEl)?.hide();
    }
};

window.clearSoSanh = function () {
    window.soSanhList = [];
    localStorage.setItem("tcl_sosanh", "[]");
    window.renderSoSanhBar();

    const modalEl = document.getElementById("soSanhModal");
    if (modalEl && modalEl.classList.contains("show")) {
        bootstrap.Modal.getInstance(modalEl)?.hide();
    }
    showFlash("Đã xóa toàn bộ.", "info");
};

window.renderSoSanhBar = function () {
    const bar = document.getElementById("so-sanh-bar");
    if (!bar) return;

    if (window.soSanhList.length === 0) {
        bar.style.display = "none";
        return;
    }

    bar.style.display = "block";
    if (document.getElementById("ssSanhCount")) {
        document.getElementById("ssSanhCount").textContent =
            window.soSanhList.length;
    }

    const itemsEl = document.getElementById("ssSanhItems");
    if (itemsEl) {
        itemsEl.innerHTML = window.soSanhList
            .map((item) => {
                const shortName =
                    item.ten.length > 22
                        ? item.ten.substring(0, 22) + "…"
                        : item.ten;
                return `<span class="ss-item-chip">${shortName} <button class="ss-item-remove" onclick="removeSoSanh(${item.id})"><i class="fas fa-times"></i></button></span>`;
            })
            .join("");
    }
};

window.openSoSanhModal = function () {
    if (window.soSanhList.length === 0) return;
    const modalEl = document.getElementById("soSanhModal");
    const modalBody = document.getElementById("soSanhModalBody");
    if (!modalEl) return;

    if (document.getElementById("soSanhModalCount")) {
        document.getElementById("soSanhModalCount").textContent =
            window.soSanhList.length + " BĐS";
    }

    if (modalBody) {
        modalBody.innerHTML =
            '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted fw-bold">Đang tải...</p></div>';
    }
    bootstrap.Modal.getOrCreateInstance(modalEl).show();

    const ids = window.soSanhList.map((item) => item.id).join(",");
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
    if (window.soSanhList.length === 0) {
        showFlash("Chưa có BĐS nào.", "warning");
        return;
    }
    window.open(
        `${window.APP.routes.soSanhIndex}?ids=${window.soSanhList.map((x) => x.id).join(",")}`,
        "_blank",
    );
};

// TỰ ĐỘNG TẢI LẠI THANH SO SÁNH KHI CHUYỂN TRANG
document.addEventListener("DOMContentLoaded", function () {
    window.renderSoSanhBar();
});
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

    // --- Xác thực phía client ---
    let hasErr = false;
    const hoTen = document.getElementById("f_ho_ten").value.trim();
    const sdt = document.getElementById("f_so_dien_thoai").value.trim();
    const errHoTen = document.getElementById("err_ho_ten");
    const errSdt = document.getElementById("err_so_dien_thoai");

    // Reset lỗi
    if (errHoTen) errHoTen.textContent = "";
    if (errSdt) errSdt.textContent = "";

    if (!hoTen) {
        if (errHoTen) errHoTen.textContent = "Vui lòng nhập họ và tên.";
        hasErr = true;
    }
    if (sdt && !/^[0-9+\- ]{7,20}$/.test(sdt)) {
        if (errSdt) errSdt.textContent = "Số điện thoại không hợp lệ.";
        hasErr = true;
    }
    if (hasErr) return;

    const btn = document.getElementById("btnSubmitThongTin");
    const orig = '<i class="fas fa-save"></i> Lưu thay đổi';
    postKhachHang(
        window.APP.routes.profileUpdate,
        {
            ho_ten: hoTen,
            so_dien_thoai: sdt,
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
    const pwCu = document.getElementById("f_mat_khau_cu").value;

    const errCu = document.getElementById("err_mat_khau_cu");
    const errMoi = document.getElementById("err_mat_khau_moi");
    const errConfirm = document.getElementById("err_mat_khau_moi_confirmation");

    // Reset lỗi cũ
    if (errCu) errCu.textContent = "";
    if (errMoi) errMoi.textContent = "";
    if (errConfirm) errConfirm.textContent = "";

    // Validate phía client
    let hasErr = false;
    if (!pwCu) {
        if (errCu) errCu.textContent = "Vui lòng nhập mật khẩu hiện tại.";
        hasErr = true;
    }
    if (!pw1) {
        if (errMoi) errMoi.textContent = "Vui lòng nhập mật khẩu mới.";
        hasErr = true;
    } else if (pw1.length < 6) {
        if (errMoi)
            errMoi.textContent = "Mật khẩu mới phải có ít nhất 6 ký tự.";
        hasErr = true;
    }
    if (pw1 && pw2 && pw1 !== pw2) {
        if (errConfirm)
            errConfirm.textContent = "Xác nhận mật khẩu không khớp.";
        hasErr = true;
    }
    if (hasErr) return;

    const btn = document.getElementById("btnSubmitMk");
    const orig = '<i class="fas fa-shield-alt"></i> Cập nhật';
    postKhachHang(
        window.APP.routes.changePassword,
        {
            mat_khau_cu: pwCu,
            mat_khau_moi: pw1,
            mat_khau_moi_confirmation: pw2,
        },
        btn,
        orig,
        () => {
            // Dùng optional chaining để tránh crash nếu form không tìm thấy
            document.getElementById("formMatKhau")?.reset();
            closeModalHoSo();
        },
    );
};

function postKhachHang(url, data, btn, orig, onSuccess) {
    const fieldInputMap = {
        ho_ten: "f_ho_ten",
        so_dien_thoai: "f_so_dien_thoai",
        email: "f_email",
        mat_khau_cu: "f_mat_khau_cu",
        mat_khau_moi: "f_mat_khau_moi",
        mat_khau_moi_confirmation: "f_mat_khau_moi_confirmation",
    };

    const normalizeFieldKey = (key) => {
        if (!key) return "";
        const normalized = String(key).replace(/\./g, "_");
        // Laravel rule `confirmed` thường trả lỗi ở field gốc.
        if (normalized === "mat_khau_moi") {
            return "mat_khau_moi_confirmation";
        }
        return normalized;
    };

    const showInlineError = (rawKey, message) => {
        const key = normalizeFieldKey(rawKey);
        const errEl =
            document.getElementById(`err_${key}`) ||
            document.getElementById(`err${key}`);
        const inputId = fieldInputMap[key] || `f_${key}`;
        const inputEl = document.getElementById(inputId);

        if (errEl) errEl.textContent = message;
        if (inputEl) inputEl.classList.add("error");

        return Boolean(errEl || inputEl);
    };

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

    // Reset tất cả field errors trước khi gửi
    document
        .querySelectorAll(".kh-field-err")
        .forEach((el) => (el.textContent = ""));
    document
        .querySelectorAll(".kh-field-input")
        .forEach((el) => el.classList.remove("error"));

    fetch(url, {
        method: "POST",
        headers: getCsrfHeaders({
            "Content-Type": "application/json",
            Accept: "application/json",
        }),
        credentials: "same-origin",
        body: JSON.stringify(data),
    })
        .then(async (r) => {
            const contentType = r.headers.get("content-type") || "";
            const isJson = contentType.includes("application/json");
            const payload = isJson
                ? await r.json()
                : {
                      success: false,
                      message: "Không đọc được phản hồi từ máy chủ.",
                  };
            return { ok: r.ok, status: r.status, payload };
        })
        .then(({ ok, status, payload: res }) => {
            if (res.success) {
                showFlash(res.message || "Cập nhật thành công!", "success");
                if (onSuccess) onSuccess();
            } else {
                const errors = res.errors || {};
                // Nếu có lỗi field cụ thể → hiện inline, KHÔNG hiện toast chung
                if (Object.keys(errors).length > 0) {
                    let firstMsg = null;
                    let hasMappedField = false;

                    Object.entries(errors).forEach(([key, msgs]) => {
                        const msg = Array.isArray(msgs) ? msgs[0] : msgs;
                        if (!firstMsg) firstMsg = msg;
                        if (showInlineError(key, msg)) hasMappedField = true;
                    });

                    // Chỉ toast nếu không có field nào match
                    if (!hasMappedField) {
                        showFlash(
                            res.message ||
                                firstMsg ||
                                "Vui lòng kiểm tra lại thông tin.",
                            "danger",
                        );
                    }
                } else {
                    if (!ok && status === 419) {
                        showFlash(
                            "Phiên làm việc đã hết hạn, vui lòng tải lại trang.",
                            "warning",
                        );
                    } else {
                        showFlash(res.message || "Có lỗi xảy ra.", "danger");
                    }
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

window.toggleMoTa = function () {
    const el = document.getElementById("motaPreview");
    const icon = document.getElementById("iconReadMore");
    const txt = document.getElementById("txtReadMore");
    const fade = document.getElementById("motaFade");
    const btn = document.getElementById("btnReadMore");

    if (!el || !icon || !txt) return;

    const isExpanded = el.classList.contains("expanded");
    el.classList.toggle("expanded", !isExpanded);
    icon.className = isExpanded ? "fas fa-chevron-down" : "fas fa-chevron-up";
    txt.textContent = isExpanded ? "Xem thêm nội dung" : "Thu gọn";

    if (fade) fade.style.display = isExpanded ? "block" : "none";
    if (btn) btn.setAttribute("aria-expanded", isExpanded ? "false" : "true");
};

function applyYeuThichUiById(batDongSanId, isLiked) {
    document
        .querySelectorAll(`.heart-icon-${batDongSanId}`)
        .forEach((targetBtn) => {
            const icon = targetBtn.querySelector("i");
            targetBtn.classList.toggle("liked", isLiked);
            targetBtn.title = isLiked ? "Bỏ yêu thích" : "Lưu tin";
            if (icon) {
                icon.className = isLiked ? "fas fa-heart" : "far fa-heart";
            }
        });
}

window.applyYeuThichUiById = applyYeuThichUiById;

window.toggleYeuThich = function (btn, batDongSanId) {
    if (!window.APP || !window.APP.isLoggedIn) {
        window.openAuthModal("login");
        showFlash("Vui lòng đăng nhập để lưu yêu thích.", "info");
        return;
    }
    var wasLiked = btn.classList.contains("liked");
    applyYeuThichUiById(batDongSanId, !wasLiked);

    fetch(window.APP.routes.yeuThichToggle, {
        method: "POST",
        headers: getCsrfHeaders({
            "Content-Type": "application/json",
            Accept: "application/json",
        }),
        credentials: "same-origin",
        body: JSON.stringify({ bat_dong_san_id: batDongSanId }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                applyYeuThichUiById(batDongSanId, data.is_liked);
                if (typeof showFlash === "function")
                    showFlash(
                        data.is_liked
                            ? "Đã lưu tin vào mục yêu thích."
                            : "Đã bỏ lưu tin yêu thích.",
                        data.is_liked ? "success" : "info",
                    );
            } else {
                applyYeuThichUiById(batDongSanId, wasLiked);
                showFlash(
                    data.message || "Không thể lưu tin lúc này.",
                    "warning",
                );
            }
        })
        .catch(() => {
            applyYeuThichUiById(batDongSanId, wasLiked);
            showFlash("Lỗi kết nối. Vui lòng thử lại.", "danger");
        });
};
