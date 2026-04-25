<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Reservation\CancelController as AdminReservationCancelController;
use App\Http\Controllers\Admin\Reservation\IndexController as AdminReservationIndexController;
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
    });
});
