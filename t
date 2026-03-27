1. Chạy dự án & cấu hình
php artisan serve
→ Chạy web local tại http://127.0.0.1:8000

php artisan key:generate


→ Tạo khóa bảo mật cho ứng dụng (APP_KEY)

php artisan optimize


→ Tối ưu toàn bộ hệ thống (config, route, view)

php artisan optimize:clear


→ Xóa toàn bộ cache tối ưu

2. Migration & Database
php artisan migrate

php artisan db:seed --class=DuAnSeeder

→ Tạo bảng theo file migration

php artisan migrate:rollback


→ Quay lại lần migrate trước

php artisan migrate:reset


→ Xóa toàn bộ bảng theo migration

php artisan migrate:refresh


→ Rollback toàn bộ rồi tạo lại bảng

php artisan migrate:fresh


→ Xóa sạch DB rồi tạo lại từ đầu

php artisan migrate:fresh --seed


→ Tạo lại DB + thêm dữ liệu mẫu

3. Tạo nhanh file
php artisan make:model User -mcr


→ Tạo Model + Migration + Controller + Resource

php artisan make:controller PostController --resource


→ Tạo controller chuẩn CRUD

php artisan make:migration create_posts_table


→ Tạo file tạo bảng

php artisan make:seeder PostSeeder


→ Tạo file dữ liệu mẫu

php artisan make:factory PostFactory


→ Tạo factory sinh dữ liệu giả

php artisan make:request StorePostRequest


→ Tạo file validate dữ liệu

php artisan make:middleware CheckLogin


→ Tạo middleware

4. Dữ liệu mẫu
php artisan db:seed


→ Chạy toàn bộ seeder

php artisan db:seed --class=PostSeeder


→ Chạy seeder chỉ định

5. Route & Debug
php artisan route:list


→ Xem toàn bộ route hiện có

php artisan tinker


→ Chạy thử code trong terminal

php artisan make:command ClearLog


→ Tạo lệnh riêng cho bạn

6. Cache & View
php artisan cache:clear


→ Xóa cache

php artisan config:clear


→ Xóa cache cấu hình

php artisan route:clear


→ Xóa cache route

php artisan view:clear


→ Xóa cache giao diện

7. Queue & Job
php artisan make:job SendMailJob


→ Tạo job xử lý nền

php artisan queue:work


→ Chạy hàng đợi

8. Log & Lỗi
php artisan storage:link


→ Tạo link thư mục storage

php artisan down


→ Đưa web vào chế độ bảo trì

php artisan up


→ Mở lại web

Ví dụ thực tế
php artisan migrate:fresh --seed


→ Xóa toàn bộ bảng
→ Tạo lại bảng
→ Thêm dữ liệu mẫu để test nhanh


Vì bạn vừa chạy lệnh migrate:fresh để thêm cột, dữ liệu cũ đã mất hết. Đây là câu lệnh để tạo nhanh tài khoản Admin mới.

Bạn có 2 cách, hãy chọn cách bạn thấy tiện nhất:

Cách 1: Dùng Tinker (Nhanh nhất - Gõ trực tiếp)
Mở Terminal và gõ lần lượt các dòng sau:

Vào môi trường dòng lệnh Laravel:

Bash

php artisan tinker
Copy đoạn code này dán vào và nhấn Enter:

PHP

\App\Models\User::create([
    'name' => 'Admin Quản Trị',
    'email' => 'admin@gmail.com',
    'password' => bcrypt('123456'),
    'role' => 'admin'
]);


resources/views/
├── admin/
│   ├── auth/
│   │   └── login.blade.php
│   ├── layouts/                  <-- Đổi thành số nhiều
│   │   └── master.blade.php
│   ├── partials/                 <-- Bổ sung để cắt nhỏ giao diện Admin
│   │   ├── sidebar.blade.php
│   │   ├── header.blade.php
│   │   └── footer.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── bat-dong-san/             <-- Chuyển sang gạch ngang (kebab-case)
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php        <-- Bổ sung (nếu cần xem chi tiết trước khi duyệt)
│   ├── du-an/
│   ├── bai-viet/
│   ├── ky-gui/
│   ├── lich-hen/
│   ├── lien-he/
│   ├── chat/
│   └── khach-hang/               <-- Bổ sung vì có KhachHangController ở Admin
│
├── frontend/
│   ├── auth/                     <-- Bổ sung cho luồng khách hàng đăng nhập/đăng ký
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── layouts/
│   │   └── master.blade.php
│   ├── partials/
│   │   ├── header.blade.php
│   │   ├── footer.blade.php
│   │   └── chat-widget.blade.php <-- Đổi thành gạch ngang
│   ├── home/
│   │   ├── index.blade.php
│   │   └── about.blade.php
│   ├── bat-dong-san/
│   ├── du-an/
│   ├── bai-viet/
│   ├── tim-kiem/
│   ├── so-sanh/
│   ├── ky-gui/
│   └── yeu-thich/
│
└── emails/                       <-- Bổ sung: Chứa các template gửi mail
    ├── xac_nhan_lich_hen.blade.php
    ├── canh_bao_gia.blade.php
    └── chao_mung.blade.php
    Duanbds/batdongsan/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php                          ← Base controller (BẮT BUỘC)
│   │   │   │
│   │   │   ├── Auth/
│   │   │   │   ├── AdminAuthController.php
│   │   │   │   └── KhachHangAuthController.php
│   │   │   │
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DuAnController.php
│   │   │   │   ├── BatDongSanController.php
│   │   │   │   ├── KyGuiController.php
│   │   │   │   ├── KhachHangController.php
│   │   │   │   ├── LichHenController.php
│   │   │   │   ├── BaiVietController.php
│   │   │   │   ├── NhanVienController.php
│   │   │   │   └── ChatController.php
│   │   │   │
│   │   │   └── Frontend/
│   │   │       ├── HomeController.php
│   │   │       ├── BatDongSanController.php
│   │   │       ├── DuAnController.php
│   │   │       ├── BaiVietController.php
│   │   │       ├── KyGuiController.php
│   │   │       ├── LienHeController.php
│   │   │       ├── ChatController.php
│   │   │       ├── YeuThichController.php
│   │   │       └── TimKiemController.php
│   │   │
│   │   ├── Middleware/
│   │   │   └── CheckRole.php                           ← Phân quyền admin/nguon/sale
│   │   │
│   │   └── Requests/                                   ← Form Request Validation
│   │       ├── StoreBatDongSanRequest.php
│   │       ├── StoreKyGuiRequest.php
│   │       └── StoreLichHenRequest.php
│   │
│   ├── Models/
│   │   ├── NhanVien.php
│   │   ├── KhachHang.php
│   │   ├── KhuVuc.php
│   │   ├── DuAn.php
│   │   ├── BatDongSan.php
│   │   ├── BaiViet.php
│   │   ├── LichHen.php
│   │   ├── KyGui.php
│   │   ├── YeuCauLienHe.php
│   │   ├── GhiChuKhachHang.php
│   │   ├── LichSuTimKiem.php
│   │   ├── LuotXemBatDongSan.php
│   │   ├── YeuThich.php
│   │   ├── PhienChat.php
│   │   ├── TinNhanChat.php
│   │   ├── TinNhanNoiBo.php
│   │   ├── ThongBao.php
│   │   └── NhatKyEmail.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   └── app.php                                         ← Đăng ký middleware 'role'
│
├── config/
│   ├── auth.php                                        ← Guard 'customer'
│   ├── app.php
│   ├── database.php
│   └── filesystems.php
│
├── database/
│   ├── migrations/
│   │   ├── 2026_01_01_000001_create_nhan_vien_table.php
│   │   ├── 2026_01_01_000002_create_khach_hang_table.php
│   │   ├── 2026_01_01_000003_create_khu_vuc_table.php
│   │   ├── 2026_01_01_000004_create_du_an_table.php
│   │   ├── 2026_01_01_000005_create_bat_dong_san_table.php
│   │   ├── 2026_01_01_000006_create_bai_viet_table.php
│   │   ├── 2026_01_01_000007_create_lich_hen_table.php
│   │   ├── 2026_01_01_000008_create_ky_gui_table.php
│   │   ├── 2026_01_01_000009_create_yeu_cau_lien_he_table.php
│   │   ├── 2026_01_01_000010_create_ghi_chu_khach_hang_table.php
│   │   ├── 2026_01_01_000011_create_lich_su_tim_kiem_table.php
│   │   ├── 2026_01_01_000012_create_luot_xem_bat_dong_san_table.php
│   │   ├── 2026_01_01_000013_create_yeu_thich_table.php
│   │   ├── 2026_01_01_000014_create_phien_chat_table.php
│   │   ├── 2026_01_01_000015_create_tin_nhan_chat_table.php
│   │   ├── 2026_01_01_000016_create_tin_nhan_noi_bo_table.php
│   │   ├── 2026_01_01_000017_create_thong_bao_table.php
│   │   └── 2026_01_01_000018_create_nhat_ky_email_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── NhanVienSeeder.php
│       ├── KhachHangSeeder.php
│       ├── KhuVucSeeder.php
│       ├── DuAnSeeder.php
│       ├── BatDongSanSeeder.php
│       ├── BaiVietSeeder.php
│       ├── YeuCauLienHeSeeder.php
│       ├── LichHenSeeder.php
│       ├── KyGuiSeeder.php
│       ├── PhienChatSeeder.php
│       └── TinNhanNoiBoSeeder.php
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/                                         ← CSS/JS/IMG tự viết
│       ├── css/
│       │   └── app.css
│       ├── js/
│       │   └── app.js
│       └── images/
│           └── logo.png
│
├── resources/
│   └── views/
│       │
│       ├── frontend/
│       │   ├── layouts/
│       │   │   └── master.blade.php                    ← Layout chính (header+footer)
│       │   │
│       │   ├── partials/
│       │   │   ├── bds-card.blade.php                  ← Card BĐS tái sử dụng
│       │   │   ├── du-an-card.blade.php
│       │   │   └── chat_widget.blade.php               ← Widget chat nổi
│       │   │
│       │   ├── components/
│       │   │   ├── header.blade.php
│       │   │   └── footer.blade.php
│       │   │
│       │   ├── home/
│       │   │   └── index.blade.php                     ← Trang chủ ✅
│       │   │
│       │   ├── bat-dong-san/
│       │   │   ├── index.blade.php                     ← Danh sách + lọc BĐS
│       │   │   └── show.blade.php                      ← Chi tiết BĐS
│       │   │
│       │   ├── du-an/
│       │   │   ├── index.blade.php                     ← Danh sách dự án
│       │   │   └── show.blade.php                      ← Chi tiết dự án
│       │   │
│       │   ├── bai-viet/
│       │   │   ├── index.blade.php                     ← Danh sách bài viết
│       │   │   └── show.blade.php                      ← Chi tiết bài viết
│       │   │
│       │   ├── ky-gui/
│       │   │   └── create.blade.php                    ← Form ký gửi BĐS
│       │   │
│       │   ├── yeu-thich/
│       │   │   └── index.blade.php                     ← Danh sách BĐS yêu thích
│       │   │
│       │   └── pages/
│       │       └── about.blade.php                     ← Trang giới thiệu ✅
│       │
│       └── admin/
│           ├── layouts/
│           │   └── master.blade.php                    ← Layout admin sidebar
│           │
│           ├── partials/
│           │   ├── sidebar.blade.php                   ← Menu theo role
│           │   ├── navbar.blade.php
│           │   └── alerts.blade.php                    ← Flash messages
│           │
│           ├── auth/
│           │   └── login.blade.php                     ← Trang đăng nhập admin
│           │
│           ├── dashboard/
│           │   └── index.blade.php                     ← Dashboard thống kê
│           │
│           ├── bat-dong-san/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   ├── edit.blade.php
│           │   └── show.blade.php
│           │
│           ├── du-an/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           │
│           ├── ky-gui/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           │
│           ├── khach-hang/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           │
│           ├── lich-hen/
│           │   └── index.blade.php
│           │
│           ├── bai-viet/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           │
│           ├── nhan-vien/
│           │   └── index.blade.php
│           │
│           └── chat/
│               ├── index.blade.php                     ← Chat với khách
│               └── noi-bo.blade.php                    ← Chat nội bộ NV
│
├── routes/
│   ├── web.php                                         ← Toàn bộ routes ✅
│   ├── api.php
│   └── console.php
│
├── storage/
│   ├── app/
│   │   └── public/                                     ← Ảnh upload
│   │       ├── bds/
│   │       │   └── album/
│   │       ├── du-an/
│   │       │   └── album/
│   │       ├── bai-viet/
│   │       └── chat/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
│       └── laravel.log
│
├── .env                                                ← Cấu hình DB, mail...
├── .env.example
├── artisan
├── composer.json
└── package.json


📦 THÀNH CÔNG LAND (Dự án Bất Động Sản)
┣ 📂 app
┃ ┣ 📂 Http
┃ ┃ ┣ 📂 Controllers
┃ ┃ ┃ ┣ 📂 Admin                 <-- [Logic Ban Quản Trị & Nhân Viên]
┃ ┃ ┃ ┃ ┣ 📜 AdminAuthController.php
┃ ┃ ┃ ┃ ┣ 📜 BaiVietController.php
┃ ┃ ┃ ┃ ┣ 📜 BatDongSanController.php
┃ ┃ ┃ ┃ ┣ 📜 ChatController.php
┃ ┃ ┃ ┃ ┣ 📜 DashboardController.php
┃ ┃ ┃ ┃ ┣ 📜 DuAnController.php
┃ ┃ ┃ ┃ ┣ 📜 KhachHangController.php
┃ ┃ ┃ ┃ ┣ 📜 KyGuiController.php
┃ ┃ ┃ ┃ ┣ 📜 LichHenController.php
┃ ┃ ┃ ┃ ┗ 📜 NhanVienController.php
┃ ┃ ┃ ┣ 📂 Auth                  <-- [Xử lý Đăng nhập/Đăng ký]
┃ ┃ ┃ ┃ ┣ 📜 AdminAuthController.php
┃ ┃ ┃ ┃ ┗ 📜 KhachHangAuthController.php
┃ ┃ ┃ ┣ 📂 Frontend              <-- [Logic Giao diện Người dùng]
┃ ┃ ┃ ┃ ┣ 📜 BaiVietController.php
┃ ┃ ┃ ┃ ┣ 📜 BatDongSanController.php
┃ ┃ ┃ ┃ ┣ 📜 ChatController.php
┃ ┃ ┃ ┃ ┣ 📜 DuAnController.php
┃ ┃ ┃ ┃ ┣ 📜 HomeController.php
┃ ┃ ┃ ┃ ┣ 📜 KyGuiController.php
┃ ┃ ┃ ┃ ┣ 📜 LienHeController.php
┃ ┃ ┃ ┃ ┣ 📜 SoSanhController.php
┃ ┃ ┃ ┃ ┣ 📜 TimKiemController.php
┃ ┃ ┃ ┃ ┗ 📜 YeuThichController.php
┃ ┃ ┃ ┗ 📜 Controller.php        <-- [Controller gốc]
┃ ┃ ┣ 📂 Middleware
┃ ┃ ┃ ┗ 📜 CheckRole.php         <-- [Phân quyền: admin, sale, nguon]
┃ ┣ 📂 Models                    <-- [19 Models tương tác CSDL]
┃ ┃ ┣ 📜 BaiViet.php, BatDongSan.php, CanhBaoGia.php, DuAn.php...
┃ ┃ ┣ 📜 KhachHang.php, GhiChuKhachHang.php, KyGui.php...
┃ ┃ ┣ 📜 NhanVien.php, ThongBao.php, YeuCauLienHe.php, YeuThich.php...
┃ ┃ ┗ 📜 PhienChat.php, TinNhanChat.php, TinNhanNoiBo.php...
┃ ┗ 📂 Providers
┃   ┗ 📜 AppServiceProvider.php
┃
┣ 📂 database
┃ ┗ 📂 migrations                <-- [20 Bảng CSDL]
┃   ┣ 📜 ...create_nhan_vien_table.php
┃   ┣ 📜 ...create_khach_hang_table.php
┃   ┣ 📜 ...create_bat_dong_san_table.php
┃   ┗ 📜 ... (và 17 file migation khác)
┃
┣ 📂 resources
┃ ┣ 📂 css
┃ ┃ ┗ 📜 app.css
┃ ┣ 📂 js
┃ ┃ ┣ 📜 app.js
┃ ┃ ┗ 📜 bootstrap.js
┃ ┗ 📂 views
┃   ┣ 📂 admin                   <-- [Giao diện Trang quản trị]
┃   ┃ ┣ 📂 auth (login)
┃   ┃ ┣ 📂 bai_viet (index, create, edit)
┃   ┃ ┣ 📂 bat_dong_san (index, create, edit)
┃   ┃ ┣ 📂 chat (index, show)
┃   ┃ ┣ 📂 dashboard (index)
┃   ┃ ┣ 📂 du_an (index, create, edit)
┃   ┃ ┣ 📂 ky_gui (index)
┃   ┃ ┣ 📂 layout (master.blade.php)
┃   ┃ ┣ 📂 lich_hen (index)
┃   ┃ ┗ 📂 lien_he (index)
┃   ┗ 📂 frontend                <-- [Giao diện Khách hàng]
┃     ┣ 📂 bai_viet (index, show)
┃     ┣ 📂 bat_dong_san (show)
┃     ┣ 📂 du_an (index, show)
┃     ┣ 📂 home (index, about)
┃     ┣ 📂 ky_gui (create)
┃     ┣ 📂 layouts (master.blade.php)
┃     ┣ 📂 partials (header, footer, chat_widget)
┃     ┣ 📂 so_sanh (partial)
┃     ┣ 📂 tim_kiem (index)
┃     ┗ 📂 yeu_thich (index)
┃
┗ 📂 routes
  ┗ 📜 web.php                   <-- [Định tuyến chuẩn SEO & Bảo mật]



  resources/views/
├── frontend/                    ← Giao diện người dùng
│   ├── layouts/
│   │   ├── master.blade.php     ← Layout chính frontend
│   │   ├── header.blade.php     ← ✅ Đã thiết kế (màu cam)
│   │   ├── footer.blade.php     ← ✅ Đã thiết kế (màu cam)
│   │   ├── partial.blade.php
│   │   └── chat_widget.blade.php
│   ├── home/
│   │   └── index.blade.php      ← Trang chủ
│   ├── bat-dong-san/
│   │   ├── index.blade.php      ← Danh sách BĐS
│   │   └── show.blade.php       ← Chi tiết BĐS
│   ├── du-an/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── bai-viet/
│   │   └── index.blade.php
│   ├── ky-gui/
│   │   └── create.blade.php
│   ├── yeu-thich/
│   │   └── index.blade.php
│   ├── gioi-thieu/
│   │   └── gioi-thieu-cong-ty.blade.php
│   └── about.blade.php
│
└── admin/                       ← Giao diện quản trị
    ├── layouts/
    │   └── master.blade.php
    ├── bat-dong-san/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    ├── khach-hang/
    │   └── index.blade.php
    ├── du-an/
    │   └── index.blade.php
    ├── ky-gui/
    │   └── index.blade.php
    ├── lich-hen/
    │   └── index.blade.php
    └── bai-viet/
        └── index.blade.php
