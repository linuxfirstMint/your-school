@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')

<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 fw-bold mb-0">ダッシュボード</h1>
        <span class="text-muted small">
            ログイン中: {{ Auth::guard('admin')->user()->last_name }} {{ Auth::guard('admin')->user()->first_name }}
        </span>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <a href="{{ route('admin.plans.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="fs-1 mb-2">📋</div>
                        <h5 class="card-title fw-bold">プラン管理</h5>
                        <p class="card-text text-muted small">宿泊プランの作成・編集・削除</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.reservation-slots.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="fs-1 mb-2">🗓️</div>
                        <h5 class="card-title fw-bold">予約枠管理</h5>
                        <p class="card-text text-muted small">予約枠の作成・編集・削除</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.reservations.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="fs-1 mb-2">📝</div>
                        <h5 class="card-title fw-bold">予約管理</h5>
                        <p class="card-text text-muted small">予約の確認・キャンセル</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection
