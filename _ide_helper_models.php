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
 * @property int $du_an_id
 * @property int $user_id
 * @property string $ma_can
 * @property \Illuminate\Support\Carbon|null $ngay_goi
 * @property \Illuminate\Support\Carbon|null $ngay_dang
 * @property string|null $huong_cua
 * @property string|null $huong_ban_cong
 * @property float $dien_tich
 * @property string $phong_ngu
 * @property string|null $noi_that
 * @property numeric $gia
 * @property string|null $hinh_thuc_thanh_toan
 * @property string|null $thoi_gian_vao
 * @property string|null $mo_ta
 * @property array<array-key, mixed>|null $hinh_anh
 * @property string $loai_hinh
 * @property string $trang_thai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DuAn $duAn
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereDienTich($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereDuAnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereGia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHinhAnh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHinhThucThanhToan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHuongBanCong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereHuongCua($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereLoaiHinh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereMaCan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereNgayDang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereNgayGoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereNoiThat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan wherePhongNgu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereThoiGianVao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereTieuDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BatDongSan whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBatDongSan {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ten_du_an
 * @property string|null $dia_chi
 * @property string|null $chu_dau_tu
 * @property string|null $don_vi_thi_cong
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatDongSan> $batDongSans
 * @property-read int|null $bat_dong_sans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereChuDauTu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereDiaChi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereDonViThiCong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereTenDuAn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DuAn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDuAn {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $so_dien_thoai
 * @property string|null $loi_nhan
 * @property int $bat_dong_san_id
 * @property string $trang_thai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BatDongSan $batDongSan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereBatDongSanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereLoiNhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LienHe whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLienHe {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

