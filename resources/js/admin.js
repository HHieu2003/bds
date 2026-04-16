"use strict";

/* =========================================================
   1. SIDEBAR
========================================================= */
(function () {
    var collapsed = localStorage.getItem("sidebarCollapsed") === "true";

    function applyState() {
        var sb = document.getElementById("sidebar");
        var tb = document.getElementById("topbar");
        var mw = document.getElementById("main-wrapper");
        if (!sb) return;
        if (window.innerWidth > 992) {
            sb.classList.toggle("collapsed", collapsed);
            tb && tb.classList.toggle("sidebar-collapsed", collapsed);
            mw && mw.classList.toggle("sidebar-collapsed", collapsed);
        } else {
            // Mobile: always show full menu labels, ignore desktop collapsed state.
            sb.classList.remove("collapsed");
            tb && tb.classList.remove("sidebar-collapsed");
            mw && mw.classList.remove("sidebar-collapsed");
        }
    }
    applyState();

    window.toggleSidebar = function () {
        var sb = document.getElementById("sidebar");
        var ov = document.getElementById("sidebarOverlay");
        if (window.innerWidth <= 992) {
            sb && sb.classList.toggle("mobile-open");
            ov && ov.classList.toggle("show");
        } else {
            collapsed = !collapsed;
            localStorage.setItem("sidebarCollapsed", collapsed);
            applyState();
        }
    };

    window.closeMobileSidebar = function () {
        document.getElementById("sidebar")?.classList.remove("mobile-open");
        document.getElementById("sidebarOverlay")?.classList.remove("show");
    };

    var resizeTimer;
    window.addEventListener("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (window.innerWidth > 992) {
                closeMobileSidebar();
                applyState();
            }
        }, 100);
    });
})();

/* =========================================================
   2. TOPBAR USER DROPDOWN
========================================================= */
(function () {
    window.toggleUserDropdown = function () {
        var dd = document.getElementById("userDropdown");
        var btn = document.getElementById("topbarUserBtn");
        var ch = btn?.querySelector(".topbar-user-chevron");
        if (!dd) return;
        var open = dd.classList.toggle("show");
        if (ch) ch.style.transform = open ? "rotate(180deg)" : "";
    };
    document.addEventListener("click", function (e) {
        if (!e.target.closest("#topbarUserBtn")) {
            var dd = document.getElementById("userDropdown");
            var ch = document.querySelector(".topbar-user-chevron");
            dd?.classList.remove("show");
            if (ch) ch.style.transform = "";
        }
    });
})();

/* =========================================================
   3. FLASH — AUTO DISMISS (Bootstrap .alert)
   Bootstrap .alert-dismissible + data-bs-dismiss="alert"
   tự xử lý nút X, chỉ cần tự dismiss sau 5s
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll(".alert:not(.alert-permanent)")
        .forEach(function (el, i) {
            setTimeout(
                function () {
                    // Ưu tiên Bootstrap Alert API, fallback về remove thủ công.
                    if (window.bootstrap?.Alert) {
                        var bsAlert =
                            window.bootstrap.Alert.getOrCreateInstance(el);
                        bsAlert && bsAlert.close();
                    } else {
                        el.remove();
                    }
                },
                5000 + i * 300,
            );
        });
});

/* =========================================================
   4. AJAX UTILITIES
========================================================= */
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
}

window.ajaxToggle = function (url, checkbox, onSuccess) {
    fetch(url, {
        method: "PATCH",
        headers: { "X-CSRF-TOKEN": getCsrf(), Accept: "application/json" },
    })
        .then((r) => r.json())
        .then((d) => {
            if (d.ok) {
                showAdminToast(d.msg || "Cập nhật thành công!", "success");
                if (typeof onSuccess === "function") onSuccess(d);
            } else {
                checkbox.checked = !checkbox.checked;
                showAdminToast(d.msg || "Không thể thực hiện!", "error");
            }
        })
        .catch(() => {
            checkbox.checked = !checkbox.checked;
            showAdminToast("Lỗi kết nối!", "error");
        });
};

window.ajaxPost = function (url, data, onSuccess, onError, method) {
    fetch(url, {
        method: method || "POST",
        headers: {
            "X-CSRF-TOKEN": getCsrf(),
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((r) => r.json())
        .then((res) => {
            if (res.ok || res.success) {
                if (typeof onSuccess === "function") onSuccess(res);
            } else {
                showAdminToast(
                    res.message || res.msg || "Có lỗi xảy ra!",
                    "error",
                );
                if (typeof onError === "function") onError(res);
            }
        })
        .catch(() => {
            showAdminToast("Lỗi kết nối, thử lại sau!", "error");
            if (typeof onError === "function") onError(null);
        });
};

/* =========================================================
   5. TOAST NOTIFICATION
   Tự viết vì cần style riêng (màu Navy/Orange brand).
   Bootstrap Toast có thể dùng cho toàn trang nhưng
   showAdminToast() tiện hơn cho dynamic JS calls.
========================================================= */
var _toastPalette = {
    success: {
        bg: "#f0fff4",
        borderL: "#27ae60",
        color: "#1a7a45",
        icon: "fa-check-circle",
    },
    error: {
        bg: "#fff5f5",
        borderL: "#e74c3c",
        color: "#c0392b",
        icon: "fa-times-circle",
    },
    warning: {
        bg: "#fffbeb",
        borderL: "#f59e0b",
        color: "#92400e",
        icon: "fa-exclamation-triangle",
    },
    info: {
        bg: "#eff8ff",
        borderL: "#2563eb",
        color: "#1e40af",
        icon: "fa-info-circle",
    },
};

window.showAdminToast = function (message, type, duration) {
    type = type || "info";
    duration = duration || 4000;
    var tone = _toastPalette[type] || _toastPalette.info;

    var root = document.getElementById("adminToastRoot");
    if (!root) {
        root = document.createElement("div");
        root.id = "adminToastRoot";
        document.body.appendChild(root);
    }

    var t = document.createElement("div");
    t.className = "admin-toast";
    t.style.cssText = `background:${tone.bg};border-left:4px solid ${tone.borderL};color:${tone.color}`;
    t.innerHTML =
        `<i class="fas ${tone.icon}" style="font-size:.9rem;flex-shrink:0"></i>` +
        `<span style="flex:1;line-height:1.4">${message}</span>` +
        `<button onclick="this.closest('.admin-toast').remove()" style="background:none;border:none;cursor:pointer;color:inherit;opacity:.45;padding:0;font-size:.78rem" ` +
        `onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.45"><i class="fas fa-times"></i></button>`;
    root.appendChild(t);

    setTimeout(function () {
        t.style.transition = "opacity .3s, transform .3s";
        t.style.opacity = "0";
        t.style.transform = "translateX(30px)";
        setTimeout(() => t.remove(), 320);
    }, duration);
    return t;
};

/* =========================================================
   6. CONFIRM DELETE MODAL (custom — không dùng Bootstrap Modal
   vì cần API confirmDelete(label, callback) đồng bộ toàn trang)
========================================================= */
var _confirmCb = null;

window.confirmDelete = function (label, callback, sub) {
    var modal = document.getElementById("confirmModal");
    if (!modal) {
        if (
            confirm(`Xóa "${label}"?\n${sub || "Thao tác không thể hoàn tác!"}`)
        )
            callback();
        return;
    }
    var nameEl = modal.querySelector(".confirm-name");
    var subEl = modal.querySelector(".confirm-sub");
    if (nameEl) nameEl.textContent = label;
    if (subEl) subEl.textContent = sub || "Thao tác này không thể hoàn tác!";
    _confirmCb = callback;
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
};

window.closeConfirmModal = function () {
    document.getElementById("confirmModal")?.classList.remove("show");
    document.body.style.overflow = "";
    _confirmCb = null;
};

window.executeConfirm = function () {
    if (typeof _confirmCb === "function") _confirmCb();
    closeConfirmModal();
};

document.addEventListener("click", (e) => {
    if (e.target.id === "confirmModal") closeConfirmModal();
});
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeConfirmModal();
});

/* =========================================================
   7. FORM HELPERS
========================================================= */
window.togglePwEye = function (inputId, iconId) {
    var inp = document.getElementById(inputId);
    var ico = document.getElementById(iconId);
    if (!inp) return;
    inp.type = inp.type === "password" ? "text" : "password";
    if (ico)
        ico.className =
            inp.type === "password" ? "fas fa-eye" : "fas fa-eye-slash";
};

window.previewImage = function (inputEl, imgElOrId) {
    if (!inputEl?.files?.[0]) return;
    var img =
        typeof imgElOrId === "string"
            ? document.getElementById(imgElOrId)
            : imgElOrId;
    var reader = new FileReader();
    reader.onload = (e) => {
        if (img) img.src = e.target.result;
    };
    reader.readAsDataURL(inputEl.files[0]);
};

window.previewMultiImages = function (inputEl, gridEl) {
    if (!inputEl || !gridEl) return;
    gridEl.innerHTML = "";
    Array.from(inputEl.files || []).forEach(function (file, idx) {
        if (!file.type.startsWith("image/")) return;
        var reader = new FileReader();
        reader.onload = (e) => {
            var item = document.createElement("div");
            item.className = "img-preview-item";
            item.innerHTML =
                `<img src="${e.target.result}" alt="Preview ${idx}">` +
                `<button type="button" class="img-preview-remove" onclick="this.closest('.img-preview-item').remove()">` +
                `<i class="fas fa-times"></i></button>`;
            gridEl.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
};

window.selectRoleCard = function (val, clickedEl) {
    clickedEl
        .closest(".role-card-grid")
        .querySelectorAll(".role-card")
        .forEach((c) => c.classList.remove("selected"));
    clickedEl.classList.add("selected");
    var radio = clickedEl.querySelector("input[type=radio]");
    if (radio) radio.checked = true;
};

window.updateToggleLabel = function (checkboxEl, labelEl) {
    if (!checkboxEl || !labelEl) return;
    function update() {
        labelEl.textContent = checkboxEl.checked
            ? "Đang hoạt động"
            : "Vô hiệu hóa";
        labelEl.className =
            "toggle-label " + (checkboxEl.checked ? "on" : "off");
    }
    checkboxEl.addEventListener("change", update);
    update();
};

window.btnLoading = function (el, text) {
    if (!el) return;
    el._orig = el.innerHTML;
    el._dis = el.disabled;
    el.disabled = true;
    el.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> ${text || "Đang xử lý..."}`;
};

window.btnRestore = function (el) {
    if (!el || !el._orig) return;
    el.innerHTML = el._orig;
    el.disabled = el._dis || false;
    delete el._orig;
    delete el._dis;
};

window.validateField = function (inputId, errorId, label) {
    var el = document.getElementById(inputId);
    var err = document.getElementById(errorId);
    if (!el) return true;
    var empty = !el.value.trim();
    el.classList.toggle("is-invalid", empty);
    if (err) {
        err.innerHTML = empty
            ? `<i class="fas fa-exclamation-circle"></i> ${label || "Trường này"} không được để trống`
            : "";
        err.style.display = empty ? "flex" : "none";
    }
    if (empty) el.focus();
    return !empty;
};

/* =========================================================
   8. TABLE HELPERS
========================================================= */
window.initSelectAll = function (selectAllId, rowClass) {
    var sa = document.getElementById(selectAllId);
    if (!sa) return;
    function syncHeader() {
        var all = document.querySelectorAll("." + rowClass);
        var checked = document.querySelectorAll("." + rowClass + ":checked");
        sa.indeterminate = checked.length > 0 && checked.length < all.length;
        sa.checked = checked.length > 0 && checked.length === all.length;
        _updateBulkBar(checked.length);
    }
    sa.addEventListener("change", function () {
        document
            .querySelectorAll("." + rowClass)
            .forEach((cb) => (cb.checked = sa.checked));
        _updateBulkBar(
            document.querySelectorAll("." + rowClass + ":checked").length,
        );
    });
    document
        .querySelectorAll("." + rowClass)
        .forEach((cb) => cb.addEventListener("change", syncHeader));
};

function _updateBulkBar(count) {
    var bar = document.getElementById("bulkActionBar");
    var label = document.querySelector(".bulk-count");
    if (bar) bar.style.display = count > 0 ? "flex" : "none";
    if (label) label.textContent = count + " mục được chọn";
}

window.getSelectedIds = function (rowClass) {
    return Array.from(
        document.querySelectorAll(
            "." + (rowClass || "row-checkbox") + ":checked",
        ),
    ).map((cb) => cb.value);
};

window.highlightKeyword = function (keyword, selector) {
    if (!keyword || keyword.length < 2) return;
    var regex = new RegExp(
        "(" + keyword.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")",
        "gi",
    );
    document.querySelectorAll(selector || ".table td").forEach(function (el) {
        if (el.children.length === 0 && el.textContent.match(regex))
            el.innerHTML = el.textContent.replace(regex, "<mark>$1</mark>");
    });
};

/* =========================================================
   9. UI UTILITIES
========================================================= */
window.copyToClipboard = function (text, msg) {
    function fallback() {
        var ta = document.createElement("textarea");
        ta.value = text;
        ta.style.cssText = "position:absolute;left:-9999px";
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand("copy");
            showAdminToast(msg || "Đã sao chép!", "success");
        } catch {
            showAdminToast("Không thể sao chép", "error");
        }
        document.body.removeChild(ta);
    }
    navigator.clipboard && window.isSecureContext
        ? navigator.clipboard
              .writeText(text)
              .then(() => showAdminToast(msg || "Đã sao chép!", "success"))
              .catch(fallback)
        : fallback();
};

window.formatMoney = function (num) {
    num = parseFloat(num);
    if (isNaN(num)) return "—";
    if (num >= 1e9) return (num / 1e9).toFixed(2).replace(/\.?0+$/, "") + " tỷ";
    if (num >= 1e6) return (num / 1e6).toFixed(0) + " triệu";
    return num.toLocaleString("vi-VN") + " đ";
};

window.formatNumber = function (num) {
    return num === null || num === undefined || num === ""
        ? "—"
        : Number(num).toLocaleString("vi-VN");
};

window.debounce = function (fn, delay) {
    var timer;
    return function () {
        var a = arguments,
            ctx = this;
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(ctx, a), delay || 300);
    };
};

window.throttle = function (fn, limit) {
    var last = 0;
    return function () {
        var now = Date.now();
        if (now - last >= (limit || 300)) {
            last = now;
            fn.apply(this, arguments);
        }
    };
};

/* =========================================================
   10. DOMContentLoaded — AUTO INIT
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    /* ── Auto-submit select ── */
    document.querySelectorAll(".filter-auto-submit").forEach((el) =>
        el.addEventListener("change", function () {
            this.closest("form")?.submit();
        }),
    );

    /* ── Search debounce ── */
    document.querySelectorAll(".search-debounce").forEach((input) =>
        input.addEventListener(
            "input",
            debounce(function () {
                input.closest("form")?.submit();
            }, 600),
        ),
    );

    /* ── Highlight từ khóa ── */
    var kw =
        new URLSearchParams(window.location.search).get("tukhoa") ||
        new URLSearchParams(window.location.search).get("search") ||
        "";
    if (kw.length > 1) highlightKeyword(kw);

    /* ── Tooltip [data-tip] ── */
    document.querySelectorAll("[data-tip]").forEach(function (el) {
        var tip = null;
        el.addEventListener("mouseenter", function () {
            tip = document.createElement("div");
            tip.textContent = el.getAttribute("data-tip");
            tip.style.cssText =
                "position:fixed;background:#1a3c5e;color:#fff;padding:.32rem .7rem;" +
                "border-radius:6px;font-size:.72rem;font-weight:600;pointer-events:none;z-index:99999;" +
                "white-space:nowrap;box-shadow:0 4px 12px rgba(0,0,0,.2);animation:fadeIn .15s ease";
            document.body.appendChild(tip);
            _positionTip(el, tip);
        });
        el.addEventListener("mousemove", () => _positionTip(el, tip));
        el.addEventListener("mouseleave", () => {
            if (tip) {
                tip.remove();
                tip = null;
            }
        });
    });

    /* ── Toggle switches [data-toggle-url] ── */
    document
        .querySelectorAll(".toggle-sw input[data-toggle-url]")
        .forEach(function (chk) {
            chk.addEventListener("change", function () {
                ajaxToggle(this.dataset.toggleUrl, this, function () {
                    var wrap = chk.closest("tr, .mobile-card");
                    if (wrap) {
                        var dot = wrap.querySelector(".status-dot");
                        if (dot)
                            dot.className =
                                "status-dot " + (chk.checked ? "on" : "off");
                    }
                });
            });
        });

    /* ── Confirm delete [data-confirm-delete] ── */
    document.querySelectorAll("[data-confirm-delete]").forEach(function (el) {
        el.addEventListener("click", function (e) {
            e.preventDefault();
            var label = this.dataset.confirmDelete || "mục này";
            var form =
                this.closest("form") ||
                document.getElementById(this.dataset.formId);
            confirmDelete(label, () => {
                if (form) form.submit();
            });
        });
    });
});

function _positionTip(el, tip) {
    if (!tip) return;
    var r = el.getBoundingClientRect();
    var tipW = tip.offsetWidth;
    var left = Math.max(
        8,
        Math.min(r.left + r.width / 2 - tipW / 2, window.innerWidth - tipW - 8),
    );
    tip.style.left = left + "px";
    tip.style.top = r.bottom + 6 + "px";
}
