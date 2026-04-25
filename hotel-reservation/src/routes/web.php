<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    });
});
