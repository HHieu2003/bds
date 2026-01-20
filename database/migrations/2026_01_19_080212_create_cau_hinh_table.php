<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cau_hinh', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Ví dụ: 'hotline', 'email_lien_he', 'facebook_url'
            $table->text('value')->nullable(); // Giá trị: '0912345678', 'admin@gmail.com'
            $table->string('mo_ta')->nullable(); // Mô tả cho Admin hiểu setting này là gì
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_hinh');
    }
};
