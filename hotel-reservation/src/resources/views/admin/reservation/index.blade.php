@extends('layouts.admin')

@section('title', '予約一覧')

@section('content')
<h1>予約一覧</h1>

@if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>宿泊者</th>
            <th>プラン</th>
            <th>部屋タイプ</th>
            <th>日程</th>
            <th>料金</th>
            <th>ステータス</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ $reservation->last_name }} {{ $reservation->first_name }}</td>
                <td>{{ $reservation->plan_name }}</td>
                <td>{{ $reservation->reservationSlot->roomType->name }}</td>
                <td>
                    {{ $reservation->reservationSlot->start->format('Y/m/d') }}
                    〜
                    {{ $reservation->reservationSlot->end->format('Y/m/d') }}
                </td>
                <td>{{ number_format($reservation->price) }}円</td>
                <td>{{ $reservation->status->name }}</td>
                <td>
                    @if ($reservation->status === \App\Enums\ReservationStatus::Confirmed)
                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('キャンセルしますか？')">キャンセル</button>
                        </form>
                    @else
                        —
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $reservations->links() }}

<p><a href="{{ route('admin.dashboard') }}">← ダッシュボードへ</a></p>
@endsection
