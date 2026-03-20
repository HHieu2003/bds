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




1. Bảng users (Quản lý Nhân viên & Admin)
id: Khóa chính (Primary Key), tự tăng.

name: Tên hiển thị của nhân viên (Ví dụ: Nguyễn Văn A).

email: Email dùng để đăng nhập (Yêu cầu duy nhất, không trùng lặp).

password: Mật khẩu đăng nhập (Đã được mã hóa).

role: Phân quyền hệ thống (Mặc định là 'sale', Admin là 'admin').

created_at: Thời gian tạo tài khoản.

updated_at: Thời gian cập nhật thông tin lần cuối.

2. Bảng du_an (Quản lý Dự án chung)
id: Khóa chính (Primary Key).

ten_du_an: Tên dự án (Ví dụ: Vinhomes Smart City, Goldmark City).

dia_chi: Địa chỉ cụ thể của dự án.

chu_dau_tu: Tên đơn vị chủ đầu tư.

don_vi_thi_cong: Tên đơn vị thi công (nếu cần).

created_at: Ngày tạo dự án trên hệ thống.

updated_at: Ngày cập nhật thông tin dự án.

3. Bảng bat_dong_san (Kho hàng / Sản phẩm)
id: Khóa chính (Primary Key).

du_an_id: Khóa ngoại liên kết với bảng du_an (Căn hộ này thuộc dự án nào).

user_id: Khóa ngoại liên kết với bảng users (Nhân viên nào đăng bài này).

tieu_de: Tiêu đề bài đăng hiển thị trên web (Quan trọng cho SEO).

slug: Đường dẫn tĩnh trên trình duyệt (Ví dụ: ban-can-ho-a1-gia-tot).

ma_can: Mã số căn hộ (Ví dụ: A.1012).

dien_tich: Diện tích căn hộ (m2).

phong_ngu: Số lượng phòng ngủ/vệ sinh (Ví dụ: 3PN2VS).

huong_cua: Hướng cửa chính.

huong_ban_cong: Hướng ban công.

noi_that: Tình trạng nội thất (Cơ bản/Full đồ).

gia: Giá tiền (Lưu số nguyên, đơn vị VNĐ).

hinh_thuc_thanh_toan: Phương thức thanh toán (Ví dụ: Cọc 1 đóng 3).

hinh_anh: Lưu danh sách đường dẫn ảnh dưới dạng JSON.

mo_ta: Bài viết mô tả chi tiết căn hộ.

thoi_gian_vao: Thời gian khách có thể vào ở.

loai_hinh: Loại giao dịch ('thue' hoặc 'ban').

trang_thai: Trạng thái kho hàng ('con_hang' hoặc 'da_chot').

ngay_goi: Ngày sale gọi điện chăm sóc (nội bộ).

ngay_dang: Ngày bắt đầu đăng tin công khai.

4. Bảng lien_he (Khách hàng tiềm năng / Leads)
id: Khóa chính (Primary Key).

so_dien_thoai: Số điện thoại khách hàng để lại (Bắt buộc).

loi_nhan: Lời nhắn hoặc câu hỏi của khách hàng.

bat_dong_san_id: Khóa ngoại liên kết với bảng bat_dong_san (Khách đang hỏi về căn nào).

trang_thai: Trạng thái xử lý của nhân viên ('chua_xu_ly' hoặc 'da_goi').

created_at: Thời gian khách gửi yêu cầu.























INSERT INTO `du_an` (`ten_du_an`, `don_vi_thi_cong`, `chu_dau_tu`, `dia_chi`, `mo_ta`, `hinh_anh`, `created_at`, `updated_at`) VALUES
('GOLDMARK CITY', 'Delta, Conteccon', 'Việt Hân', '136 Hồ Tùng Mậu', '<strong>TỔNG QUAN:</strong><br>Dự án gồm 9 tòa cao 40 tầng...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>8k/m2, Xe máy 60k, Oto 1,1tr<br><br><strong>DẠNG CĂN:</strong><br>2PN, 3PN, 4PN', NULL, NOW(), NOW()),
('THE ZEI', 'Delta', 'HD Mon Holdings', 'Số 8 Lê Đức Thọ', '<strong>TỔNG QUAN:</strong><br>42 tầng nổi, tiện ích cao cấp...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>12k/m2<br><br><strong>DẠNG CĂN:</strong><br>2PN, 3PN, Duplex', NULL, NOW(), NOW()),
('IRIS GARDEN', 'ECOBA Việt Nam', 'VimeFulland', '30 Trần Hữu Dực', '<strong>TỔNG QUAN:</strong><br>5 tòa tháp...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>10k/m2<br><br><strong>DẠNG CĂN:</strong><br>2PN, 3PN', NULL, NOW(), NOW()),
('VINHOMES GARDENIA', 'Conteccons', 'Vinhomes', '18 Hàm Nghi', '<strong>TỔNG QUAN:</strong><br>3 tòa A1, A2, A3...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>15k/m2<br><br><strong>DẠNG CĂN:</strong><br>1PN-4PN, Duplex', NULL, NOW(), NOW()),
('Vinhomes Green Bay', 'HÒA BÌNH', 'Vinhomes', 'Số 7 Đại lộ Thăng Long', '<strong>TỔNG QUAN:</strong><br>Gồm G1, G2, G3...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>16.5k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio - 4PN', NULL, NOW(), NOW()),
('Mỹ Đình Pearl', 'Phục Hưng', 'SSG GROUP', 'Số 1 Châu Văn Liêm', '<strong>TỔNG QUAN:</strong><br>2 tháp cao 38 tầng...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>13k/m2<br><br><strong>DẠNG CĂN:</strong><br>1PN-4PN, Duplex', NULL, NOW(), NOW()),
('The Matrix One', 'Conteccons', 'MIK Group', 'Lê Quang Đạo', '<strong>TỔNG QUAN:</strong><br>View đường đua F1...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>Miễn phí<br><br><strong>DẠNG CĂN:</strong><br>2PN, 3PN, Dual Key', NULL, NOW(), NOW()),
('THE EMERALD (CT8)', 'ECOBA', 'VimeFulland', 'KĐT Mỹ Đình', '<strong>TỔNG QUAN:</strong><br>4 tòa E1-E4...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>11k/m2<br><br><strong>DẠNG CĂN:</strong><br>2PN-4PN', NULL, NOW(), NOW()),
('VINHOMES SKYLAKE', 'Conteccons', 'Vinhomes', 'Phạm Hùng', '<strong>TỔNG QUAN:</strong><br>View hồ điều hòa...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>16k/m2<br><br><strong>DẠNG CĂN:</strong><br>1PN-4PN', NULL, NOW(), NOW()),
('VINHOMES WEST POINT', 'Coteccons', 'Vinhomes', 'Phạm Hùng - Đỗ Đức Dục', '<strong>TỔNG QUAN:</strong><br>3 tòa West 1,2,3...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>17k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-4PN', NULL, NOW(), NOW()),
('Florence Mỹ Đình', 'Phục Hưng', 'Phục Hưng', '28 Trần Hữu Dực', '<strong>TỔNG QUAN:</strong><br>2 tòa R1, R2...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>12k/m2<br><br><strong>DẠNG CĂN:</strong><br>2PN-4PN', NULL, NOW(), NOW()),
('HD Mon', 'Delta', 'HD Mon', 'Hàm Nghi', '<strong>TỔNG QUAN:</strong><br>2 tòa CT1A, CT1B...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>8k/m2<br><br><strong>DẠNG CĂN:</strong><br>2PN, 3PN', NULL, NOW(), NOW()),
('GOLDEN PALACE', 'Delta', 'Mai Linh', '99 Mễ Trì', '<strong>TỔNG QUAN:</strong><br>3 tòa A, B, C...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>6k/m2<br><br><strong>DẠNG CĂN:</strong><br>2PN-4PN', NULL, NOW(), NOW()),
('D\'CAPITAL', 'Coteccons', 'Tân Hoàng Minh', '119 Trần Duy Hưng', '<strong>TỔNG QUAN:</strong><br>6 tòa tháp C1-C7...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>19.5k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('Vinhomes Smart City - SAPPHIRE 1', 'Coteccons', 'Vinhomes', 'Đại lộ Thăng Long', '<strong>TỔNG QUAN:</strong><br>5 tòa S101-S106...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>8.8k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('Vinhomes Smart City - SAPPHIRE 2', 'Coteccons', 'Vinhomes', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>4 tòa S201-S205...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>8.8k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('Vinhomes Smart City - SAPPHIRE 3', 'Coteccons', 'Vinhomes', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>3 tòa S301-S303...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>8.8k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('Vinhomes Smart City - SAPPHIRE 4', 'Coteccons', 'Vinhomes', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>3 tòa S401-S403...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>8.8k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('THE MIAMI', 'Coteccons', 'Vinhomes', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>5 tòa GS1-GS6...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>Updating<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('THE SAKURA', 'Coteccons', 'Samty', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>4 tòa SA1-SA5...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>17k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('THE TONKIN', 'Coteccons', 'Vinhomes', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>2 tòa TK1-TK2...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>18k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('THE CANOPY', 'Coteccons', 'GIC', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>3 tòa TC1-TC3...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>Updating<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('IMPERIA SMART CITY', 'Coteccons', 'MIK Group', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>5 tòa I1-I5...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>Updating<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('THE SOLA PARK', 'Coteccons', 'MIK Group', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>5 tòa G1-G6...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>Updating<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('MASTERI WEST HEIGHTS', 'Coteccons', 'Masterise', 'Tây Mỗ', '<strong>TỔNG QUAN:</strong><br>4 tòa A-D...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>24k/m2<br><br><strong>DẠNG CĂN:</strong><br>Studio-3PN', NULL, NOW(), NOW()),
('LUMIERE EVERGREEN', 'Coteccons', 'Masterise', 'Đại lộ Ánh Sáng', '<strong>TỔNG QUAN:</strong><br>3 tòa A1-A3...<br><br><strong>PHÍ DỊCH VỤ:</strong><br>Updating<br><br><strong>DẠNG CĂN:</strong><br>Studio-4PN', NULL, NOW(), NOW());


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
