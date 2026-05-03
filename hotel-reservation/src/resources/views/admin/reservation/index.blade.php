@extends('layouts.admin')

@section('title', '予約管理')

@section('content')

<div class="container">

    <h1 class="h4 fw-bold mb-4">予約管理</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>宿泊者</th>
                            <th>プラン</th>
                            <th>部屋タイプ</th>
                            <th>日程</th>
                            <th>料金</th>
                            <th>ステータス</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td class="text-muted small">{{ $reservation->id }}</td>
                                <td>{{ $reservation->last_name }} {{ $reservation->first_name }}</td>
                                <td>{{ $reservation->plan_name }}</td>
                                <td>{{ $reservation->reservationSlot->roomType->name }}</td>
                                <td class="small">
                                    {{ $reservation->reservationSlot->start->format('Y/m/d') }}
                                    〜
                                    {{ $reservation->reservationSlot->end->format('Y/m/d') }}
                                </td>
                                <td>{{ number_format($reservation->price) }}円</td>
                                <td>
                                    @if ($reservation->status === \App\Enums\ReservationStatus::Confirmed)
                                        <span class="badge bg-primary">確定</span>
                                    @else
                                        <span class="badge bg-secondary">キャンセル済み</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($reservation->status === \App\Enums\ReservationStatus::Confirmed)
                                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('キャンセルしますか？')">
                                                キャンセル
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">予約データがありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $reservations->links() }}
    </div>

</div>

@endsection
