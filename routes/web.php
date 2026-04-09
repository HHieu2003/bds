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
        Route::post('/',          [FrontendKyGuiController::class, 'store'])->name('store');
        Route::get('/thanh-cong', [FrontendKyGuiController::class, 'success'])->name('success');
        Route::middleware('auth:customer')->group(function () {
            Route::get('/cua-toi', [FrontendKyGuiController::class, 'myKyGui'])->name('cua-toi');
            Route::delete('/{kyGui}/huy', [FrontendKyGuiController::class, 'huy'])->name('huy');
        });
    });

    Route::post('/lien-he', [FrontendLienHeController::class, 'store'])->name('frontend.lien-he.store');
    Route::post('/dang-ky-nhan-tin', [DangKyNhanTinController::class, 'store'])->name('dang-ky-nhan-tin.store');
    Route::post('/dang-ky-nhan-tin', [DangKyNhanTinController::class, 'store'])->name('dang-ky-nhan-tin.store');

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
        Route::post('/', [FrontendLienHeController::class, 'store'])->name('store');
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
Route::middleware(['auth:nhanvien', 'checkrole:admin,sale'])->prefix('admin')->name('nhanvien.admin.')->group(function () {

    // Quản lý Khách Hàng
    Route::resource('khach-hang', \App\Http\Controllers\Admin\KhachHangController::class);

    // Thêm Nhật ký chăm sóc khách hàng
    Route::post('khach-hang/{khachHang}/nhat-ky', [\App\Http\Controllers\Admin\KhachHangController::class, 'storeNhatKy'])->name('khach-hang.nhat-ky');
});
Route::post('/dat-lich-xem-nha', [FeLichHenController::class, 'datLich'])->name('frontend.dat-lich');
// ══════════════════════════════════════════════════════════
// KHÁCH HÀNG — Authentication
// ══════════════════════════════════════════════════════════
Route::post('/khach-hang/login', [KhachHangAuthController::class, 'login'])->name('khach-hang.login.post');
Route::post('/khach-hang/logout', [KhachHangAuthController::class, 'logout'])->name('khach-hang.logout');
Route::prefix('tai-khoan')->name('khach-hang.')->group(function () {

    Route::middleware('guest:customer')->group(function () {
        Route::get('dang-nhap', [KhachHangAuthController::class, 'showLogin'])->name('login');
        Route::post('dang-nhap', [KhachHangAuthController::class, 'login'])->name('login.post');
        Route::get('dang-ky', [KhachHangAuthController::class, 'showRegister'])->name('register');
        Route::post('dang-ky', [KhachHangAuthController::class, 'register'])->name('register.post');
        Route::post('send-otp', [KhachHangAuthController::class, 'sendOtp'])->name('send-otp');
        Route::post('verify-otp', [KhachHangAuthController::class, 'verifyOtp'])->name('verify-otp');
        Route::get('quen-mat-khau', [KhachHangAuthController::class, 'showForgot'])->name('forgot');
        Route::post('quen-mat-khau', [KhachHangAuthController::class, 'sendReset'])->name('forgot.post');
        Route::get('dat-lai-mat-khau', [KhachHangAuthController::class, 'showReset'])->name('reset');
        Route::post('dat-lai-mat-khau', [KhachHangAuthController::class, 'reset'])->name('reset.post');
    });
    // Xem danh sách lịch hẹn
    Route::get('lich-hen', [\App\Http\Controllers\Frontend\LichHenController::class, 'lichHen'])->name('lich-hen');

    // Khách hàng chủ động hủy lịch
    Route::post('lich-hen/{id}/huy', [\App\Http\Controllers\Frontend\LichHenController::class, 'huyLichHen'])->name('lich-hen.huy');
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

        Route::prefix('ky-gui')->name('ky-gui.')->group(function () {
            Route::get('/',                   [AdminKyGuiController::class, 'index'])->name('index');
            Route::get('/create',             [AdminKyGuiController::class, 'create'])->name('create');
            Route::post('/',                  [AdminKyGuiController::class, 'store'])->name('store');
            Route::get('/{kyGui}',            [AdminKyGuiController::class, 'show'])->name('show');
            Route::get('/{kyGui}/edit',       [AdminKyGuiController::class, 'edit'])->name('edit');
            Route::put('/{kyGui}',            [AdminKyGuiController::class, 'update'])->name('update');
            Route::delete('/{kyGui}',         [AdminKyGuiController::class, 'destroy'])->name('destroy');
            Route::post('/{kyGui}/xu-ly',     [AdminKyGuiController::class, 'xuLy'])->name('xu-ly');
            Route::post('/{kyGui}/phan-cong', [AdminKyGuiController::class, 'phanCong'])->name('phan-cong');
        });

        Route::prefix('bai-viet')->name('bai-viet.')->group(function () {
            Route::get('/',                     [BaiVietController::class, 'index'])->name('index');
            Route::get('/create',               [BaiVietController::class, 'create'])->name('create');
            Route::post('/',                    [BaiVietController::class, 'store'])->name('store');
            Route::get('/{baiViet}',            [BaiVietController::class, 'show'])->name('show');
            Route::get('/{baiViet}/edit',       [BaiVietController::class, 'edit'])->name('edit');
            Route::put('/{baiViet}',            [BaiVietController::class, 'update'])->name('update');
            Route::delete('/{baiViet}',         [BaiVietController::class, 'destroy'])->name('destroy');
            Route::patch('/{baiViet}/hien-thi', [BaiVietController::class, 'toggleHienThi'])->name('toggle-hien-thi');
            Route::patch('/{baiViet}/noi-bat',  [BaiVietController::class, 'toggleNoiBat'])->name('toggle-noi-bat');
        });

        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/',              [AdminChatController::class, 'index'])->name('index');
            Route::get('/{id}',         [AdminChatController::class, 'show'])->name('show');
            Route::post('/{id}/tra-loi', [AdminChatController::class, 'traLoi'])->name('tra-loi');
            Route::post('/{id}/tiep-nhan', [AdminChatController::class, 'tiepNhan'])->name('tiep-nhan');
            Route::post('/{id}/dong',   [AdminChatController::class, 'dongPhien'])->name('dong');
            Route::get('/{id}/long-poll', [AdminChatController::class, 'longPoll'])->name('long-poll');
        });

        // ══════════════════════════════════════
        // ADMIN PANEL
        // ══════════════════════════════════════
        Route::prefix('admin')->name('admin.')->group(function () {

            // ── Chỉ Admin ──────────────────────

            Route::middleware('check.role:admin')->group(function () {
                Route::get('nhan-vien/{nhanVien}/edit-data', [NhanVienController::class, 'editData'])
                    ->name('nhanvien.admin.nhan-vien.edit-data');
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
                    Route::get('/create',              [LichHenController::class, 'create'])->name('create');
                    Route::post('/',                   [LichHenController::class, 'store'])->name('store');
                    Route::get('/api-events',          [LichHenController::class, 'apiEvents'])->name('api-events');
                    Route::get('/{lichHen}',           [LichHenController::class, 'show'])->name('show');
                    Route::patch('/{lichHen}/tiep-nhan', [LichHenController::class, 'tiepNhan'])->name('tiep-nhan');
                    Route::patch('/{lichHen}/hoan-thanh', [LichHenController::class, 'hoanThanh'])->name('hoan-thanh');
                    Route::patch('/{lichHen}/huy',     [LichHenController::class, 'huy'])->name('huy');
                    Route::patch('/{lichHen}/bao-lai-gio', [LichHenController::class, 'baoLaiGio'])->name('bao-lai-gio');
                    Route::patch('/{lichHen}/sale-doi-gio', [LichHenController::class, 'saleDoiGio'])->name('sale-doi-gio');
                    Route::patch('/{lichHen}/xac-nhan', [LichHenController::class, 'xacNhan'])->name('xac-nhan');
                    Route::patch('/{lichHen}/tu-choi', [LichHenController::class, 'tuChoi'])->name('tu-choi');
                });
            });

            // ── Admin + Nguồn hàng ─────────────
            Route::middleware('check.role:admin,nguon_hang')->group(function () {
                Route::resource('du-an', DuAnController::class);
                Route::patch('du-an/{duAn}/toggle', [DuAnController::class, 'toggleHienThi'])
                    ->name('du-an.toggle');
                Route::resource('chu-nha', \App\Http\Controllers\Admin\ChuNhaController::class)
                    ->except(['create', 'edit']);
                Route::delete('bat-dong-san/{batDongSan}', [BatDongSanController::class, 'destroy'])
                    ->name('bat-dong-san.destroy');


                // Route Ký Gửi
                // Màn hình Form Chuyển đổi (Duyệt Ký gửi)
                Route::get('ky-gui/{kyGui}/duyet', [\App\Http\Controllers\Admin\KyGuiController::class, 'duyetForm'])->name('ky-gui.duyet');

                // Action Lưu Form Chuyển đổi
                Route::post('ky-gui/{kyGui}/duyet', [\App\Http\Controllers\Admin\KyGuiController::class, 'duyetSubmit']);

                // Route resource gốc của Ký Gửi
                Route::resource('ky-gui', \App\Http\Controllers\Admin\KyGuiController::class);

                // Bổ sung route xử lý AJAX cập nhật trạng thái nếu chưa có
                Route::patch('ky-gui/{kyGui}/xu-ly', [\App\Http\Controllers\Admin\KyGuiController::class, 'xuLy'])->name('ky-gui.xu-ly');

                Route::prefix('ky-gui')->name('ky-gui.')->group(function () {
                    Route::get('/',               [AdminKyGuiController::class, 'index'])->name('index');
                    Route::get('/create',         [AdminKyGuiController::class, 'create'])->name('create');
                    Route::post('/',              [AdminKyGuiController::class, 'store'])->name('store');
                    Route::get('/{kyGui}',        [AdminKyGuiController::class, 'show'])->name('show');
                    Route::get('/{kyGui}/edit',   [AdminKyGuiController::class, 'edit'])->name('edit');
                    Route::put('/{kyGui}',        [AdminKyGuiController::class, 'update'])->name('update');
                    Route::delete('/{kyGui}',     [AdminKyGuiController::class, 'destroy'])->name('destroy');
                    Route::post('/{kyGui}/xu-ly', [AdminKyGuiController::class, 'xuLy'])->name('xu-ly');
                    Route::patch('/ky-gui/{kyGui}/xu-ly', [AdminKyGuiController::class, 'xuLy'])->name('ky-gui.xu-ly');

                    Route::resource('ky-gui', AdminKyGuiController::class);
                });



                Route::patch('/bat-dong-san/{batDongSan}/toggle', [BatDongSanController::class, 'toggle']);
                Route::patch('/bat-dong-san/{batDongSan}/trang-thai', [BatDongSanController::class, 'updateTrangThai']);
                Route::delete('/bat-dong-san/{batDongSan}/xoa-anh', [BatDongSanController::class, 'xoaAnh']);
            });

            // ── Admin + Sale ───────────────────
            Route::middleware('check.role:admin,sale')->group(function () {

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
Route::resource('lien-he', \App\Http\Controllers\Admin\LienHeController::class);

    // Route cập nhật nhanh trạng thái
    Route::post('lien-he/{lienHe}/cap-nhat-nhanh', [\App\Http\Controllers\Admin\LienHeController::class, 'capNhatNhanh'])->name('lien-he.cap-nhat-nhanh');

    // MỚI: Route chuyển đổi Lead thành Khách hàng
    Route::post('lien-he/{lienHe}/chuyen-khach', [\App\Http\Controllers\Admin\LienHeController::class, 'chuyenKhachHang'])->name('lien-he.chuyen-khach');
                Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
                    Route::get('/',                           [KhachHangController::class, 'index'])->name('index');
                    Route::get('/create',                     [KhachHangController::class, 'create'])->name('create');
                    Route::post('/',                          [KhachHangController::class, 'store'])->name('store');
                    Route::get('/{khachHang}',                [KhachHangController::class, 'show'])->name('show');
                    Route::get('/{khachHang}/edit',           [KhachHangController::class, 'edit'])->name('edit');
                    Route::put('/{khachHang}',                [KhachHangController::class, 'update'])->name('update');
                    Route::delete('/{khachHang}',             [KhachHangController::class, 'destroy'])->name('destroy');
                    Route::patch('/{khachHang}/toggle',       [KhachHangController::class, 'toggleKichHoat'])->name('toggle');
                    Route::patch('/{khachHang}/muc-do',       [KhachHangController::class, 'doiMucDo'])->name('muc-do');
                    Route::patch('/{khachHang}/ghi-chu',      [KhachHangController::class, 'capNhatGhiChu'])->name('ghi-chu');
                    Route::patch('/{khachHang}/lien-he-cuoi', [KhachHangController::class, 'capNhatLienHe'])->name('lien-he-cuoi');
                });

                Route::prefix('lien-he')->name('lien-he.')->group(function () {
                    Route::get('/',               [AdminLienHeController::class, 'index'])->name('index');
                    Route::get('/{lienHe}',       [AdminLienHeController::class, 'show'])->name('show');
                    Route::put('/{lienHe}',       [AdminLienHeController::class, 'update'])->name('update');
                    Route::delete('/{lienHe}',    [AdminLienHeController::class, 'destroy'])->name('destroy');
                    Route::post('/{lienHe}/cap-nhat-nhanh', [AdminLienHeController::class, 'capNhatNhanh'])->name('cap-nhat-nhanh');
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
                Route::get('/',                   [\App\Http\Controllers\Admin\KhuVucController::class, 'index'])->name('index');
                Route::get('/create',             [\App\Http\Controllers\Admin\KhuVucController::class, 'create'])->name('create');
                Route::post('/',                  [\App\Http\Controllers\Admin\KhuVucController::class, 'store'])->name('store');
                Route::get('/ajax/danh-sach-con', [\App\Http\Controllers\Admin\KhuVucController::class, 'getDanhSachCon'])->name('ajax.con');
                Route::get('/{khuVuc}',           [\App\Http\Controllers\Admin\KhuVucController::class, 'show'])->name('show');
                Route::get('/{khuVuc}/edit',      [\App\Http\Controllers\Admin\KhuVucController::class, 'edit'])->name('edit');
                Route::put('/{khuVuc}',           [\App\Http\Controllers\Admin\KhuVucController::class, 'update'])->name('update');
                Route::delete('/{khuVuc}',        [\App\Http\Controllers\Admin\KhuVucController::class, 'destroy'])->name('destroy');
                Route::patch('/{khuVuc}/toggle',  [\App\Http\Controllers\Admin\KhuVucController::class, 'toggleHienThi'])->name('toggle');
            });

            Route::prefix('chat')->name('chat.')->group(function () {
                Route::get('/',              [AdminChatController::class, 'index'])->name('index');
                Route::get('/{id}',          [AdminChatController::class, 'show'])->name('show');
                Route::post('/{id}/tra-loi', [AdminChatController::class, 'traLoi'])->name('tra-loi');
            });
        });
    });
});
