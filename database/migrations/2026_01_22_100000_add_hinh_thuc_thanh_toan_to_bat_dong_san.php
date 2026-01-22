<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            $table->string('hinh_thuc_thanh_toan')->nullable()->after('gia');
        });
    }

    public function down(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            $table->dropColumn('hinh_thuc_thanh_toan');
        });
    }
};
