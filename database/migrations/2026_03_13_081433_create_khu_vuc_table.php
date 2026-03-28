<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khu_vuc', function (Blueprint $table) {
            $table->id();

            // tinh_thanh | quan_huyen | phuong_xa
            $table->string('cap_khu_vuc')->default('quan_huyen');

            $table->foreignId('khu_vuc_cha_id')
                ->nullable()
                ->constrained('khu_vuc')
                ->nullOnDelete();

            $table->string('ten_khu_vuc');
            $table->string('slug')->unique();
            $table->text('mo_ta')->nullable();

            $table->boolean('hien_thi')->default(true);
            $table->unsignedInteger('thu_tu_hien_thi')->default(0);

            // SEO cho trang landing page theo khu vuc
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['cap_khu_vuc', 'hien_thi']);
            $table->index(['khu_vuc_cha_id', 'thu_tu_hien_thi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khu_vuc');
    }
};
