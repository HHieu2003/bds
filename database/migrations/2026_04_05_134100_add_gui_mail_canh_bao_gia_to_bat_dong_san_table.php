<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            if (! Schema::hasColumn('bat_dong_san', 'gui_mail_canh_bao_gia')) {
                $table->boolean('gui_mail_canh_bao_gia')->default(true)->after('noi_bat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bat_dong_san', function (Blueprint $table) {
            if (Schema::hasColumn('bat_dong_san', 'gui_mail_canh_bao_gia')) {
                $table->dropColumn('gui_mail_canh_bao_gia');
            }
        });
    }
};
