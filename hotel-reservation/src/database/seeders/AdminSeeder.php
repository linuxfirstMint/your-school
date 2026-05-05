<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['last_name' => '山田', 'first_name' => '太郎',  'email' => 'admin@example.com'],
            ['last_name' => '佐藤', 'first_name' => '花子',  'email' => 'sato@example.com'],
            ['last_name' => '鈴木', 'first_name' => '一郎',  'email' => 'suzuki@example.com'],
            ['last_name' => '田中', 'first_name' => '美咲',  'email' => 'tanaka@example.com'],
            ['last_name' => '伊藤', 'first_name' => '健太',  'email' => 'ito@example.com'],
        ];

        foreach ($admins as $admin) {
            Admin::create([
                ...$admin,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
