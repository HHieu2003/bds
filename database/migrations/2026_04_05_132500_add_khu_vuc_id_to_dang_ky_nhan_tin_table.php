<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dang_ky_nhan_tin', function (Blueprint $table) {
            if (! Schema::hasColumn('dang_ky_nhan_tin', 'khu_vuc_id')) {
                $table->foreignId('khu_vuc_id')
                    ->nullable()
                    ->after('nhu_cau')
                    ->constrained('khu_vuc')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dang_ky_nhan_tin', function (Blueprint $table) {
            if (Schema::hasColumn('dang_ky_nhan_tin', 'khu_vuc_id')) {
                $table->dropConstrainedForeignId('khu_vuc_id');
            }
        });
    }
};
