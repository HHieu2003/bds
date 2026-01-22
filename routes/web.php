<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DuAnController;
use App\Http\Controllers\BatDongSanController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\KyGuiController;
use App\Http\Controllers\LichHenController;
use App\Http\Controllers\TimKiemController;
use App\Http\Controllers\SoSanhController;
use App\Http\Controllers\YeuThichController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerAuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================
// PHẦN 1: FRONTEND (KHÁCH HÀNG)
// =========================================================

// 1. Trang chủ & Giới thiệu
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');

// 2. Tìm kiếm Bất Động Sản
Route::get('/tim-kiem', [TimKiemController::class, 'index'])->name('tim-kiem');

// 3. Chi tiết Bất Động Sản
Route::get('/bat-dong-san/{slug}', [BatDongSanController::class, 'show'])->name('bat-dong-san.show');

// 4. Dự án
Route::get('/du-an', [DuAnController::class, 'frontendIndex'])->name('du-an.index');
Route::get('/du-an/{slug}', [DuAnController::class, 'show'])->name('du-an.show');

// 5. Tin tức / Bài viết
Route::get('/tin-tuc', [BaiVietController::class, 'frontendIndex'])->name('bai-viet.index');
Route::get('/tin-tuc/{slug}', [BaiVietController::class, 'show'])->name('bai-viet.show');

// 6. Liên hệ & Gửi yêu cầu
Route::get('/lien-he', [LienHeController::class, 'index'])->name('lien-he.index');
Route::post('/lien-he', [LienHeController::class, 'store'])->name('lien-he.store');

// 7. Ký gửi nhà đất
Route::get('/ky-gui', [KyGuiController::class, 'create'])->name('ky-gui.create');
Route::post('/ky-gui', [KyGuiController::class, 'store'])->name('ky-gui.store');

// 8. Đặt lịch xem nhà
Route::post('/dat-lich-hen', [LichHenController::class, 'store'])->name('lich-hen.store');

// =========================================================
// PHẦN 2: TÍNH NĂNG TƯƠNG TÁC (AJAX/SESSION)
// =========================================================

// 1. So sánh Bất động sản
Route::prefix('so-sanh')->name('so-sanh.')->group(function () {
    Route::get('/', [SoSanhController::class, 'index'])->name('index');
    Route::get('/load-table', [SoSanhController::class, 'loadTable'])->name('load_table');
    Route::post('/add/{id}', [SoSanhController::class, 'addToCompare'])->name('add');
    Route::post('/remove/{id}', [SoSanhController::class, 'removeCompare'])->name('remove');
});
// Auth Khách hàng (API)
Route::prefix('customer')->name('customer.')->group(function () {
    Route::post('/quick-login', [CustomerAuthController::class, 'quickLogin'])->name('quick_login');
    Route::post('/send-otp', [CustomerAuthController::class, 'sendVerificationOtp'])->name('send_otp');
    Route::post('/confirm-otp', [CustomerAuthController::class, 'confirmVerificationOtp'])->name('confirm_otp');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
});

// Yêu thích
Route::prefix('yeu-thich')->name('yeu-thich.')->group(function () {
    Route::get('/', [YeuThichController::class, 'index'])->name('index');
    // SỬA LẠI ROUTE TOGGLE NHẬN ID
    Route::post('/toggle/{id}', [YeuThichController::class, 'toggle'])->name('toggle');
});

// 3. Hệ thống Chat (Khách hàng)
Route::prefix('chat')->name('chat.')->group(function () {
    Route::post('/start', [ChatController::class, 'startChat'])->name('start');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('messages');
});


// =========================================================
// PHẦN 3: AUTHENTICATION (ĐĂNG NHẬP QUẢN TRỊ)
// =========================================================

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================
// PHẦN 4: ADMIN DASHBOARD (QUẢN TRỊ VIÊN & SALE)
// =========================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Quản lý Dự án
    Route::resource('du-an', DuAnController::class);

    // 3. Quản lý Bất động sản
    Route::resource('bat-dong-san', BatDongSanController::class);

    // 4. Quản lý Bài viết / Tin tức
    Route::resource('bai-viet', BaiVietController::class);

    // 5. Quản lý Khách hàng liên hệ
    Route::prefix('lien-he')->name('lien-he.')->group(function () {
        Route::get('/', [LienHeController::class, 'adminIndex'])->name('index');
        Route::post('/update-status/{id}', [LienHeController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{id}', [LienHeController::class, 'destroy'])->name('destroy');
    });

    // 6. Quản lý Ký gửi
    Route::prefix('ky-gui')->name('ky-gui.')->group(function () {
        Route::get('/', [KyGuiController::class, 'adminIndex'])->name('index');
        Route::post('/update-status/{id}', [KyGuiController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{id}', [KyGuiController::class, 'destroy'])->name('destroy');
    });

    // 7. Quản lý Lịch hẹn
    Route::prefix('lich-hen')->name('lich-hen.')->group(function () {
        Route::get('/', [LichHenController::class, 'adminIndex'])->name('index');
        Route::post('/confirm/{id}', [LichHenController::class, 'confirm'])->name('confirm');
        Route::delete('/{id}', [LichHenController::class, 'destroy'])->name('destroy');
    });
    // 8. Hệ thống Chat (Admin) - Đã sửa lỗi
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'adminIndex'])->name('index');
        Route::post('/reply', [ChatController::class, 'adminReply'])->name('reply');
        Route::get('/get-messages', [ChatController::class, 'adminGetMessages'])->name('get_messages');
        Route::get('/{id}', [ChatController::class, 'adminShow'])->name('show');
    });
});
