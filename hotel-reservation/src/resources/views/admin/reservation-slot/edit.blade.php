@extends('layouts.admin')

@section('title', '予約枠編集')

@section('content')

<div class="container" style="max-width: 560px;">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.reservation-slots.index') }}">予約枠管理</a></li>
            <li class="breadcrumb-item active" aria-current="page">編集</li>
        </ol>
    </nav>

    <h1 class="h4 fw-bold mb-4">予約枠編集</h1>

    @if (session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.reservation-slots.update', $reservationSlot) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">部屋タイプ <span class="text-danger">*</span></label>
                    <select name="room_type_id" class="form-select" required>
                        @foreach ($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}"
                                @selected(old('room_type_id', $reservationSlot->room_type_id) == $roomType->id)>
                                {{ $roomType->name }}（{{ $roomType->count }}室）
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">チェックイン <span class="text-danger">*</span></label>
                    <input type="date" name="start" class="form-control"
                           value="{{ old('start', $reservationSlot->start->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">チェックアウト <span class="text-danger">*</span></label>
                    <input type="date" name="end" class="form-control"
                           value="{{ old('end', $reservationSlot->end->format('Y-m-d')) }}" required>
                </div>

                <button type="submit" class="btn btn-dark">更新</button>
            </form>
        </div>
    </div>

</div>

@endsection
