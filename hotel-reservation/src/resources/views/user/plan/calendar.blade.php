<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>空室カレンダー - {{ $plan->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<a href="{{ route('user.plans.show', $plan) }}">← プラン詳細へ</a>

<h1>{{ $plan->name }} — 空室カレンダー</h1>
<h2>{{ $firstDay->format('Y年n月') }}</h2>

<table border="1">
    <thead>
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>
    </thead>
    <tbody>
        @php
            $startDow = $firstDay->dayOfWeek; // 0=Sun
            $cellIndex = 0;
        @endphp
        <tr>
            @for ($i = 0; $i < $startDow; $i++)
                <td></td>
                @php $cellIndex++ @endphp
            @endfor

            @foreach ($calendar as $date => $info)
                @if ($cellIndex > 0 && $cellIndex % 7 === 0)
                    </tr><tr>
                @endif
                <td>
                    {{ \Carbon\Carbon::parse($date)->day }}日<br>
                    @if ($info['availability'] === \App\Enums\CalendarAvailability::Available)
                        <a href="{{ route('user.reservations.create', ['slot_id' => $info['slot_id'], 'plan_id' => $plan->id]) }}">○</a>
                    @elseif ($info['availability'] === \App\Enums\CalendarAvailability::Limited)
                        <a href="{{ route('user.reservations.create', ['slot_id' => $info['slot_id'], 'plan_id' => $plan->id]) }}">△</a>
                    @else
                        ×
                    @endif
                </td>
                @php $cellIndex++ @endphp
            @endforeach
        </tr>
    </tbody>
</table>

<div>
    @php
        $prevMonth = $firstDay->copy()->subMonth();
        $nextMonth = $firstDay->copy()->addMonth();
    @endphp
    <a href="{{ route('user.plans.calendar', ['plan' => $plan, 'year' => $prevMonth->year, 'month' => $prevMonth->month]) }}">← 前月</a>
    &nbsp;
    <a href="{{ route('user.plans.calendar', ['plan' => $plan, 'year' => $nextMonth->year, 'month' => $nextMonth->month]) }}">翌月 →</a>
</div>
</body>
</html>
