-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 03:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `batdongsan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bai_viet`
--

CREATE TABLE `bai_viet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhan_vien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `mo_ta_ngan` text DEFAULT NULL,
  `noi_dung` longtext NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `album_anh` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`album_anh`)),
  `loai_bai_viet` varchar(255) NOT NULL DEFAULT 'tin_tuc',
  `noi_bat` tinyint(1) NOT NULL DEFAULT 0,
  `hien_thi` tinyint(1) NOT NULL DEFAULT 1,
  `luot_xem` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `thu_tu_hien_thi` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `thoi_diem_dang` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bai_viet`
--

INSERT INTO `bai_viet` (`id`, `nhan_vien_id`, `tieu_de`, `slug`, `mo_ta_ngan`, `noi_dung`, `hinh_anh`, `album_anh`, `loai_bai_viet`, `noi_bat`, `hien_thi`, `luot_xem`, `thu_tu_hien_thi`, `seo_title`, `seo_description`, `seo_keywords`, `thoi_diem_dang`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Thị trường bất động sản Hà Nội quý 1/2026: Xu hướng và cơ hội', 'thi-truong-bat-dong-san-ha-noi-quy-12026-xu-huong-va-co-hoi-1', 'Phân tích toàn diện thị trường BĐS Hà Nội đầu năm 2026, những phân khúc đang tăng mạnh.', '<h2>Thị trường bất động sản Hà Nội quý 1/2026: Xu hướng và cơ hội</h2><p>Phân tích toàn diện thị trường BĐS Hà Nội đầu năm 2026, những phân khúc đang tăng mạnh.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'tin_tuc', 1, 1, 1071, 1, 'Thị trường bất động sản Hà Nội quý 1/2026: Xu hướng và cơ hội | Thành Công Land', 'Phân tích toàn diện thị trường BĐS Hà Nội đầu năm 2026, những phân khúc đang tăng mạnh.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 23:22:07', NULL),
(2, 1, 'Kinh nghiệm mua căn hộ lần đầu: 10 điều cần biết', 'kinh-nghiem-mua-can-ho-lan-dau-10-dieu-can-biet-2', 'Hướng dẫn chi tiết cho người mua căn hộ lần đầu, tránh các bẫy phổ biến.', '<h2>Kinh nghiệm mua căn hộ lần đầu: 10 điều cần biết</h2><p>Hướng dẫn chi tiết cho người mua căn hộ lần đầu, tránh các bẫy phổ biến.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'kien_thuc', 1, 1, 1170, 2, 'Kinh nghiệm mua căn hộ lần đầu: 10 điều cần biết | Thành Công Land', 'Hướng dẫn chi tiết cho người mua căn hộ lần đầu, tránh các bẫy phổ biến.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 22:05:15', NULL),
(3, 1, 'Phong thủy nhà ở: Hướng cửa chính ảnh hưởng thế nào đến tài vận', 'phong-thuy-nha-o-huong-cua-chinh-anh-huong-the-nao-den-tai-van-3', 'Bí quyết chọn hướng nhà theo phong thủy, mang lại may mắn và thịnh vượng.', '<h2>Phong thủy nhà ở: Hướng cửa chính ảnh hưởng thế nào đến tài vận</h2><p>Bí quyết chọn hướng nhà theo phong thủy, mang lại may mắn và thịnh vượng.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'phong_thuy', 0, 1, 487, 3, 'Phong thủy nhà ở: Hướng cửa chính ảnh hưởng thế nào đến tài vận | Thành Công Land', 'Bí quyết chọn hướng nhà theo phong thủy, mang lại may mắn và thịnh vượng.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 23:18:27', NULL),
(4, 1, 'Vinhomes Smart City: Khu đô thị đáng sống nhất Hà Nội 2026', 'vinhomes-smart-city-khu-do-thi-dang-song-nhat-ha-noi-2026-4', 'Tổng quan về Vinhomes Smart City sau 5 năm hoạt động, cộng đồng cư dân đông đúc.', '<h2>Vinhomes Smart City: Khu đô thị đáng sống nhất Hà Nội 2026</h2><p>Tổng quan về Vinhomes Smart City sau 5 năm hoạt động, cộng đồng cư dân đông đúc.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'tin_tuc', 1, 1, 1224, 4, 'Vinhomes Smart City: Khu đô thị đáng sống nhất Hà Nội 2026 | Thành Công Land', 'Tổng quan về Vinhomes Smart City sau 5 năm hoạt động, cộng đồng cư dân đông đúc.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 22:05:15', NULL),
(5, 1, 'Thủ tục sang tên sổ đỏ năm 2026: Hướng dẫn từng bước', 'thu-tuc-sang-ten-so-do-nam-2026-huong-dan-tung-buoc-5', 'Hồ sơ, thời gian, chi phí sang tên sổ đỏ theo quy định mới nhất.', '<h2>Thủ tục sang tên sổ đỏ năm 2026: Hướng dẫn từng bước</h2><p>Hồ sơ, thời gian, chi phí sang tên sổ đỏ theo quy định mới nhất.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'kien_thuc', 0, 1, 1589, 5, 'Thủ tục sang tên sổ đỏ năm 2026: Hướng dẫn từng bước | Thành Công Land', 'Hồ sơ, thời gian, chi phí sang tên sổ đỏ theo quy định mới nhất.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 22:05:15', NULL),
(6, 1, 'Top 5 khu vực đầu tư BĐS sinh lời cao tại Hà Nội', 'top-5-khu-vuc-dau-tu-bds-sinh-loi-cao-tai-ha-noi-6', 'Phân tích chi tiết 5 khu vực có tiềm năng tăng giá tốt nhất Hà Nội.', '<h2>Top 5 khu vực đầu tư BĐS sinh lời cao tại Hà Nội</h2><p>Phân tích chi tiết 5 khu vực có tiềm năng tăng giá tốt nhất Hà Nội.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'kien_thuc', 0, 1, 159, 6, 'Top 5 khu vực đầu tư BĐS sinh lời cao tại Hà Nội | Thành Công Land', 'Phân tích chi tiết 5 khu vực có tiềm năng tăng giá tốt nhất Hà Nội.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 22:05:15', NULL),
(7, 1, 'Thành Công Land tuyển dụng nhân viên kinh doanh BĐS 2026', 'thanh-cong-land-tuyen-dung-nhan-vien-kinh-doanh-bds-2026-7', 'Thành Công Land đang tuyển dụng nhân viên sale BĐS, thu nhập hấp dẫn, không giới hạn.', '<h2>Thành Công Land tuyển dụng nhân viên kinh doanh BĐS 2026</h2><p>Thành Công Land đang tuyển dụng nhân viên sale BĐS, thu nhập hấp dẫn, không giới hạn.</p><p>Nội dung chi tiết đang được cập nhật. Vui lòng liên hệ <strong>Thành Công Land</strong> qua hotline <strong>0123 456 789</strong> để được tư vấn miễn phí.</p>', NULL, NULL, 'tuyen_dung', 0, 1, 200, 7, 'Thành Công Land tuyển dụng nhân viên kinh doanh BĐS 2026 | Thành Công Land', 'Thành Công Land đang tuyển dụng nhân viên sale BĐS, thu nhập hấp dẫn, không giới hạn.', NULL, '2026-04-06 22:05:15', '2026-04-04 11:39:22', '2026-04-06 22:05:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bat_dong_san`
--

CREATE TABLE `bat_dong_san` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `du_an_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_phu_trach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `chu_nha_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `ma_bat_dong_san` varchar(255) NOT NULL,
  `loai_hinh` varchar(255) NOT NULL DEFAULT 'can_ho',
  `nhu_cau` varchar(255) NOT NULL DEFAULT 'ban',
  `ma_can` varchar(255) DEFAULT NULL,
  `toa` varchar(255) DEFAULT NULL,
  `tang` varchar(255) DEFAULT NULL,
  `huong_cua` varchar(255) DEFAULT NULL,
  `huong_ban_cong` varchar(255) DEFAULT NULL,
  `dien_tich` decimal(10,2) NOT NULL,
  `so_phong_ngu` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `noi_that` varchar(255) DEFAULT NULL,
  `mo_ta` longtext DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `album_anh` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`album_anh`)),
  `gia` decimal(15,2) DEFAULT NULL,
  `phi_moi_gioi` decimal(15,2) DEFAULT NULL,
  `phi_sang_ten` decimal(15,2) DEFAULT NULL,
  `phap_ly` varchar(255) DEFAULT NULL,
  `gia_thue` decimal(15,2) DEFAULT NULL,
  `thoi_gian_vao_thue` varchar(255) DEFAULT NULL,
  `hinh_thuc_thanh_toan` varchar(255) DEFAULT NULL,
  `noi_bat` tinyint(1) NOT NULL DEFAULT 0,
  `gui_mail_canh_bao_gia` tinyint(1) NOT NULL DEFAULT 1,
  `hien_thi` tinyint(1) NOT NULL DEFAULT 1,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'con_hang',
  `luot_xem` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `thu_tu_hien_thi` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `thoi_diem_dang` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bat_dong_san`
--

INSERT INTO `bat_dong_san` (`id`, `du_an_id`, `nhan_vien_phu_trach_id`, `chu_nha_id`, `tieu_de`, `slug`, `ma_bat_dong_san`, `loai_hinh`, `nhu_cau`, `ma_can`, `toa`, `tang`, `huong_cua`, `huong_ban_cong`, `dien_tich`, `so_phong_ngu`, `noi_that`, `mo_ta`, `hinh_anh`, `album_anh`, `gia`, `phi_moi_gioi`, `phi_sang_ten`, `phap_ly`, `gia_thue`, `thoi_gian_vao_thue`, `hinh_thuc_thanh_toan`, `noi_bat`, `gui_mail_canh_bao_gia`, `hien_thi`, `trang_thai`, `luot_xem`, `thu_tu_hien_thi`, `seo_title`, `seo_description`, `seo_keywords`, `thoi_diem_dang`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 'Căn hộ 2PN Vinhomes Smart City view hồ', 'can-ho-2pn-vinhomes-smart-city-view-ho-1', 'BDS-0001', 'can_ho', 'ban', 'VS-A1-1201', 'Tòa A', 'Tầng 20', 'Bắc', NULL, 65.50, 2, 'cao_cap', '<p>Căn hộ 2PN Vinhomes Smart City view hồ. Vị tr&iacute; đẹp, ph&aacute;p l&yacute; r&otilde; r&agrave;ng, sổ hồng ch&iacute;nh chủ. Li&ecirc;n hệ ngay để được tư vấn miễn ph&iacute;.</p>', NULL, '[]', 2800000000.00, 28000000.00, NULL, 'so_do', NULL, NULL, NULL, 1, 1, 1, 'con_hang', 464, 1, 'Căn hộ 2PN Vinhomes Smart City view hồ - Thành Công Land', 'Chi tiết Căn hộ 2PN Vinhomes Smart City view hồ', NULL, '2026-04-06 17:00:00', '2026-04-04 11:39:22', '2026-04-07 01:42:28', NULL),
(2, 2, 2, NULL, 'Căn hộ 3PN Vinhomes Smart City tầng cao', 'can-ho-3pn-vinhomes-smart-city-tang-cao-2', 'BDS-0002', 'can_ho', 'ban', 'VS-B2-2501', 'Tòa B', 'Tầng 13', 'Nam', NULL, 89.20, 3, 'full', '<p>Căn hộ 3PN Vinhomes Smart City tầng cao. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 4200000000.00, 42000000.00, NULL, 'so_hong', NULL, NULL, NULL, 1, 1, 1, 'con_hang', 110, 2, 'Căn hộ 3PN Vinhomes Smart City tầng cao - Thành Công Land', 'Chi tiết Căn hộ 3PN Vinhomes Smart City tầng cao', NULL, '2026-03-14 11:39:22', '2026-04-04 11:39:22', '2026-04-06 09:23:07', NULL),
(3, 3, 3, NULL, 'Nhà phố Hà Đông 4 tầng mặt tiền đường lớn', 'nha-pho-ha-dong-4-tang-mat-tien-duong-lon-3', 'BDS-0003', 'nha_pho', 'ban', NULL, NULL, NULL, 'Đông', NULL, 82.00, 4, 'co_ban', '<p>Nhà phố Hà Đông 4 tầng mặt tiền đường lớn. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 7500000000.00, 75000000.00, NULL, 'so_do', NULL, NULL, NULL, 1, 1, 1, 'con_hang', 298, 3, 'Nhà phố Hà Đông 4 tầng mặt tiền đường lớn - Thành Công Land', 'Chi tiết Nhà phố Hà Đông 4 tầng mặt tiền đường lớn', NULL, '2026-02-19 11:39:22', '2026-04-04 11:39:22', '2026-04-05 01:17:02', NULL),
(4, 4, 1, NULL, 'Biệt thự đơn lập Thanh Hà Cienco 5', 'biet-thu-don-lap-thanh-ha-cienco-5-4', 'BDS-0004', 'biet_thu', 'ban', 'TH-BT-001', NULL, NULL, 'Đông Bắc', NULL, 200.00, 5, 'full', '<p>Biệt thự đơn lập Thanh Hà Cienco 5. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 15000000000.00, 150000000.00, NULL, 'hop_dong', NULL, NULL, NULL, 1, 1, 1, 'dat_coc', 482, 4, 'Biệt thự đơn lập Thanh Hà Cienco 5 - Thành Công Land', 'Chi tiết Biệt thự đơn lập Thanh Hà Cienco 5', NULL, '2026-03-06 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(5, 5, 2, NULL, 'Căn hộ 1PN+1 Mipec Rubik 360 giá tốt', 'can-ho-1pn1-mipec-rubik-360-gia-tot-5', 'BDS-0005', 'can_ho', 'ban', 'MR-T5-0803', 'Tòa A', 'Tầng 23', 'Tây Bắc', NULL, 52.30, 1, 'co_ban', '<p>Căn hộ 1PN+1 Mipec Rubik 360 giá tốt. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 2100000000.00, 21000000.00, NULL, 'hop_dong', NULL, NULL, NULL, 0, 1, 1, 'con_hang', 127, 5, 'Căn hộ 1PN+1 Mipec Rubik 360 giá tốt - Thành Công Land', 'Chi tiết Căn hộ 1PN+1 Mipec Rubik 360 giá tốt', NULL, '2026-02-24 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(6, 6, 3, NULL, 'Đất nền liền kề Thanh Hà 80m² sổ đỏ', 'dat-nen-lien-ke-thanh-ha-80m2-so-do-6', 'BDS-0006', 'dat_nen', 'ban', NULL, NULL, NULL, 'Bắc', NULL, 80.00, 0, 'co_ban', '<p>Đất nền liền kề Thanh Hà 80m² sổ đỏ. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 3200000000.00, 32000000.00, NULL, 'so_hong', NULL, NULL, NULL, 0, 1, 1, 'con_hang', 458, 6, 'Đất nền liền kề Thanh Hà 80m² sổ đỏ - Thành Công Land', 'Chi tiết Đất nền liền kề Thanh Hà 80m² sổ đỏ', NULL, '2026-03-10 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(7, 7, 1, NULL, 'Shophouse An Bình City tầng 1 kinh doanh1', 'shophouse-an-binh-city-tang-1-kinh-doanh1-7', 'BDS-0007', 'shophouse', 'ban', 'AB-SH-012', NULL, NULL, 'Nam', NULL, 95.00, 0, 'cao_cap', '<p>Shophouse An B&igrave;nh City tầng 1 kinh doanh. Vị tr&iacute; đẹp, ph&aacute;p l&yacute; r&otilde; r&agrave;ng, sổ hồng ch&iacute;nh chủ. Li&ecirc;n hệ ngay để được tư vấn miễn ph&iacute;.</p>', NULL, '[]', 6800000000.00, 68000000.00, NULL, 'hop_dong', NULL, NULL, NULL, 0, 1, 1, 'con_hang', 365, 7, 'Shophouse An Bình City tầng 1 kinh doanh - Thành Công Land', 'Chi tiết Shophouse An Bình City tầng 1 kinh doanh', NULL, '2026-03-25 17:00:00', '2026-04-04 11:39:22', '2026-04-07 01:06:11', NULL),
(8, 8, 2, NULL, 'Căn hộ 2PN An Bình City full nội thất', 'can-ho-2pn-an-binh-city-full-noi-that-8', 'BDS-0008', 'can_ho', 'ban', 'AB-CT1-1502', 'Tòa D', 'Tầng 18', 'Nam', NULL, 70.80, 2, 'co_ban', '<p>Căn hộ 2PN An Bình City full nội thất. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 2500000000.00, 25000000.00, NULL, 'so_hong', NULL, NULL, NULL, 0, 1, 1, 'con_hang', 95, 8, 'Căn hộ 2PN An Bình City full nội thất - Thành Công Land', 'Chi tiết Căn hộ 2PN An Bình City full nội thất', NULL, '2026-02-13 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(9, 9, 3, NULL, 'Nhà phố Cầu Giấy 5 tầng gần Keangnam', 'nha-pho-cau-giay-5-tang-gan-keangnam-9', 'BDS-0009', 'nha_pho', 'ban', NULL, NULL, NULL, 'Tây Bắc', NULL, 55.00, 5, 'full', '<p>Nhà phố Cầu Giấy 5 tầng gần Keangnam. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 12000000000.00, 120000000.00, NULL, 'so_do', NULL, NULL, NULL, 0, 1, 1, 'con_hang', 456, 9, 'Nhà phố Cầu Giấy 5 tầng gần Keangnam - Thành Công Land', 'Chi tiết Nhà phố Cầu Giấy 5 tầng gần Keangnam', NULL, '2026-02-22 11:39:22', '2026-04-04 11:39:22', '2026-04-04 14:31:10', NULL),
(10, 10, 1, NULL, 'Căn hộ 3PN Eurowindow Twin Parks', 'can-ho-3pn-eurowindow-twin-parks-10', 'BDS-0010', 'can_ho', 'ban', 'ET-T2-1801', 'Tòa B', 'Tầng 17', 'Đông Nam', NULL, 93.50, 3, 'cao_cap', '<p>Căn hộ 3PN Eurowindow Twin Parks. Vị trí đẹp, pháp lý rõ ràng, sổ hồng chính chủ. Liên hệ ngay để được tư vấn miễn phí.</p>', NULL, NULL, 3500000000.00, 35000000.00, NULL, 'so_hong', NULL, NULL, NULL, 0, 1, 1, 'con_hang', 298, 10, 'Căn hộ 3PN Eurowindow Twin Parks - Thành Công Land', 'Chi tiết Căn hộ 3PN Eurowindow Twin Parks', NULL, '2026-02-23 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(11, 1, 1, NULL, 'Cho thuê căn hộ 2PN Vinhomes Smart City đầy đủ nội thất', 'cho-thue-can-ho-2pn-vinhomes-smart-city-day-du-noi-that-th-1', 'BDS-TH-001', 'can_ho', 'thue', NULL, NULL, NULL, NULL, NULL, 65.50, 2, 'full', '<p>Cho thuê căn hộ 2PN Vinhomes Smart City đầy đủ nội thất. Đầy đủ tiện nghi, an ninh 24/7. Liên hệ ngay để xem nhà.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 12000000.00, 'vao_luon', '3_coc_1', 1, 1, 1, 'con_hang', 189, 11, 'Cho thuê căn hộ 2PN Vinhomes Smart City đầy đủ nội thất - Thành Công Land', 'Chi tiết Cho thuê căn hộ 2PN Vinhomes Smart City đầy đủ nội thất', NULL, '2026-03-06 11:39:22', '2026-04-04 11:39:22', '2026-04-06 06:28:16', NULL),
(12, 2, 2, NULL, 'Cho thuê căn hộ 1PN Mipec Rubik 360 tầng cao', 'cho-thue-can-ho-1pn-mipec-rubik-360-tang-cao-th-2', 'BDS-TH-002', 'can_ho', 'thue', NULL, NULL, NULL, NULL, NULL, 50.00, 1, 'full', '<p>Cho thuê căn hộ 1PN Mipec Rubik 360 tầng cao. Đầy đủ tiện nghi, an ninh 24/7. Liên hệ ngay để xem nhà.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 9000000.00, 'vao_luon', '3_coc_1', 1, 1, 1, 'con_hang', 316, 12, 'Cho thuê căn hộ 1PN Mipec Rubik 360 tầng cao - Thành Công Land', 'Chi tiết Cho thuê căn hộ 1PN Mipec Rubik 360 tầng cao', NULL, '2026-03-14 11:39:22', '2026-04-04 11:39:22', '2026-04-06 08:37:48', NULL),
(13, 3, 3, NULL, 'Cho thuê nhà phố Hà Đông 4 tầng làm văn phòng', 'cho-thue-nha-pho-ha-dong-4-tang-lam-van-phong-th-3', 'BDS-TH-003', 'nha_pho', 'thue', NULL, NULL, NULL, NULL, NULL, 80.00, 0, 'full', '<p>Cho thuê nhà phố Hà Đông 4 tầng làm văn phòng. Đầy đủ tiện nghi, an ninh 24/7. Liên hệ ngay để xem nhà.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 25000000.00, 'vao_luon', '3_coc_1', 0, 1, 1, 'con_hang', 125, 13, 'Cho thuê nhà phố Hà Đông 4 tầng làm văn phòng - Thành Công Land', 'Chi tiết Cho thuê nhà phố Hà Đông 4 tầng làm văn phòng', NULL, '2026-03-16 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(14, 4, 1, NULL, 'Cho thuê shophouse An Bình City kinh doanh', 'cho-thue-shophouse-an-binh-city-kinh-doanh-th-4', 'BDS-TH-004', 'shophouse', 'thue', NULL, NULL, NULL, NULL, NULL, 90.00, 0, 'full', '<p>Cho thuê shophouse An Bình City kinh doanh. Đầy đủ tiện nghi, an ninh 24/7. Liên hệ ngay để xem nhà.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 35000000.00, 'vao_luon', '3_coc_1', 0, 1, 1, 'con_hang', 65, 14, 'Cho thuê shophouse An Bình City kinh doanh - Thành Công Land', 'Chi tiết Cho thuê shophouse An Bình City kinh doanh', NULL, '2026-03-05 11:39:22', '2026-04-04 11:39:22', '2026-04-05 01:49:54', NULL),
(15, 5, 2, NULL, 'Cho thuê căn hộ 3PN Vinhomes Smart City cao cấp', 'cho-thue-can-ho-3pn-vinhomes-smart-city-cao-cap-15', 'BDS-TH-005', 'can_ho', 'thue', NULL, NULL, NULL, NULL, NULL, 89.00, 3, 'full', '<p>Cho thu&ecirc; căn hộ 3PN Vinhomes Smart City cao cấp. Đầy đủ tiện nghi, an ninh 24/7. Li&ecirc;n hệ ngay để xem nh&agrave;.</p>', NULL, '[]', NULL, NULL, NULL, NULL, 13000000.00, NULL, NULL, 0, 0, 1, 'con_hang', 147, 15, 'Cho thuê căn hộ 3PN Vinhomes Smart City cao cấp - Thành Công Land', 'Chi tiết Cho thuê căn hộ 3PN Vinhomes Smart City cao cấp', NULL, '2026-03-22 17:00:00', '2026-04-04 11:39:22', '2026-04-05 10:37:59', NULL),
(16, 6, 3, NULL, 'Cho thuê nhà nguyên căn Thanh Xuân 3 tầng', 'cho-thue-nha-nguyen-can-thanh-xuan-3-tang-th-6', 'BDS-TH-006', 'nha_pho', 'thue', NULL, NULL, NULL, NULL, NULL, 68.00, 3, 'full', '<p>Cho thuê nhà nguyên căn Thanh Xuân 3 tầng. Đầy đủ tiện nghi, an ninh 24/7. Liên hệ ngay để xem nhà.</p>', NULL, NULL, NULL, NULL, NULL, NULL, 20000000.00, 'vao_luon', '3_coc_1', 0, 1, 1, 'con_hang', 120, 16, 'Cho thuê nhà nguyên căn Thanh Xuân 3 tầng - Thành Công Land', 'Chi tiết Cho thuê nhà nguyên căn Thanh Xuân 3 tầng', NULL, '2026-02-20 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(17, 3, NULL, NULL, 'testfff222211111222a', 'testfff222211111222a-1775392294', 'BDS-PWSYTB', 'can_ho', 'thue', NULL, '111', NULL, NULL, NULL, 1.30, 0, NULL, '<p>111111</p>', NULL, '[]', NULL, NULL, NULL, NULL, 20000000.00, 'ngay_lap_tuc', 'thang_6', 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-04 17:00:00', '2026-04-05 05:31:34', '2026-04-05 05:32:25', '2026-04-05 05:32:25'),
(18, 3, NULL, NULL, 'testfff222211111222a', 'testfff222211111222a-1775393039', 'BDS-AF55PF', 'can_ho', 'thue', NULL, NULL, NULL, NULL, NULL, 1.30, 0, NULL, '<p>22222</p>', NULL, '[]', NULL, NULL, NULL, NULL, 22222.00, NULL, NULL, 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-04 17:00:00', '2026-04-05 05:43:59', '2026-04-05 05:48:49', '2026-04-05 05:48:49'),
(19, 3, NULL, NULL, 'test', 'test-1775393451', 'BDS-QNFHSD', 'can_ho', 'thue', NULL, NULL, NULL, NULL, NULL, 1.10, 0, NULL, '<p>ddd</p>', NULL, '[]', NULL, NULL, NULL, NULL, 29999.00, NULL, NULL, 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-04 17:00:00', '2026-04-05 05:50:51', '2026-04-05 06:24:21', '2026-04-05 06:24:21'),
(20, 3, NULL, NULL, 'test', 'test-1775395485', 'BDS-VWQKIH', 'can_ho', 'ban', NULL, NULL, NULL, NULL, NULL, 1.20, 0, NULL, '<p>sss</p>', NULL, '[]', 200000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-04 17:00:00', '2026-04-05 06:24:45', '2026-04-05 06:24:45', NULL),
(21, NULL, NULL, NULL, 'testfff222211111', 'testfff222211111-1775395653', 'BDS-M05F5C', 'can_ho', 'thue', NULL, NULL, NULL, NULL, NULL, 1.20, 0, NULL, '<p>111</p>', NULL, '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-04 17:00:00', '2026-04-05 06:27:33', '2026-04-05 06:27:33', NULL),
(22, NULL, NULL, NULL, 'testfff222211111', 'testfff222211111-1775395686', 'BDS-NTWYBC', 'nha_pho', 'ban', NULL, NULL, NULL, NULL, NULL, 1.20, 0, NULL, '<p>111</p>', NULL, '[]', 200000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-04 17:00:00', '2026-04-05 06:28:06', '2026-04-05 06:28:06', NULL),
(23, 15, 1, 2, 'Bán/Cho thuê can ho - Vùng lõi Vinhomes Smart City', 'bancho-thue-can-ho-vung-loi-vinhomes-smart-city-1775554969', 'KG-QRYRT8', 'can_ho', 'ban', NULL, NULL, NULL, NULL, NULL, 23.00, 0, NULL, 'Vị trí: Vùng lõi Vinhomes Smart City\r\nGhi chú:', NULL, NULL, 333000000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 'con_hang', 0, 0, NULL, NULL, NULL, '2026-04-07 02:42:49', '2026-04-07 02:42:49', '2026-04-07 02:42:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chu_nha`
--

CREATE TABLE `chu_nha` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cccd` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `nhan_vien_phu_trach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chu_nha`
--

INSERT INTO `chu_nha` (`id`, `ho_ten`, `so_dien_thoai`, `email`, `cccd`, `dia_chi`, `ghi_chu`, `nhan_vien_phu_trach_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nguyễn Thành Công', '11112311', NULL, NULL, NULL, NULL, NULL, '2026-04-06 23:33:56', '2026-04-06 23:33:56', NULL),
(2, 'hieu', '0336 132 012', 'hieutran170623@gmail.com', NULL, NULL, 'Khách hàng từ hệ thống Ký gửi', NULL, '2026-04-07 02:42:49', '2026-04-07 02:42:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dang_ky_nhan_tin`
--

CREATE TABLE `dang_ky_nhan_tin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `nhu_cau` varchar(50) DEFAULT NULL,
  `khu_vuc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `du_an_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED DEFAULT NULL,
  `so_phong_ngu` varchar(50) DEFAULT NULL,
  `muc_gia_tu` decimal(15,2) DEFAULT NULL,
  `muc_gia_den` decimal(15,2) DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dang_ky_nhan_tin`
--

INSERT INTO `dang_ky_nhan_tin` (`id`, `khach_hang_id`, `email`, `nhu_cau`, `khu_vuc_id`, `du_an_id`, `bat_dong_san_id`, `so_phong_ngu`, `muc_gia_tu`, `muc_gia_den`, `trang_thai`, `created_at`, `updated_at`) VALUES
(5, 13, 'hieutran170623@gmail.com', 'thue', 4, 18, 15, NULL, 111.00, 333333.00, 1, '2026-04-05 09:04:42', '2026-04-05 09:04:42'),
(7, NULL, 'hieutran170623@gmail.com', 'ban', NULL, NULL, NULL, NULL, 2.00, 7.00, 1, '2026-04-06 06:08:29', '2026-04-06 06:08:29');

-- --------------------------------------------------------

--
-- Table structure for table `du_an`
--

CREATE TABLE `du_an` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khu_vuc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten_du_an` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `chu_dau_tu` varchar(255) DEFAULT NULL,
  `don_vi_thi_cong` varchar(255) DEFAULT NULL,
  `mo_ta_ngan` text DEFAULT NULL,
  `noi_dung_chi_tiet` longtext DEFAULT NULL,
  `hinh_anh_dai_dien` varchar(255) DEFAULT NULL,
  `map_url` text DEFAULT NULL,
  `noi_bat` tinyint(1) NOT NULL DEFAULT 0,
  `hien_thi` tinyint(1) NOT NULL DEFAULT 1,
  `thu_tu_hien_thi` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'dang_mo_ban',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `thoi_diem_dang` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `du_an`
--

INSERT INTO `du_an` (`id`, `khu_vuc_id`, `ten_du_an`, `slug`, `dia_chi`, `chu_dau_tu`, `don_vi_thi_cong`, `mo_ta_ngan`, `noi_dung_chi_tiet`, `hinh_anh_dai_dien`, `map_url`, `noi_bat`, `hien_thi`, `thu_tu_hien_thi`, `trang_thai`, `seo_title`, `seo_description`, `seo_keywords`, `thoi_diem_dang`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'VINHOMES GARDENIA', 'vinhomes-gardenia-1', '18 Hàm Nghi', 'Vinhomes', 'Conteccons', 'Dự án VINHOMES GARDENIA - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<p>Vinhomes Gardenia l&agrave; tổ hợp căn hộ cao cấp, biệt thự, liền kề, shophouse, trường học nằm trong khu đ&ocirc; thị Mỹ Đ&igrave;nh 1, quận Nam Từ Li&ecirc;m, H&agrave; Nội. Lấy cảm hứng từ h&igrave;nh tượng b&ocirc;ng hoa nh&agrave;i t&acirc;y tinh khiết, Vinhomes Gardenia Mỹ Đ&igrave;nh mở ra một miền thi&ecirc;n nhi&ecirc;n xanh bao la, mang đến một cuộc sống an l&agrave;nh, ngập tr&agrave;n hạnh ph&uacute;c m&agrave; bạn vẫn hằng mong ước.</p>\r\n\r\n<p><strong>Chủ đầu tư:</strong>&nbsp;C&ocirc;ng ty Cổ Phần Đầu Tư V&agrave; Ph&aacute;t Triển Đ&ocirc; Thị S&agrave;i Đồng (Th&agrave;nh vi&ecirc;n của Vingroup)</p>\r\n\r\n<p><strong>Tổng diện t&iacute;ch đất dự &aacute;n:</strong>&nbsp;17.6 ha</p>\r\n\r\n<p><strong>Mật độ x&acirc;y dựng:&nbsp;</strong>31%</p>\r\n\r\n<p><strong>Quy m&ocirc; ph&aacute;t triển:</strong>&nbsp;3 t&ograve;a căn hộ (A1&nbsp; A2&nbsp; &nbsp;A3) &amp; 364 căn nh&agrave; ở thấp tầng, c&oacute; 2 tầng hầm th&ocirc;ng với nhau</p>\r\n\r\n<p>- 2 T&ograve;a A1 v&agrave; A2 c&oacute; thiết kế chữ U giống nhau, mật độ 19 căn/s&agrave;n, 8 thang m&aacute;y, 2 thang h&agrave;ng v&agrave; 2 thang bộ tho&aacute;t hiểm.<br />\r\n- T&ograve;a A3 thiết kế h&igrave;nh chữ I mật độ 21 căn/s&agrave;n, 12 thang m&aacute;y, 3 thang h&agrave;ng, 2 thang bộ tho&aacute;t hiểm.</p>\r\n\r\n<p>- Ph&iacute; dịch vụ 16.000.000/m2, &ocirc;t&ocirc; 1.450.000/xe/th&aacute;ng, xe m&aacute;y 60.000.000/xe/th&aacute;ng</p>\r\n\r\n<p><strong>Loại h&igrave;nh ph&aacute;t triển:</strong></p>\r\n\r\n<ul>\r\n	<li>Cao tầng (The Arcadia): 1PN, 2PN, 3PN, 4PN, Duplex</li>\r\n	<li>Thấp tầng (The Botanica): Biệt thự đơn lập, biệt thự song lập, liền kề, shophouse</li>\r\n</ul>\r\n\r\n<p><strong>Thời điểm khởi c&ocirc;ng:</strong>&nbsp;Năm 2015</p>\r\n\r\n<p><strong>Thời điểm b&agrave;n giao:</strong>&nbsp;Th&aacute;ng 11/2017</p>\r\n\r\n<p><strong>H&igrave;nh thức sở hữu:</strong>&nbsp;Sổ đỏ l&acirc;u d&agrave;i</p>\r\n\r\n<hr />\r\n<p>Vinhomes Gardenia nằm tr&ecirc;n mặt đường H&agrave;m Nghi, phường Cầu Diễn, quận Nam Từ Li&ecirc;m, H&agrave; Nội. Nằm tại cửa ng&otilde; s&ocirc;i động ph&iacute;a T&acirc;y thủ đ&ocirc;, xung quanh Vinhomes Mỹ Đ&igrave;nh l&agrave; h&agrave;ng loạt c&aacute;c khu đ&ocirc; thị, trung t&acirc;m h&agrave;nh ch&iacute;nh, trường học, bệnh viện lớn nhỏ, nổi bật như khu đ&ocirc; thị Mỹ Đ&igrave;nh 1, HD Mon City, Goldmark City, UBND, TAND Nam Từ Li&ecirc;m, trường quốc tế li&ecirc;n cấp Việt -&Uacute;c, trường cao đẳng Đại Việt&hellip; mang đến lợi thế trong việc tiếp cận hệ thống gi&aacute;o dục, y tế, giải tr&iacute; vv&hellip; tới cư d&acirc;n.</p>\r\n\r\n<p>B&ecirc;n cạnh đ&oacute;, khu đ&ocirc; thị c&ograve;n nằm gần c&aacute;c tuyến giao th&ocirc;ng huyết mạch L&ecirc; Đức Thọ, Phạm H&ugrave;ng, Hồ T&ugrave;ng Mậu, dễ d&agrave;ng kết nối tới trung t&acirc;m th&agrave;nh phố cũng như c&aacute;c khu vực ngoại th&agrave;nh H&agrave; Nội. Hiện nay, những dự &aacute;n vừa sở hữu quần thể xanh rộng mở, vừa nằm gần trung t&acirc;m, kết nối giao th&ocirc;ng thuận tiện như khu đ&ocirc; thị Vinhomes Gardenia Mỹ Đ&igrave;nh thực sự v&ocirc; c&ugrave;ng &iacute;t ỏi, n&ecirc;n dự &aacute;n đang nhận được sự quan t&acirc;m rất lớn ngay từ khi ra mắt.</p>\r\n\r\n<p><strong>MẶT BẰNG DỰ &Aacute;N</strong></p>\r\n\r\n<p><img alt=\"mat-bang-tong-the-vinhomes-gardenia\" src=\"https://vinhomesland.vn/wp-content/uploads/2019/06/mat-bang-tong-the-vinhomes-gardenia.jpg\" /></p>\r\n\r\n<p>&nbsp;</p>', 'du-an/2alELTrBe9jflvzCIasarUJNHhA4LSTgcOHLp1fV.png', 'https://maps.app.goo.gl/84rxy4Nktj3iijpPA', 1, 1, 1, 'dang_mo_ban', 'VINHOMES GARDENIA - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại VINHOMES GARDENIA', NULL, '2025-11-25 11:39:22', '2026-04-04 11:39:22', '2026-04-07 00:29:20', NULL),
(2, 4, 'Vinhomes Green Bay', 'vinhomes-green-bay-2', 'Số 7 Đại lộ Thăng Long, phường Mễ Trì, quận Nam Từ Liêm, Hà Nội', 'Vinhomes', 'HÒA BÌNH, CONTECCONS', 'Dự án Vinhomes Green Bay - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<p>Vinhomes Green Bay l&agrave; tổ hợp căn hộ cao cấp, biệt thự, liền kề, shophouse nằm tại khu đất rộng 31.8 ha tr&ecirc;n mặt đường đại lộ Thăng Long, đối diện với trung t&acirc;m hội nghị quốc gia. Sở hữu hồ điều h&ograve;a rộng tới 8 ha nằm gọn b&ecirc;n trong c&ugrave;ng v&ocirc; v&agrave;n c&aacute;c tiện &iacute;ch xanh độc đ&aacute;o, Vinhomes Green Bay Mễ Tr&igrave; được v&iacute; như &ldquo;<strong>Vịnh xanh trong l&ograve;ng phố</strong>&rdquo;, hứa hẹn sẽ mang đến nơi an cư l&yacute; tưởng cho người d&acirc;n thủ đ&ocirc; H&agrave; Nội.</p>\r\n\r\n<p><strong>Chủ đầu tư:</strong>&nbsp;C&ocirc;ng ty Cổ Phần Ph&aacute;t Triển Thể Thao V&agrave; Giải Tr&iacute; Mễ Tr&igrave; (Th&agrave;nh vi&ecirc;n của Vingroup)</p>\r\n\r\n<p><strong>Tổng diện t&iacute;ch đất dự &aacute;n:</strong>&nbsp;31.8 ha</p>\r\n\r\n<p><strong>Mật độ x&acirc;y dựng:</strong>19.3%</p>\r\n\r\n<p><strong>Quy m&ocirc; ph&aacute;t triển:</strong>&nbsp;Khu căn hộ chung cư bao gồm 3 t&ograve;a căn hộ G1, G2 v&agrave; G3 &amp; 398 căn nh&agrave; ở thấp tầng.</p>\r\n\r\n<p>- C&aacute;c t&ograve;a đều c&oacute; 3 tầng hầm th&ocirc;ng nhau. Hầm B1 gửi xe m&aacute;y, Hầm B2,B3 gửi oto<br />\r\n- T&ograve;a G1: 34 tầng,thiết kế theo h&igrave;nh chữ L,9 thang thường+3 thang h&agrave;ng, 22 căn 1 mặt s&agrave;n. Tầng 1+2 dịch vụ, từ 3-34 để ở, sổ hồng l&acirc;u d&agrave;i<br />\r\n- T&ograve;a G2: 38 tầng,thiết kế chữ L, 9 thang thường+3 thang h&agrave;ng, 20 căn/s&agrave;n. Sổ hồng l&acirc;u d&agrave;i<br />\r\n- T&ograve;a G3: 40 tầng, thiết kế chữ U. 15 thang thường+2 thang h&agrave;ng. Chia 2 dạng sổ: Tầng 3-34 sổ 50 năm chờ l&agrave;m sổ (ở v&agrave; VP). Tầng 35-40 sổ hồng l&acirc;u d&agrave;i.</p>\r\n\r\n<p><strong>Loại h&igrave;nh ph&aacute;t triển:</strong></p>\r\n\r\n<ul>\r\n	<li>Cao tầng: Studio, 1PN, 2PN, 3PN, 4PN</li>\r\n	<li>Thấp tầng: Biệt thự đơn lập, biệt thự song lập, liền kề, shophouse</li>\r\n</ul>\r\n\r\n<p><strong>Thời điểm khởi c&ocirc;ng:</strong>&nbsp;Năm 2016</p>\r\n\r\n<p><strong>Thời điểm b&agrave;n giao:</strong>&nbsp;Th&aacute;ng 3/2019</p>\r\n\r\n<p><strong>H&igrave;nh thức sở hữu:</strong>&nbsp;Sổ đỏ l&acirc;u d&agrave;i</p>\r\n\r\n<p>16.500.000/m2 (đ&atilde; bao gồm c&aacute;c dịch vụ)</p>\r\n\r\n<p>Ph&iacute; gửi xe m&aacute;y 100.000VND/xe/th&aacute;ng</p>\r\n\r\n<p>Ph&iacute; gửi &ocirc;t&ocirc; 1.450.000VND/Th&aacute;ng</p>\r\n\r\n<hr />\r\n<p>Vinhomes Green Bay nằm tại số 7 đại lộ Thăng Long, phường Mễ Tr&igrave;, quận Nam Từ Li&ecirc;m, H&agrave; Nội. Tọa lạc ngay tại cửa ng&otilde; s&ocirc;i động ph&iacute;a T&acirc;y thủ đ&ocirc;, Vinhomes Mễ Tr&igrave; nắm trong tay hạ tầng giao th&ocirc;ng ho&agrave;n hảo, kết nối với nhiều tuyến đường lớn như đại lộ Thăng Long, Lương Thế Vinh, Khuất Duy Tiến, Phạm H&ugrave;ng, Trần Duy Hưng&hellip; Từ Vinhomes Mễ Tr&igrave;, c&oacute; thể dễ d&agrave;ng di chuyển v&agrave;o trung t&acirc;m th&agrave;nh phố cũng như nhiều khu vực trọng điểm ph&iacute;a T&acirc;y H&agrave; Nội.</p>\r\n\r\n<p>B&ecirc;n cạnh đ&oacute;, với lợi thế đối diện trung t&acirc;m hội nghị quốc gia, khu đ&ocirc; thị Vinhomes Mễ Tr&igrave; thừa hưởng trọn vẹn cảnh quan tho&aacute;ng đạt, kh&iacute; hậu trong l&agrave;nh từ những khu vườn c&acirc;y, hồ nước, mang đến một cuộc sống tr&agrave;n đầy năng lượng tới những chủ sở hữu tương lai thời thượng. Dự &aacute;n l&agrave; lựa chọn l&yacute; tưởng cho một cuộc sống xanh năng động ngay giữa l&ograve;ng trung t&acirc;m ph&iacute;a T&acirc;y đang ng&agrave;y c&agrave;ng đổi mới v&agrave; ph&aacute;t triển.</p>\r\n\r\n<p><strong>MẶT BẰNG TỔNG THỂ DỰ &Aacute;N</strong></p>\r\n\r\n<p><img alt=\"mat-bang-tong-the-vinhomes-green-bay\" src=\"https://vinhomesland.vn/wp-content/uploads/2019/05/mat-bang-tong-the-vinhomes-green-bay.jpg\" /></p>\r\n\r\n<p><strong>MẶT BẰNG CHUNG CƯ&nbsp;</strong></p>\r\n\r\n<p><img alt=\"mat-bang-vinhomes-green-bay-the-residence\" src=\"https://vinhomesland.vn/wp-content/uploads/2019/05/mat-bang-vinhomes-green-bay-the-residence.jpg\" /></p>', 'du-an/Z3NHB6OFVhMPV6Au9RX53Rq7HC1kbZ8vdwhrLaQX.jpg', 'https://maps.app.goo.gl/C2tTk5EuEZoj4CsV8', 1, 0, 2, 'dang_mo_ban', 'Vinhomes Green Bay - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại Vinhomes Green Bay', NULL, '2026-02-07 11:39:22', '2026-04-04 11:39:22', '2026-04-07 00:29:25', NULL),
(3, 4, 'Mỹ Đình Pearl', 'my-dinh-pearl-3', 'Số 1 Châu Văn Liêm, phường mễ Trì, Quận Nam Từ Liêm, Hà Nội', 'SSG GROUP', 'FECON (móng), Phục Hưng Holdings (thân)', 'Dự án Mỹ Đình Pearl - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<p><strong>Chung cư Mỹ Đ&igrave;nh Pearl&nbsp;</strong>l&agrave; si&ecirc;u dự &aacute;n hot,<strong><em>&nbsp;</em></strong>tọa lạc tại vị tr&iacute; v&ocirc; c&ugrave;ng đắc địa tại số 1 Ch&acirc;u Văn Li&ecirc;m &ndash; Đại Lộ Thăng Long l&agrave; cửa ng&otilde; của khu đ&ocirc; thị mới đang ph&aacute;t triển nhất tại H&agrave; Nội.</p>\r\n\r\n<p>+ 3 ph&uacute;t để đến SVĐ Mỹ Đ&igrave;nh</p>\r\n\r\n<p>+ 3 ph&uacute;t để đến TT Hội Nghị Quốc Gia</p>\r\n\r\n<p>+ 2 ph&uacute;t để đến Keangnam</p>\r\n\r\n<p>+ 3 ph&uacute;t để đến si&ecirc;u thị Big C Thăng Long</p>\r\n\r\n<p><strong>Th&aacute;p căn hộ </strong>: 2 th&aacute;p cao 38 tầng, tổng số căn hộ 951 căn, 2 tầng hầm đổ xe th&ocirc;ng nhau.<br />\r\n<strong>T&ograve;a Pearl 1:</strong></p>\r\n\r\n<p>- 38 tầng, c&oacute; 6 thang thường + 1 thang h&agrave;ng.</p>\r\n\r\n<p>- Tầng 3~32: 15 căn/s&agrave;n.</p>\r\n\r\n<p>- Tầng 33+34: 2 căn Duplex, 12 căn/s&agrave;n.</p>\r\n\r\n<p>-Tầng 35+36: 2 căn Duplex, 10 căn/s&agrave;n.</p>\r\n\r\n<p>- Tầng 37+38: 4 căn Penhouse.</p>\r\n\r\n<p><strong>T&ograve;a Pearl 2:<br />\r\n-&nbsp;</strong>38 tầng, c&oacute; 6 thang thường + 1 thang h&agrave;ng.</p>\r\n\r\n<p>- Tầng 3~32: 14 căn/s&agrave;n. C&aacute;c tầng tr&ecirc;n tương tự Pearl 1.</p>\r\n\r\n<p><strong>C&aacute;c dạng căn hộ:&nbsp;</strong>1PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS, Duplex, Penhouse</p>\r\n\r\n<p><strong>Ph&iacute; dịch vụ: </strong>13.000VND/Th&aacute;ng, Ph&iacute; gửi xe m&aacute;y 100.000VND/xe/th&aacute;ng, &Ocirc;t&ocirc;&nbsp; 1.350.000/Th&aacute;ng</p>\r\n\r\n<hr />\r\n<p>Điểm nhấn lớn nhất về tiện &iacute;ch của dự &aacute;n ch&iacute;nh l&agrave; m&ocirc;i trường sống trong l&agrave;nh h&ograve;a m&igrave;nh c&ugrave;ng với thi&ecirc;n nhi&ecirc;n, t&aacute;ch biệt với kh&ocirc;ng gian ồn &agrave;o của đ&ocirc; thị xung quanh. Với phần lớn diện t&iacute;ch d&agrave;nh cho c&acirc;y xanh, cảnh quan thi&ecirc;n nhi&ecirc;n, đặc biệt l&agrave; dự &aacute;n nằm ngay cạnh c&ocirc;ng vi&ecirc;n Mễ Tr&igrave; rộng lớn, l&agrave; nơi tuyệt vời để thư gi&atilde;n sau ng&agrave;y l&agrave;m việc căng thẳng.</p>\r\n\r\n<p>Bể bơi ngo&agrave;i trời tại<em>&nbsp;</em>Mỹ Đ&igrave;nh Pearl&nbsp;rộng gần 700m2,được thiết kế bể tr&agrave;n, như một vi&ecirc;n&nbsp;ngọc xanh lam đ&iacute;nh tr&ecirc;n m&agrave;u xanh của thi&ecirc;n nhi&ecirc;n&nbsp;tại dự &aacute;n. Được x&acirc;y dựng v&agrave; thiết kế sang trọng với&nbsp;cảnh quan v&ocirc; c&ugrave;ng hấp dẫn xung quanh, đ&acirc;y l&agrave;&nbsp;kh&ocirc;ng gian l&yacute; tưởng cho cư d&acirc;n đắm m&igrave;nh trong l&agrave;n&nbsp;nước trong vắt để thoải m&aacute;i vận động v&agrave; thư gi&atilde;n.</p>\r\n\r\n<p><strong>TỔNG QUAN VỀ DỰ &Aacute;N</strong></p>\r\n\r\n<p><img alt=\"vi-tri-du-an-my-dinh-pearl\" src=\"https://mydinhpearl.net.vn/wp-content/uploads/2016/08/vi-tri-du-an-my-dinh-pearl-1024x519.jpg\" /></p>\r\n\r\n<p><strong>MẶT BẰNG TH&Aacute;P P1</strong></p>\r\n\r\n<p><img alt=\"Mặt bằng Mỹ Đình Pearl\" src=\"https://chungcumydinhpearl.com.vn/wp-content/uploads/2025/07/my-Dinh-pearl-5_optimized.jpg\" /></p>\r\n\r\n<p><strong>MẶT BẰNG TH&Aacute;P P2</strong></p>\r\n\r\n<p><img alt=\"Mặt bằng Mỹ Đình Pearl\" src=\"https://chungcumydinhpearl.com.vn/wp-content/uploads/2025/07/my-Dinh-pearl-6_optimized.jpg\" /></p>', 'du-an/maOgJ3atznxWSGW3lcetVYGScZhl3SFJhKCzoQcL.webp', 'https://maps.app.goo.gl/F1MuFgtfZUGW5nRh7', 0, 1, 3, 'dang_mo_ban', 'M', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại Mỹ Đình Pearl', NULL, '2026-01-18 11:39:22', '2026-04-04 11:39:22', '2026-04-04 14:14:08', NULL),
(4, 4, 'The Matrix One', 'the-matrix-one-4', 'Đường Lê Quang Đạo, phường Mễ Trì, quận Nam Từ Liêm, Thành phố Hà Nội.', 'Công ty TNHH Tập đoàn MIK Group Việt Nam', 'Tổng công ty xây dựng Conteccons', 'Dự án The Matrix One - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<p>Tọa lạc tại vị tr&iacute; v&agrave;ng đắc địa, nơi c&oacute; mật độ d&acirc;n số cao c&ugrave;ng với h&agrave;ng loạt c&ocirc;ng tr&igrave;nh kiến tr&uacute;c, c&ocirc;ng tr&igrave;nh giao th&ocirc;ng trọng điểm, The Matrix One lại được thừa hưởng trọn vẹn kh&ocirc;ng gian của 14ha c&ocirc;ng vi&ecirc;n c&acirc;y xanh l&agrave; một điều v&ocirc; c&ugrave;ng qu&yacute; gi&aacute;. Cư d&acirc;n The Matrix One Premium sẽ trực tiếp sử dụng những tiện &iacute;ch thuộc c&ocirc;ng vi&ecirc;n n&agrave;y ngay khi nhận nh&agrave;, bởi chủ đầu tư MIK đ&atilde; đ&uacute;ng hẹn khi ho&agrave;n th&agrave;nh c&ocirc;ng vi&ecirc;n c&ugrave;ng thời điểm với giai đoạn 1 của dự &aacute;n.</p>\r\n\r\n<p>Tạo dựng cuộc sống tốt đẹp xuất ph&aacute;t từ c&aacute;i t&acirc;m. X&acirc;y dựng được m&ocirc;i trường sống tốt đẹp l&agrave; bởi c&aacute;i tầm. Duy tr&igrave;, ph&aacute;t triển được cộng đồng cư d&acirc;n văn minh l&agrave; cả một h&agrave;nh tr&igrave;nh d&agrave;i. V&agrave; tr&ecirc;n con đường đ&oacute; đội ngũ ch&uacute;ng t&ocirc;i, tập đo&agrave;n MIK Group lu&ocirc;n lu&ocirc;n tạo ra những sản phẩm mới, những gi&aacute; trị mới gửi đến hơn 100 triệu cư d&acirc;n tr&ecirc;n l&atilde;nh thổ Việt Nam. Dự &aacute;n chung cư&nbsp;<strong><a href=\"https://thematrixones.com.vn/\">The Matrix One</a>&nbsp;</strong><strong>Premium</strong>&nbsp;&ndash;&nbsp;<strong>Time Luxe Living</strong>&nbsp;sẽ l&agrave; sự kh&aacute;c biệt, kh&aacute;c biệt ho&agrave;n to&agrave;n so với phần c&ograve;n lại. Kh&ocirc;ng kh&iacute; tươi, nguồn nước sạch, c&ocirc;ng vi&ecirc;n v&agrave; hồ cảnh quan rộng lớn, tầm nh&igrave;n trọn S&acirc;n vận động Mỹ Đ&igrave;nh &ndash; View ngắm ph&aacute;o hoa đẳng cấp nhất phia T&acirc;y H&agrave; Nội l&agrave; lời khẳng định &ldquo;vượt l&ecirc;n tất cả&rdquo; về chất sống. Tất cả t&acirc;m huyết của chủ đầu tư được hội tụ lại để mang đến một&nbsp;<strong>&ldquo;The Matrix One&rdquo;:&nbsp;</strong>d&agrave;nh ri&ecirc;ng cho những chủ nh&acirc;n danh gi&aacute;, th&agrave;nh đạt, d&aacute;m nghĩ d&aacute;m l&agrave;m, ph&aacute; vỡ mọi giới hạn để vươn tới đỉnh cao của th&agrave;nh c&ocirc;ng.</p>\r\n\r\n<p>Dự &aacute;n bao gồm 2 t&ograve;a th&aacute;p cao 44 tầng, 3 tầng hầm, 740 căn hộ, 20 shophouse, 2 Penhouse</p>\r\n\r\n<p>- Trung T&acirc;m Thương Mại, c&ocirc;ng vi&ecirc;n, hồ cảnh quan, nh&agrave; h&aacute;t quy m&ocirc; lớn v&agrave; c&aacute;c tiện &iacute;ch nội khu đẳng cấp</p>\r\n\r\n<p>- Khởi c&ocirc;ng x&acirc;y dựng:&nbsp;<strong>Qu&yacute; I/2019</strong></p>\r\n\r\n<p>- B&agrave;n Giao Căn Hộ:&nbsp;<strong>Qu&yacute; III/2022</strong></p>\r\n\r\n<p>- Tầng 4 bể bơi, tầng 23 tiện &iacute;ch :Gym,Yoga,,thư viện...., tầng 43 vườn nhật</p>\r\n\r\n<p>- T&ograve;a A :10 căn 1 s&agrave;n, 6 thang thường, 1 thang h&agrave;ng.</p>\r\n\r\n<p>- T&ograve;a B: 10 căn 1 s&agrave;n, 6 thang thường, 1 thang h&agrave;ng.</p>\r\n\r\n<p>- Hiện tại đang miễn ph&iacute; dịch vụ t&ugrave;y thời điểm mua.&nbsp;</p>\r\n\r\n<p><strong>Ph&iacute; dịch vụ: </strong>Xe m&aacute;y 50.000/xe/th&aacute;ng. &Ocirc;t&ocirc; 1.450.000/xe/th&aacute;ng</p>\r\n\r\n<p><strong>TỔNG QUAN DỰ &Aacute;N</strong></p>\r\n\r\n<p><img alt=\"vị trí the matrix one \" src=\"https://thematrixones.com.vn/wp-content/uploads/2025/05/vi-tri-tmo-pre.jpg\" /></p>\r\n\r\n<p><strong>MẶT BẰNG DỰ &Aacute;N</strong></p>\r\n\r\n<p><img alt=\"mặt bằng tổng thế the matrix one\" src=\"https://thematrixones.com.vn/wp-content/uploads/2025/05/Mat-bang-TMOP-tong-the.png\" /></p>\r\n\r\n<p><strong>TIỆN &Iacute;CH NỘI KHU</strong></p>\r\n\r\n<p>Chung cư&nbsp;<strong>The Matrix One</strong>&nbsp;sẽ trở th&agrave;nh dự &aacute;n &ldquo;căn hộ si&ecirc;u sang&rdquo; bởi gi&aacute; trị m&agrave; n&oacute; mang lại vượt sự k&igrave; vọng của bất k&igrave; một dự &aacute;n bất động sản n&agrave;o kh&aacute;c tại Việt Nam. Cung cấp 41 tiện &iacute;ch nội khu, mỗi tiện &iacute;ch mang lại trải nghiệm kh&aacute;c nhau, nhưng dễ d&agrave;ng t&igrave;m thấy một điểm chung l&agrave; sự thương lưu, đẳng cấp trong mỗi lần sử dụng dịch vụ. Cuộc sống ph&aacute;t triển khiến nhu cầu sống trở n&ecirc;n rất cao. Kh&aacute;ch h&agrave;ng sẵn s&agrave;ng chi tiền để sử dụng những dịch vụ sang chảnh, tuy nhi&ecirc;n họ cần được đ&aacute;p lại bằng sự h&agrave;i l&ograve;ng v&agrave; xứng đ&aacute;ng với số tiền họ bỏ ra. V&agrave; với chung cư The Matrix One, sẽ chẳng c&oacute; sự thất vọng n&agrave;o cả, hoặc nếu c&oacute; cũng chỉ l&agrave; do thượng đế chỉ ban cho con người 24 giờ mỗi ng&agrave;y, c&ograve;n kh&aacute;ch h&agrave;ng th&igrave; muốn nhiều hơn để trải nghiệm cuộc sống tại đ&acirc;y m&agrave; th&ocirc;i. Vậy n&ecirc;n, h&atilde;y d&agrave;nh nhiều thời gian hơn cho bản th&acirc;n, cho gia đ&igrave;nh, cho những người th&acirc;n y&ecirc;u nhất bằng c&aacute;ch nhanh nhất l&agrave; lựa chọn căn hộ sinh sống tại chung cư&nbsp;<strong><a href=\"https://thematrixones.com.vn/\">The Matrix One</a></strong>&nbsp;Mễ Tr&igrave;!</p>\r\n\r\n<p><img alt=\"tiện ích the matrix one premium\" src=\"https://thematrixones.com.vn/wp-content/uploads/2025/05/tien-ich-TMO-Pre.jpg\" /></p>', 'du-an/azhTj5pDAGIsI8sMtnGo7HqyzVEeFgmkuhexZ4mq.jpg', NULL, 1, 1, 4, 'dang_mo_ban', 'The Matrix One - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại The Matrix One', NULL, '2026-03-25 11:39:22', '2026-04-04 11:39:22', '2026-04-04 14:29:08', NULL),
(5, 4, 'VINHOMES SKYLAKE', 'vinhomes-skylake-5', 'Ngã tư Phạm Hùng - Dương Đình Nghệ, Nam Từ Liêm, HN', 'Vinhomes', 'Conteccons', 'Dự án VINHOMES SKYLAKE - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>- Thừa hưởng trọn vẹn không gian và tiện ích của công viên hồ điều hòa Cầu Giấy 19 ha.<br />\n- Gồm 3 tòa S1, S2 và S3 và 3 tầng hầm. TTTM Vincom và bể bơi ngoài trời giữa hai tòa S1, S2. Tòa S3 có bể bơi bốn mùa ở tầng T.<br />\n- Căn hộ chung cư tòa S1 từ tầng 4 ~ 36, tòa S2 và S3 là từ tầng 4 ~ 39. Có ban công, điều hòa hành lang.<br />\n- Diện tích: 1PN (51~54m2), 2PN (65~75m2), 3PN (99~109m2), 4PN (130~142m2).<br />\n- Có Vinschool mầm non.<br />\n- Thang máy đi lại dễ dàng, không cần thẻ</p><h3>Phí dịch vụ</h3><p>16k/m2 (đã bao gồm các tiện ích). Ô tô: 1450 k, Xe máy: 60k</p><h3>Các dạng căn hộ</h3><p><strong>1PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS, Penhouse</strong></p>', NULL, NULL, 1, 1, 5, 'dang_mo_ban', 'VINHOMES SKYLAKE - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại VINHOMES SKYLAKE', NULL, '2026-02-08 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(6, 4, 'VINHOMES WEST POINT', 'vinhomes-west-point-6', 'Ngã 3 Phạm Hùng - Đỗ Đức Dục, Mễ Trì, Nam Từ Liêm, HN', 'Vinhomes', 'Coteccons', 'Dự án VINHOMES WEST POINT - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>- Tổ hợp căn hộ cao cấp, officetel &amp; trung tâm thương mại. Vị trí giao thoa giữa 3 quận Cầu Giấy – Thanh Xuân – Nam Từ Liêm.<br />\n- Chung cư: Tòa West 2 (tầng 5 up) &amp; West 3 (sổ đỏ lâu dài)<br />\n- Officetel: Tòa West 1 và tầng 2 ~ 5A Tòa West 2 (HĐMB 50 năm)<br />\n- Tiên ích bể bơi ngoài trời, GYM, Vinmart, Vinschool.<br />\n- Dự án gồm 3 tòa: W1 (tầng 6~37), W2 (Tầng 5~37), W3 (Tầng 2~35).<br />\n- 3 hầm thông nhau.</p><h3>Phí dịch vụ</h3><p>17k/m2 (đã bao gồm các tiện ích). Ô tô: 1450 k, Xe máy: 60k</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS</strong></p>', NULL, NULL, 1, 1, 6, 'dang_mo_ban', 'VINHOMES WEST POINT - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại VINHOMES WEST POINT', NULL, '2026-02-03 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(7, 4, 'GOLDEN PALACE', 'golden-palace-7', 'Số 99, Đ. Mễ Trì, Nam Từ Liêm, HN', 'Đang cập nhật', 'Delta (phần hầm)', 'Dự án GOLDEN PALACE - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>Dự án gồm 3 tòa A, B, C cao 30 tầng, 4 tầng hầm. Tầng thứ 31 là 20 căn Penthouse<br />\nKhối đế 4 tầng từ T1 ~ 4, Căn hộ 26 tầng từ T5 ~ T30.<br />\n- 4 tầng hầm. Hầm B1 là TTTM và rạp chiếu phim. 3 hầm để xe.<br />\n- Số slot oto/ số căn hộ là 1,2. Dư giả slot oto.<br />\n- Tòa A: 14 căn/sàn, Tòa B: 15 căn/sàn, Tòa C: 13 căn/sàn. Tầng 13 thực tế đánh số 12A.</p><h3>Phí dịch vụ</h3><p>6k/m2. Xe máy: 60k, oto: 1 tr</p><h3>Các dạng căn hộ</h3><p><strong>2PN-2VS, 3PN-2VS, 4PN-2VS</strong></p>', NULL, NULL, 0, 1, 7, 'dang_mo_ban', 'GOLDEN PALACE - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại GOLDEN PALACE', NULL, '2025-11-30 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(8, 3, 'D\'CAPITAL', 'dcapital-8', '119 Trần Duy Hưng, Phường Trung Hòa, quận Cầu Giấy, Hà Nội', 'Tập đoàn Tân Hoàng Minh Group', 'Coteccons', 'Dự án D\'CAPITAL - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>Khu phức hợp 6 tòa tháp C1, C2, C3, C5, C6, C7 cao từ 39 đến 45 tầng với mô hình căn hộ cao cấp – văn phòng officetel – shophouse.<br />\n+ 1 phòng ngủ: 55 m2<br />\n+ 2 phòng ngủ: 72 m2 – 74 m2 – 78 m2<br />\n+ 3 phòng ngủ: 90m2 – 93m2 – 97m2</p><h3>Phí dịch vụ</h3><p>19,500đ/m2, Xe ô tô: 1,200,000 - 1,500,000đ/xe/tháng , Xe máy : 100k/tháng</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 2PN-1VS, 2PN-2VS, 3PN-2VS</strong></p>', NULL, NULL, 1, 1, 8, 'dang_mo_ban', 'D\'CAPITAL - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại D\'CAPITAL', NULL, '2026-03-13 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(9, 2, 'SAPPHIRE 1', 'sapphire-1-9', 'Đại lộ Thăng Long và tuyến đường sắt đô thị số 6 (Smart City)', 'Vinhomes', 'Coteccons', 'Dự án SAPPHIRE 1 - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>5 Tòa: S101, S102, S105, S106 cao 34 tầng và tòa S103 cao 35 tầng. 1 tầng hầm.<br />\n- Tòa S103: 30 căn/sàn, 8 thang máy. 4 tòa còn lại: 19 căn/sàn, 5 thang máy.<br />\n- Diện tích từ 25m2 đến 75m2.<br />\n- Tiêu chuẩn bàn giao cơ bản: sàn gạch, KHÔNG tủ bếp, KHÔNG tủ quần áo<br />\n- Bàn giao Quý II/2020</p><h3>Phí dịch vụ</h3><p>8,8k/m2</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 9, 'dang_mo_ban', 'SAPPHIRE 1 - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại SAPPHIRE 1', NULL, '2025-12-06 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(10, 2, 'SAPPHIRE 2', 'sapphire-2-10', 'Nằm giữa phân khu The Sapphire 1 và the metrolines (Smart City)', 'Vinhomes', 'Coteccons', 'Dự án SAPPHIRE 2 - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>4 Tòa: S201 cao 30 tầng, S202, S203 cao 34 tầng và tòa S205 cao 29 tầng. 1 tầng hầm.<br />\n- S203, S205: 19 căn/sàn, 5 thang máy.<br />\n- S201, S202: 30 căn/sàn.<br />\n- Tiêu chuẩn bàn giao cơ bản.<br />\n- Bàn giao Quý II/2020</p><h3>Phí dịch vụ</h3><p>8,8k/m2</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 10, 'dang_mo_ban', 'SAPPHIRE 2 - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại SAPPHIRE 2', NULL, '2026-03-13 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(11, 2, 'SAPPHIRE 3', 'sapphire-3-11', 'Cạnh Vinschool và The Diamond (Smart City)', 'Vinhomes', 'Coteccons', 'Dự án SAPPHIRE 3 - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>3 Tòa: S301, S302, S303 cao 38 tầng. 1 tầng hầm.<br />\n- Mật độ 19 căn/sàn, 5 thang máy.<br />\n- Diện tích: 25 - 80m2.<br />\n- Bàn giao Tháng 3/2021</p><h3>Phí dịch vụ</h3><p>8,8k/m2</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 11, 'dang_mo_ban', 'SAPPHIRE 3 - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại SAPPHIRE 3', NULL, '2025-12-10 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(12, 2, 'SAPPHIRE 4', 'sapphire-4-12', 'Trục đường giao thông chính kết nối đại lộ Thăng Long (Smart City)', 'Vinhomes', 'Coteccons', 'Dự án SAPPHIRE 4 - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>3 Tòa: S401, S402, S403 cao 35 tầng. 1 tầng hầm. Đối diện 2 công viên Sportia Park và Central Park.<br />\n- S401: 30 căn/sàn. S402, S403: 22 căn/sàn.<br />\n- Bàn giao Tháng 12/2021</p><h3>Phí dịch vụ</h3><p>8,8k/m2</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 12, 'dang_mo_ban', 'SAPPHIRE 4 - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại SAPPHIRE 4', NULL, '2026-03-06 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(13, 2, 'THE MIAMI', 'the-miami-13', 'Chân cầu vượt số 2, ngõ phía Tây Smart City', 'Công ty Cổ phần Đầu tư Xây dựng Thái Sơn (công ty con của Vinhomes)', 'Coteccons', 'Dự án THE MIAMI - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>5 Tòa: GS1, GS3 (39 tầng) và GS2, GS5, GS6 (38 tầng). 1 tầng hầm.<br />\n- GS2: 19 căn/sàn. GS1, GS3: 30 căn/sàn. GS5, GS6: 16 căn/sàn.<br />\n- Tiêu chuẩn bàn giao: Sapphire + cơ bản.<br />\n- Bàn giao đa dạng từ Quý 3/2022 đến Quý 1/2026.</p><h3>Phí dịch vụ</h3><p>Chưa cập nhật</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 13, 'dang_mo_ban', 'THE MIAMI - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại THE MIAMI', NULL, '2025-11-29 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(14, 2, 'THE SAKURA', 'the-sakura-14', 'Trung tâm The Metroline, Smart City', 'Samty Việt Nam', 'Coteccons', 'Dự án THE SAKURA - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>4 Tòa: SA1 (37 tầng), SA2 (38 tầng), SA3 và SA5 (39 tầng). Tầng 39 có vườn nhật. 1 tầng hầm.<br />\n- SA1, SA2: 19 căn/sàn. SA3, SA5: 30 căn/sàn.<br />\n- Bàn giao: 2022 - 2025. Tiêu chuẩn: sàn gỗ, khóa vân tay, điều hòa nổi.</p><h3>Phí dịch vụ</h3><p>17k/m2 (đã bao gồm tiện ích nội khu)</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 14, 'dang_mo_ban', 'THE SAKURA - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại THE SAKURA', NULL, '2026-03-15 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(15, 2, 'TONKIN', 'tonkin-15', 'Vùng lõi KĐT Smart City', 'Vinhomes', 'Coteccons', 'Dự án TONKIN - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>2 Tòa: TK1 và TK2 cao 38 tầng. 1 tầng hầm. Mật độ 16 căn/sàn.<br />\n- Tiêu chuẩn Ruby liền tường: sàn gỗ, điều hòa âm trần, tủ áo, thiết bị bếp + vệ sinh.<br />\n- Bàn giao 2022 - 2023.</p><h3>Phí dịch vụ</h3><p>18k/m2 (đã bao gồm tiện ích nội khu)</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 15, 'dang_mo_ban', 'TONKIN - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại TONKIN', NULL, '2026-02-25 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(16, 2, 'CANOPY', 'canopy-16', 'Vùng lõi KĐT Smart City', 'Newlife (Đại diện GIC)', 'Coteccons', 'Dự án CANOPY - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>3 Tòa: TC1, TC2, TC3 cao 38 tầng. 2 tầng hầm. Mật độ 16 căn/sàn.<br />\n- Tiêu chuẩn Ruby liền tường. Bàn giao Tháng 7/2025.</p><h3>Phí dịch vụ</h3><p>Chưa cập nhật</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 16, 'dang_mo_ban', 'CANOPY - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại CANOPY', NULL, '2025-11-29 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(17, 2, 'IMPERIA', 'imperia-17', 'Khu đô thị Vinhomes Smart City', 'Công ty cổ phần HBI (thành viên MIK GROUP)', 'Coteccons', 'Dự án IMPERIA - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự &aacute;n</h3>\r\n\r\n<p>5 T&ograve;a: I1 (39 tầng), I2, I3, I4, I5 (38 tầng). 1 tầng hầm.<br />\r\nTiếp gi&aacute;p Sportia Park &amp; Central Park. Ti&ecirc;u chuẩn liền tường: s&agrave;n gỗ, điều h&ograve;a nổi (I1, I2 c&oacute; bếp). B&agrave;n giao 2023-2024.</p>\r\n\r\n<h3>Ph&iacute; dịch vụ</h3>\r\n\r\n<p>Chưa cập nhật</p>\r\n\r\n<h3>C&aacute;c dạng căn hộ</h3>\r\n\r\n<p><strong>STUDIO, 1PN+1-2VS, 2PN+1-1VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 1, 'dang_mo_ban', 'IMPERIA - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại IMPERIA', NULL, '2026-03-05 11:39:22', '2026-04-04 11:39:22', '2026-04-07 00:44:15', NULL),
(18, 2, 'THE SOLA PARK', 'the-sola-park-18', 'Đại lộ Ánh Sáng, Vinhomes Smart City', 'Công ty cổ phần HBI (thành viên MIK GROUP)', 'Coteccons', 'Dự án THE SOLA PARK - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>5 Tòa: G1, G2, G3 (35 tầng, 1 hầm), G5 (2 hầm), G6 (1 hầm) cao 39 tầng. Tầng 2-14 khối G5,G6 là văn phòng.<br />\nBàn giao Quý 1-3/2027. Tiêu chuẩn: sàn gỗ, thiết bị bếp + vệ sinh.</p><h3>Phí dịch vụ</h3><p>Chưa cập nhật</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 0, 1, 18, 'dang_mo_ban', 'THE SOLA PARK - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại THE SOLA PARK', NULL, '2026-03-08 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(19, 2, 'MASTERI WEST HEIGHTS', 'masteri-west-heights-19', 'Trục đường chính, đối diện Central Park (Smart City)', 'Masterise Homes', 'Coteccons', 'Dự án MASTERI WEST HEIGHTS - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>4 Tòa: A, B (39 tầng), C, D (38 tầng). Tầng mái có vườn thượng uyển. 2 tầng hầm.<br />\n- A, B: 30 căn/sàn. C, D: 19 căn/sàn.<br />\n- Bàn giao 2023-2024. Tiêu chuẩn Ruby cao cấp liền tường.</p><h3>Phí dịch vụ</h3><p>24k/m2</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS</strong></p>', NULL, NULL, 1, 1, 19, 'dang_mo_ban', 'MASTERI WEST HEIGHTS - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại MASTERI WEST HEIGHTS', NULL, '2025-11-02 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(20, 2, 'LUMIERE EVERGREEN', 'lumiere-evergreen-20', 'Đại lộ Ánh Sáng, KĐT Vinhomes Smart City', 'Masterise Homes', 'Coteccons', 'Dự án LUMIERE EVERGREEN - Cơ sở hạ tầng hiện đại, vị trí đắc địa.', '<h3>Tổng quan dự án</h3><p>3 Tòa: A1, A2, A3 cao 39 tầng. 1 tầng hầm.<br />\n- A1, A2: 18 căn/sàn. A3: 26 căn/sàn.<br />\n- Bàn giao Tháng 5/2026. Tiêu chuẩn Ruby liền tường cao cấp.</p><h3>Phí dịch vụ</h3><p>Chưa cập nhật</p><h3>Các dạng căn hộ</h3><p><strong>STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS, 4PN</strong></p>', NULL, NULL, 1, 1, 20, 'dang_mo_ban', 'LUMIERE EVERGREEN - Đánh giá chi tiết', 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại LUMIERE EVERGREEN', NULL, '2026-01-25 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ghi_chu_khach_hang`
--

CREATE TABLE `ghi_chu_khach_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED NOT NULL,
  `nhan_vien_id` bigint(20) UNSIGNED NOT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `noi_dung` text NOT NULL,
  `kenh_tuong_tac` varchar(255) DEFAULT NULL,
  `ket_qua` varchar(255) DEFAULT NULL,
  `nhac_lai_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khach_hang`
--

CREATE TABLE `khach_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `token_expiry` timestamp NULL DEFAULT NULL,
  `nguon_khach_hang` varchar(255) NOT NULL DEFAULT 'website',
  `muc_do_tiem_nang` varchar(255) NOT NULL DEFAULT 'am',
  `nhan_vien_phu_trach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ghi_chu_noi_bo` text DEFAULT NULL,
  `kich_hoat` tinyint(1) NOT NULL DEFAULT 1,
  `sdt_xac_thuc_at` timestamp NULL DEFAULT NULL,
  `email_xac_thuc_at` timestamp NULL DEFAULT NULL,
  `lien_he_cuoi_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khach_hang`
--

INSERT INTO `khach_hang` (`id`, `ho_ten`, `so_dien_thoai`, `email`, `password`, `verification_token`, `token_expiry`, `nguon_khach_hang`, `muc_do_tiem_nang`, `nhan_vien_phu_trach_id`, `ghi_chu_noi_bo`, `kich_hoat`, `sdt_xac_thuc_at`, `email_xac_thuc_at`, `lien_he_cuoi_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nguyễn Văn An', '0971111111', 'an.nv@gmail.com', NULL, NULL, NULL, 'website', 'nong', 4, NULL, 1, NULL, NULL, '2026-03-16 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(2, 'Trần Thị Bình', '0972222222', 'binh.tt@gmail.com', NULL, NULL, NULL, 'chat', 'nong', 5, NULL, 1, NULL, NULL, '2026-03-11 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(3, 'Lê Minh Cường', '0973333333', 'cuong.lm@gmail.com', NULL, NULL, NULL, 'lien_he', 'am', 6, NULL, 1, NULL, NULL, '2026-03-13 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(4, 'Phạm Thu Hà', '0974444444', 'ha.pt@gmail.com', NULL, NULL, NULL, 'sale', 'am', 4, NULL, 1, NULL, NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(5, 'Hoàng Văn Đức', '0975555555', 'duc.hv@gmail.com', NULL, NULL, NULL, 'website', 'lanh', 5, NULL, 1, NULL, NULL, '2026-03-07 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(6, 'Vũ Thị Hoa', '0976666666', 'hoa.vt@gmail.com', NULL, NULL, NULL, 'chat', 'nong', 6, NULL, 1, NULL, NULL, '2026-03-05 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(7, 'Đặng Quốc Hùng', '0977777777', 'hung.dq@gmail.com', NULL, NULL, NULL, 'lien_he', 'am', 4, NULL, 1, NULL, NULL, '2026-03-07 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(8, 'Ngô Thị Lan', '0978888888', 'lan.nt@gmail.com', NULL, NULL, NULL, 'sale', 'lanh', 5, NULL, 1, NULL, NULL, '2026-03-20 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(9, 'Bùi Văn Long', '0979999999', 'long.bv@gmail.com', NULL, NULL, NULL, 'website', 'nong', 6, NULL, 1, NULL, NULL, '2026-03-13 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(10, 'Đinh Thị Mai', '0981111111', 'mai.dt@gmail.com', NULL, NULL, NULL, 'chat', 'am', 4, NULL, 1, NULL, NULL, '2026-03-19 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(11, 'Hà Văn Nam', '0982222222', NULL, NULL, NULL, NULL, 'lien_he', 'lanh', 5, NULL, 1, NULL, NULL, '2026-04-01 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(12, 'Kiều Thị Oanh', '0983333333', NULL, NULL, NULL, NULL, 'sale', 'am', 6, NULL, 1, NULL, NULL, '2026-03-27 11:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(13, 'hieu', '0336 132 012', 'hieutran170623@gmail.com', '$2y$12$Ysm.uH4X8PhwOFPrpFgdJOsIiCko8itM7DaLM0pXUCaVLhrx5KO8O', '486661', '2026-04-05 10:45:40', 'website', 'am', NULL, NULL, 1, NULL, '2026-04-04 14:03:53', NULL, '2026-04-04 14:03:07', '2026-04-05 10:30:40', NULL),
(14, '11111', '1111', 'hieutran170626@gmail.com', '$2y$12$lB0B9tNRlewJg3/5MBJ3teJDyqqpAEd8Spd0lKGV4QKi1Jj.nyZpG', '077633', '2026-04-05 10:23:58', 'website', 'am', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-05 09:50:06', '2026-04-05 10:08:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `khu_vuc`
--

CREATE TABLE `khu_vuc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cap_khu_vuc` varchar(255) NOT NULL DEFAULT 'quan_huyen',
  `khu_vuc_cha_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten_khu_vuc` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `hien_thi` tinyint(1) NOT NULL DEFAULT 1,
  `thu_tu_hien_thi` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khu_vuc`
--

INSERT INTO `khu_vuc` (`id`, `cap_khu_vuc`, `khu_vuc_cha_id`, `ten_khu_vuc`, `slug`, `mo_ta`, `hien_thi`, `thu_tu_hien_thi`, `seo_title`, `seo_description`, `seo_keywords`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'tinh_thanh', NULL, 'Hà Nội', 'ha-noi', NULL, 1, 1, 'Bất động sản Hà Nội', 'Mua bán cho thuê bất động sản Hà Nội uy tín', NULL, '2026-04-04 11:39:22', '2026-04-07 00:29:14', NULL),
(2, 'quan_huyen', 1, 'Vinhomes Smart City', 'Vinhomes Smart City', NULL, 1, 1, 'Bất động sản Vinhomes Smart City', 'Mua bán, cho thuê BĐS tại Vinhomes Smart City, Hà Nội', NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(3, 'quan_huyen', 1, 'Cầu Giấy', 'cau-giay', NULL, 1, 3, 'Bất động sản Cầu Giấy', 'Mua bán, cho thuê BĐS tại Cầu Giấy, Hà Nội', NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(4, 'quan_huyen', 1, 'Nam Từ Liêm', 'nam-tu-liem', NULL, 1, 2, 'Bất động sản Nam Từ Liêm', 'Mua bán, cho thuê BĐS tại Nam Từ Liêm, Hà Nội', NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ky_gui`
--

CREATE TABLE `ky_gui` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_phu_trach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ho_ten_chu_nha` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `loai_hinh` varchar(255) NOT NULL,
  `nhu_cau` varchar(255) NOT NULL DEFAULT 'ban',
  `du_an` varchar(150) DEFAULT NULL,
  `ma_can` varchar(50) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `dien_tich` decimal(10,2) NOT NULL,
  `huong_nha` varchar(255) DEFAULT NULL,
  `so_phong_ngu` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `so_phong_tam` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `noi_that` varchar(255) DEFAULT NULL,
  `gia_ban_mong_muon` decimal(15,2) DEFAULT NULL,
  `phap_ly` varchar(255) DEFAULT NULL,
  `gia_thue_mong_muon` decimal(15,2) DEFAULT NULL,
  `hinh_thuc_thanh_toan` varchar(255) DEFAULT NULL,
  `hinh_anh_tham_khao` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hinh_anh_tham_khao`)),
  `ghi_chu` text DEFAULT NULL,
  `nguon_ky_gui` varchar(255) NOT NULL DEFAULT 'website',
  `trang_thai` varchar(255) NOT NULL DEFAULT 'cho_duyet',
  `phan_hoi_cua_admin` text DEFAULT NULL,
  `thoi_diem_xu_ly` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ky_gui`
--

INSERT INTO `ky_gui` (`id`, `khach_hang_id`, `nhan_vien_phu_trach_id`, `ho_ten_chu_nha`, `so_dien_thoai`, `email`, `loai_hinh`, `nhu_cau`, `du_an`, `ma_can`, `dia_chi`, `dien_tich`, `huong_nha`, `so_phong_ngu`, `so_phong_tam`, `noi_that`, `gia_ban_mong_muon`, `phap_ly`, `gia_thue_mong_muon`, `hinh_thuc_thanh_toan`, `hinh_anh_tham_khao`, `ghi_chu`, `nguon_ky_gui`, `trang_thai`, `phan_hoi_cua_admin`, `thoi_diem_xu_ly`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, 'Nguyễn Văn Chính', '0961111111', 'kygui1@gmail.com', 'can_ho', 'ban', NULL, NULL, 'Tầng 12 Toà A, Vinhomes Smart City', 68.50, NULL, 4, 0, NULL, 2900000000.00, 'so_hong', NULL, NULL, NULL, 'Bán gấp, cần tiền gấp. Giá có thể thương lượng.', 'website', 'cho_duyet', NULL, NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(2, 2, 2, 'Trần Thị Dung', '0962222222', 'kygui2@gmail.com', 'nha_pho', 'ban', NULL, NULL, '25 Ngõ 45, Hà Đông, Hà Nội', 52.00, NULL, 2, 0, NULL, 6500000000.00, 'so_hong', NULL, NULL, NULL, 'Bán gấp, cần tiền gấp. Giá có thể thương lượng.', 'phone', 'da_lien_he', NULL, '2026-04-03 11:39:23', '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(3, 3, 3, 'Lê Văn Tú', '0963333333', 'kygui3@gmail.com', 'can_ho', 'thue', NULL, NULL, 'Tầng 8 Toà B2, Mipec Rubik 360', 55.00, NULL, 3, 0, NULL, NULL, 'so_hong', 14000000.00, NULL, NULL, 'Bán gấp, cần tiền gấp. Giá có thể thương lượng.', 'zalo', 'da_nhan', NULL, '2026-03-28 11:39:23', '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(4, 4, NULL, 'Phạm Thị Kim', '0964444444', 'kygui4@gmail.com', 'dat_nen', 'ban', NULL, NULL, 'Lô TT-05, Khu đô thị Thanh Hà', 90.00, NULL, 0, 0, NULL, 4200000000.00, 'so_hong', NULL, NULL, NULL, 'Bán gấp, cần tiền gấp. Giá có thể thương lượng.', 'website', 'cho_duyet', NULL, NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(5, 13, NULL, 'hieu', '0336 132 012', 'hieutran170623@gmail.com', 'can_ho', 'ban', NULL, NULL, NULL, 1.10, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'website', 'cho_duyet', NULL, NULL, '2026-04-04 14:12:54', '2026-04-06 07:37:22', '2026-04-06 07:37:22'),
(6, 13, NULL, 'hieu', '0336 132 012', 'hieutran170623@gmail.com', 'can_ho', 'thue', NULL, NULL, NULL, 1.30, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, '[\"ky-gui\\/dv0MI7S7nMK7HQX4Io7bgwI7WNPorZ0VbCJpx7ki.jpg\",\"ky-gui\\/b5Y3Ga8r6tL5R0ogzJp7e2ciYFRplbrhE1ET5C2b.jpg\",\"ky-gui\\/t3xtgm5gWzVFbVY7uJ4rfjzc28UYk7LtonvT60UY.jpg\",\"ky-gui\\/gkUkHCMBHhdU287HPbPvDmjtbGBXdZs3aUYE9eki.webp\",\"ky-gui\\/zzh4hKRUIIK9Zo8lmVCIwKEcTNQQ2S4TV8owpW9X.jpg\",\"ky-gui\\/vi8F3TlpDljmHDOrq9YSUnNWAzZHC3mswHw1dGQM.jpg\",\"ky-gui\\/a4kMlLSOSm8C7eHF2KKpGveNHj4jD67hg7xkEJEq.png\"]', NULL, 'website', 'cho_duyet', NULL, NULL, '2026-04-04 14:21:40', '2026-04-06 07:59:44', '2026-04-06 07:59:44'),
(7, NULL, NULL, 'Hieu', '336123130', 'hieutran170626@gmail.com', 'can_ho', 'thue', NULL, NULL, '111', 30.00, 'bac', 3, 0, 'co_ban', NULL, NULL, NULL, '3_coc_1', NULL, NULL, 'website', 'cho_duyet', NULL, NULL, '2026-04-05 03:12:34', '2026-04-05 03:12:34', NULL),
(8, NULL, 1, 'thu phương', '0962501832', 'nthuphuong2710@gmail.com', 'can_ho', 'ban', NULL, NULL, 'vinhomes gardenia', 75.00, 'dong_nam', 3, 2, 'nguyen_ban', 12000000000.00, 'so_hong', NULL, NULL, '[\"ky-gui\\/pqH01xrJB07goJPFQS9Er3E82nWfZ7up06iHPiRw.webp\"]', 'giá muốn 12 tỷ thu về, có hỗ trợ vay nhưng k muốn vay nhiều, sẽ mang đi hết nội thất', 'website', 'dang_tham_dinh', NULL, '2026-04-07 02:25:02', '2026-04-06 06:56:17', '2026-04-07 02:25:02', NULL),
(9, 13, NULL, 'hieu', '0336 132 012', 'hieutran170623@gmail.com', 'can_ho', 'ban', 'AAA', 'p1 2201', 'Vùng lõi Vinhomes Smart City', 23.00, 'bac', 0, 2, 'co_ban', 333000000.00, 'so_hong', NULL, NULL, NULL, NULL, 'website', 'cho_duyet', NULL, NULL, '2026-04-06 07:21:04', '2026-04-06 08:08:46', '2026-04-06 08:08:46'),
(10, 13, 1, 'hieu', '0336 132 012', 'hieutran170623@gmail.com', 'can_ho', 'ban', 'AAA', 'p1 2201', 'Vùng lõi Vinhomes Smart City', 23.00, NULL, 0, 0, NULL, 333000000.00, NULL, NULL, NULL, NULL, NULL, 'website', 'da_duyet', NULL, '2026-04-07 02:42:49', '2026-04-06 08:09:51', '2026-04-07 02:42:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lich_hen`
--

CREATE TABLE `lich_hen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED NOT NULL,
  `nhan_vien_sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_nguon_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten_khach_hang` varchar(255) NOT NULL,
  `sdt_khach_hang` varchar(255) NOT NULL,
  `email_khach_hang` varchar(255) DEFAULT NULL,
  `thoi_gian_hen` datetime NOT NULL,
  `dia_diem_hen` varchar(255) DEFAULT NULL,
  `ghi_chu_sale` text DEFAULT NULL,
  `ghi_chu_nguon_hang` text DEFAULT NULL,
  `nguon_dat_lich` varchar(255) NOT NULL DEFAULT 'sale',
  `trang_thai` varchar(255) NOT NULL DEFAULT 'moi_dat',
  `ly_do_tu_choi` text DEFAULT NULL,
  `xac_nhan_at` timestamp NULL DEFAULT NULL,
  `tu_choi_at` timestamp NULL DEFAULT NULL,
  `hoan_thanh_at` timestamp NULL DEFAULT NULL,
  `huy_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_hen`
--

INSERT INTO `lich_hen` (`id`, `khach_hang_id`, `bat_dong_san_id`, `nhan_vien_sale_id`, `nhan_vien_nguon_hang_id`, `ten_khach_hang`, `sdt_khach_hang`, `email_khach_hang`, `thoi_gian_hen`, `dia_diem_hen`, `ghi_chu_sale`, `ghi_chu_nguon_hang`, `nguon_dat_lich`, `trang_thai`, `ly_do_tu_choi`, `xac_nhan_at`, `tu_choi_at`, `hoan_thanh_at`, `huy_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 4, 2, 'Nguyễn Văn An', '0971111111', 'an.nv@gmail.com', '2026-04-05 09:00:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'website', 'da_xac_nhan', NULL, '2026-04-04 11:39:22', NULL, NULL, NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(2, 2, 2, 5, 3, 'Trần Thị Bình', '0972222222', 'binh.tt@gmail.com', '2026-04-05 14:00:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'phone', 'da_xac_nhan', NULL, '2026-04-04 11:39:23', NULL, NULL, NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(3, 3, 3, 6, 2, 'Lê Minh Cường', '0973333333', 'cuong.lm@gmail.com', '2026-04-10 19:48:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', 'ee', 'sale', 'da_xac_nhan', NULL, '2026-04-07 03:54:24', NULL, NULL, NULL, '2026-04-04 11:39:23', '2026-04-07 03:54:24', NULL),
(4, 4, 4, 4, 3, 'Phạm Thu Hà', '0974444444', 'ha.pt@gmail.com', '2026-04-07 15:00:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'chat', 'cho_xac_nhan', NULL, NULL, NULL, NULL, NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(5, 5, 5, 5, 2, 'Hoàng Văn Đức', '0975555555', 'duc.hv@gmail.com', '2026-04-02 09:00:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'website', 'hoan_thanh', NULL, '2026-04-04 11:39:23', NULL, '2026-04-04 11:39:23', NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(6, 6, 6, 6, 3, 'Vũ Thị Hoa', '0976666666', 'hoa.vt@gmail.com', '2026-03-30 14:00:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'phone', 'hoan_thanh', NULL, '2026-04-04 11:39:23', NULL, '2026-04-04 11:39:23', NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(7, 7, 7, 4, 2, 'Đặng Quốc Hùng', '0977777777', 'hung.dq@gmail.com', '2026-04-03 10:00:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'sale', 'huy', NULL, NULL, NULL, NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23', '2026-04-04 11:39:23', NULL),
(8, 8, 8, 1, 2, 'Ngô Thị Lan', '0978888888', 'lan.nt@gmail.com', '2026-04-09 09:30:00', 'Văn phòng Thành Công Land - 123 Đường Thành Công, Hà Đông', 'Khách muốn xem trực tiếp căn hộ, ưu tiên tầng cao.', NULL, 'chat', 'da_xac_nhan', NULL, '2026-04-07 05:35:01', NULL, NULL, NULL, '2026-04-04 11:39:23', '2026-04-07 05:35:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_tim_kiem`
--

CREATE TABLE `lich_su_tim_kiem` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `tu_khoa` varchar(255) DEFAULT NULL,
  `bo_loc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bo_loc`)),
  `sap_xep_theo` varchar(255) DEFAULT NULL,
  `so_ket_qua` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `thoi_diem_tim_kiem` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_tim_kiem`
--

INSERT INTO `lich_su_tim_kiem` (`id`, `khach_hang_id`, `session_id`, `tu_khoa`, `bo_loc`, `sap_xep_theo`, `so_ket_qua`, `thoi_diem_tim_kiem`, `created_at`, `updated_at`) VALUES
(1, NULL, 's5AQN4YOXOMNuxF4lye6Aksh01c4EJtQIigK0LY7', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-04 11:39:54', '2026-04-04 11:39:54', '2026-04-04 11:39:54'),
(2, NULL, 's5AQN4YOXOMNuxF4lye6Aksh01c4EJtQIigK0LY7', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 12:01:39', '2026-04-04 12:01:39', '2026-04-04 12:01:39'),
(3, NULL, 's5AQN4YOXOMNuxF4lye6Aksh01c4EJtQIigK0LY7', NULL, '{\"nhu_cau\":\"ban\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 12:02:11', '2026-04-04 12:02:11', '2026-04-04 12:02:11'),
(4, NULL, '1UhfbHVS3vjsEaeCLcRPguKgqOUHTrk93PyCHELI', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-04 12:27:16', '2026-04-04 12:27:16', '2026-04-04 12:27:16'),
(5, NULL, '7X8GcEafKyLffXXbdGfFdm1fLxpK96G33Yqu44mN', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"1\",\"du_an_id\":\"10\",\"gia_tu\":2000000000,\"gia_den\":5000000000}', NULL, 0, '2026-04-04 12:56:29', '2026-04-04 12:56:29', '2026-04-04 12:56:29'),
(6, NULL, '7X8GcEafKyLffXXbdGfFdm1fLxpK96G33Yqu44mN', NULL, '{\"nhu_cau\":\"ban\",\"so_phong_ngu\":\"studio\"}', NULL, 2, '2026-04-04 12:56:42', '2026-04-04 12:56:42', '2026-04-04 12:56:42'),
(7, NULL, '7X8GcEafKyLffXXbdGfFdm1fLxpK96G33Yqu44mN', NULL, '{\"nhu_cau\":\"ban\",\"so_phong_ngu\":\"2\"}', NULL, 2, '2026-04-04 12:56:48', '2026-04-04 12:56:48', '2026-04-04 12:56:48'),
(8, NULL, '7X8GcEafKyLffXXbdGfFdm1fLxpK96G33Yqu44mN', NULL, '{\"nhu_cau\":\"ban\",\"so_phong_ngu\":\"3\"}', NULL, 4, '2026-04-04 12:57:02', '2026-04-04 12:57:02', '2026-04-04 12:57:02'),
(9, NULL, '7X8GcEafKyLffXXbdGfFdm1fLxpK96G33Yqu44mN', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-04 12:58:12', '2026-04-04 12:58:12', '2026-04-04 12:58:12'),
(10, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-04 13:30:05', '2026-04-04 13:30:05', '2026-04-04 13:30:05'),
(11, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-04 13:30:15', '2026-04-04 13:30:15', '2026-04-04 13:30:15'),
(12, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 13:41:37', '2026-04-04 13:41:37', '2026-04-04 13:41:37'),
(13, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', NULL, '{\"nhu_cau\":\"ban\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 13:42:33', '2026-04-04 13:42:33', '2026-04-04 13:42:33'),
(14, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"2\"}', NULL, 1, '2026-04-04 13:46:09', '2026-04-04 13:46:09', '2026-04-04 13:46:09'),
(15, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 14:06:38', '2026-04-04 14:06:38', '2026-04-04 14:06:38'),
(16, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"ban\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 14:06:46', '2026-04-04 14:06:46', '2026-04-04 14:06:46'),
(17, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-04 14:31:02', '2026-04-04 14:31:02', '2026-04-04 14:31:02'),
(18, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"2\"}', 'moinhat', 2, '2026-04-04 14:31:06', '2026-04-04 14:31:06', '2026-04-04 14:31:06'),
(19, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-04 14:36:02', '2026-04-04 14:36:02', '2026-04-04 14:36:02'),
(20, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 14:43:01', '2026-04-04 14:43:01', '2026-04-04 14:43:01'),
(21, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 14:46:36', '2026-04-04 14:46:36', '2026-04-04 14:46:36'),
(22, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-04 14:52:41', '2026-04-04 14:52:41', '2026-04-04 14:52:41'),
(23, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-04 14:52:45', '2026-04-04 14:52:45', '2026-04-04 14:52:45'),
(24, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 01:17:00', '2026-04-05 01:17:00', '2026-04-05 01:17:00'),
(25, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"ban\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-05 01:24:24', '2026-04-05 01:24:24', '2026-04-05 01:24:24'),
(26, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 01:24:35', '2026-04-05 01:24:27', '2026-04-05 01:24:35'),
(27, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"3\"}', 'moinhat', 1, '2026-04-05 01:24:39', '2026-04-05 01:24:39', '2026-04-05 01:24:39'),
(28, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"3\",\"du_an_id\":\"8\"}', 'moinhat', 1, '2026-04-05 01:24:42', '2026-04-05 01:24:42', '2026-04-05 01:24:42'),
(29, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 01:27:27', '2026-04-05 01:24:44', '2026-04-05 01:27:27'),
(30, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"4\"}', NULL, 1, '2026-04-05 01:38:05', '2026-04-05 01:38:05', '2026-04-05 01:38:05'),
(31, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-05 01:50:07', '2026-04-05 01:50:07', '2026-04-05 01:50:07'),
(32, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 01:50:18', '2026-04-05 01:50:18', '2026-04-05 01:50:18'),
(33, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 01:53:39', '2026-04-05 01:53:39', '2026-04-05 01:53:39'),
(34, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', 'Studio Green Bay', '{\"nhu_cau\":\"thue\"}', NULL, 0, '2026-04-05 01:53:42', '2026-04-05 01:53:42', '2026-04-05 01:53:42'),
(35, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 01:53:45', '2026-04-05 01:53:45', '2026-04-05 01:53:45'),
(36, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', '2N2VS DCapital', '{\"nhu_cau\":\"thue\"}', NULL, 0, '2026-04-05 01:53:47', '2026-04-05 01:53:47', '2026-04-05 01:53:47'),
(37, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 01:53:50', '2026-04-05 01:53:50', '2026-04-05 01:53:50'),
(38, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"gia_tu\":0,\"gia_den\":10000000}', NULL, 1, '2026-04-05 01:53:57', '2026-04-05 01:53:57', '2026-04-05 01:53:57'),
(39, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"3\",\"gia_tu\":0,\"gia_den\":10000000}', 'moinhat', 0, '2026-04-05 01:54:00', '2026-04-05 01:54:00', '2026-04-05 01:54:00'),
(40, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"1\",\"gia_tu\":0,\"gia_den\":10000000}', 'moinhat', 0, '2026-04-05 01:54:03', '2026-04-05 01:54:03', '2026-04-05 01:54:03'),
(41, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\",\"gia_tu\":0,\"gia_den\":10000000}', 'moinhat', 1, '2026-04-05 01:54:07', '2026-04-05 01:54:07', '2026-04-05 01:54:07'),
(42, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"1\",\"gia_tu\":0,\"gia_den\":10000000}', 'moinhat', 0, '2026-04-05 01:54:13', '2026-04-05 01:54:13', '2026-04-05 01:54:13'),
(43, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"1\",\"gia_tu\":0,\"gia_den\":10000000,\"toa\":\"T\\u00f2a A\"}', 'moinhat', 0, '2026-04-05 01:54:19', '2026-04-05 01:54:19', '2026-04-05 01:54:19'),
(44, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"1\",\"toa\":\"T\\u00f2a A\"}', 'moinhat', 0, '2026-04-05 01:54:31', '2026-04-05 01:54:24', '2026-04-05 01:54:31'),
(45, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"1\",\"toa\":\"T\\u00f2a A\"}', 'moinhat', 1, '2026-04-05 01:54:33', '2026-04-05 01:54:33', '2026-04-05 01:54:33'),
(46, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 01:54:43', '2026-04-05 01:54:43', '2026-04-05 01:54:43'),
(47, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:06:50', '2026-04-05 02:04:37', '2026-04-05 02:06:50'),
(48, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:08:13', '2026-04-05 02:08:13', '2026-04-05 02:08:13'),
(49, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\"}', NULL, 6, '2026-04-05 02:08:17', '2026-04-05 02:08:17', '2026-04-05 02:08:17'),
(50, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:08:21', '2026-04-05 02:08:20', '2026-04-05 02:08:21'),
(51, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-05 02:08:24', '2026-04-05 02:08:24', '2026-04-05 02:08:24'),
(52, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:08:27', '2026-04-05 02:08:27', '2026-04-05 02:08:27'),
(53, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 02:08:36', '2026-04-05 02:08:36', '2026-04-05 02:08:36'),
(54, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:08:51', '2026-04-05 02:08:51', '2026-04-05 02:08:51'),
(55, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"3\"}', 'moinhat', 0, '2026-04-05 02:08:56', '2026-04-05 02:08:56', '2026-04-05 02:08:56'),
(56, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"1\"}', 'moinhat', 0, '2026-04-05 02:09:01', '2026-04-05 02:09:01', '2026-04-05 02:09:01'),
(57, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\"}', 'moinhat', 6, '2026-04-05 02:09:07', '2026-04-05 02:09:07', '2026-04-05 02:09:07'),
(58, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"3\"}', 'moinhat', 1, '2026-04-05 02:09:14', '2026-04-05 02:09:14', '2026-04-05 02:09:14'),
(59, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 02:09:53', '2026-04-05 02:09:53', '2026-04-05 02:09:53'),
(60, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:10:42', '2026-04-05 02:10:42', '2026-04-05 02:10:42'),
(61, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"khu_vuc_id\":\"1\"}', 'moinhat', 6, '2026-04-05 02:11:54', '2026-04-05 02:11:54', '2026-04-05 02:11:54'),
(62, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-05 02:13:10', '2026-04-05 02:13:10', '2026-04-05 02:13:10'),
(63, NULL, '65DUQhdAnYBtTwlfY3DQGLrxp8JxVmG0LgfmgBPR', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 02:32:28', '2026-04-05 02:32:28', '2026-04-05 02:32:28'),
(64, NULL, '65DUQhdAnYBtTwlfY3DQGLrxp8JxVmG0LgfmgBPR', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 02:32:36', '2026-04-05 02:32:36', '2026-04-05 02:32:36'),
(65, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 03:24:16', '2026-04-05 03:24:16', '2026-04-05 03:24:16'),
(66, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 03:30:56', '2026-04-05 03:30:04', '2026-04-05 03:30:56'),
(67, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 03:30:58', '2026-04-05 03:30:58', '2026-04-05 03:30:58'),
(68, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 03:31:26', '2026-04-05 03:31:26', '2026-04-05 03:31:26'),
(69, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 03:31:28', '2026-04-05 03:31:28', '2026-04-05 03:31:28'),
(70, NULL, 'D5j3N4FYVFYCK757ieLsbC6GJKK9vE0KuCS0KVkt', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 03:47:08', '2026-04-05 03:47:08', '2026-04-05 03:47:08'),
(71, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 9, '2026-04-05 03:57:29', '2026-04-05 03:56:18', '2026-04-05 03:57:29'),
(72, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"4\"}', NULL, 6, '2026-04-05 03:57:33', '2026-04-05 03:57:33', '2026-04-05 03:57:33'),
(73, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"2\"}', 'moinhat', 1, '2026-04-05 03:57:36', '2026-04-05 03:57:36', '2026-04-05 03:57:36'),
(74, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"ban\",\"khu_vuc_id\":\"4\",\"du_an_id\":\"2\"}', 'moinhat', 1, '2026-04-05 04:04:57', '2026-04-05 04:02:03', '2026-04-05 04:04:57'),
(75, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 6, '2026-04-05 04:54:04', '2026-04-05 04:54:04', '2026-04-05 04:54:04'),
(76, NULL, 'hGGUcUyxr07ra2XJTEwesyh9VRG3KnsGvv6bSvYK', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-05 14:16:57', '2026-04-05 14:16:57', '2026-04-05 14:16:57'),
(77, NULL, 'qE8uCSclHNrTXkbsSfgYJnnKxkh2oj8I675y18YX', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 05:49:22', '2026-04-06 05:49:22', '2026-04-06 05:49:22'),
(78, NULL, 'qE8uCSclHNrTXkbsSfgYJnnKxkh2oj8I675y18YX', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 05:52:27', '2026-04-06 05:52:27', '2026-04-06 05:52:27'),
(79, NULL, 'qE8uCSclHNrTXkbsSfgYJnnKxkh2oj8I675y18YX', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 7, '2026-04-06 06:08:33', '2026-04-06 06:08:07', '2026-04-06 06:08:33'),
(80, NULL, 'qE8uCSclHNrTXkbsSfgYJnnKxkh2oj8I675y18YX', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 7, '2026-04-06 06:12:43', '2026-04-06 06:12:43', '2026-04-06 06:12:43'),
(81, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-06 06:21:01', '2026-04-06 06:21:01', '2026-04-06 06:21:01'),
(82, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 7, '2026-04-06 06:38:41', '2026-04-06 06:38:41', '2026-04-06 06:38:41'),
(83, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"ban\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-06 06:45:23', '2026-04-06 06:43:53', '2026-04-06 06:45:23'),
(84, NULL, 'MBVFmfOcmUmDKXJTxa70RZEsSm3R6GslPxJczGIk', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 07:51:55', '2026-04-06 07:51:55', '2026-04-06 07:51:55'),
(85, NULL, 'MBVFmfOcmUmDKXJTxa70RZEsSm3R6GslPxJczGIk', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 07:55:32', '2026-04-06 07:55:32', '2026-04-06 07:55:32'),
(86, NULL, 'MBVFmfOcmUmDKXJTxa70RZEsSm3R6GslPxJczGIk', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"1\"}', NULL, 1, '2026-04-06 08:00:44', '2026-04-06 08:00:44', '2026-04-06 08:00:44'),
(87, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 7, '2026-04-06 08:04:26', '2026-04-06 08:04:26', '2026-04-06 08:04:26'),
(88, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 08:04:30', '2026-04-06 08:04:30', '2026-04-06 08:04:30'),
(89, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"thue\",\"du_an_id\":\"2\"}', NULL, 1, '2026-04-06 08:37:38', '2026-04-06 08:37:38', '2026-04-06 08:37:38'),
(90, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 08:39:52', '2026-04-06 08:39:52', '2026-04-06 08:39:52'),
(91, 13, 'i4lGKCWTO45tDQNx4IhQIjRxvr4PozmNXHizytkp', NULL, '{\"nhu_cau\":\"ban\"}', NULL, 11, '2026-04-06 21:30:09', '2026-04-06 21:30:09', '2026-04-06 21:30:09'),
(92, 13, 'i4lGKCWTO45tDQNx4IhQIjRxvr4PozmNXHizytkp', NULL, '{\"nhu_cau\":\"thue\"}', NULL, 7, '2026-04-06 21:30:14', '2026-04-06 21:30:14', '2026-04-06 21:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_xem_bds`
--

CREATE TABLE `lich_su_xem_bds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `loai_hinh` varchar(50) DEFAULT NULL,
  `nhu_cau` varchar(20) DEFAULT NULL,
  `du_an_id` bigint(20) UNSIGNED DEFAULT NULL,
  `khu_vuc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gia_tu` decimal(15,2) DEFAULT NULL,
  `gia_den` decimal(15,2) DEFAULT NULL,
  `thoi_gian_xem` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_xem_bds`
--

INSERT INTO `lich_su_xem_bds` (`id`, `bat_dong_san_id`, `khach_hang_id`, `session_id`, `loai_hinh`, `nhu_cau`, `du_an_id`, `khu_vuc_id`, `gia_tu`, `gia_den`, `thoi_gian_xem`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, 's5AQN4YOXOMNuxF4lye6Aksh01c4EJtQIigK0LY7', 'can_ho', 'thue', 1, 4, 10800000.00, 13200000.00, 0, '2026-04-04 12:01:42', '2026-04-04 12:01:42'),
(2, 1, NULL, 's5AQN4YOXOMNuxF4lye6Aksh01c4EJtQIigK0LY7', 'can_ho', 'ban', 1, 4, 2520000000.00, 3080000000.00, 0, '2026-04-04 12:02:13', '2026-04-04 12:02:13'),
(3, 12, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', 'can_ho', 'thue', 2, 4, 8100000.00, 9900000.00, 0, '2026-04-04 13:30:08', '2026-04-04 13:30:08'),
(4, 1, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', 'can_ho', 'ban', 1, 4, 2520000000.00, 3080000000.00, 0, '2026-04-04 13:30:18', '2026-04-04 13:30:18'),
(5, 11, NULL, '2VFsimyiCIscUxDviVFYdNVxRApiQBsm6rZBbdsR', 'can_ho', 'thue', 1, 4, 10800000.00, 13200000.00, 0, '2026-04-04 13:41:40', '2026-04-04 13:41:40'),
(6, 11, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', 'can_ho', 'thue', 1, 4, 10800000.00, 13200000.00, 0, '2026-04-04 14:06:40', '2026-04-04 14:06:40'),
(7, 1, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', 'can_ho', 'ban', 1, 4, 2520000000.00, 3080000000.00, 0, '2026-04-04 14:06:48', '2026-04-04 14:06:48'),
(8, 9, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', 'nha_pho', 'ban', 9, 2, 10800000000.00, 13200000000.00, 0, '2026-04-04 14:31:10', '2026-04-04 14:31:10'),
(9, 12, 13, '7XrsbhcuPDk64AW5KjZjGtyXC2SmqYiiJdv5lbPm', 'can_ho', 'thue', 2, 4, 8100000.00, 9900000.00, 0, '2026-04-04 14:36:05', '2026-04-04 14:36:05'),
(10, 3, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', 'nha_pho', 'ban', 3, 4, 6750000000.00, 8250000000.00, 0, '2026-04-05 01:17:02', '2026-04-05 01:17:02'),
(11, 14, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', 'shophouse', 'thue', 4, 4, 31500000.00, 38500000.00, 0, '2026-04-05 01:49:34', '2026-04-05 01:49:34'),
(12, 14, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', 'shophouse', 'thue', 4, 4, 31500000.00, 38500000.00, 0, '2026-04-05 01:49:54', '2026-04-05 01:49:54'),
(13, 12, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', 'can_ho', 'thue', 2, 4, 8100000.00, 9900000.00, 0, '2026-04-05 02:08:33', '2026-04-05 02:08:33'),
(14, 1, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', 'can_ho', 'ban', 1, 4, 2520000000.00, 3080000000.00, 0, '2026-04-05 02:10:04', '2026-04-05 02:10:04'),
(15, 2, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', 'can_ho', 'ban', 2, 4, 3780000000.00, 4620000000.00, 0, '2026-04-05 04:05:26', '2026-04-05 04:05:26'),
(16, 15, 13, 'fHk4QNQyJ3ZVTXdEV26i9nNwqkjrcmTwkqca01h0', 'can_ho', 'thue', 5, 4, 16200000.00, 19800000.00, 0, '2026-04-05 04:56:38', '2026-04-05 04:56:38'),
(17, 15, 13, 'z4wuQPIZQivJbCQNqJW8aTyRzSPkTJbFhYUDAnwv', 'can_ho', 'thue', 5, 4, 16200000.00, 19800000.00, 0, '2026-04-05 08:05:35', '2026-04-05 08:05:35'),
(18, 2, NULL, 'qE8uCSclHNrTXkbsSfgYJnnKxkh2oj8I675y18YX', 'can_ho', 'ban', 2, 4, 3780000000.00, 4620000000.00, 0, '2026-04-06 05:49:24', '2026-04-06 05:49:24'),
(19, 11, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', 'can_ho', 'thue', 1, 4, 10800000.00, 13200000.00, 0, '2026-04-06 06:21:06', '2026-04-06 06:21:06'),
(20, 12, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', 'can_ho', 'thue', 2, 4, 8100000.00, 9900000.00, 0, '2026-04-06 06:38:44', '2026-04-06 06:38:44'),
(21, 1, NULL, 'MBVFmfOcmUmDKXJTxa70RZEsSm3R6GslPxJczGIk', 'can_ho', 'ban', 1, 4, 2520000000.00, 3080000000.00, 0, '2026-04-06 07:52:11', '2026-04-06 07:52:11'),
(22, 1, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', 'can_ho', 'ban', 1, 4, 2520000000.00, 3080000000.00, 0, '2026-04-06 08:04:32', '2026-04-06 08:04:32'),
(23, 12, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', 'can_ho', 'thue', 2, 4, 8100000.00, 9900000.00, 0, '2026-04-06 08:37:48', '2026-04-06 08:37:48'),
(24, 2, 13, 'KMHOIBc1C2BUCCHUHI9q5wYWK508ySGUQv06emhb', 'can_ho', 'ban', 2, 4, 3780000000.00, 4620000000.00, 0, '2026-04-06 08:39:55', '2026-04-06 08:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_03_13_081430_create_nhan_vien_table', 1),
(2, '2026_03_13_081431_create_chu_nhas_table', 1),
(3, '2026_03_13_081432_create_khach_hang_table', 1),
(4, '2026_03_13_081433_create_khu_vuc_table', 1),
(5, '2026_03_13_081434_create_du_an_table', 1),
(6, '2026_03_13_081435_create_bai_viet_table', 1),
(7, '2026_03_13_081435_create_bat_dong_san_table', 1),
(8, '2026_03_13_081439_create_yeu_cau_lien_he_table', 1),
(9, '2026_03_13_081440_create_lich_hen_table', 1),
(10, '2026_03_13_081441_create_ky_gui_table', 1),
(11, '2026_03_13_081441_create_yeu_thich_table', 1),
(12, '2026_03_13_081443_create_canh_bao_gia_table', 1),
(13, '2026_03_13_081444_create_lich_su_tim_kiem_table', 1),
(14, '2026_03_13_081445_create_phien_chat_table', 1),
(15, '2026_03_13_081446_create_tin_nhan_chat_table', 1),
(16, '2026_03_13_081448_create_ghi_chu_khach_hang_table', 1),
(17, '2026_03_13_081448_create_nhat_ky_email_table', 1),
(18, '2026_03_13_081450_create_thong_bao_table', 1),
(19, '2026_03_13_083634_create_tin_nhan_noi_bo_table', 1),
(20, '2026_04_01_184448_create_lich_su_xem_bds_table', 1),
(21, '2026_04_04_183342_create_ngan_hang_table', 1),
(22, '2026_04_05_103317_create_dang_ky_nhan_tins_table', 2),
(23, '2026_04_05_125733_create_failed_jobs_table', 3),
(24, '2026_04_05_125733_create_jobs_table', 3),
(25, '2026_04_05_132500_add_khu_vuc_id_to_dang_ky_nhan_tin_table', 4),
(26, '2026_04_05_134100_add_gui_mail_canh_bao_gia_to_bat_dong_san_table', 5),
(27, '2026_04_06_100500_add_du_an_ma_can_to_ky_gui_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `ngan_hang`
--

CREATE TABLE `ngan_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_ngan_hang` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `lai_suat_uu_dai` decimal(5,2) NOT NULL,
  `thoi_gian_uu_dai` int(11) NOT NULL DEFAULT 12,
  `lai_suat_tha_noi` decimal(5,2) DEFAULT NULL,
  `ty_le_vay_toi_da` int(11) NOT NULL DEFAULT 70,
  `thoi_gian_vay_toi_da` int(11) NOT NULL DEFAULT 25,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngan_hang`
--

INSERT INTO `ngan_hang` (`id`, `ten_ngan_hang`, `logo`, `lai_suat_uu_dai`, `thoi_gian_uu_dai`, `lai_suat_tha_noi`, `ty_le_vay_toi_da`, `thoi_gian_vay_toi_da`, `trang_thai`, `created_at`, `updated_at`) VALUES
(1, 'Mb bank', NULL, 8.00, 12, 8.70, 70, 20, 1, '2026-04-04 13:32:42', '2026-04-04 13:32:42'),
(2, 'VietComBank', NULL, 8.00, 12, 11.00, 70, 25, 1, '2026-04-06 21:40:12', '2026-04-06 21:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `vai_tro` varchar(255) NOT NULL DEFAULT 'sale',
  `so_dien_thoai` varchar(255) DEFAULT NULL,
  `anh_dai_dien` varchar(255) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `kich_hoat` tinyint(1) NOT NULL DEFAULT 1,
  `dang_nhap_cuoi_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhan_vien`
--

INSERT INTO `nhan_vien` (`id`, `ho_ten`, `email`, `password`, `vai_tro`, `so_dien_thoai`, `anh_dai_dien`, `dia_chi`, `kich_hoat`, `dang_nhap_cuoi_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nguyễn Thành Công', 'admin@gmail.com', '$2y$12$BDvT6xnFKO2vbbjljR2CHOfhNoug9dliQmWoGaj7PdviwF.gVIyuu', 'admin', '0901234567', NULL, '123 Đường Thành Công, Hà Đông, Hà Nội', 1, '2026-04-07 00:05:30', 'CIdOHQWvZuPK6Zn6XvPeswevjlhLh7xlsKXI1njBwcBRrZ1Ie68yd8g3dQi3', '2026-04-04 11:39:22', '2026-04-07 00:05:30', NULL),
(2, 'Trần Văn Nguồn', 'nguon1@thanhcongland.com', '$2y$12$Ys3c11kio8krJIwq9rUDmOUVeQAivBi3onuyE81ktS2b94rQC2w2q', 'nguon_hang', '0912345678', NULL, 'Hà Đông, Hà Nội', 1, '2026-04-07 03:28:24', NULL, '2026-04-04 11:39:22', '2026-04-07 03:28:24', NULL),
(3, 'Lê Thị Hương', 'nguon2@thanhcongland.com', '$2y$12$F1E.p8cgH3PFsxPVqKY9SuJnymRZ/LMdUIMWa2q7gnGaKHJ8WSomq', 'nguon', '0923456789', NULL, 'Nam Từ Liêm, Hà Nội', 1, NULL, NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(4, 'Phạm Minh Sale', 'sale1@thanhcongland.com', '$2y$12$8VYEr9XcwUhZaAQ5VMMRK.eQ8jlXoKRlFk3bzj7lRxa2kqznNyqea', 'sale', '0934567890', NULL, 'Cầu Giấy, Hà Nội', 1, '2026-04-07 03:29:09', NULL, '2026-04-04 11:39:22', '2026-04-07 03:29:09', NULL),
(5, 'Hoàng Thị Sale', 'sale2@thanhcongland.com', '$2y$12$fmqLWQOyUqL2tRSrbxbEveT6oSkpn786BWsU2Yz9eScPBIQwjBvHK', 'sale', '0945678901', NULL, 'Thanh Xuân, Hà Nội', 1, NULL, NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(6, 'Vũ Đức Sale', 'sale3@thanhcongland.com', '$2y$12$Vbo03.kRjcdZrZUWi0FNq.fDL2PMIZ6YCZKeglWBxgrOM4y0F2zRm', 'sale', '0956789012', NULL, 'Hoàng Mai, Hà Nội', 1, NULL, NULL, '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(7, 'hieu', 'hieutran170626@gmail.com', '$2y$12$pdxWcyQLH2J1YfpfvaKw5Oy8bCIbXop1mNjF16LkTeuTc9zDux5bq', 'admin', '0336 132 012', NULL, NULL, 1, '2026-04-07 02:13:57', '30NR0Ljt3VW9omke6amedeF3UU8At2K946RGUygECupCAbyk4e92C1A0jqsy', '2026-04-05 05:30:37', '2026-04-07 02:13:57', NULL),
(8, 'aaa', 'hieutran1706213@gmail.com', '$2y$12$J6jOtNDkdXitTGZxEYZ/feMYOa7HiOtVRlxnfSSsoQ6cfWS8Tev0a', 'nguon_hang', NULL, NULL, NULL, 1, NULL, NULL, '2026-04-06 23:34:34', '2026-04-06 23:34:48', '2026-04-06 23:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `nhat_ky_email`
--

CREATE TABLE `nhat_ky_email` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai_email` varchar(255) NOT NULL,
  `email_nguoi_nhan` varchar(255) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` longtext DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'thanh_cong',
  `loi` text DEFAULT NULL,
  `doi_tuong_lien_quan` varchar(255) DEFAULT NULL,
  `doi_tuong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `thoi_diem_gui` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phien_chat`
--

CREATE TABLE `phien_chat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_phu_trach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `ten_khach_vang_lai` varchar(255) DEFAULT NULL,
  `sdt_khach_vang_lai` varchar(255) DEFAULT NULL,
  `email_khach_vang_lai` varchar(255) DEFAULT NULL,
  `da_xac_thuc_sdt` tinyint(1) NOT NULL DEFAULT 0,
  `url_ngu_canh` varchar(255) DEFAULT NULL,
  `loai_ngu_canh` varchar(255) DEFAULT NULL,
  `ngu_canh_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten_ngu_canh` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'dang_mo',
  `dang_bot_xu_ly` tinyint(1) NOT NULL DEFAULT 1,
  `tin_nhan_cuoi_at` timestamp NULL DEFAULT NULL,
  `het_han_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phien_chat`
--

INSERT INTO `phien_chat` (`id`, `khach_hang_id`, `nhan_vien_phu_trach_id`, `session_id`, `ten_khach_vang_lai`, `sdt_khach_vang_lai`, `email_khach_vang_lai`, `da_xac_thuc_sdt`, `url_ngu_canh`, `loai_ngu_canh`, `ngu_canh_id`, `ten_ngu_canh`, `trang_thai`, `dang_bot_xu_ly`, `tin_nhan_cuoi_at`, `het_han_at`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '0939a5cc-447f-4588-8f3f-320412b10480', NULL, NULL, NULL, 0, '/bat-dong-san', NULL, NULL, NULL, 'dang_mo', 1, '2026-04-04 11:06:23', NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23'),
(2, 2, 5, 'aaf3ee8f-1879-48bc-b4c1-c5804701d8a3', NULL, NULL, NULL, 0, '/bat-dong-san', NULL, NULL, NULL, 'dang_mo', 1, '2026-04-04 11:05:23', NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23'),
(3, 3, 6, '03b59676-4536-4447-be10-6e18697d0382', NULL, NULL, NULL, 0, '/bat-dong-san', NULL, NULL, NULL, 'da_dong', 1, '2026-04-04 10:37:23', NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23'),
(4, NULL, 4, '4d4fdfe1-c49d-4f5d-bbf0-6719cd8741f7', 'Khách Vãng Lai', '0999888777', NULL, 0, '/bat-dong-san/can-ho-2pn-vinhomes-1', NULL, NULL, NULL, 'dang_mo', 1, '2026-04-04 11:34:23', NULL, '2026-04-04 11:39:23', '2026-04-04 11:39:23'),
(5, NULL, NULL, 'RTgyTtt44EZNJWRpuLjaJfIIogRB8wNnwMH6FkMg', 'Khach vang lai', NULL, NULL, 0, 'http://127.0.0.1:8000/bat-dong-san/nha-pho-ha-dong-4-tang-mat-tien-duong-lon-3', 'bat_dong_san', 3, 'Nhà phố Hà Đông 4 tầng mặt tiền đường lớn', 'dang_bot', 1, '2026-04-05 01:17:14', '2026-04-06 01:17:06', '2026-04-05 01:17:06', '2026-04-05 01:17:14'),
(6, 13, NULL, 'z4wuQPIZQivJbCQNqJW8aTyRzSPkTJbFhYUDAnwv', 'hieu', '0336 132 012', 'hieutran170623@gmail.com', 0, 'http://127.0.0.1:8000/bat-dong-san/cho-thue-can-ho-3pn-vinhomes-smart-city-cao-cap-th-5', 'bat_dong_san', 15, 'Cho thuê căn hộ 3PN Vinhomes Smart City cao cấp', 'dang_bot', 1, NULL, NULL, '2026-04-05 08:42:41', '2026-04-05 08:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `id` char(36) NOT NULL,
  `loai` varchar(255) NOT NULL,
  `doi_tuong_nhan` varchar(255) NOT NULL,
  `doi_tuong_nhan_id` bigint(20) UNSIGNED NOT NULL,
  `tieu_de` varchar(255) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `du_lieu` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`du_lieu`)),
  `lien_ket` varchar(255) DEFAULT NULL,
  `da_doc_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thong_bao`
--

INSERT INTO `thong_bao` (`id`, `loai`, `doi_tuong_nhan`, `doi_tuong_nhan_id`, `tieu_de`, `noi_dung`, `du_lieu`, `lien_ket`, `da_doc_at`, `created_at`, `updated_at`) VALUES
('5e3fabb4-6b83-4792-b69e-abe7e7f6364d', 'lich_hen_xac_nhan', 'nhan_vien', 1, 'Lịch hẹn ĐÃ ĐƯỢC XÁC NHẬN ✓', 'Nguồn hàng đã chốt xong với chủ nhà lúc 09:30 09/04/2026. Hãy dẫn khách đi xem.', '\"{\\\"lich_hen_id\\\":8}\"', 'http://127.0.0.1:8000/nhan-vien/admin/lich-hen', NULL, '2026-04-07 05:35:01', '2026-04-07 05:35:01'),
('7fe1304c-817b-4929-ad49-a60d788478ce', 'lich_hen_xac_nhan', 'nhan_vien', 6, 'Lịch hẹn ĐÃ ĐƯỢC XÁC NHẬN ✓', 'Nguồn hàng đã chốt xong với chủ nhà lúc 19:48 10/04/2026. Hãy dẫn khách đi xem.', '\"{\\\"lich_hen_id\\\":3}\"', 'http://127.0.0.1:8000/nhan-vien/admin/lich-hen', NULL, '2026-04-07 03:54:25', '2026-04-07 03:54:25'),
('853d7610-f984-48b3-a532-b3bad7937caf', 'lich_hen_moi', 'nhan_vien', 2, 'Có lịch hẹn mới cần lấy chìa khóa', 'Sale Nguyễn Thành Công vừa yêu cầu xác nhận lịch.', '\"{\\\"lich_hen_id\\\":8}\"', 'http://127.0.0.1:8000/nhan-vien/admin/lich-hen', NULL, '2026-04-07 05:34:30', '2026-04-07 05:34:30'),
('fc7c25a9-a2f4-42c4-9895-c257c7b0970d', 'lich_hen_xac_nhan', 'nhan_vien', 6, 'Nguồn hàng BÁO ĐỔI GIỜ', 'Chủ nhà báo lại: ee', '\"{\\\"lich_hen_id\\\":3}\"', 'http://127.0.0.1:8000/nhan-vien/admin/lich-hen', NULL, '2026-04-07 03:48:09', '2026-04-07 03:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `tin_nhan_chat`
--

CREATE TABLE `tin_nhan_chat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phien_chat_id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nguoi_gui` varchar(255) NOT NULL DEFAULT 'khach_hang',
  `loai_tin_nhan` varchar(255) NOT NULL DEFAULT 'van_ban',
  `noi_dung` text DEFAULT NULL,
  `tep_dinh_kem` varchar(255) DEFAULT NULL,
  `da_doc` tinyint(1) NOT NULL DEFAULT 0,
  `da_doc_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tin_nhan_chat`
--

INSERT INTO `tin_nhan_chat` (`id`, `phien_chat_id`, `khach_hang_id`, `nhan_vien_id`, `nguoi_gui`, `loai_tin_nhan`, `noi_dung`, `tep_dinh_kem`, `da_doc`, `da_doc_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'khach_hang', 'van_ban', 'Xin chào, tôi muốn tìm căn hộ 2 phòng ngủ tại Vinhomes Smart City.', NULL, 1, '2026-04-04 11:14:23', '2026-04-04 10:04:23', '2026-04-04 11:39:23'),
(2, 1, NULL, 4, 'nhan_vien', 'van_ban', 'Chào bạn! Hiện tại chúng tôi có một số căn hộ 2PN tại Vinhomes Smart City. Bạn có thể cho biết mức ngân sách dự kiến không ạ?', NULL, 1, '2026-04-04 11:16:23', '2026-04-04 11:13:23', '2026-04-04 11:39:23'),
(3, 2, 2, NULL, 'khach_hang', 'van_ban', 'Cho hỏi căn hộ BDS-0001 còn trống không?', NULL, 1, '2026-04-04 11:05:23', '2026-04-04 10:52:23', '2026-04-04 11:39:23'),
(4, 2, NULL, 5, 'nhan_vien', 'van_ban', 'Dạ căn hộ đó hiện vẫn còn ạ! Bạn có muốn đặt lịch xem nhà không?', NULL, 1, '2026-04-04 11:27:23', '2026-04-04 11:13:23', '2026-04-04 11:39:23'),
(5, 3, 3, NULL, 'khach_hang', 'van_ban', 'Tôi muốn thuê nhà tháng 4, giá khoảng 10-15 triệu.', NULL, 1, '2026-04-04 10:50:23', '2026-04-04 09:54:23', '2026-04-04 11:39:23'),
(6, 3, NULL, 6, 'nhan_vien', 'van_ban', 'Dạ, với mức giá đó chúng tôi có một số căn hộ 2PN rất phù hợp. Để tôi gửi thông tin chi tiết cho bạn nhé!', NULL, 1, '2026-04-04 11:21:23', '2026-04-04 11:16:23', '2026-04-04 11:39:23'),
(7, 4, NULL, NULL, 'khach_hang', 'van_ban', 'Cho hỏi giá căn hộ này bao nhiêu vậy ạ?', NULL, 1, '2026-04-04 14:51:33', '2026-04-04 11:39:23', '2026-04-04 14:51:33'),
(8, 5, NULL, NULL, 'he_thong', 'van_ban', 'Xin chào! Tôi là trợ lý AI của Thành Công Land. Tôi có thể hỗ trợ bạn ngay bây giờ.', NULL, 1, NULL, '2026-04-05 01:17:06', '2026-04-05 01:17:06'),
(9, 5, NULL, NULL, 'he_thong', 'van_ban', 'Luu y: Ban chua xac thuc tai khoan. Noi dung chat se bi xoa sau 24 gio neu khong dang nhap/xac thuc.', NULL, 1, NULL, '2026-04-05 01:17:06', '2026-04-05 01:17:06'),
(10, 5, NULL, NULL, 'khach_hang', 'van_ban', 'xin chaof', NULL, 0, NULL, '2026-04-05 01:17:11', '2026-04-05 01:17:11'),
(11, 5, NULL, NULL, 'bot', 'van_ban', 'Chào mừng quý khách đến với Thành Công Land! Rất vui được hỗ trợ quý khách.\n\nQuý khách đang quan tâm đến căn nhà phố 4 tầng mặt tiền đường lớn tại Hà Đông với giá 7.5 tỷ đồng và diện tích 82m² phải không ạ?\n\nNếu đúng, quý khách có muốn Thành Công Land tư vấn thêm thông tin chi tiết về căn nhà này, hoặc quý khách đang tìm kiếm một bất động sản khác không ạ?', NULL, 1, '2026-04-05 01:17:14', '2026-04-05 01:17:14', '2026-04-05 01:17:14'),
(12, 6, NULL, NULL, 'he_thong', 'van_ban', 'Xin chào! Tôi là trợ lý AI của Thành Công Land. Tôi có thể hỗ trợ bạn ngay bây giờ.', NULL, 1, NULL, '2026-04-05 08:42:41', '2026-04-05 08:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `tin_nhan_noi_bo`
--

CREATE TABLE `tin_nhan_noi_bo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nguoi_gui_id` bigint(20) UNSIGNED NOT NULL,
  `nguoi_nhan_id` bigint(20) UNSIGNED NOT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED DEFAULT NULL,
  `du_an_id` bigint(20) UNSIGNED DEFAULT NULL,
  `khach_hang_lq_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai_tin_nhan` varchar(255) NOT NULL DEFAULT 'van_ban',
  `noi_dung` text DEFAULT NULL,
  `tep_dinh_kem` varchar(255) DEFAULT NULL,
  `da_doc` tinyint(1) NOT NULL DEFAULT 0,
  `da_doc_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yeu_cau_lien_he`
--

CREATE TABLE `yeu_cau_lien_he` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nhan_vien_phu_trach_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `nguon_lien_he` varchar(255) NOT NULL DEFAULT 'website',
  `muc_do_quan_tam` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'moi',
  `ghi_chu_admin` text DEFAULT NULL,
  `thoi_diem_lien_he` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `yeu_cau_lien_he`
--

INSERT INTO `yeu_cau_lien_he` (`id`, `khach_hang_id`, `bat_dong_san_id`, `nhan_vien_phu_trach_id`, `ho_ten`, `so_dien_thoai`, `email`, `noi_dung`, `nguon_lien_he`, `muc_do_quan_tam`, `trang_thai`, `ghi_chu_admin`, `thoi_diem_lien_he`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 4, 'Nguyễn Văn An', '0971111111', 'an.nv@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'website', 'cao', 'moi', NULL, '2026-03-31 12:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(2, 2, 2, 5, 'Trần Thị Bình', '0972222222', 'binh.tt@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'hotline', 'trung_binh', 'da_lien_he', NULL, '2026-03-18 14:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(3, 3, 3, 6, 'Lê Minh Cường', '0973333333', 'cuong.lm@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'chat', 'thap', 'dang_tu_van', NULL, '2026-03-13 20:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(4, 4, 4, 4, 'Phạm Thu Hà', '0974444444', 'ha.pt@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'form_bds', 'cao', 'da_chot', NULL, '2026-04-03 03:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(5, 5, 5, 5, 'Hoàng Văn Đức', '0975555555', 'duc.hv@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'website', 'trung_binh', 'moi', NULL, '2026-03-31 12:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(6, 6, 6, 6, 'Vũ Thị Hoa', '0976666666', 'hoa.vt@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'hotline', 'thap', 'da_lien_he', NULL, '2026-04-03 19:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(7, 7, 7, 4, 'Đặng Quốc Hùng', '0977777777', 'hung.dq@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'chat', 'cao', 'dang_tu_van', NULL, '2026-04-01 08:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(8, 8, 8, 5, 'Ngô Thị Lan', '0978888888', 'lan.nt@gmail.com', 'Tôi muốn tìm hiểu thêm về BĐS này, xin vui lòng liên hệ lại.', 'form_bds', 'trung_binh', 'da_chot', NULL, '2026-03-06 18:39:22', '2026-04-04 11:39:22', '2026-04-04 11:39:22', NULL),
(9, 13, 2, NULL, 'Khách hàng', 'qqqqq', NULL, 'Tôi quan tâm đến BĐS: Căn hộ 3PN Vinhomes Smart City tầng cao', 'form_bds', 'chua_ro', 'moi', NULL, '2026-04-06 09:10:16', '2026-04-06 09:10:16', '2026-04-06 09:10:16', NULL),
(10, 13, 2, NULL, 'Khách hàng', '0987654', NULL, 'Tôi quan tâm đến BĐS: Căn hộ 3PN Vinhomes Smart City tầng cao', 'form_bds', 'chua_ro', 'moi', NULL, '2026-04-06 09:10:19', '2026-04-06 09:10:19', '2026-04-06 09:10:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `yeu_thich`
--

CREATE TABLE `yeu_thich` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khach_hang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bat_dong_san_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `yeu_thich`
--

INSERT INTO `yeu_thich` (`id`, `khach_hang_id`, `bat_dong_san_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, 13, 14, NULL, '2026-04-05 01:49:58', '2026-04-05 01:49:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bai_viet_slug_unique` (`slug`),
  ADD KEY `bai_viet_nhan_vien_id_foreign` (`nhan_vien_id`),
  ADD KEY `bai_viet_loai_bai_viet_hien_thi_noi_bat_index` (`loai_bai_viet`,`hien_thi`,`noi_bat`),
  ADD KEY `bai_viet_thoi_diem_dang_index` (`thoi_diem_dang`);

--
-- Indexes for table `bat_dong_san`
--
ALTER TABLE `bat_dong_san`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bat_dong_san_slug_unique` (`slug`),
  ADD UNIQUE KEY `bat_dong_san_ma_bat_dong_san_unique` (`ma_bat_dong_san`),
  ADD KEY `bat_dong_san_chu_nha_id_foreign` (`chu_nha_id`),
  ADD KEY `bat_dong_san_du_an_id_trang_thai_index` (`du_an_id`,`trang_thai`),
  ADD KEY `bat_dong_san_nhan_vien_phu_trach_id_index` (`nhan_vien_phu_trach_id`),
  ADD KEY `bat_dong_san_loai_hinh_nhu_cau_trang_thai_index` (`loai_hinh`,`nhu_cau`,`trang_thai`),
  ADD KEY `bat_dong_san_gia_dien_tich_index` (`gia`,`dien_tich`),
  ADD KEY `bat_dong_san_gia_thue_dien_tich_index` (`gia_thue`,`dien_tich`),
  ADD KEY `bat_dong_san_noi_bat_hien_thi_thu_tu_hien_thi_index` (`noi_bat`,`hien_thi`,`thu_tu_hien_thi`);

--
-- Indexes for table `chu_nha`
--
ALTER TABLE `chu_nha`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chu_nha_so_dien_thoai_unique` (`so_dien_thoai`),
  ADD KEY `chu_nha_nhan_vien_phu_trach_id_foreign` (`nhan_vien_phu_trach_id`);

--
-- Indexes for table `dang_ky_nhan_tin`
--
ALTER TABLE `dang_ky_nhan_tin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dang_ky_nhan_tin_khach_hang_id_foreign` (`khach_hang_id`),
  ADD KEY `dang_ky_nhan_tin_du_an_id_foreign` (`du_an_id`),
  ADD KEY `dang_ky_nhan_tin_bat_dong_san_id_foreign` (`bat_dong_san_id`),
  ADD KEY `dang_ky_nhan_tin_khu_vuc_id_foreign` (`khu_vuc_id`);

--
-- Indexes for table `du_an`
--
ALTER TABLE `du_an`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `du_an_slug_unique` (`slug`),
  ADD KEY `du_an_khu_vuc_id_trang_thai_index` (`khu_vuc_id`,`trang_thai`),
  ADD KEY `du_an_hien_thi_noi_bat_thu_tu_hien_thi_index` (`hien_thi`,`noi_bat`,`thu_tu_hien_thi`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ghi_chu_khach_hang`
--
ALTER TABLE `ghi_chu_khach_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ghi_chu_khach_hang_bat_dong_san_id_foreign` (`bat_dong_san_id`),
  ADD KEY `ghi_chu_khach_hang_lich_hen_id_foreign` (`lich_hen_id`),
  ADD KEY `ghi_chu_khach_hang_khach_hang_id_created_at_index` (`khach_hang_id`,`created_at`),
  ADD KEY `ghi_chu_khach_hang_nhan_vien_id_nhac_lai_at_index` (`nhan_vien_id`,`nhac_lai_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `khach_hang_so_dien_thoai_unique` (`so_dien_thoai`),
  ADD UNIQUE KEY `khach_hang_email_unique` (`email`),
  ADD KEY `khach_hang_nhan_vien_phu_trach_id_foreign` (`nhan_vien_phu_trach_id`),
  ADD KEY `khach_hang_muc_do_tiem_nang_nhan_vien_phu_trach_id_index` (`muc_do_tiem_nang`,`nhan_vien_phu_trach_id`),
  ADD KEY `khach_hang_nguon_khach_hang_kich_hoat_index` (`nguon_khach_hang`,`kich_hoat`);

--
-- Indexes for table `khu_vuc`
--
ALTER TABLE `khu_vuc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `khu_vuc_slug_unique` (`slug`),
  ADD KEY `khu_vuc_cap_khu_vuc_hien_thi_index` (`cap_khu_vuc`,`hien_thi`),
  ADD KEY `khu_vuc_khu_vuc_cha_id_thu_tu_hien_thi_index` (`khu_vuc_cha_id`,`thu_tu_hien_thi`);

--
-- Indexes for table `ky_gui`
--
ALTER TABLE `ky_gui`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ky_gui_khach_hang_id_foreign` (`khach_hang_id`),
  ADD KEY `ky_gui_nhu_cau_trang_thai_index` (`nhu_cau`,`trang_thai`),
  ADD KEY `ky_gui_nhan_vien_phu_trach_id_trang_thai_index` (`nhan_vien_phu_trach_id`,`trang_thai`);

--
-- Indexes for table `lich_hen`
--
ALTER TABLE `lich_hen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_hen_bat_dong_san_id_foreign` (`bat_dong_san_id`),
  ADD KEY `lich_hen_thoi_gian_hen_trang_thai_index` (`thoi_gian_hen`,`trang_thai`),
  ADD KEY `lich_hen_nhan_vien_sale_id_trang_thai_index` (`nhan_vien_sale_id`,`trang_thai`),
  ADD KEY `lich_hen_nhan_vien_nguon_hang_id_trang_thai_index` (`nhan_vien_nguon_hang_id`,`trang_thai`),
  ADD KEY `lich_hen_khach_hang_id_trang_thai_index` (`khach_hang_id`,`trang_thai`);

--
-- Indexes for table `lich_su_tim_kiem`
--
ALTER TABLE `lich_su_tim_kiem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_su_tim_kiem_khach_hang_id_thoi_diem_tim_kiem_index` (`khach_hang_id`,`thoi_diem_tim_kiem`),
  ADD KEY `lich_su_tim_kiem_session_id_thoi_diem_tim_kiem_index` (`session_id`,`thoi_diem_tim_kiem`);

--
-- Indexes for table `lich_su_xem_bds`
--
ALTER TABLE `lich_su_xem_bds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_su_xem_bds_khach_hang_id_created_at_index` (`khach_hang_id`,`created_at`),
  ADD KEY `lich_su_xem_bds_session_id_created_at_index` (`session_id`,`created_at`),
  ADD KEY `lich_su_xem_bds_bat_dong_san_id_index` (`bat_dong_san_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ngan_hang`
--
ALTER TABLE `ngan_hang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nhan_vien_email_unique` (`email`),
  ADD UNIQUE KEY `nhan_vien_so_dien_thoai_unique` (`so_dien_thoai`),
  ADD KEY `nhan_vien_vai_tro_kich_hoat_index` (`vai_tro`,`kich_hoat`);

--
-- Indexes for table `nhat_ky_email`
--
ALTER TABLE `nhat_ky_email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhat_ky_email_nhan_vien_id_foreign` (`nhan_vien_id`),
  ADD KEY `nhat_ky_email_loai_email_trang_thai_index` (`loai_email`,`trang_thai`),
  ADD KEY `nhat_ky_email_khach_hang_id_thoi_diem_gui_index` (`khach_hang_id`,`thoi_diem_gui`);

--
-- Indexes for table `phien_chat`
--
ALTER TABLE `phien_chat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phien_chat_session_id_unique` (`session_id`),
  ADD KEY `phien_chat_nhan_vien_phu_trach_id_foreign` (`nhan_vien_phu_trach_id`),
  ADD KEY `phien_chat_trang_thai_nhan_vien_phu_trach_id_index` (`trang_thai`,`nhan_vien_phu_trach_id`),
  ADD KEY `phien_chat_khach_hang_id_trang_thai_index` (`khach_hang_id`,`trang_thai`),
  ADD KEY `idx_phien_chat_session_status` (`session_id`,`trang_thai`),
  ADD KEY `idx_phien_chat_kh_status_updated` (`khach_hang_id`,`trang_thai`,`updated_at`);

--
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thong_bao_doi_tuong_nhan_doi_tuong_nhan_id_da_doc_at_index` (`doi_tuong_nhan`,`doi_tuong_nhan_id`,`da_doc_at`);

--
-- Indexes for table `tin_nhan_chat`
--
ALTER TABLE `tin_nhan_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tin_nhan_chat_khach_hang_id_foreign` (`khach_hang_id`),
  ADD KEY `tin_nhan_chat_phien_chat_id_created_at_index` (`phien_chat_id`,`created_at`),
  ADD KEY `idx_tin_nhan_chat_phien_id_id` (`phien_chat_id`,`id`),
  ADD KEY `tin_nhan_chat_nhan_vien_id_da_doc_index` (`nhan_vien_id`,`da_doc`);

--
-- Indexes for table `tin_nhan_noi_bo`
--
ALTER TABLE `tin_nhan_noi_bo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tin_nhan_noi_bo_nguoi_gui_id_nguoi_nhan_id_created_at_index` (`nguoi_gui_id`,`nguoi_nhan_id`,`created_at`),
  ADD KEY `tin_nhan_noi_bo_nguoi_nhan_id_da_doc_index` (`nguoi_nhan_id`,`da_doc`),
  ADD KEY `tin_nhan_noi_bo_lich_hen_id_index` (`lich_hen_id`),
  ADD KEY `tin_nhan_noi_bo_bat_dong_san_id_index` (`bat_dong_san_id`),
  ADD KEY `tin_nhan_noi_bo_du_an_id_index` (`du_an_id`),
  ADD KEY `tin_nhan_noi_bo_khach_hang_lq_id_index` (`khach_hang_lq_id`);

--
-- Indexes for table `yeu_cau_lien_he`
--
ALTER TABLE `yeu_cau_lien_he`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yeu_cau_lien_he_khach_hang_id_foreign` (`khach_hang_id`),
  ADD KEY `yeu_cau_lien_he_nhan_vien_phu_trach_id_foreign` (`nhan_vien_phu_trach_id`),
  ADD KEY `yeu_cau_lien_he_trang_thai_nhan_vien_phu_trach_id_index` (`trang_thai`,`nhan_vien_phu_trach_id`),
  ADD KEY `yeu_cau_lien_he_bat_dong_san_id_trang_thai_index` (`bat_dong_san_id`,`trang_thai`);

--
-- Indexes for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_yeu_thich_khach_bds` (`khach_hang_id`,`bat_dong_san_id`),
  ADD UNIQUE KEY `uniq_yeu_thich_session_bds` (`session_id`,`bat_dong_san_id`),
  ADD KEY `yeu_thich_bat_dong_san_id_foreign` (`bat_dong_san_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bai_viet`
--
ALTER TABLE `bai_viet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bat_dong_san`
--
ALTER TABLE `bat_dong_san`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `chu_nha`
--
ALTER TABLE `chu_nha`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dang_ky_nhan_tin`
--
ALTER TABLE `dang_ky_nhan_tin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `du_an`
--
ALTER TABLE `du_an`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ghi_chu_khach_hang`
--
ALTER TABLE `ghi_chu_khach_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `khu_vuc`
--
ALTER TABLE `khu_vuc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ky_gui`
--
ALTER TABLE `ky_gui`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lich_hen`
--
ALTER TABLE `lich_hen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lich_su_tim_kiem`
--
ALTER TABLE `lich_su_tim_kiem`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `lich_su_xem_bds`
--
ALTER TABLE `lich_su_xem_bds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `ngan_hang`
--
ALTER TABLE `ngan_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nhat_ky_email`
--
ALTER TABLE `nhat_ky_email`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phien_chat`
--
ALTER TABLE `phien_chat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tin_nhan_chat`
--
ALTER TABLE `tin_nhan_chat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tin_nhan_noi_bo`
--
ALTER TABLE `tin_nhan_noi_bo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yeu_cau_lien_he`
--
ALTER TABLE `yeu_cau_lien_he`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD CONSTRAINT `bai_viet_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bat_dong_san`
--
ALTER TABLE `bat_dong_san`
  ADD CONSTRAINT `bat_dong_san_chu_nha_id_foreign` FOREIGN KEY (`chu_nha_id`) REFERENCES `chu_nha` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bat_dong_san_du_an_id_foreign` FOREIGN KEY (`du_an_id`) REFERENCES `du_an` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bat_dong_san_nhan_vien_phu_trach_id_foreign` FOREIGN KEY (`nhan_vien_phu_trach_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chu_nha`
--
ALTER TABLE `chu_nha`
  ADD CONSTRAINT `chu_nha_nhan_vien_phu_trach_id_foreign` FOREIGN KEY (`nhan_vien_phu_trach_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dang_ky_nhan_tin`
--
ALTER TABLE `dang_ky_nhan_tin`
  ADD CONSTRAINT `dang_ky_nhan_tin_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dang_ky_nhan_tin_du_an_id_foreign` FOREIGN KEY (`du_an_id`) REFERENCES `du_an` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dang_ky_nhan_tin_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dang_ky_nhan_tin_khu_vuc_id_foreign` FOREIGN KEY (`khu_vuc_id`) REFERENCES `khu_vuc` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `du_an`
--
ALTER TABLE `du_an`
  ADD CONSTRAINT `du_an_khu_vuc_id_foreign` FOREIGN KEY (`khu_vuc_id`) REFERENCES `khu_vuc` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ghi_chu_khach_hang`
--
ALTER TABLE `ghi_chu_khach_hang`
  ADD CONSTRAINT `ghi_chu_khach_hang_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ghi_chu_khach_hang_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ghi_chu_khach_hang_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hen` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ghi_chu_khach_hang_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_vien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD CONSTRAINT `khach_hang_nhan_vien_phu_trach_id_foreign` FOREIGN KEY (`nhan_vien_phu_trach_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `khu_vuc`
--
ALTER TABLE `khu_vuc`
  ADD CONSTRAINT `khu_vuc_khu_vuc_cha_id_foreign` FOREIGN KEY (`khu_vuc_cha_id`) REFERENCES `khu_vuc` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ky_gui`
--
ALTER TABLE `ky_gui`
  ADD CONSTRAINT `ky_gui_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ky_gui_nhan_vien_phu_trach_id_foreign` FOREIGN KEY (`nhan_vien_phu_trach_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lich_hen`
--
ALTER TABLE `lich_hen`
  ADD CONSTRAINT `lich_hen_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_hen_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lich_hen_nhan_vien_nguon_hang_id_foreign` FOREIGN KEY (`nhan_vien_nguon_hang_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lich_hen_nhan_vien_sale_id_foreign` FOREIGN KEY (`nhan_vien_sale_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lich_su_tim_kiem`
--
ALTER TABLE `lich_su_tim_kiem`
  ADD CONSTRAINT `lich_su_tim_kiem_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lich_su_xem_bds`
--
ALTER TABLE `lich_su_xem_bds`
  ADD CONSTRAINT `lich_su_xem_bds_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhat_ky_email`
--
ALTER TABLE `nhat_ky_email`
  ADD CONSTRAINT `nhat_ky_email_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `nhat_ky_email_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `phien_chat`
--
ALTER TABLE `phien_chat`
  ADD CONSTRAINT `phien_chat_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phien_chat_nhan_vien_phu_trach_id_foreign` FOREIGN KEY (`nhan_vien_phu_trach_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tin_nhan_chat`
--
ALTER TABLE `tin_nhan_chat`
  ADD CONSTRAINT `tin_nhan_chat_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tin_nhan_chat_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tin_nhan_chat_phien_chat_id_foreign` FOREIGN KEY (`phien_chat_id`) REFERENCES `phien_chat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tin_nhan_noi_bo`
--
ALTER TABLE `tin_nhan_noi_bo`
  ADD CONSTRAINT `tin_nhan_noi_bo_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tin_nhan_noi_bo_du_an_id_foreign` FOREIGN KEY (`du_an_id`) REFERENCES `du_an` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tin_nhan_noi_bo_khach_hang_lq_id_foreign` FOREIGN KEY (`khach_hang_lq_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tin_nhan_noi_bo_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hen` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tin_nhan_noi_bo_nguoi_gui_id_foreign` FOREIGN KEY (`nguoi_gui_id`) REFERENCES `nhan_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tin_nhan_noi_bo_nguoi_nhan_id_foreign` FOREIGN KEY (`nguoi_nhan_id`) REFERENCES `nhan_vien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `yeu_cau_lien_he`
--
ALTER TABLE `yeu_cau_lien_he`
  ADD CONSTRAINT `yeu_cau_lien_he_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `yeu_cau_lien_he_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `yeu_cau_lien_he_nhan_vien_phu_trach_id_foreign` FOREIGN KEY (`nhan_vien_phu_trach_id`) REFERENCES `nhan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `yeu_thich`
--
ALTER TABLE `yeu_thich`
  ADD CONSTRAINT `yeu_thich_bat_dong_san_id_foreign` FOREIGN KEY (`bat_dong_san_id`) REFERENCES `bat_dong_san` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yeu_thich_khach_hang_id_foreign` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
