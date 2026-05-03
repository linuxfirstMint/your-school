@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 fw-bold mb-0">ダッシュボード</h1>
        <span class="text-muted small">
            ログイン中: {{ Auth::guard('admin')->user()->last_name }} {{ Auth::guard('admin')->user()->first_name }}
        </span>
    </div>

    {{-- サマリーカード --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="fs-2 text-primary">📅</div>
                    <div>
                        <div class="text-muted small">今日のチェックイン</div>
                        <div class="fs-3 fw-bold">{{ $todayCount }}<span class="fs-6 fw-normal text-muted ms-1">件</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="fs-2 text-info">🌅</div>
                    <div>
                        <div class="text-muted small">明日のチェックイン</div>
                        <div class="fs-3 fw-bold">{{ $tomorrowCount }}<span class="fs-6 fw-normal text-muted ms-1">件</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="fs-2 text-danger">✉️</div>
                    <div>
                        <div class="text-muted small">未対応のお問い合わせ</div>
                        <div class="fs-3 fw-bold">{{ $pendingInquiryCount }}<span class="fs-6 fw-normal text-muted ms-1">件</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="fs-2 text-success">📊</div>
                    <div>
                        <div class="text-muted small">今月の予約件数</div>
                        <div class="fs-3 fw-bold">{{ $monthlyCount }}<span class="fs-6 fw-normal text-muted ms-1">件</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 直近の予約 --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold border-bottom">直近の予約</div>
        <div class="card-body p-0">
            @if ($recentReservations->isEmpty())
                <p class="text-muted text-center py-4 mb-0">予約はまだありません。</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>氏名</th>
                                <th>プラン</th>
                                <th>チェックイン</th>
                                <th>料金</th>
                                <th>ステータス</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentReservations as $reservation)
                            <tr>
                                <td>{{ $reservation->last_name }} {{ $reservation->first_name }}</td>
                                <td>{{ $reservation->plan_name }}</td>
                                <td>{{ $reservation->reservationSlot?->start?->format('Y/m/d') ?? '—' }}</td>
                                <td>¥{{ number_format($reservation->price) }}</td>
                                <td>
                                    @if ($reservation->status === \App\Enums\ReservationStatus::Confirmed)
                                        <span class="badge bg-success">確定</span>
                                    @else
                                        <span class="badge bg-secondary">キャンセル</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="card-footer bg-white text-end">
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-secondary">すべての予約を見る</a>
        </div>
    </div>

    {{-- ナビゲーションカード --}}
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
