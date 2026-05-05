<?php

namespace Database\Seeders;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            '予約について',
            'キャンセルポリシーについて',
            'チェックイン時間について',
            '駐車場の有無について',
            '食事アレルギーについて',
            'アクセス方法について',
            '特別なリクエストについて',
            '周辺観光スポットについて',
            '連泊割引について',
            '団体予約について',
        ];

        $statuses = [
            InquiryStatus::Pending,
            InquiryStatus::Pending,
            InquiryStatus::Pending,
            InquiryStatus::InProgress,
            InquiryStatus::InProgress,
            InquiryStatus::Resolved,
        ];

        for ($i = 0; $i < 30; $i++) {
            Inquiry::create([
                'last_name'  => fake('ja_JP')->lastName(),
                'first_name' => fake('ja_JP')->firstName(),
                'email'      => fake()->safeEmail(),
                'address'    => fake('ja_JP')->address(),
                'phone'      => fake('ja_JP')->phoneNumber(),
                'message'    => fake()->randomElement($subjects) . 'について質問があります。' . fake('ja_JP')->text(50),
                'status'     => fake()->randomElement($statuses),
            ]);
        }
    }
}
