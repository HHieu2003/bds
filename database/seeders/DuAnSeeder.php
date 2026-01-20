<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class DuAnSeeder extends Seeder
{
    public function run()
    {
        // 1. Tắt kiểm tra khóa ngoại để xóa dữ liệu cũ sạch sẽ
        Schema::disableForeignKeyConstraints();
        DB::table('bat_dong_san')->truncate();
        DB::table('du_an')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Danh sách dữ liệu gốc của bạn (Giữ nguyên)
        $projects = [
            [
                'ten_du_an' => 'GOLDMARK CITY',
                'don_vi_thi_cong' => 'Delta, Conteccon',
                'chu_dau_tu' => 'Công ty TNHH Thương mại – Quảng cáo – Xây dựng- Địa ốc Việt Hân',
                'dia_chi' => '136 Hồ Tùng Mậu',
                'tong_quan' => '- Dự án gồm 9 tòa cao 40 tầng, Khu R gồm R1, R2, R3, R4; Khu S gồm S1, S2, S3, S4 và tòa Diamond. Mỗi khu gồm 2 tầng hầm thông các tòa trong khu với nhau.
- Khu R bàn giao cuối 2017, khu S bàn giao 2019. Tòa S2 bàn giao muộn nhất năm 2020. Khu S có chất lượng xây dựng tốt hơn Khu R. Tòa R2 có chất lượng kém nhất.
- Khu R về ở trước, diện tích hầm nhỏ nên hiếm slot oto. Khu S về ở sau, hầm rộng (thông 5 tòa và trường học Newton nên slot oto rất thoải mái. Mỗi căn hộ được đăng ký 2 slot oto.
- R1, R4, S1, S2 gồm các căn có diện tích lớn. R1, S1, S2 mật độ 11 căn/sàn. R4 mật độ 16 căn/sàn, có 2 sảnh R4A và R4B. Tòa R2, R3, S3, S4 gồm các căn có diện tích nhỏ, mật độ 17 căn/sàn.',
                'phi_dich_vu' => '8k/m2, Xe máy 60k, Oto thứ nhất: 1,1 tr, oto 2: 1,36 tr',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS ,4PN-2VS'
            ],
            [
                'ten_du_an' => 'THE ZEI',
                'don_vi_thi_cong' => 'Delta',
                'chu_dau_tu' => 'HD Mon Holdings',
                'dia_chi' => 'Số 8 Lê Đức Thọ',
                'tong_quan' => '- Dự án thiết kế hình chữ H, chia làm 2 tòa A và B gồm 42 tầng nổi và 4 tầng hầm.
Trong đó có 4 tầng khôi đế: Tầng 1,2 là sảnh cư dân, TTTM; Tầng 3: Rạp chiếu phim; Tầng 4: Bể bơi 4 mùa 700m2...
- Khu vui chơi trẻ em xung quanh tầng 1 của tòa nhà, phòng thiền và bộ tứ vườn xanh tại tầng 42.
- Mật độ 26 căn/14 thang máy/tầng. Tiêu chuẩn bàn giao rất cao.',
                'phi_dich_vu' => '12k/m2, Oto 1,5 tr, Xe máy 100k',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS, Duplex'
            ],
            [
                'ten_du_an' => 'IRIS GARDEN',
                'don_vi_thi_cong' => 'ECOBA Việt Nam',
                'chu_dau_tu' => 'VimeFulland',
                'dia_chi' => '30 Trần Hữu Dực',
                'tong_quan' => '- Dự án gồm 5 tòa; CT1A, CT1B cao 29 tầng và tòa CT2, CT3, CT4 cao 25 tầng. 2 tầng hầm thông nhau.
- Bàn giao quý 3, 2019.
- 3 Tòa CT1A, CT1B và CT2 có thiết kế giống nhau, mật độ 8 căn/sàn.
- 2 Tòa CT3 và CT4 thiết kế giống nhau, mật độ 12 căn/sàn.
- Căn 2PN ở CT1A, CT1B, CT2 có ban công và không có lô gia phơi đồ. Căn 2PN ở CT3, CT4 có ban công và có lô gia phơi đồ.',
                'phi_dich_vu' => '10k/m2, Oto 1,250 tr',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'VINHOMES GARDENIA',
                'don_vi_thi_cong' => 'Conteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => '18 Hàm Nghi',
                'tong_quan' => '- Dự án gồm 3 tòa; A1,A2 cao 37 tầng và tòa A3 cao 39 tầng. 2 tầng hầm thông nhau.
- Bàn giao quý 4, 2018.
- 2 Tòa A1 và A2 thiết kế chữ U, mật độ 19 căn/sàn. Tòa A3 thiết kế hình chữ I mật độ 21 căn/sàn.
- Thang máy đi lại dễ dàng, không cần thẻ.',
                'phi_dich_vu' => '15k/m2, phí gửi oto 1,45 tr/ tháng, xe máy 60k',
                'dang_can_ho' => '1PN-1VS, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-2VS, 4PN-3VS, Duplex'
            ],
            [
                'ten_du_an' => 'Vinhomes Green Bay',
                'don_vi_thi_cong' => 'HÒA BÌNH, CONTECCONS',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Số 7 Đại lộ Thăng Long, Mễ Trì, Nam Từ Liêm',
                'tong_quan' => 'Bàn giao: T3 năm 2019. Gồm 3 tòa G1, G2, G3. Các tòa đều có 3 tầng hầm thông nhau.
- Tòa G1: 34 tầng, hình chữ L, 22 căn/sàn.
- Tòa G2: 38 tầng, hình chữ L, 20 căn/sàn.
- Tòa G3: 40 tầng, hình chữ U. Từ tầng 3-34 là sổ 50 năm (Officetel), tầng 35-40 là sổ lâu dài.',
                'phi_dich_vu' => '16.5 k/m2 (đã bao gồm dịch vụ), Xe máy 100k, Oto 1.450k',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-2VS, 4PN-3VS'
            ],
            [
                'ten_du_an' => 'Mỹ Đình Pearl',
                'don_vi_thi_cong' => 'Phục Hưng Holdings',
                'chu_dau_tu' => 'SSG GROUP',
                'dia_chi' => 'Số 1 Châu Văn Liêm, Mễ Trì, Nam Từ Liêm',
                'tong_quan' => '- 2 tháp cao 38 tầng, 2 tầng hầm đỗ xe thông nhau.
- Tòa Pearl 1 & Pearl 2 thiết kế hình chữ L.
- Từ tầng 3~32 là căn hộ thường. Tầng 33-36 có Duplex. Tầng 37-38 có Penthouse.',
                'phi_dich_vu' => '13k/tháng (đã VAT), Xe máy 100k, Oto 1.350k',
                'dang_can_ho' => '1PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS, Duplex, Penhouse'
            ],
            [
                'ten_du_an' => 'The Matrix One',
                'don_vi_thi_cong' => 'Conteccons',
                'chu_dau_tu' => 'MIK Group',
                'dia_chi' => 'Đường Lê Quang Đạo, Mễ Trì, Nam Từ Liêm',
                'tong_quan' => 'Dự án gồm 2 tòa tháp cao 44 tầng, 3 tầng hầm.
- Tầng 4 bể bơi, tầng 23 tiện ích Gym/Yoga, tầng 43 vườn nhật.
- Tòa A & B mật độ 10 căn/sàn, 6 thang thường + 1 thang hàng.',
                'phi_dich_vu' => 'Miễn phí (tùy chính sách), Xe máy 50k, Oto 1.45 tr',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS, Dual key, Penhouse'
            ],
            [
                'ten_du_an' => 'THE EMERALD (CT8)',
                'don_vi_thi_cong' => 'ECOBA Việt Nam và Coteccons',
                'chu_dau_tu' => 'VimeFulland',
                'dia_chi' => 'Khu đất CT8, KĐT Mỹ Đình (The Manor)',
                'tong_quan' => 'Dự án gồm 4 tòa E1, E2, E3, E4 cao 30 tầng, 3 tầng hầm. Bàn giao quý 1/2019.
- Tòa E1 và E2 thông sàn nhau: 12 căn/tòa, 7 thang máy.
- Tòa E3 và E4 thông sàn nhau: 12 căn/tòa, 7 thang máy.',
                'phi_dich_vu' => '11k/m2, Xe máy 100k, Oto 1.35tr',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS, 4PN-2VS'
            ],
            [
                'ten_du_an' => 'VINHOMES SKYLAKE',
                'don_vi_thi_cong' => 'Conteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Ngã tư Phạm Hùng - Dương Đình Nghệ',
                'tong_quan' => '- Vị trí cạnh công viên hồ điều hòa Cầu Giấy (19ha).
- Gồm 3 tòa S1, S2, S3 và 3 tầng hầm.
- Có Vincom và bể bơi ngoài trời giữa S1, S2. Tòa S3 có bể bơi bốn mùa.
- Có Vinschool mầm non.',
                'phi_dich_vu' => '16k/m2, Ô tô 1450k, Xe máy 60k',
                'dang_can_ho' => '1PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS, Penhouse'
            ],
            [
                'ten_du_an' => 'VINHOMES WEST POINT',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Ngã 3 Phạm Hùng - Đỗ Đức Dục',
                'tong_quan' => 'Tổ hợp căn hộ cao cấp & Officetel.
- Chung cư: Tòa West 2 (tầng 5 trở lên) & West 3 (Sổ lâu dài).
- Officetel: Tòa West 1 và tầng 2-5A Tòa West 2 (50 năm).
- 3 tầng hầm thông nhau.',
                'phi_dich_vu' => '17k/m2, Ô tô 1450k, Xe máy 60k',
                'dang_can_ho' => 'STUDIO, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS'
            ],
            [
                'ten_du_an' => 'Florence Mỹ Đình',
                'don_vi_thi_cong' => 'Phục Hưng Holdings',
                'chu_dau_tu' => 'Phục Hưng Holdings',
                'dia_chi' => '28 Trần Hữu Dực',
                'tong_quan' => '- Gồm 2 tòa R1 và R2 cao 25 tầng, 3 tầng hầm thông nhau.
- Bàn giao quý 4, 2019.
- Thiết kế đối xứng, mật độ 24 căn/sàn, 8 thang máy.',
                'phi_dich_vu' => '12k/m2, Oto 1,3 tr, xe máy 100k',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS, 4PN-2VS'
            ],
            [
                'ten_du_an' => 'HD Mon',
                'don_vi_thi_cong' => 'Delta',
                'chu_dau_tu' => 'HD Mon Holdings',
                'dia_chi' => 'Ngã tư Hàm Nghi - Nguyễn Cơ Thạch',
                'tong_quan' => '- Gồm 2 tòa CT1A và CT1B cao 30 tầng, 2 tầng hầm thông nhau.
- Bàn giao 2018.
- Mật độ 16 căn/sàn, chia 2 sảnh (chẵn/lẻ).
- Diện tích căn nhỏ (52-86m2). Trần thấp, hành lang nhỏ.',
                'phi_dich_vu' => '8k/m2, Oto 1,2 tr',
                'dang_can_ho' => '2PN-1VS, 2PN-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'GOLDEN PALACE',
                'don_vi_thi_cong' => 'Delta',
                'chu_dau_tu' => 'Mai Linh',
                'dia_chi' => 'Số 99 Mễ Trì',
                'tong_quan' => '- Gồm 3 tòa A, B, C cao 30 tầng, 4 tầng hầm.
- Hầm B1 là TTTM và rạp chiếu phim. 3 hầm còn lại để xe (dư giả slot oto).
- Lưu ý số tầng/căn thực tế khác với trên thang máy (Tầng 13 đổi thành 12A).',
                'phi_dich_vu' => '6k/m2, Xe máy 60k, Oto 1 tr',
                'dang_can_ho' => '2PN-2VS, 3PN-2VS, 4PN-2VS'
            ],
            [
                'ten_du_an' => 'D\'CAPITAL',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Tân Hoàng Minh Group',
                'dia_chi' => '119 Trần Duy Hưng',
                'tong_quan' => 'Khu phức hợp 6 tòa tháp (C1, C2, C3, C5, C6, C7) cao 39-45 tầng.
- Mô hình: Căn hộ cao cấp, Officetel, Shophouse.',
                'phi_dich_vu' => '19,5k/m2, Ô tô 1,2-1,5 tr, Xe máy 100k',
                'dang_can_ho' => 'STUDIO, 2PN-1VS, 2PN-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'Vinhomes Smart City - SAPPHIRE 1',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Giao lộ Đại lộ Thăng Long & Đường sắt đô thị số 6',
                'tong_quan' => '- Gồm 5 tòa: S101, S102, S103, S105, S106.
- Bàn giao Quý II/2020.
- Tiêu chuẩn bàn giao cơ bản (Sàn gạch, không tủ bếp/quần áo).',
                'phi_dich_vu' => '8,8k/m2',
                'dang_can_ho' => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'Vinhomes Smart City - SAPPHIRE 2',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Giữa Sapphire 1 và The Metrolines',
                'tong_quan' => '- Gồm 4 tòa: S201, S202, S203, S205.
- Bàn giao Quý II/2020.
- Tiêu chuẩn bàn giao cơ bản.',
                'phi_dich_vu' => '8,8k/m2',
                'dang_can_ho' => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'Vinhomes Smart City - SAPPHIRE 3',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Cạnh Vinschool và The Diamond',
                'tong_quan' => '- Gồm 3 tòa: S301, S302, S303 cao 38 tầng.
- Bàn giao Tháng 3/2021.
- Mật độ 19 căn/sàn.',
                'phi_dich_vu' => '8,8k/m2',
                'dang_can_ho' => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'Vinhomes Smart City - SAPPHIRE 4',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Kết nối Đại lộ Thăng Long & Lê Trọng Tấn',
                'tong_quan' => '- Vị trí đẹp, đối diện công viên Sportia Park và Central Park.
- Gồm 3 tòa: S401, S402, S403.
- Bàn giao Tháng 12/2021.',
                'phi_dich_vu' => '8,8k/m2',
                'dang_can_ho' => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'THE MIAMI',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Thái Sơn (Vinhomes)',
                'dia_chi' => 'Chân cầu vượt số 2, Vinhomes Smart City',
                'tong_quan' => '- Gồm 5 tòa: GS1, GS2, GS3, GS5, GS6.
- Bàn giao: GS2 (Q3/2022), GS1-GS3 (Q2/2023), GS5-GS6 (Q1/2026).
- Tiêu chuẩn Sapphire+.',
                'phi_dich_vu' => 'Đang cập nhật',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'THE SAKURA',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Samty Việt Nam',
                'dia_chi' => 'Trung tâm The Metroline, Vinhomes Smart City',
                'tong_quan' => '- Gồm 4 tòa: SA1, SA2, SA3, SA5 (có vườn Nhật tầng 39).
- Bàn giao: SA2 (T8/2022), SA3 (T12/2022), SA5 (T10/2024), SA1 (T7/2025).
- Tiêu chuẩn Sapphire+ (có điều hòa, khóa vân tay).',
                'phi_dich_vu' => '17k/m2 (đã gồm tiện ích nội khu)',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'THE TONKIN',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Vinhomes',
                'dia_chi' => 'Vùng lõi Vinhomes Smart City',
                'tong_quan' => '- Gồm 2 tòa TK1, TK2.
- Bàn giao: TK1 (T10/2022), TK2 (T6/2023).
- Tiêu chuẩn Ruby (Sàn gỗ, điều hòa âm trần, tủ bếp/quần áo).',
                'phi_dich_vu' => '18k/m2 (đã gồm tiện ích nội khu)',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'THE CANOPY RESIDENCES',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Newlife (GIC)',
                'dia_chi' => 'Vùng lõi Vinhomes Smart City',
                'tong_quan' => '- Gồm 3 tòa TC1, TC2, TC3.
- Bàn giao: Tháng 7/2025.
- Tiêu chuẩn Ruby (Sàn gỗ, điều hòa âm trần, tủ bếp/quần áo).',
                'phi_dich_vu' => 'Đang cập nhật',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'IMPERIA SMART CITY',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'HBI (MIK GROUP)',
                'dia_chi' => 'Tiếp giáp công viên Sportia Park & Central Park',
                'tong_quan' => '- Gồm 5 tòa: I1, I2, I3, I4, I5.
- Bàn giao: I2-I5 (Q4/2023), I1 (Q2/2024).
- Tiêu chuẩn bàn giao liền tường (Sàn gỗ, điều hòa, thiết bị vệ sinh. I1, I2 có bếp).',
                'phi_dich_vu' => 'Đang cập nhật',
                'dang_can_ho' => 'STUDIO, 1PN+1-2VS, 2PN+1-1VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'THE SOLA PARK',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'HBI (MIK GROUP)',
                'dia_chi' => 'Cạnh Đại lộ Thăng Long và Sapphire 1, 2',
                'tong_quan' => '- Gồm 5 tòa: G1, G2, G3 (35 tầng), G5, G6 (39 tầng).
- Bàn giao: G1-G3 (Q1/2027), G5-G6 (Q3/2027).
- Tiêu chuẩn Sapphire liền tường.',
                'phi_dich_vu' => 'Đang cập nhật',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'MASTERI WEST HEIGHTS',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Masterise Homes',
                'dia_chi' => 'Đối diện công viên trung tâm Central Park',
                'tong_quan' => '- Gồm 4 tòa: A, B, C, D (Có bể bơi tầng thượng).
- Bàn giao: 2023 - 2024.
- Tiêu chuẩn cao cấp: Sàn gỗ, điều hòa âm trần, tủ bếp, tủ quần áo, thiết bị vệ sinh xịn.',
                'phi_dich_vu' => '24k/m2',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS'
            ],
            [
                'ten_du_an' => 'LUMIERE EVERGREEN',
                'don_vi_thi_cong' => 'Coteccons',
                'chu_dau_tu' => 'Masterise Homes',
                'dia_chi' => 'Đại lộ Ánh Sáng, Vinhomes Smart City',
                'tong_quan' => '- Gồm 3 tòa: A1, A2, A3 cao 39 tầng.
- Bàn giao: Tháng 5/2026.
- Tiêu chuẩn bàn giao cao cấp nhất.',
                'phi_dich_vu' => 'Đang cập nhật',
                'dang_can_ho' => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS, 4PN'
            ],
        ];

        foreach ($projects as $da) {
            // A. Tạo Mô tả HTML
            $moTaHTML = "<strong>ĐƠN VỊ THI CÔNG:</strong> " . ($da['don_vi_thi_cong'] ?? 'Đang cập nhật') . "<br>" .
                "<strong>TỔNG QUAN:</strong><br>" . nl2br($da['tong_quan']) . "<br><br>" .
                "<strong>PHÍ DỊCH VỤ:</strong><br>" . ($da['phi_dich_vu'] ?? 'Đang cập nhật') . "<br><br>" .
                "<strong>CÁC DẠNG CĂN HỘ:</strong><br>" . nl2br($da['dang_can_ho']);

            // B. Tạo Dự án (Chỉ tạo 1 lần duy nhất cho mỗi item)
            $duAnId = DB::table('du_an')->insertGetId([
                'ten_du_an' => $da['ten_du_an'],
                'slug' => Str::slug($da['ten_du_an']) . '-' . rand(100, 999),
                'dia_chi' => $da['dia_chi'],
                'chu_dau_tu' => $da['chu_dau_tu'],
                'mo_ta_ngan' => Str::limit($da['tong_quan'], 150),
                'noi_dung_chi_tiet' => $moTaHTML,
                'trang_thai' => 'dang_mo_ban',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // C. Tạo các Căn hộ thuộc dự án này
            $types = explode(',', $da['dang_can_ho']);

            foreach ($types as $type) {
                $type = trim($type);
                if (empty($type)) continue;

                // Sinh ra 2 căn cho mỗi loại căn hộ
                for ($i = 1; $i <= 2; $i++) {

                    // Logic giả lập thông số
                    $soPhongNgu = 1;
                    $dienTich = 40;
                    $giaTy = 1.5;

                    if (str_contains($type, '2PN')) {
                        $soPhongNgu = 2;
                        $dienTich = rand(65, 80);
                        $giaTy = rand(25, 40) / 10;
                    } elseif (str_contains($type, '3PN')) {
                        $soPhongNgu = 3;
                        $dienTich = rand(90, 110);
                        $giaTy = rand(45, 60) / 10;
                    } elseif (str_contains($type, '4PN') || stripos($type, 'Duplex') !== false) {
                        $soPhongNgu = 4;
                        $dienTich = rand(120, 200);
                        $giaTy = rand(70, 150) / 10;
                    }

                    $tenToa = ['S1', 'S2', 'R1', 'R2', 'CT1', 'CT2'][rand(0, 5)];

                    DB::table('bat_dong_san')->insert([
                        'du_an_id' => $duAnId,
                        'ma_can' => $tenToa . '.' . rand(10, 30) . rand(10, 99),
                        'toa' => $tenToa,
                        'tieu_de' => 'Căn hộ ' . $type . ' ' . $da['ten_du_an'] . ' tầng trung',
                        'slug' => Str::slug($da['ten_du_an'] . '-' . $type . '-' . uniqid()), // Slug này đảm bảo không trùng
                        'gia' => $giaTy,
                        'dien_tich' => $dienTich,
                        'so_phong_ngu' => $soPhongNgu,
                        'so_phong_tam' => 2,
                        'huong_cua' => ['Đông Nam', 'Tây Bắc', 'Đông', 'Tây'][rand(0, 3)],
                        'huong_ban_cong' => ['Đông Nam', 'Tây Bắc', 'Đông', 'Tây'][rand(0, 3)],
                        'loai_hinh' => 'can_ho',
                        'nhu_cau' => 'ban',
                        'mo_ta' => 'Căn hộ view thoáng, nội thất cơ bản.',
                        'is_hot' => rand(0, 1),
                        'trang_thai' => 'con_hang',
                        'luot_xem' => rand(10, 500),
                        'user_id' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
