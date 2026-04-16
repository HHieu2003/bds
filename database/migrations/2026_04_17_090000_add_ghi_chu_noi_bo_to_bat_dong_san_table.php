<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            if (!Schema::hasColumn('bat_dong_san', 'ghi_chu_noi_bo')) {
                $table->longText('ghi_chu_noi_bo')->nullable()->after('mo_ta');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            if (Schema::hasColumn('bat_dong_san', 'ghi_chu_noi_bo')) {
                $table->dropColumn('ghi_chu_noi_bo');
            }
        });
    }
};
