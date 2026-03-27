/* =========================================================
   ADMIN.JS — THÀNH CÔNG LAND  v2.0
   Modules: Sidebar | Topbar | Flash | Confirm | Toast
            | DataTable | Form Helpers | Utilities
========================================================= */

"use strict";

/* =========================================================
   1. SIDEBAR TOGGLE
========================================================= */
(function initSidebar() {
    var sidebarCollapsed =
        localStorage.getItem("adminSidebarCollapsed") === "true";
    var sidebar = document.getElementById("sidebar");
    var topbar = document.getElementById("topbar");
    var mainWrap = document.getElementById("main-wrapper");

    function applySidebarState() {
        var sidebar = document.getElementById("sidebar");
        var topbar = document.getElementById("topbar");
        var mainWrap = document.getElementById("main-wrapper");
        var toggleBtn = document.querySelector(".btn-toggle-sidebar i");

        if (window.innerWidth > 992) {
            if (sidebarCollapsed) {
                sidebar && sidebar.classList.add("collapsed");
                topbar && topbar.classList.add("sidebar-collapsed");
                mainWrap && mainWrap.classList.add("sidebar-collapsed");
                // Icon đổi chiều
                if (toggleBtn) toggleBtn.style.transform = "rotate(180deg)";
            } else {
                sidebar && sidebar.classList.remove("collapsed");
                topbar && topbar.classList.remove("sidebar-collapsed");
                mainWrap && mainWrap.classList.remove("sidebar-collapsed");
                if (toggleBtn) toggleBtn.style.transform = "";
            }
        }
    }

    applySidebarState();

    window.toggleSidebar = function () {
        if (window.innerWidth <= 992) {
            // Mobile: show/hide hoàn toàn
            var sidebar = document.getElementById("sidebar");
            var overlay = document.getElementById("sidebarOverlay");
            sidebar && sidebar.classList.toggle("mobile-open");
            overlay && overlay.classList.toggle("show");
        } else {
            // Desktop: thu nhỏ/mở rộng
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem("adminSidebarCollapsed", sidebarCollapsed);
            applySidebarState();
        }
    };
    applySidebarState;
    window.closeMobileSidebar = function () {
        sidebar?.classList.remove("mobile-open");
        document.getElementById("sidebarOverlay")?.classList.remove("show");
        document.body.style.overflow = "";
    };

    var resizeTimer;
    window.addEventListener("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (window.innerWidth > 992) {
                closeMobileSidebar();
                applySidebarState();
            }
        }, 100);
    });
})();

/* =========================================================
   2. TOPBAR — USER DROPDOWN
========================================================= */
(function initTopbar() {
    var btn = document.getElementById("topbarUserBtn");
    var dropdown = document.getElementById("userDropdown");
    var chevron = btn?.querySelector(".topbar-user-chevron");

    window.toggleUserDropdown = function () {
        var isOpen = dropdown?.classList.toggle("show");
        if (chevron) chevron.style.transform = isOpen ? "rotate(180deg)" : "";
    };

    document.addEventListener("click", function (e) {
        if (!e.target.closest("#topbarUserBtn")) {
            dropdown?.classList.remove("show");
            if (chevron) chevron.style.transform = "";
        }
    });
})();

/* =========================================================
   3. FLASH MESSAGES — AUTO DISMISS
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    var flashes = document.querySelectorAll(".flash-admin");

    flashes.forEach(function (el, i) {
        setTimeout(
            function () {
                el.style.transition = "opacity .4s ease, transform .4s ease";
                el.style.opacity = "0";
                el.style.transform = "translateY(-6px)";
                setTimeout(function () {
                    el.remove();
                }, 420);
            },
            5000 + i * 300,
        );
    });
});

/* =========================================================
   4. CONFIRM DELETE MODAL
========================================================= */
var _confirmCallback = null;

window.confirmDelete = function (message, callback) {
    var modal = document.getElementById("confirmModal");
    var msgEl = document.getElementById("confirmMessage");
    if (!modal) return;
    if (msgEl && message) msgEl.textContent = message;
    _confirmCallback = callback;
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
};

window.closeConfirmModal = function () {
    var modal = document.getElementById("confirmModal");
    modal?.classList.remove("show");
    document.body.style.overflow = "";
    _confirmCallback = null;
};

window.executeConfirm = function () {
    if (typeof _confirmCallback === "function") {
        _confirmCallback();
    }
    closeConfirmModal();
};

// Đóng khi click ra ngoài
document.addEventListener("click", function (e) {
    var modal = document.getElementById("confirmModal");
    if (modal && e.target === modal) closeConfirmModal();
});

// Phím ESC
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeConfirmModal();
});

/* =========================================================
   5. TOAST NOTIFICATION
========================================================= */
window.showAdminToast = function (message, type) {
    type = type || "success";

    var palette = {
        success: {
            bg: "#f0fff4",
            border: "#27ae60",
            color: "#276749",
            borderColor: "#bbf7d0",
            icon: "fa-check-circle",
        },
        error: {
            bg: "#fff5f5",
            border: "#e74c3c",
            color: "#c0392b",
            borderColor: "#fecaca",
            icon: "fa-times-circle",
        },
        warning: {
            bg: "#fffbeb",
            border: "#f59e0b",
            color: "#92400e",
            borderColor: "#fde68a",
            icon: "fa-exclamation-triangle",
        },
        info: {
            bg: "#eff8ff",
            border: "#2563eb",
            color: "#1e40af",
            borderColor: "#bfdbfe",
            icon: "fa-info-circle",
        },
    };

    var tone = palette[type] || palette.info;

    var root = document.getElementById("adminToastRoot");
    if (!root) {
        root = document.createElement("div");
        root.id = "adminToastRoot";
        document.body.appendChild(root);
    }

    var toast = document.createElement("div");
    toast.className = "admin-toast";
    toast.style.cssText = [
        "background:" + tone.bg,
        "border-left:4px solid " + tone.border,
        "border-color:" + tone.borderColor,
        "color:" + tone.color,
    ].join(";");

    toast.innerHTML =
        '<i class="fas ' +
        tone.icon +
        '" style="font-size:.9rem;flex-shrink:0"></i>' +
        '<span style="flex:1;line-height:1.4">' +
        message +
        "</span>" +
        "<button onclick=\"this.closest('.admin-toast').remove()\" " +
        'style="background:none;border:none;cursor:pointer;color:inherit;' +
        'opacity:.45;padding:0;font-size:.78rem;flex-shrink:0;transition:opacity .2s" ' +
        'onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.45">' +
        '<i class="fas fa-times"></i></button>';

    root.appendChild(toast);

    setTimeout(function () {
        toast.style.transition = "opacity .3s ease, transform .3s ease";
        toast.style.opacity = "0";
        toast.style.transform = "translateX(30px)";
        setTimeout(function () {
            toast.remove();
        }, 320);
    }, 4200);

    return toast;
};

/* =========================================================
   6. FORM HELPERS
========================================================= */

// Preview ảnh trước khi upload
window.previewImage = function (inputEl, imgEl) {
    if (!inputEl || !inputEl.files || !inputEl.files[0]) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        if (imgEl) imgEl.src = e.target.result;
    };
    reader.readAsDataURL(inputEl.files[0]);
};

// Preview nhiều ảnh (grid)
window.previewMultiImages = function (inputEl, gridEl) {
    if (!inputEl || !gridEl) return;
    var files = Array.from(inputEl.files || []);
    gridEl.innerHTML = "";

    files.forEach(function (file, idx) {
        if (!file.type.startsWith("image/")) return;

        var reader = new FileReader();
        reader.onload = function (e) {
            var item = document.createElement("div");
            item.className = "img-preview-item";
            item.innerHTML =
                '<img src="' +
                e.target.result +
                '" alt="Preview ' +
                idx +
                '">' +
                '<button type="button" class="img-preview-remove" onclick="this.closest(\'.img-preview-item\').remove()">' +
                '<i class="fas fa-times"></i></button>';
            gridEl.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
};

// Submit form với confirm
window.deleteWithConfirm = function (formId, label) {
    confirmDelete(
        'Xóa "' + (label || "mục này") + '"? Hành động này không thể hoàn tác.',
        function () {
            var form = document.getElementById(formId);
            if (form) form.submit();
        },
    );
};

// Validate field required trực tiếp
window.validateRequired = function (inputEl, errorId, label) {
    var el =
        typeof inputEl === "string"
            ? document.getElementById(inputEl)
            : inputEl;
    var err = document.getElementById(errorId);
    if (!el) return true;

    var val = el.value.trim();
    if (!val) {
        el.classList.add("is-invalid");
        if (err) {
            err.innerHTML =
                '<i class="fas fa-exclamation-circle"></i> ' +
                (label || "Trường này") +
                " không được để trống";
            err.style.display = "flex";
        }
        el.focus();
        return false;
    }

    el.classList.remove("is-invalid");
    if (err) {
        err.innerHTML = "";
        err.style.display = "none";
    }
    return true;
};

/* =========================================================
   7. AJAX UTILITIES
========================================================= */

// Toggle trạng thái (AJAX POST)
window.ajaxToggleStatus = function (url, csrfToken, onSuccess) {
    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                csrfToken ||
                document.querySelector('meta[name="csrf-token"]')?.content,
            Accept: "application/json",
        },
    })
        .then(function (r) {
            return r.json();
        })
        .then(function (data) {
            if (data.success) {
                showAdminToast(
                    data.message || "Cập nhật thành công",
                    "success",
                );
                if (typeof onSuccess === "function") onSuccess(data);
            } else {
                showAdminToast(data.message || "Có lỗi xảy ra", "error");
            }
        })
        .catch(function () {
            showAdminToast("Lỗi kết nối, thử lại sau", "error");
        });
};

// AJAX POST chung
window.ajaxPost = function (url, data, onSuccess, onError) {
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrf,
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then(function (r) {
            return r.json();
        })
        .then(function (res) {
            if (res.success) {
                if (typeof onSuccess === "function") onSuccess(res);
            } else {
                showAdminToast(res.message || "Có lỗi xảy ra", "error");
                if (typeof onError === "function") onError(res);
            }
        })
        .catch(function () {
            showAdminToast("Lỗi kết nối, thử lại sau", "error");
            if (typeof onError === "function") onError(null);
        });
};

/* =========================================================
   8. UI UTILITIES
========================================================= */

// Copy to clipboard
window.copyToClipboard = function (text, successMsg) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard
            .writeText(text)
            .then(function () {
                showAdminToast(successMsg || "Đã sao chép: " + text, "success");
            })
            .catch(function () {
                fallbackCopy(text, successMsg);
            });
    } else {
        fallbackCopy(text, successMsg);
    }
};

function fallbackCopy(text, msg) {
    var el = document.createElement("textarea");
    el.value = text;
    el.style.cssText = "position:absolute;left:-9999px;top:-9999px";
    document.body.appendChild(el);
    el.select();
    try {
        document.execCommand("copy");
        showAdminToast(msg || "Đã sao chép", "success");
    } catch (e) {
        showAdminToast("Không thể sao chép", "error");
    }
    document.body.removeChild(el);
}

// Format tiền
window.formatMoney = function (num) {
    if (num === null || num === undefined || num === "") return "—";
    num = parseFloat(num);
    if (isNaN(num)) return "—";
    if (num >= 1e9) return (num / 1e9).toFixed(2).replace(/\.?0+$/, "") + " tỷ";
    if (num >= 1e6) return (num / 1e6).toFixed(0) + " triệu";
    return num.toLocaleString("vi-VN") + " đ";
};

// Format số có dấu phẩy
window.formatNumber = function (num) {
    if (!num && num !== 0) return "—";
    return Number(num).toLocaleString("vi-VN");
};

// Debounce
window.debounce = function (fn, delay) {
    var timer;
    return function () {
        var args = arguments;
        var ctx = this;
        clearTimeout(timer);
        timer = setTimeout(function () {
            fn.apply(ctx, args);
        }, delay || 300);
    };
};

// Throttle
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
   9. TABLE HELPERS
========================================================= */

// Select all checkbox
window.initSelectAll = function (selectAllId, checkboxClass) {
    var selectAll = document.getElementById(selectAllId);
    if (!selectAll) return;

    selectAll.addEventListener("change", function () {
        document.querySelectorAll("." + checkboxClass).forEach(function (cb) {
            cb.checked = selectAll.checked;
        });
        updateBulkBar();
    });

    document.querySelectorAll("." + checkboxClass).forEach(function (cb) {
        cb.addEventListener("change", function () {
            var all = document.querySelectorAll("." + checkboxClass);
            var done = document.querySelectorAll(
                "." + checkboxClass + ":checked",
            );
            selectAll.indeterminate =
                done.length > 0 && done.length < all.length;
            selectAll.checked = done.length === all.length;
            updateBulkBar();
        });
    });
};

function updateBulkBar() {
    var bar = document.getElementById("bulkActionBar");
    var count = document.querySelectorAll(".row-checkbox:checked").length;
    if (bar) {
        bar.style.display = count > 0 ? "flex" : "none";
        var countEl = bar.querySelector(".bulk-count");
        if (countEl) countEl.textContent = count + " mục được chọn";
    }
}

window.getSelectedIds = function (checkboxClass) {
    return Array.from(
        document.querySelectorAll(
            "." + (checkboxClass || "row-checkbox") + ":checked",
        ),
    ).map(function (cb) {
        return cb.value;
    });
};

/* =========================================================
   10. SEARCH BAR — LIVE SEARCH HELPER
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    // Auto submit filter form khi thay đổi select
    document.querySelectorAll(".filter-auto-submit").forEach(function (el) {
        el.addEventListener("change", function () {
            var form = this.closest("form");
            if (form) form.submit();
        });
    });

    // Search với debounce
    var searchInputs = document.querySelectorAll(".search-debounce");
    searchInputs.forEach(function (input) {
        var debouncedSubmit = debounce(function () {
            var form = input.closest("form");
            if (form) form.submit();
        }, 600);
        input.addEventListener("input", debouncedSubmit);
    });

    // Highlight từ tìm kiếm trong table
    var searchVal =
        new URLSearchParams(window.location.search).get("search") || "";
    if (searchVal.length > 1) {
        highlightText(document.querySelectorAll(".table-admin td"), searchVal);
    }
});

function highlightText(elements, keyword) {
    var regex = new RegExp(
        "(" + keyword.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")",
        "gi",
    );
    elements.forEach(function (el) {
        if (el.children.length === 0 && el.textContent.match(regex)) {
            el.innerHTML = el.textContent.replace(
                regex,
                '<mark style="background:#fff3cd;padding:0 2px;border-radius:2px">$1</mark>',
            );
        }
    });
}

/* =========================================================
   11. LOADING SPINNER
========================================================= */
window.showLoading = function (el, text) {
    if (!el) return;
    el._origHTML = el.innerHTML;
    el._origDisabled = el.disabled;
    el.disabled = true;
    el.innerHTML =
        '<i class="fas fa-circle-notch fa-spin"></i> ' +
        (text || "Đang xử lý...");
};

window.hideLoading = function (el) {
    if (!el || !el._origHTML) return;
    el.innerHTML = el._origHTML;
    el.disabled = el._origDisabled || false;
    delete el._origHTML;
    delete el._origDisabled;
};

/* =========================================================
   12. INIT TOOLTIPS (Tự động với data-tip)
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-tip]").forEach(function (el) {
        var tip = null;

        el.addEventListener("mouseenter", function () {
            tip = document.createElement("div");
            tip.textContent = el.getAttribute("data-tip");
            tip.style.cssText = [
                "position:fixed",
                "background:#1a3c5e",
                "color:#fff",
                "padding:.3rem .65rem",
                "border-radius:6px",
                "font-size:.72rem",
                "font-weight:600",
                "pointer-events:none",
                "z-index:99999",
                "white-space:nowrap",
                "box-shadow:0 4px 12px rgba(0,0,0,.2)",
                "animation:fadeIn .15s ease",
            ].join(";");
            document.body.appendChild(tip);
            positionTip(el, tip);
        });

        el.addEventListener("mousemove", function () {
            positionTip(el, tip);
        });

        el.addEventListener("mouseleave", function () {
            if (tip) {
                tip.remove();
                tip = null;
            }
        });
    });

    function positionTip(el, tip) {
        if (!tip) return;
        var rect = el.getBoundingClientRect();
        var tipW = tip.offsetWidth;
        var left = rect.left + rect.width / 2 - tipW / 2;
        left = Math.max(8, Math.min(left, window.innerWidth - tipW - 8));
        tip.style.left = left + "px";
        tip.style.top = rect.bottom + 6 + "px";
    }
});
