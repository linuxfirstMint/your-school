@extends('layouts.app')

@section('title')空室カレンダー - {{ $plan->name }}@endsection

@section('content')

<section class="py-5">
    <div class="container" style="max-width: 760px;">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.plans.index') }}">宿泊プラン一覧</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.plans.show', $plan) }}">{{ $plan->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">空室カレンダー</li>
            </ol>
        </nav>

        <h1 class="h3 fw-light mb-1">{{ $plan->name }}</h1>
        <p class="text-muted mb-3">空室カレンダー</p>

        {{-- 部屋タイプタブ --}}
        @if ($roomTypes->isNotEmpty())
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $roomTypeId === null ? 'active' : '' }}"
                   href="{{ route('user.plans.calendar', ['plan' => $plan, 'year' => $firstDay->year, 'month' => $firstDay->month]) }}">
                    全室タイプ
                </a>
            </li>
            @foreach ($roomTypes as $roomType)
            <li class="nav-item">
                <a class="nav-link {{ $roomTypeId === $roomType->id ? 'active' : '' }}"
                   href="{{ route('user.plans.calendar', ['plan' => $plan, 'room_type_id' => $roomType->id, 'year' => $firstDay->year, 'month' => $firstDay->month]) }}">
                    {{ $roomType->name }}
                </a>
            </li>
            @endforeach
        </ul>
        @endif

        {{-- 月ナビゲーション --}}
        @php
            $prevMonth  = $firstDay->copy()->subMonth();
            $nextMonth  = $firstDay->copy()->addMonth();
            $prevParams = ['plan' => $plan, 'year' => $prevMonth->year, 'month' => $prevMonth->month];
            $nextParams = ['plan' => $plan, 'year' => $nextMonth->year, 'month' => $nextMonth->month];
            if ($roomTypeId !== null) {
                $prevParams['room_type_id'] = $roomTypeId;
                $nextParams['room_type_id'] = $roomTypeId;
            }
        @endphp
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('user.plans.calendar', $prevParams) }}"
               class="btn btn-outline-secondary btn-sm">← 前月</a>
            <h2 class="h5 mb-0">{{ $firstDay->format('Y年n月') }}</h2>
            <a href="{{ route('user.plans.calendar', $nextParams) }}"
               class="btn btn-outline-secondary btn-sm">翌月 →</a>
        </div>

        {{-- カレンダーテーブル --}}
        <table class="table table-bordered text-center align-middle mb-4">
            <thead class="table-light">
                <tr>
                    <th class="text-danger">日</th>
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th class="text-primary">土</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $startDow  = $firstDay->dayOfWeek;
                    $cellIndex = 0;
                @endphp
                <tr>
                    @for ($i = 0; $i < $startDow; $i++)
                        <td class="bg-light"></td>
                        @php $cellIndex++ @endphp
                    @endfor

                    @foreach ($calendar as $date => $info)
                        @if ($cellIndex > 0 && $cellIndex % 7 === 0)
                            </tr><tr>
                        @endif
                        <td style="height: 64px;">
                            <div class="small text-muted mb-1">{{ \Carbon\Carbon::parse($date)->day }}</div>
                            @if ($info['availability'] === \App\Enums\CalendarAvailability::Available)
                                <a href="{{ route('user.reservations.create', ['slot_id' => $info['slot_id'], 'plan_id' => $plan->id]) }}"
                                   class="btn btn-success btn-sm px-2 py-0 fw-bold">○</a>
                            @elseif ($info['availability'] === \App\Enums\CalendarAvailability::Limited)
                                <a href="{{ route('user.reservations.create', ['slot_id' => $info['slot_id'], 'plan_id' => $plan->id]) }}"
                                   class="btn btn-warning btn-sm px-2 py-0 fw-bold">△</a>
                            @else
                                <span class="text-muted">×</span>
                            @endif
                        </td>
                        @php $cellIndex++ @endphp
                    @endforeach
                </tr>
            </tbody>
        </table>

        {{-- 凡例 --}}
        <div class="d-flex gap-3 justify-content-center small text-muted">
            <span><span class="badge bg-success me-1">○</span>空室あり</span>
            <span><span class="badge bg-warning text-dark me-1">△</span>残りわずか</span>
            <span><span class="text-muted me-1">×</span>満室</span>
        </div>

    </div>
</section>

@endsection
