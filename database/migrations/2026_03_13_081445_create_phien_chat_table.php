<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phien_chat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khach_hang_id')
                ->nullable()
                ->constrained('khach_hang')
                ->nullOnDelete();

            $table->foreignId('nhan_vien_phu_trach_id')
                ->nullable()
                ->constrained('nhan_vien')
                ->nullOnDelete();

            // Session ID duy nhat de dinh danh phien
            $table->string('session_id')->unique();

            // Thong tin khach vang lai chua co tai khoan
            $table->string('ten_khach_vang_lai')->nullable();
            $table->string('sdt_khach_vang_lai')->nullable();
            $table->boolean('da_xac_thuc_sdt')->default(false);

            // URL trang khach dang xem luc mo chat
            $table->string('url_ngu_canh')->nullable();

            // dang_mo | da_dong
            $table->string('trang_thai')->default('dang_mo');

            $table->timestamp('tin_nhan_cuoi_at')->nullable();
            $table->timestamps();

            $table->index(['trang_thai', 'nhan_vien_phu_trach_id']);
            $table->index(['khach_hang_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phien_chat');
    }
};
