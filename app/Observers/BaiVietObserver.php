<?php

namespace App\Observers;

use App\Models\BaiViet;
use Illuminate\Support\Str;

/**
 * Observer tự động tạo slug SEO-friendly cho Bài viết.
 * Đảm bảo URL thân thiện: /tin-tuc/meo-phong-thuy-cho-can-ho-moi
 */
class BaiVietObserver
{
    /**
     * TỰ ĐỘNG TẠO SLUG KHI THÊM MỚI BÀI VIẾT (nếu chưa có slug)
     */
    public function creating(BaiViet $baiViet): void
    {
        if (empty($baiViet->slug) && !empty($baiViet->tieu_de)) {
            $baiViet->slug = $this->generateUniqueSlug($baiViet->tieu_de);
        }
    }

    /**
     * TỰ ĐỘNG CẬP NHẬT SLUG KHI THAY ĐỔI TIÊU ĐỀ (nếu admin không nhập slug thủ công)
     */
    public function updating(BaiViet $baiViet): void
    {
        // Chỉ cập nhật slug khi tiêu đề thay đổi VÀ slug chưa bị admin sửa thủ công
        if ($baiViet->isDirty('tieu_de') && !$baiViet->isDirty('slug')) {
            $baiViet->slug = $this->generateUniqueSlug($baiViet->tieu_de, $baiViet->id);
        }
    }

    /**
     * Tạo slug duy nhất. Nếu bị trùng, thêm hậu tố -2, -3,...
     */
    private function generateUniqueSlug(string $title, ?int $exceptId = null): string
    {
        $slug = Str::slug($title);

        if (empty($slug)) {
            $slug = 'bai-viet-' . time();
        }

        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = BaiViet::withTrashed()->where('slug', $slug);

            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }

            if (!$query->exists()) {
                break;
            }

            $counter++;
            $slug = $originalSlug . '-' . $counter;
        }

        return $slug;
    }
}
