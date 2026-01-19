<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản Admin mặc định
        \App\Models\User::create([
            'name' => 'Admin Boss',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'), // Mật khẩu là 123456
            'role' => 'admin'
        ]);

        // Tạo sẵn 2 dự án mẫu để test cho nhanh (đỡ phải nhập tay)
        \App\Models\DuAn::create(['ten_du_an' => 'Vinhomes Smart City', 'dia_chi' => 'Tây Mỗ']);
        \App\Models\DuAn::create(['ten_du_an' => 'Ecopark Hưng Yên', 'dia_chi' => 'Văn Giang']);
    }
}
