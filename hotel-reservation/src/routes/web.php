<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Plan\DestroyController as AdminPlanDestroyController;
use App\Http\Controllers\Admin\Plan\EditController as AdminPlanEditController;
use App\Http\Controllers\Admin\Plan\IndexController as AdminPlanIndexController;
use App\Http\Controllers\Admin\Plan\StoreController as AdminPlanStoreController;
use App\Http\Controllers\Admin\Plan\UpdateController as AdminPlanUpdateController;
use App\Http\Controllers\Admin\Reservation\CancelController as AdminReservationCancelController;
use App\Http\Controllers\Admin\Reservation\IndexController as AdminReservationIndexController;
use App\Http\Controllers\Admin\ReservationSlot\BulkStoreController as AdminReservationSlotBulkStoreController;
use App\Http\Controllers\Admin\ReservationSlot\DestroyController as AdminReservationSlotDestroyController;
use App\Http\Controllers\Admin\ReservationSlot\EditController as AdminReservationSlotEditController;
use App\Http\Controllers\Admin\ReservationSlot\IndexController as AdminReservationSlotIndexController;
use App\Http\Controllers\Admin\ReservationSlot\StoreController as AdminReservationSlotStoreController;
use App\Http\Controllers\Admin\ReservationSlot\UpdateController as AdminReservationSlotUpdateController;
use App\Http\Controllers\User\Reservation\ConfirmController as UserReservationConfirmController;
use App\Http\Controllers\User\Reservation\CreateController as UserReservationCreateController;
use App\Http\Controllers\User\Reservation\StoreController as UserReservationStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 宿泊者：予約フロー（認証不要）
Route::prefix('reservations')->name('user.reservations.')->group(function () {
    Route::get('create', UserReservationCreateController::class)->name('create');
    Route::post('confirm', UserReservationConfirmController::class)->name('confirm');
    Route::post('/', UserReservationStoreController::class)->name('store');
    Route::get('/', fn () => redirect('/'))->name('index');
    Route::get('complete', fn () => view('user.reservation.complete'))->name('complete');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // ゲストのみアクセス可
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
    });

    // 認証済みのみアクセス可
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::delete('logout', [LoginController::class, 'destroy'])->name('logout');

        // 予約管理
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', AdminReservationIndexController::class)->name('index');
            Route::delete('{reservation}/cancel', AdminReservationCancelController::class)->name('cancel');
        });

        // 宿泊プラン管理
        Route::prefix('plans')->name('plans.')->group(function () {
            Route::get('/', AdminPlanIndexController::class)->name('index');
            Route::post('/', AdminPlanStoreController::class)->name('store');
            Route::get('{plan}/edit', AdminPlanEditController::class)->name('edit');
            Route::put('{plan}', AdminPlanUpdateController::class)->name('update');
            Route::delete('{plan}', AdminPlanDestroyController::class)->name('destroy');
        });

        // 予約枠管理
        Route::prefix('reservation-slots')->name('reservation-slots.')->group(function () {
            Route::get('/', AdminReservationSlotIndexController::class)->name('index');
            Route::post('/', AdminReservationSlotStoreController::class)->name('store');
            Route::post('bulk', AdminReservationSlotBulkStoreController::class)->name('bulk-store');
            Route::get('{reservationSlot}/edit', AdminReservationSlotEditController::class)->name('edit');
            Route::put('{reservationSlot}', AdminReservationSlotUpdateController::class)->name('update');
            Route::delete('{reservationSlot}', AdminReservationSlotDestroyController::class)->name('destroy');
        });
    });
});
