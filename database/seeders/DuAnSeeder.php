<?php

namespace Database\Seeders;

use App\Models\DuAn;
use App\Models\KhuVuc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DuAnSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách khu vực và map theo slug để gán ID chính xác
        $khuVuc = KhuVuc::where('cap_khu_vuc', 'quan_huyen')->get()->keyBy('slug');

        $duAn = [

            [
                'ten'        => 'VINHOMES GARDENIA',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => '18 Hàm Nghi',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Conteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "- Dự án gồm 3 tòa; A1,A2 cao 37 tầng và tòa A3 cao 39 tầng. Dự án có 2 tầng hầm thông các tòa với nhau.\n- Bàn giao quý 4, 2018\n- Phí dịch vụ 15k/m2, phí gửi oto 1,45 tr/ tháng\n- 2Tòa A1 và A2 có thiết kế chữ U giống nhau, mật độ 19 căn/sàn, 8 thang máy, 2 thang hàng và 2 thang bộ thoát hiểm.\n- Tòa A3 thiết kế hình chữ I mật độ 21 căn/sàn, 12 thang máy, 3 thang hàng, 2 thang bộ thoát hiểm.\n- Dự án gồm loại căn 1PN1VS (52m2) 2PN1VS (52m2), căn 2PN2VS (74-81m2), 3PN2VS (97-118m2), 4PN2VS (122m2) và căn 4PN3VS (137m2).\n- Thang máy đi lại dễ dàng, không cần thẻ",
                'phi_dv'     => '16k/m2, oto 1450k/m2, xe máy 60k',
                'can_ho'     => '1PN-1VS, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-2VS, 4PN-3VS, Duplex',
            ],
            [
                'ten'        => 'Vinhomes Green Bay',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Số 7 Đại lộ Thăng Long, phường Mễ Trì, quận Nam Từ Liêm, Hà Nội',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'HÒA BÌNH, CONTECCONS',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "Khởi công: Quý 4 năm 2016. Bàn giao: T3 năm 2019.\nKhu căn hộ chung cư bao gồm 3 tòa căn hộ G1, G2 và G3:\n- Các tòa đều có 3 tầng hầm thông nhau. Hầm B1 gửi xe máy, Hầm B2,B3 gửi oto\n- Tòa G1: 34 tầng,thiết kế theo hình chữ L,9 thang thường+3 thang hàng, 22 căn 1 mặt sàn. Tầng 1+2 dịch vụ, từ 3-34 để ở, sổ hồng lâu dài\n- Tòa G2: 38 tầng,thiết kế chữ L, 9 thang thường+3 thang hàng, 20 căn/sàn. Sổ hồng lâu dài\n- Tòa G3: 40 tầng, thiết kế chữ U. 15 thang thường+2 thang hàng. Chia 2 dạng sổ: Tầng 3-34 sổ 50 năm chờ làm sổ (ở và VP). Tầng 35-40 sổ hồng lâu dài.",
                'phi_dv'     => '16.5 k/m2 (đã bao gồm các dịch vụ). Phí gửi xe máy 100.000VND/Tháng. Phí gửi oto 1.450.000VND/Tháng',
                'can_ho'     => 'STUDIO, 1PN-1VS, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-2VS, 4PN-3VS',
            ],
            [
                'ten'        => 'Mỹ Đình Pearl',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Số 1 Châu Văn Liêm, phường mễ Trì, Quận Nam Từ Liêm, Hà Nội',
                'chu_dt'     => 'SSG GROUP',
                'thi_cong'   => 'FECON (móng), Phục Hưng Holdings (thân)',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "- Tháp căn hộ : 2 tháp cao 38 tầng, Tổng số căn hộ :951 căn\n- 2 tầng hầm đổ xe thông nhau\n- Tòa Pearl 1: 38 tầng, chữ L, 6 thang thường + 1 thang hàng. Tầng 3~32: 15 căn/sàn. Tầng 33+34: 2 căn Duplex, 12 căn/sàn. Tầng 35+36: 2 căn Duplex, 10 căn/sàn. Tầng 37+38: 4 căn Penhouse.\n- Tòa Pearl 2: 38 tầng, chữ L, 6 thang thường + 1 thang hàng. Tầng 3~32: 14 căn/sàn. Các tầng trên tương tự Pearl 1.",
                'phi_dv'     => '13.000VND/Tháng đã VAT. Phí gửi xe máy 100.000VND/Tháng, Oto 1.350.000VND/Tháng',
                'can_ho'     => '1PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS, Duplex, Penhouse',
            ],
            [
                'ten'        => 'The Matrix One',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Đường Lê Quang Đạo, phường Mễ Trì, quận Nam Từ Liêm, Thành phố Hà Nội.',
                'chu_dt'     => 'Công ty TNHH Tập đoàn MIK Group Việt Nam',
                'thi_cong'   => 'Tổng công ty xây dựng Conteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "Dự án bao gồm 2 tòa tháp cao 44 tầng,3 tầng hầm,740 căn hộ,20 shophouse, 2 Penhouse\nKhởi công quý 1-2019,bàn giao T8-2022\nTầng 4 bể bơi, tầng 23 tiện ích :Gym,Yoga,,thư viện...., tầng 43 vườn nhật\nTòa A :10 căn 1 sàn, 6 thang thường, 1 thang hàng.\nTòa B: 10 căn 1 sàn, 6 thang thường, 1 thang hàng.\nHiện tại đang miễn phí dịch vụ tùy thời điểm mua.",
                'phi_dv'     => 'Miễn phí. Xe máy 50.000VND/Tháng. Oto 1.45 tr/tháng',
                'can_ho'     => '2PN-2VS, 3PN-2VS, Dual key, Penhouse',
            ],

            [
                'ten'        => 'VINHOMES SKYLAKE',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Ngã tư Phạm Hùng - Dương Đình Nghệ, Nam Từ Liêm, HN',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Conteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "- Thừa hưởng trọn vẹn không gian và tiện ích của công viên hồ điều hòa Cầu Giấy 19 ha.\n- Gồm 3 tòa S1, S2 và S3 và 3 tầng hầm. TTTM Vincom và bể bơi ngoài trời giữa hai tòa S1, S2. Tòa S3 có bể bơi bốn mùa ở tầng T.\n- Căn hộ chung cư tòa S1 từ tầng 4 ~ 36, tòa S2 và S3 là từ tầng 4 ~ 39. Có ban công, điều hòa hành lang.\n- Diện tích: 1PN (51~54m2), 2PN (65~75m2), 3PN (99~109m2), 4PN (130~142m2).\n- Có Vinschool mầm non.\n- Thang máy đi lại dễ dàng, không cần thẻ",
                'phi_dv'     => '16k/m2 (đã bao gồm các tiện ích). Ô tô: 1450 k, Xe máy: 60k',
                'can_ho'     => '1PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS, Penhouse',
            ],
            [
                'ten'        => 'VINHOMES WEST POINT',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Ngã 3 Phạm Hùng - Đỗ Đức Dục, Mễ Trì, Nam Từ Liêm, HN',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "- Tổ hợp căn hộ cao cấp, officetel & trung tâm thương mại. Vị trí giao thoa giữa 3 quận Cầu Giấy – Thanh Xuân – Nam Từ Liêm.\n- Chung cư: Tòa West 2 (tầng 5 up) & West 3 (sổ đỏ lâu dài)\n- Officetel: Tòa West 1 và tầng 2 ~ 5A Tòa West 2 (HĐMB 50 năm)\n- Tiên ích bể bơi ngoài trời, GYM, Vinmart, Vinschool.\n- Dự án gồm 3 tòa: W1 (tầng 6~37), W2 (Tầng 5~37), W3 (Tầng 2~35).\n- 3 hầm thông nhau.",
                'phi_dv'     => '17k/m2 (đã bao gồm các tiện ích). Ô tô: 1450 k, Xe máy: 60k',
                'can_ho'     => 'STUDIO, 2PN-1VS, 2PN-2VS, 3PN-2VS, 4PN-3VS',
            ],

            [
                'ten'        => 'GOLDEN PALACE',
                'slug_kv'    => 'nam-tu-liem',
                'dia_chi'    => 'Số 99, Đ. Mễ Trì, Nam Từ Liêm, HN',
                'chu_dt'     => 'Đang cập nhật',
                'thi_cong'   => 'Delta (phần hầm)',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "Dự án gồm 3 tòa A, B, C cao 30 tầng, 4 tầng hầm. Tầng thứ 31 là 20 căn Penthouse\nKhối đế 4 tầng từ T1 ~ 4, Căn hộ 26 tầng từ T5 ~ T30.\n- 4 tầng hầm. Hầm B1 là TTTM và rạp chiếu phim. 3 hầm để xe.\n- Số slot oto/ số căn hộ là 1,2. Dư giả slot oto.\n- Tòa A: 14 căn/sàn, Tòa B: 15 căn/sàn, Tòa C: 13 căn/sàn. Tầng 13 thực tế đánh số 12A.",
                'phi_dv'     => '6k/m2. Xe máy: 60k, oto: 1 tr',
                'can_ho'     => '2PN-2VS, 3PN-2VS, 4PN-2VS',
            ],
            [
                'ten'        => 'D\'CAPITAL',
                'slug_kv'    => 'cau-giay',
                'dia_chi'    => '119 Trần Duy Hưng, Phường Trung Hòa, quận Cầu Giấy, Hà Nội',
                'chu_dt'     => 'Tập đoàn Tân Hoàng Minh Group',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "Khu phức hợp 6 tòa tháp C1, C2, C3, C5, C6, C7 cao từ 39 đến 45 tầng với mô hình căn hộ cao cấp – văn phòng officetel – shophouse.\n+ 1 phòng ngủ: 55 m2\n+ 2 phòng ngủ: 72 m2 – 74 m2 – 78 m2\n+ 3 phòng ngủ: 90m2 – 93m2 – 97m2",
                'phi_dv'     => '19,500đ/m2, Xe ô tô: 1,200,000 - 1,500,000đ/xe/tháng , Xe máy : 100k/tháng',
                'can_ho'     => 'STUDIO, 2PN-1VS, 2PN-2VS, 3PN-2VS',
            ],
            // TOÀN BỘ DỰ ÁN SMART CITY ĐƯỢC MAP VỀ slug_kv = 'Vinhomes Smart City' THEO KHU VỰC ĐÃ KHAI BÁO
            [
                'ten'        => 'SAPPHIRE 1',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Đại lộ Thăng Long và tuyến đường sắt đô thị số 6 (Smart City)',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "5 Tòa: S101, S102, S105, S106 cao 34 tầng và tòa S103 cao 35 tầng. 1 tầng hầm.\n- Tòa S103: 30 căn/sàn, 8 thang máy. 4 tòa còn lại: 19 căn/sàn, 5 thang máy.\n- Diện tích từ 25m2 đến 75m2.\n- Tiêu chuẩn bàn giao cơ bản: sàn gạch, KHÔNG tủ bếp, KHÔNG tủ quần áo\n- Bàn giao Quý II/2020",
                'phi_dv'     => '8,8k/m2',
                'can_ho'     => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS',
            ],
            [
                'ten'        => 'SAPPHIRE 2',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Nằm giữa phân khu The Sapphire 1 và the metrolines (Smart City)',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "4 Tòa: S201 cao 30 tầng, S202, S203 cao 34 tầng và tòa S205 cao 29 tầng. 1 tầng hầm.\n- S203, S205: 19 căn/sàn, 5 thang máy.\n- S201, S202: 30 căn/sàn.\n- Tiêu chuẩn bàn giao cơ bản.\n- Bàn giao Quý II/2020",
                'phi_dv'     => '8,8k/m2',
                'can_ho'     => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS',
            ],
            [
                'ten'        => 'SAPPHIRE 3',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Cạnh Vinschool và The Diamond (Smart City)',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "3 Tòa: S301, S302, S303 cao 38 tầng. 1 tầng hầm.\n- Mật độ 19 căn/sàn, 5 thang máy.\n- Diện tích: 25 - 80m2.\n- Bàn giao Tháng 3/2021",
                'phi_dv'     => '8,8k/m2',
                'can_ho'     => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS',
            ],
            [
                'ten'        => 'SAPPHIRE 4',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Trục đường giao thông chính kết nối đại lộ Thăng Long (Smart City)',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "3 Tòa: S401, S402, S403 cao 35 tầng. 1 tầng hầm. Đối diện 2 công viên Sportia Park và Central Park.\n- S401: 30 căn/sàn. S402, S403: 22 căn/sàn.\n- Bàn giao Tháng 12/2021",
                'phi_dv'     => '8,8k/m2',
                'can_ho'     => 'STUDIO, 1PN-1VS+1, 2PN-1VS, 2PN-2VS+1, 3PN-2VS',
            ],
            [
                'ten'        => 'THE MIAMI',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Chân cầu vượt số 2, ngõ phía Tây Smart City',
                'chu_dt'     => 'Công ty Cổ phần Đầu tư Xây dựng Thái Sơn (công ty con của Vinhomes)',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "5 Tòa: GS1, GS3 (39 tầng) và GS2, GS5, GS6 (38 tầng). 1 tầng hầm.\n- GS2: 19 căn/sàn. GS1, GS3: 30 căn/sàn. GS5, GS6: 16 căn/sàn.\n- Tiêu chuẩn bàn giao: Sapphire + cơ bản.\n- Bàn giao đa dạng từ Quý 3/2022 đến Quý 1/2026.",
                'phi_dv'     => 'Chưa cập nhật',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'THE SAKURA',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Trung tâm The Metroline, Smart City',
                'chu_dt'     => 'Samty Việt Nam',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "4 Tòa: SA1 (37 tầng), SA2 (38 tầng), SA3 và SA5 (39 tầng). Tầng 39 có vườn nhật. 1 tầng hầm.\n- SA1, SA2: 19 căn/sàn. SA3, SA5: 30 căn/sàn.\n- Bàn giao: 2022 - 2025. Tiêu chuẩn: sàn gỗ, khóa vân tay, điều hòa nổi.",
                'phi_dv'     => '17k/m2 (đã bao gồm tiện ích nội khu)',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'TONKIN',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Vùng lõi KĐT Smart City',
                'chu_dt'     => 'Vinhomes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "2 Tòa: TK1 và TK2 cao 38 tầng. 1 tầng hầm. Mật độ 16 căn/sàn.\n- Tiêu chuẩn Ruby liền tường: sàn gỗ, điều hòa âm trần, tủ áo, thiết bị bếp + vệ sinh.\n- Bàn giao 2022 - 2023.",
                'phi_dv'     => '18k/m2 (đã bao gồm tiện ích nội khu)',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'CANOPY',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Vùng lõi KĐT Smart City',
                'chu_dt'     => 'Newlife (Đại diện GIC)',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "3 Tòa: TC1, TC2, TC3 cao 38 tầng. 2 tầng hầm. Mật độ 16 căn/sàn.\n- Tiêu chuẩn Ruby liền tường. Bàn giao Tháng 7/2025.",
                'phi_dv'     => 'Chưa cập nhật',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'IMPERIA',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Khu đô thị Vinhomes Smart City',
                'chu_dt'     => 'Công ty cổ phần HBI (thành viên MIK GROUP)',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "5 Tòa: I1 (39 tầng), I2, I3, I4, I5 (38 tầng). 1 tầng hầm.\nTiếp giáp Sportia Park & Central Park. Tiêu chuẩn liền tường: sàn gỗ, điều hòa nổi (I1, I2 có bếp). Bàn giao 2023-2024.",
                'phi_dv'     => 'Chưa cập nhật',
                'can_ho'     => 'STUDIO, 1PN+1-2VS, 2PN+1-1VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'THE SOLA PARK',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Đại lộ Ánh Sáng, Vinhomes Smart City',
                'chu_dt'     => 'Công ty cổ phần HBI (thành viên MIK GROUP)',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => false,
                'tong_quan'  => "5 Tòa: G1, G2, G3 (35 tầng, 1 hầm), G5 (2 hầm), G6 (1 hầm) cao 39 tầng. Tầng 2-14 khối G5,G6 là văn phòng.\nBàn giao Quý 1-3/2027. Tiêu chuẩn: sàn gỗ, thiết bị bếp + vệ sinh.",
                'phi_dv'     => 'Chưa cập nhật',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'MASTERI WEST HEIGHTS',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Trục đường chính, đối diện Central Park (Smart City)',
                'chu_dt'     => 'Masterise Homes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "4 Tòa: A, B (39 tầng), C, D (38 tầng). Tầng mái có vườn thượng uyển. 2 tầng hầm.\n- A, B: 30 căn/sàn. C, D: 19 căn/sàn.\n- Bàn giao 2023-2024. Tiêu chuẩn Ruby cao cấp liền tường.",
                'phi_dv'     => '24k/m2',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS',
            ],
            [
                'ten'        => 'LUMIERE EVERGREEN',
                'slug_kv'    => 'Vinhomes Smart City',
                'dia_chi'    => 'Đại lộ Ánh Sáng, KĐT Vinhomes Smart City',
                'chu_dt'     => 'Masterise Homes',
                'thi_cong'   => 'Coteccons',
                'trang_thai' => 'dang_mo_ban',
                'noi_bat'    => true,
                'tong_quan'  => "3 Tòa: A1, A2, A3 cao 39 tầng. 1 tầng hầm.\n- A1, A2: 18 căn/sàn. A3: 26 căn/sàn.\n- Bàn giao Tháng 5/2026. Tiêu chuẩn Ruby liền tường cao cấp.",
                'phi_dv'     => 'Chưa cập nhật',
                'can_ho'     => 'STUDIO, 1PN-1VS, 1PN+1-2VS, 2PN-2VS, 2PN+1-2VS, 3PN-2VS, 4PN',
            ],
        ];

        foreach ($duAn as $i => $da) {

            // Xử lý HTML an toàn cho production
            $noiDungHTML = '<h3>Tổng quan dự án</h3>';
            $noiDungHTML .= '<p>' . nl2br(e($da['tong_quan'])) . '</p>';

            $noiDungHTML .= '<h3>Phí dịch vụ</h3>';
            $noiDungHTML .= '<p>' . e($da['phi_dv']) . '</p>';

            $noiDungHTML .= '<h3>Các dạng căn hộ</h3>';
            $noiDungHTML .= '<p><strong>' . e($da['can_ho']) . '</strong></p>';

            DuAn::create([
                'khu_vuc_id'          => $khuVuc[$da['slug_kv']]->id ?? null,
                'ten_du_an'           => $da['ten'],
                'slug'                => Str::slug($da['ten']) . '-' . ($i + 1),
                'dia_chi'             => $da['dia_chi'],
                'chu_dau_tu'          => $da['chu_dt'],
                'don_vi_thi_cong'     => $da['thi_cong'],
                'mo_ta_ngan'          => 'Dự án ' . $da['ten'] . ' - Cơ sở hạ tầng hiện đại, vị trí đắc địa.',
                'noi_dung_chi_tiet'   => $noiDungHTML,
                'hien_thi'            => true,
                'noi_bat'             => $da['noi_bat'],
                'trang_thai'          => $da['trang_thai'],
                'thu_tu_hien_thi'     => $i + 1,
                'seo_title'           => $da['ten'] . ' - Đánh giá chi tiết',
                'seo_description'     => 'Cập nhật thông tin tổng quan, phí dịch vụ và thiết kế mặt bằng tại ' . $da['ten'],
                'thoi_diem_dang'      => now()->subDays(rand(10, 180)),
            ]);
        }

        $this->command->info('✅ DuAn: ' . count($duAn) . ' bản ghi đã được tạo thành công với liên kết khu vực chuẩn chỉnh.');
    }
}
