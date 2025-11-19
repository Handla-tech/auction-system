<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'المسؤول',
            'email' => 'admin@auction.com',
            'password' => Hash::make('password123'),
            'phone' => '0512345678',
            'address' => 'عنوان المسؤول',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('تم إنشاء المستخدم المسؤول بنجاح!');
        $this->command->info('البريد: admin@auction.com');
        $this->command->info('كلمة المرور: password123');
    }
}