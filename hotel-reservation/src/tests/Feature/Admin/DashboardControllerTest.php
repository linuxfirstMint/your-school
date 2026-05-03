<?php

namespace Tests\Feature\Admin;

use App\Enums\InquiryStatus;
use App\Enums\ReservationStatus;
use App\Models\Admin;
use App\Models\Inquiry;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'last_name'  => '管理',
            'first_name' => '太郎',
            'email'      => 'admin@example.com',
            'password'   => Hash::make('password'),
        ]);
    }

    public function test_未認証ユーザーはダッシュボードにアクセスできない(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_ダッシュボードが表示される(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_今日のチェックイン件数が表示される(): void
    {
        $today = now()->toDateString();
        $slot = ReservationSlot::factory()->create(['start' => $today]);
        Reservation::factory()->create([
            'reservation_slot_id' => $slot->id,
            'status'              => ReservationStatus::Confirmed,
        ]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertSee('1')
            ->assertViewHas('todayCount', 1);
    }

    public function test_明日のチェックイン件数が表示される(): void
    {
        $tomorrow = now()->addDay()->toDateString();
        $slot = ReservationSlot::factory()->create(['start' => $tomorrow]);
        Reservation::factory()->create([
            'reservation_slot_id' => $slot->id,
            'status'              => ReservationStatus::Confirmed,
        ]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertViewHas('tomorrowCount', 1);
    }

    public function test_未対応のお問い合わせ件数が表示される(): void
    {
        Inquiry::factory()->count(2)->create(['status' => InquiryStatus::Pending]);
        Inquiry::factory()->create(['status' => InquiryStatus::Resolved]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertViewHas('pendingInquiryCount', 2);
    }

    public function test_今月の予約件数が表示される(): void
    {
        Reservation::factory()->count(3)->create(['status' => ReservationStatus::Confirmed]);
        Reservation::factory()->create(['status' => ReservationStatus::Cancelled]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertViewHas('monthlyCount', 3);
    }

    public function test_直近5件の予約が表示される(): void
    {
        Reservation::factory()->count(7)->create(['status' => ReservationStatus::Confirmed]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertViewHas('recentReservations', fn ($r) => $r->count() === 5);
    }
}
