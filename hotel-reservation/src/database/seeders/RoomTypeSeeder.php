<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'スタンダードルーム', 'count' => 3],
            ['name' => 'デラックスルーム',  'count' => 2],
            ['name' => 'スイートルーム',    'count' => 1],
        ];

        foreach ($types as $type) {
            RoomType::create($type);
        }
    }
}
