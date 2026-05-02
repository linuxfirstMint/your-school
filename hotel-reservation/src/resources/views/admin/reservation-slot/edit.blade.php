@extends('layouts.admin')

@section('title', '予約枠編集')

@section('content')
<h1>予約枠編集</h1>
<a href="{{ route('admin.reservation-slots.index') }}">← 一覧へ戻る</a>

@if (session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif
@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.reservation-slots.update', $reservationSlot) }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    <p>
        <label>部屋タイプ <span style="color:red">*</span>：
            <select name="room_type_id" required>
                @foreach ($roomTypes as $roomType)
                    <option value="{{ $roomType->id }}"
                        @selected(old('room_type_id', $reservationSlot->room_type_id) == $roomType->id)>
                        {{ $roomType->name }}（{{ $roomType->count }}室）
                    </option>
                @endforeach
            </select>
        </label>
    </p>
    <p>
        <label>チェックイン <span style="color:red">*</span>：
            <input type="date" name="start"
                   value="{{ old('start', $reservationSlot->start->format('Y-m-d')) }}" required>
        </label>
    </p>
    <p>
        <label>チェックアウト <span style="color:red">*</span>：
            <input type="date" name="end"
                   value="{{ old('end', $reservationSlot->end->format('Y-m-d')) }}" required>
        </label>
    </p>

    <button type="submit">更新</button>
</form>
@endsection
