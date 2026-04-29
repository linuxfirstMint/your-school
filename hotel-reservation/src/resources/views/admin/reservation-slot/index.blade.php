<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約枠管理</title>
</head>
<body>
<h1>予約枠管理</h1>
<a href="{{ route('admin.dashboard') }}">← ダッシュボードへ</a>

@if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<h2>個別作成</h2>
<form action="{{ route('admin.reservation-slots.store') }}" method="POST">
    @csrf
    <label>部屋タイプ：
        <select name="room_type_id" required>
            <option value="">選択してください</option>
            @foreach ($roomTypes as $roomType)
                <option value="{{ $roomType->id }}" @selected(old('room_type_id') == $roomType->id)>
                    {{ $roomType->name }}（{{ $roomType->count }}室）
                </option>
            @endforeach
        </select>
    </label>
    <label>チェックイン：<input type="date" name="start" value="{{ old('start') }}" required></label>
    <label>チェックアウト：<input type="date" name="end" value="{{ old('end') }}" required></label>
    <button type="submit">作成</button>
</form>
@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<h2>期間一括作成</h2>
<form action="{{ route('admin.reservation-slots.bulk-store') }}" method="POST">
    @csrf
    <label>部屋タイプ：
        <select name="room_type_id" required>
            <option value="">選択してください</option>
            @foreach ($roomTypes as $roomType)
                <option value="{{ $roomType->id }}">
                    {{ $roomType->name }}（{{ $roomType->count }}室）
                </option>
            @endforeach
        </select>
    </label>
    <label>開始日：<input type="date" name="from" required></label>
    <label>終了日（最終チェックアウト日）：<input type="date" name="to" required></label>
    <button type="submit">一括作成</button>
    <small>※開始日〜終了日の前日まで、1泊ずつ予約枠を作成します。定員に達した日程はスキップします。</small>
</form>

<h2>予約枠一覧</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>部屋タイプ</th>
            <th>チェックイン</th>
            <th>チェックアウト</th>
            <th>ステータス</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($slots as $slot)
            <tr>
                <td>{{ $slot->id }}</td>
                <td>{{ $slot->roomType->name }}</td>
                <td>{{ $slot->start->format('Y/m/d') }}</td>
                <td>{{ $slot->end->format('Y/m/d') }}</td>
                <td>{{ $slot->status->name }}</td>
                <td>
                    @if ($slot->status->name === 'Available')
                        <a href="{{ route('admin.reservation-slots.edit', $slot) }}">編集</a>
                        <form action="{{ route('admin.reservation-slots.destroy', $slot) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    @else
                        予約済み
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6">予約枠がありません</td></tr>
        @endforelse
    </tbody>
</table>

{{ $slots->links() }}
</body>
</html>
