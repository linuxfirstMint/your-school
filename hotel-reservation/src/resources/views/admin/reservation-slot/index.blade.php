@extends('layouts.admin')

@section('title', '予約枠管理')

@section('content')

<div class="container">

    <h1 class="h4 fw-bold mb-4">予約枠管理</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4 mb-4">
        {{-- 個別作成 --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">個別作成</div>
                <div class="card-body">
                    <form action="{{ route('admin.reservation-slots.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">部屋タイプ <span class="text-danger">*</span></label>
                            <select name="room_type_id" class="form-select" required>
                                <option value="">選択してください</option>
                                @foreach ($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}" @selected(old('room_type_id') == $roomType->id)>
                                        {{ $roomType->name }}（{{ $roomType->count }}室）
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">チェックイン <span class="text-danger">*</span></label>
                            <input type="date" name="start" value="{{ old('start') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">チェックアウト <span class="text-danger">*</span></label>
                            <input type="date" name="end" value="{{ old('end') }}" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm">作成</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 期間一括作成 --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">期間一括作成</div>
                <div class="card-body">
                    <form action="{{ route('admin.reservation-slots.bulk-store') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">部屋タイプ <span class="text-danger">*</span></label>
                            <select name="room_type_id" class="form-select" required>
                                <option value="">選択してください</option>
                                @foreach ($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}">
                                        {{ $roomType->name }}（{{ $roomType->count }}室）
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">開始日 <span class="text-danger">*</span></label>
                            <input type="date" name="from" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">終了日（最終チェックアウト日） <span class="text-danger">*</span></label>
                            <input type="date" name="to" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm">一括作成</button>
                        <p class="text-muted small mt-2 mb-0">※開始日〜終了日の前日まで、1泊ずつ予約枠を作成します。定員に達した日程はスキップします。</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 予約枠一覧 --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold">予約枠一覧</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>部屋タイプ</th>
                            <th>チェックイン</th>
                            <th>チェックアウト</th>
                            <th>ステータス</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slots as $slot)
                            <tr>
                                <td class="text-muted small">{{ $slot->id }}</td>
                                <td>{{ $slot->roomType->name }}</td>
                                <td>{{ $slot->start->format('Y/m/d') }}</td>
                                <td>{{ $slot->end->format('Y/m/d') }}</td>
                                <td>
                                    @if ($slot->status === \App\Enums\ReservationSlotStatus::Available)
                                        <span class="badge bg-success">空室</span>
                                    @else
                                        <span class="badge bg-warning text-dark">予約済み</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($slot->status === \App\Enums\ReservationSlotStatus::Available)
                                        <a href="{{ route('admin.reservation-slots.edit', $slot) }}"
                                           class="btn btn-outline-secondary btn-sm me-1">編集</a>
                                        <form action="{{ route('admin.reservation-slots.destroy', $slot) }}" method="POST"
                                              style="display:inline" onsubmit="return confirm('削除しますか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">削除</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">予約枠がありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $slots->links() }}
    </div>

</div>

@endsection
