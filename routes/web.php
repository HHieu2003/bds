<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DuAnController;
use App\Http\Controllers\BatDongSanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;

// --- KHU VỰC CÔNG KHAI (KHÁCH HÀNG) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/chi-tiet/{slug}', [HomeController::class, 'show'])->name('home.show');
Route::post('/gui-lien-he', [LienHeController::class, 'store'])->name('lien-he.store');
Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');



// --- API CHAT (KHÔNG CẦN LOGIN) ---
Route::post('/chat/start', [ChatController::class, 'startChat'])->name('chat.start');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

// --- ADMIN CHAT (CẦN LOGIN) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/chat', [ChatController::class, 'adminIndex'])->name('admin.chat.index');
    Route::get('/admin/chat/{id}', [ChatController::class, 'adminShow'])->name('admin.chat.show');
});


// --- THÊM MỚI: ROUTE DỰ ÁN ---
Route::get('/danh-sach-du-an', [HomeController::class, 'listProjects'])->name('project.index');
Route::get('/du-an/{id}', [HomeController::class, 'showProject'])->name('project.show');

// --- KHU VỰC ĐĂNG NHẬP ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- KHU VỰC QUẢN TRỊ (BẢO MẬT) ---
// Nhóm này yêu cầu phải đăng nhập mới vào được
Route::middleware(['auth'])->group(function () {

    // Quản lý Dự án
    Route::resource('du-an', DuAnController::class);

    // Quản lý Bất động sản
    Route::resource('bat-dong-san', BatDongSanController::class);

    // Quản lý Liên hệ (Khách hàng)
    Route::get('/quan-ly-lien-he', [LienHeController::class, 'index'])->name('lien-he.index');

    // Route mặc định khi vào admin chuyển hướng sang danh sách BĐS
    Route::get('/admin', function () {
        return redirect()->route('bat-dong-san.index');
    });
});
