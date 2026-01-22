<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $tieu_de
 * @property string $slug
 * @property string|null $mo_ta_ngan
 * @property string $noi_dung
 * @property string|null $hinh_anh
 * @property string $loai_bai_viet
 * @property int $hien_thi
 * @property int $luot_xem
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereHienThi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereHinhAnh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereLoaiBaiViet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereLuotXem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereMoTaNgan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereNoiDung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereTieuDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaiViet whereUserId($value)
 */
	class BaiViet extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperBatDongSan
 * @property int $id
 * @property string $tieu_de
 * @property string $slug
 * @property int|null $du_an_id
 * @property int|null $user_id
 * @property string|null $toa
 * @property string $ma_can
 * @property \Illuminate\Support\Carbon|null $ngay_dang
 * @property string|null $huong_cua
 * @property string|null $huong_ban_cong
 * @property float $dien_tich
 * @property int $so_phong_ngu
 * @property int $so_phong_tam
 * @property string|null $noi_that
 * @property numeric $gia
 * @property string|null $hinh_thuc_thanh_toan
 * @property string|null $mo_ta
 * @property string|null $tien_ich
 * @property array<array-key, mixed>|null $hinh_anh
 * @property string|null $album_anh
 * @property string $loai_hinh
 * @property string $nhu_cau
 * @property int $is_hot
 * @property string $trang_thai
 * @property int $luot_xem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DuAn|null $duAn
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereAlbumAnh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereDienTich($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereDuAnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereGia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHinhAnh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHinhThucThanhToan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHuongBanCong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHuongCua($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereIsHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereLoaiHinh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereLuotXem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereMaCan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereNgayDang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereNhuCau($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereNoiThat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereSoPhongNgu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereSoPhongTam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereTienIch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereTieuDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereToa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereUserId($value)
 */
	class BatDongSan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $chat_session_id
 * @property int|null $user_id
 * @property string $message
 * @property int $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ChatSession $chatSession
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereChatSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereUserId($value)
 */
	class ChatMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $khach_hang_id
 * @property string $session_id
 * @property string|null $user_name
 * @property string|null $user_phone
 * @property int $is_verified
 * @property string|null $context_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KhachHang|null $khachHang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatMessage> $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereContextUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereKhachHangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatSession whereUserPhone($value)
 */
	class ChatSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperDuAn
 * @property int $id
 * @property string $ten_du_an
 * @property string $slug
 * @property string $dia_chi
 * @property string|null $chu_dau_tu
 * @property string|null $mo_ta_ngan
 * @property string|null $noi_dung_chi_tiet
 * @property string|null $hinh_anh_dai_dien
 * @property string|null $album_anh
 * @property string|null $dien_tich_tong_the
 * @property string|null $map_url
 * @property string $trang_thai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatDongSan> $batDongSans
 * @property-read int|null $bat_dong_sans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereAlbumAnh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereChuDauTu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereDiaChi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereDienTichTongThe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereHinhAnhDaiDien($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereMapUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereMoTaNgan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereNoiDungChiTiet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereTenDuAn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereUpdatedAt($value)
 */
	class DuAn extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperKhachHang
 * @property int $id
 * @property string|null $ho_ten
 * @property string|null $so_dien_thoai
 * @property string|null $email
 * @property string|null $password
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $phone_verified_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereHoTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KhachHang whereUpdatedAt($value)
 */
	class KhachHang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ho_ten_chu_nha
 * @property string $so_dien_thoai
 * @property string|null $email
 * @property string $loai_hinh
 * @property string $dia_chi
 * @property numeric $dien_tich
 * @property numeric $gia_mong_muon
 * @property string|null $hinh_anh_tham_khao
 * @property string|null $ghi_chu
 * @property string $trang_thai
 * @property string|null $phan_hoi_cua_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereDiaChi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereDienTich($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereGiaMongMuon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereHinhAnhThamKhao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereHoTenChuNha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereLoaiHinh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui wherePhanHoiCuaAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KyGui whereUpdatedAt($value)
 */
	class KyGui extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $khach_hang_id
 * @property string $ten_khach_hang
 * @property string $sdt_khach_hang
 * @property string|null $email_khach_hang
 * @property int $bat_dong_san_id
 * @property string $thoi_gian_hen
 * @property int|null $nhan_vien_id
 * @property string $trang_thai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BatDongSan $batDongSan
 * @property-read \App\Models\User|null $nhanVien
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereBatDongSanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereEmailKhachHang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereKhachHangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereNhanVienId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereSdtKhachHang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereTenKhachHang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereThoiGianHen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LichHen whereUpdatedAt($value)
 */
	class LichHen extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperLienHe
 * @property int $id
 * @property string $ho_ten
 * @property string $email
 * @property string $so_dien_thoai
 * @property string $noi_dung
 * @property int|null $bat_dong_san_id
 * @property string $trang_thai
 * @property string|null $ghi_chu_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BatDongSan|null $batDongSan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereBatDongSanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereGhiChuAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereHoTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereNoiDung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereUpdatedAt($value)
 */
	class LienHe extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperUser
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatDongSan> $batDongSans
 * @property-read int|null $bat_dong_sans_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $bat_dong_san_id
 * @property int|null $khach_hang_id
 * @property int|null $user_id
 * @property string|null $session_id
 * @property string|null $so_dien_thoai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BatDongSan $batDongSan
 * @property-read \App\Models\KhachHang|null $khachHang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereBatDongSanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereKhachHangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YeuThich whereUserId($value)
 */
	class YeuThich extends \Eloquent {}
}

