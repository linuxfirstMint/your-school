<?php

namespace Database\Seeders;

use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class AccommodationPlanSeeder extends Seeder
{
    public function run(): void
    {
        $standard = RoomType::where('name', 'スタンダードルーム')->first();
        $deluxe   = RoomType::where('name', 'デラックスルーム')->first();
        $suite    = RoomType::where('name', 'スイートルーム')->first();

        $plans = [
            [
                'plan' => [
                    'name'        => '【朝食付き】森の恵みスタンダードプラン',
                    'description' => '旬の野菜を使った和朝食をご用意するシンプルな宿泊プランです。山の清涼な空気の中でゆっくりとした朝をお過ごしください。',
                ],
                'prices' => [
                    [$standard, 12000],
                    [$deluxe,   18000],
                    [$suite,    35000],
                ],
            ],
            [
                'plan' => [
                    'name'        => '【2食付き】山の幸グルメプラン',
                    'description' => '地元の山の幸をふんだんに使った会席料理をご堪能いただけます。夕食・朝食ともにシェフ自慢の一品をご提供します。',
                ],
                'prices' => [
                    [$standard, 22000],
                    [$deluxe,   30000],
                    [$suite,    55000],
                ],
            ],
            [
                'plan' => [
                    'name'        => '【露天風呂付き客室】絶景プレミアムプラン',
                    'description' => '客室に露天風呂が付いた特別なプランです。四季折々の絶景を眺めながら、贅沢なひとときをお楽しみいただけます。2食付き。',
                ],
                'prices' => [
                    [$deluxe, 45000],
                    [$suite,  80000],
                ],
            ],
            [
                'plan' => [
                    'name'        => '【記念日・特別】アニバーサリープラン',
                    'description' => '大切な方との記念日に。ウェルカムスイーツ・バルーン装飾・記念写真撮影サービスをご用意します。特別な思い出をお手伝いします。',
                ],
                'prices' => [
                    [$deluxe, 50000],
                    [$suite,  90000],
                ],
            ],
            [
                'plan' => [
                    'name'        => '【素泊まり】お気軽ステイプラン',
                    'description' => '食事なしで気軽にご利用いただけるプランです。近隣のレストランや温泉街をご自由にお楽しみください。',
                ],
                'prices' => [
                    [$standard, 8000],
                    [$deluxe,   13000],
                    [$suite,    25000],
                ],
            ],
        ];

        foreach ($plans as $data) {
            $plan = AccommodationPlan::create($data['plan']);

            foreach ($data['prices'] as [$roomType, $price]) {
                if ($roomType) {
                    PlanRoomPrice::create([
                        'accommodation_plan_id' => $plan->id,
                        'room_type_id'          => $roomType->id,
                        'price'                 => $price,
                    ]);
                }
            }
        }
    }
}
