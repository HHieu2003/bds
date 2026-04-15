<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DuAnController;
use App\Http\Controllers\Admin\BatDongSanController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\LichHenController;
use App\Http\Controllers\Admin\KyGuiController    as AdminKyGuiController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\NhanVienController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\LienHeController as AdminLienHeController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\KhachHangAuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BatDongSanController as FeBatDongSanController;
use App\Http\Controllers\Frontend\DuAnController as FeDuAnController;
use App\Http\Controllers\Frontend\BaiVietController as FeBaiVietController;
use App\Http\Controllers\Frontend\KyGuiController as FrontendKyGuiController;
use App\Http\Controllers\Frontend\TimKiemController;
use App\Http\Controllers\Frontend\SoSanhController;
use App\Http\Controllers\Frontend\YeuThichController;
use App\Http\Controllers\Frontend\LienHeController as FrontendLienHeController;
use App\Http\Controllers\Frontend\ChatController as FeChatController;
use App\Http\Controllers\Frontend\DangKyNhanTinController;
use App\Http\Controllers\Admin\NganHangController;
use App\Http\Controllers\Frontend\LichHenController as FeLichHenController;
use App\Http\Controllers\Admin\ChuNhaController;
use App\Http\Controllers\Admin\KhuVucController;
use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════
// FRONTEND
// ══════════════════════════════════════════════════════════
Route::prefix('')->name('frontend.')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/gioi-thieu', [HomeController::class, 'gioiThieu'])->name('gioi-thieu');
    Route::get('/noi-that', [HomeController::class, 'noiThat'])->name('noi-that');
    Route::get('/tuyen-dung', [HomeController::class, 'tuyendung'])->name('tuyen-dung');
    Route::get('/cong-cu-tai-chinh', [HomeController::class, 'congCuTaiChinh'])->name('cong-cu-tai-chinh');

    Route::get('/bat-dong-san', [FeBatDongSanController::class, 'index'])->name('bat-dong-san.index');
    Route::get('/bat-dong-san/{slug}', [FeBatDongSanController::class, 'show'])->name('bat-dong-san.show');
    Route::post('/bds/track-time', [FeBatDongSanController::class, 'trackTime'])
        ->name('bds.track-time');
    Route::get('/api/toa-by-du-an/{duAnId}', [FeBatDongSanController::class, 'toaByDuAn'])
        ->name('api.toa-by-du-an');
    Route::get('/du-an', [FeDuAnController::class, 'index'])->name('du-an.index');
    Route::get('/du-an/{slug}', [FeDuAnController::class, 'show'])->name('du-an.show');

    Route::prefix('tin-tuc')->name('tin-tuc.')->group(function () {
        Route::get('/', [FeBaiVietController::class, 'index'])->name('index');
        Route::get('/{slug}', [FeBaiVietController::class, 'show'])->name('show');
    });

    Route::prefix('ky-gui')->name('ky-gui.')->group(function () {
        Route::get('/',           [FrontendKyGuiController::class, 'create'])->name('create');
        Route::post('/',          [FrontendKyGuiController::class, 'store'])->middleware('throttle:anti-spam')->name('store');
        Route::get('/thanh-cong', [FrontendKyGuiController::class, 'success'])->name('success');
        Route::middleware('auth:customer')->group(function () {
            Route::get('/cua-toi', [FrontendKyGuiController::class, 'myKyGui'])->name('cua-toi');
            Route::delete('/{kyGui}/huy', [FrontendKyGuiController::class, 'huy'])->name('huy');
        });
    });

    Route::post('/dang-ky-nhan-tin', [DangKyNhanTinController::class, 'store'])
        ->middleware('throttle:anti-spam')
        ->name('dang-ky-nhan-tin.store');

    Route::delete('/dang-ky-nhan-tin/{id}', [DangKyNhanTinController::class, 'destroy'])->name('dang-ky-nhan-tin.destroy');
    Route::get('/tim-kiem', [TimKiemController::class, 'index'])->name('tim-kiem.index');

    Route::get('/so-sanh',              [SoSanhController::class, 'index'])->name('so-sanh.index');
    Route::post('/so-sanh/them/{id}',   [SoSanhController::class, 'them'])->name('so-sanh.them');
    Route::post('/so-sanh/xoa/{id}',    [SoSanhController::class, 'xoa'])->name('so-sanh.xoa');
    Route::get('/so-sanh/modal',        [SoSanhController::class, 'modal'])->name('so-sanh.modal');

    Route::get('/yeu-thich', [YeuThichController::class, 'index'])->name('yeu-thich.index');
    Route::post('/yeu-thich/toggle', [YeuThichController::class, 'toggle'])->name('yeu-thich.toggle');
    Route::post('/yeu-thich/xoa/{id}', [YeuThichController::class, 'xoa'])->name('yeu-thich.xoa');

    Route::prefix('lien-he')->name('lien-he.')->group(function () {
        Route::get('/',  [FrontendLienHeController::class, 'index'])->name('index');
        Route::post('/', [FrontendLienHeController::class, 'store'])->middleware('throttle:anti-spam')->name('store');
    });
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::post('/khoi-tao',        [FeChatController::class, 'khoiTao'])->name('khoi-tao');
        Route::post('/gui',             [FeChatController::class, 'guiTinNhan'])->name('gui');
        Route::get('/lich-su/{id}',     [FeChatController::class, 'lichSu'])->name('lich-su');
        Route::post('/chuyen-nhan-vien', [FeChatController::class, 'chuyenNhanVien'])->name('chuyen-nv');
        Route::post('/chuyen-lai-he-thong', [FeChatController::class, 'chuyenLaiHeThong'])->name('chuyen-bot');
        Route::get('/{id}/long-poll',   [FeChatController::class, 'longPoll'])->name('long-poll');
    });
});

Route::post('/dat-lich-xem-nha', [FeLichHenController::class, 'datLich'])
    ->middleware('throttle:anti-spam')
    ->name('frontend.dat-lich');
// ══════════════════════════════════════════════════════════
// KHÁCH HÀNG — Authentication
// ══════════════════════════════════════════════════════════
Route::post('/khach-hang/login', [KhachHangAuthController::class, 'login'])
    ->middleware('throttle:anti-spam')
    ->name('khach-hang.login.post');
Route::post('/khach-hang/logout', [KhachHangAuthController::class, 'logout'])->name('khach-hang.logout');
Route::prefix('tai-khoan')->name('khach-hang.')->group(function () {

    Route::middleware('guest:customer')->group(function () {
        Route::get('dang-nhap', [KhachHangAuthController::class, 'showLogin'])->name('login');
        Route::post('dang-nhap', [KhachHangAuthController::class, 'login'])->middleware('throttle:anti-spam')->name('login.post');
        Route::get('dang-ky', [KhachHangAuthController::class, 'showRegister'])->name('register');
        Route::post('dang-ky', [KhachHangAuthController::class, 'register'])->middleware('throttle:anti-spam')->name('register.post');
        Route::post('send-otp', [KhachHangAuthController::class, 'sendOtp'])->middleware('throttle:anti-spam')->name('send-otp');
        Route::post('verify-otp', [KhachHangAuthController::class, 'verifyOtp'])->middleware('throttle:anti-spam')->name('verify-otp');
        Route::get('quen-mat-khau', [KhachHangAuthController::class, 'showForgot'])->name('forgot');
        Route::post('quen-mat-khau', [KhachHangAuthController::class, 'sendReset'])->middleware('throttle:anti-spam')->name('forgot.post');
        Route::get('dat-lai-mat-khau', [KhachHangAuthController::class, 'showReset'])->name('reset');
        Route::post('dat-lai-mat-khau', [KhachHangAuthController::class, 'reset'])->middleware('throttle:anti-spam')->name('reset.post');
    });
    // Xem danh sách lịch hẹn
    Route::get('lich-hen', [FeLichHenController::class, 'lichHen'])->name('lich-hen');

    // Khách hàng chủ động hủy lịch
    Route::post('lich-hen/{id}/huy', [FeLichHenController::class, 'huyLichHen'])->name('lich-hen.huy');
    Route::middleware('auth:customer')->group(function () {
        Route::post('dang-xuat', [KhachHangAuthController::class, 'logout'])->name('logout');
        Route::get('ho-so', [KhachHangAuthController::class, 'profile'])->name('profile');
        Route::post('ho-so', [KhachHangAuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('doi-mat-khau', [KhachHangAuthController::class, 'changePassword'])->name('change-password');
        Route::get('lich-su-xem', [KhachHangAuthController::class, 'lichSuXem'])->name('lich-su-xem');
        Route::get('ky-gui-cua-toi', [KhachHangAuthController::class, 'kyGuiCuaToi'])->name('ky-gui-cua-toi');
        Route::get('lich-hen-cua-toi', [KhachHangAuthController::class, 'lichHenCuaToi'])->name('lich-hen-cua-toi');
    });
});
// ══════════════════════════════════════════════════════════
// NHÂN VIÊN — Authentication + Admin Panel
// ══════════════════════════════════════════════════════════
Route::prefix('nhan-vien')->name('nhanvien.')->group(function () {

    Route::middleware('guest:nhanvien')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware('auth:nhanvien')->group(function () {

        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::post('cap-nhat-thong-tin', [AdminAuthController::class, 'updateProfile'])
            ->name('update-profile');
        Route::post('doi-mat-khau', [AdminAuthController::class, 'changePassword'])
            ->name('change-password');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ══════════════════════════════════════
        // ADMIN PANEL
        // ══════════════════════════════════════
        Route::prefix('admin')->name('admin.')->group(function () {

            // ── Chỉ Admin ──────────────────────

            Route::middleware('check.role:admin')->group(function () {
                Route::get('nhan-vien/{nhanVien}/edit-data', [NhanVienController::class, 'editData'])
                    ->name('nhanvien.edit-data');
                Route::prefix('nhan-vien')->name('nhan-vien.')->group(function () {
                    Route::get('/',                          [NhanVienController::class, 'index'])->name('index');
                    Route::post('/',                         [NhanVienController::class, 'store'])->name('store');
                    Route::get('/{nhanVien}',                [NhanVienController::class, 'show'])->name('show');
                    Route::put('/{nhanVien}',                [NhanVienController::class, 'update'])->name('update');
                    Route::delete('/{nhanVien}',             [NhanVienController::class, 'destroy'])->name('destroy');
                    Route::patch('/{nhanVien}/toggle',       [NhanVienController::class, 'toggleKichHoat'])->name('toggle');
                    Route::patch('/{nhanVien}/doi-mat-khau', [NhanVienController::class, 'doiMatKhau'])->name('doi-mat-khau');
                });
            });

            Route::middleware('check.role:admin,sale,nguon_hang')->group(function () {
                Route::resource('ngan-hang', NganHangController::class)->except(['create', 'edit']);

                // Lịch hẹn dùng chung cho Admin/Sale/Nguồn hàng.
                // Quyền thao tác chi tiết tiếp tục được kiểm soát trong controller.
                Route::prefix('lich-hen')->name('lich-hen.')->group(function () {
                    Route::get('/',                    [LichHenController::class, 'index'])->name('index');
                    Route::get('/thong-ke',            [LichHenController::class, 'thongKe'])->name('thong-ke');
                    Route::get('/api/hom-nay',         [LichHenController::class, 'apiLichHenHomNay'])->name('api.hom-nay');
                    Route::get('/create',              [LichHenController::class, 'create'])->name('create');
                    Route::post('/',                   [LichHenController::class, 'store'])->name('store');
                    Route::get('/api-events',          [LichHenController::class, 'apiEvents'])->name('api-events');
                    Route::get('/{lichHen}',           [LichHenController::class, 'show'])->name('show');
                    Route::patch('/{lichHen}/tiep-nhan', [LichHenController::class, 'tiepNhan'])->name('tiep-nhan');
                    Route::post('/{lichHen}/doi-gio-nhanh', [LichHenController::class, 'doiGioNhanh'])->name('doi-gio-nhanh');
                    Route::post('/{lichHen}/xac-nhan-doi-gio', [LichHenController::class, 'xacNhanDoiGio'])->name('xac-nhan-doi-gio');
                    Route::patch('/{lichHen}/hoan-thanh', [LichHenController::class, 'hoanThanh'])->name('hoan-thanh');
                    Route::patch('/{lichHen}/huy',     [LichHenController::class, 'huy'])->name('huy');
                    Route::patch('/{lichHen}/bao-lai-gio', [LichHenController::class, 'baoLaiGio'])->name('bao-lai-gio');
                    Route::patch('/{lichHen}/sale-doi-gio', [LichHenController::class, 'saleDoiGio'])->name('sale-doi-gio');
                    Route::patch('/{lichHen}/xac-nhan', [LichHenController::class, 'xacNhan'])->name('xac-nhan');
                    Route::patch('/{lichHen}/tu-choi', [LichHenController::class, 'tuChoi'])->name('tu-choi');
                });
            });

            // ── Admin + Nguồn hàng ─────────────
            Route::middleware('check.role:admin,sale,nguon_hang')->group(function () {
                Route::resource('du-an', DuAnController::class);
                Route::patch('du-an/{duAn}/toggle', [DuAnController::class, 'toggleHienThi'])
                    ->name('du-an.toggle');
            });

            Route::middleware('check.role:admin,nguon_hang')->group(function () {
                Route::resource('chu-nha', ChuNhaController::class)
                    ->except(['create', 'edit']);
                Route::delete('bat-dong-san/{batDongSan}', [BatDongSanController::class, 'destroy'])
                    ->name('bat-dong-san.destroy');


                // Route Ký Gửi
                // Màn hình Form Chuyển đổi (Duyệt Ký gửi)
                Route::get('ky-gui/{kyGui}/duyet', [AdminKyGuiController::class, 'duyetForm'])->name('ky-gui.duyet');

                // Action Lưu Form Chuyển đổi
                Route::post('ky-gui/{kyGui}/duyet', [AdminKyGuiController::class, 'duyetSubmit']);

                // Route resource gốc của Ký Gửi
                Route::resource('ky-gui', AdminKyGuiController::class);

                // Bổ sung route xử lý AJAX cập nhật trạng thái nếu chưa có
                Route::match(['post', 'patch'], 'ky-gui/{kyGui}/xu-ly', [AdminKyGuiController::class, 'xuLy'])
                    ->name('ky-gui.xu-ly');
                Route::post('ky-gui/{kyGui}/phan-cong', [AdminKyGuiController::class, 'phanCong'])
                    ->name('ky-gui.phan-cong');
            });

            // ── Admin + Sale + Nguồn hàng (BĐS) ───────
            Route::middleware('check.role:admin,sale,nguon_hang')->group(function () {
                Route::prefix('bat-dong-san')->name('bat-dong-san.')->group(function () {
                    Route::get('/',                        [BatDongSanController::class, 'index'])->name('index');
                    Route::get('/create',                  [BatDongSanController::class, 'create'])->name('create');
                    Route::post('/',                       [BatDongSanController::class, 'store'])->name('store');
                    Route::get('/{batDongSan}/edit',       [BatDongSanController::class, 'edit'])->name('edit');
                    Route::put('/{batDongSan}',            [BatDongSanController::class, 'update'])->name('update');
                    Route::patch('/{batDongSan}/toggle',   [BatDongSanController::class, 'toggleHienThi'])->name('toggle');
                    Route::patch('/{batDongSan}/trang-thai', [BatDongSanController::class, 'doiTrangThai'])->name('trang-thai');
                    Route::delete('/{batDongSan}/xoa-anh', [BatDongSanController::class, 'xoaAnh'])->name('xoa-anh');
                });
            });

            // ── Admin + Sale ───────────────────
            Route::middleware('check.role:admin,sale')->group(function () {
                // MỚI: Route Nhận Lead (phải đặt TRƯỚC resource để tránh bị nhầm với {lienHe})
                Route::post('lien-he/{lienHe}/nhan-lead', [AdminLienHeController::class, 'nhanLead'])
                    ->name('lien-he.nhan-lead');

                Route::resource('lien-he', AdminLienHeController::class);

                // Route cập nhật nhanh trạng thái
                Route::post('lien-he/{lienHe}/cap-nhat-nhanh', [AdminLienHeController::class, 'capNhatNhanh'])
                    ->name('lien-he.cap-nhat-nhanh');

                // Route chuyển đổi Lead thành Khách hàng + alias
                Route::post('lien-he/{lienHe}/chuyen-khach', [AdminLienHeController::class, 'chuyenKhachHang'])
                    ->name('lien-he.chuyen-khach');
                Route::post('lien-he/{lienHe}/chuyen-khach-hang', [AdminLienHeController::class, 'chuyenKhachHang'])
                    ->name('lien-he.convert');

                Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
                    Route::get('/',                           [KhachHangController::class, 'index'])->name('index');
                    Route::get('/create',                     [KhachHangController::class, 'create'])->name('create');
                    Route::post('/',                          [KhachHangController::class, 'store'])->name('store');
                    Route::get('/{khachHang}',                [KhachHangController::class, 'show'])->name('show');
                    Route::post('/{khachHang}/nhat-ky',       [KhachHangController::class, 'storeNhatKy'])->name('nhat-ky');
                    Route::get('/{khachHang}/edit',           [KhachHangController::class, 'edit'])->name('edit');
                    Route::put('/{khachHang}',                [KhachHangController::class, 'update'])->name('update');
                    Route::delete('/{khachHang}',             [KhachHangController::class, 'destroy'])->name('destroy');
                    Route::patch('/{khachHang}/toggle',       [KhachHangController::class, 'toggleKichHoat'])->name('toggle');
                    Route::patch('/{khachHang}/muc-do',       [KhachHangController::class, 'doiMucDo'])->name('muc-do');
                    Route::patch('/{khachHang}/ghi-chu',      [KhachHangController::class, 'capNhatGhiChu'])->name('ghi-chu');
                    Route::patch('/{khachHang}/lien-he-cuoi', [KhachHangController::class, 'capNhatLienHe'])->name('lien-he-cuoi');
                });
            });

            // ── Tất cả nhân viên ──────────────
            Route::resource('bai-viet', BaiVietController::class);
            Route::patch('bai-viet/{baiViet}/hien-thi', [BaiVietController::class, 'toggleHienThi'])
                ->name('bai-viet.toggle-hien-thi');
            Route::patch('bai-viet/{baiViet}/noi-bat', [BaiVietController::class, 'toggleNoiBat'])
                ->name('bai-viet.toggle-noi-bat');
            Route::post('bai-viet/bulk-action', [BaiVietController::class, 'bulkAction'])
                ->name('bai-viet.bulk-action');

            Route::prefix('khu-vuc')->name('khu-vuc.')->group(function () {
                Route::get('/',                   [KhuVucController::class, 'index'])->name('index');
                Route::get('/create',             [KhuVucController::class, 'create'])->name('create');
                Route::post('/',                  [KhuVucController::class, 'store'])->name('store');
                Route::get('/ajax/danh-sach-con', [KhuVucController::class, 'getDanhSachCon'])->name('ajax.con');
                Route::get('/{khuVuc}',           [KhuVucController::class, 'show'])->name('show');
                Route::get('/{khuVuc}/edit',      [KhuVucController::class, 'edit'])->name('edit');
                Route::put('/{khuVuc}',           [KhuVucController::class, 'update'])->name('update');
                Route::delete('/{khuVuc}',        [KhuVucController::class, 'destroy'])->name('destroy');
                Route::patch('/{khuVuc}/toggle',  [KhuVucController::class, 'toggleHienThi'])->name('toggle');
            });

            Route::middleware('check.role:admin,sale')->group(function () {
                Route::prefix('chat')->name('chat.')->group(function () {
                    Route::get('/',              [AdminChatController::class, 'index'])->name('index');
                    Route::get('/{id}',          [AdminChatController::class, 'show'])->name('show');
                    Route::post('/{id}/tra-loi', [AdminChatController::class, 'traLoi'])->name('tra-loi');

                    Route::post('/{id}/tiep-nhan', [AdminChatController::class, 'tiepNhan'])->name('tiep-nhan');
                    Route::post('/{id}/dong',      [AdminChatController::class, 'dongPhien'])->name('dong');
                    Route::get('/{id}/long-poll',  [AdminChatController::class, 'longPoll'])->name('long-poll');
                    Route::delete('/{id}',         [AdminChatController::class, 'destroy'])->name('destroy');
                });
            });
        });
    });
});
