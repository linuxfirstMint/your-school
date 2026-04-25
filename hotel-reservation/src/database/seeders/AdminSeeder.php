<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
