<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            $table->json('album_video')->nullable()->after('album_anh')->comment('Danh sách đường dẫn video upload');
        });
    }

    public function down(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            $table->dropColumn('album_video');
        });
    }
};
