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

        // ページネーション確認用（3ページ分になるよう追加、合計25件）
        $extraPlans = [
            ['name' => '【早割30】30日前予約限定プラン',              'prices' => [[$standard, 10000], [$deluxe, 15000], [$suite, 28000]]],
            ['name' => '【連泊割】3泊以上でお得プラン',               'prices' => [[$standard, 11000], [$deluxe, 16000], [$suite, 30000]]],
            ['name' => '【ファミリー】子連れ大歓迎プラン',            'prices' => [[$standard, 20000], [$deluxe, 28000]]],
            ['name' => '【シニア】ゆったり温泉湯治プラン',            'prices' => [[$standard, 15000], [$deluxe, 22000]]],
            ['name' => '【カップル】二人旅ロマンティックプラン',      'prices' => [[$deluxe, 36000], [$suite, 65000]]],
            ['name' => '【ビジネス】出張応援シンプルプラン',          'prices' => [[$standard, 9000]]],
            ['name' => '【女子旅】エステ＆スパ付きプラン',            'prices' => [[$deluxe, 38000], [$suite, 70000]]],
            ['name' => '【冬季限定】雪見鍋と温泉プラン',              'prices' => [[$standard, 18000], [$deluxe, 26000], [$suite, 48000]]],
            ['name' => '【春限定】花見と懐石料理プラン',              'prices' => [[$standard, 16000], [$deluxe, 24000], [$suite, 45000]]],
            ['name' => '【夏限定】川遊びと流しそうめんプラン',        'prices' => [[$standard, 14000], [$deluxe, 20000]]],
            ['name' => '【秋限定】紅葉狩りと山の幸プラン',            'prices' => [[$standard, 17000], [$deluxe, 25000], [$suite, 46000]]],
            ['name' => '【ペット同伴】愛犬と一緒に泊まれるプラン',   'prices' => [[$standard, 13000], [$deluxe, 19000]]],
            ['name' => '【禁煙】空気清浄ルームプラン',                'prices' => [[$standard, 12000], [$deluxe, 18000], [$suite, 35000]]],
            ['name' => '【長期滞在】1週間まるごとステイプラン',       'prices' => [[$standard, 60000], [$deluxe, 90000]]],
            ['name' => '【ワーケーション】高速Wi-Fi完備・仕事も捗るプラン', 'prices' => [[$standard, 10500], [$deluxe, 16500]]],
            ['name' => '【VIP】プライベートダイニング付き最上級プラン', 'prices' => [[$suite, 120000]]],
            ['name' => '【学生・若者割】25歳以下限定お得プラン',      'prices' => [[$standard, 7500], [$deluxe, 12000]]],
            ['name' => '【温泉三昧】貸切風呂2時間付きプラン',         'prices' => [[$standard, 19000], [$deluxe, 27000], [$suite, 50000]]],
            ['name' => '【ヨガ＆瞑想】朝の自然体験プラン',            'prices' => [[$standard, 21000], [$deluxe, 32000]]],
            ['name' => '【釣り体験】川釣りと地魚料理プラン',          'prices' => [[$standard, 23000], [$deluxe, 33000], [$suite, 58000]]],
        ];

        foreach ($extraPlans as $data) {
            $plan = AccommodationPlan::create(['name' => $data['name']]);
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
