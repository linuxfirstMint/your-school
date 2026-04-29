<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>宿泊プラン編集</title>
</head>
<body>
<h1>宿泊プラン編集</h1>
<a href="{{ route('admin.plans.index') }}">← 一覧へ</a>

<form action="{{ route('admin.plans.update', $plan) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label>プラン名：<input type="text" name="name" value="{{ old('name', $plan->name) }}" required></label>
    <label>説明：<textarea name="description">{{ old('description', $plan->description) }}</textarea></label>
    <label>画像（変更する場合のみ）：<input type="file" name="images[]" multiple accept="image/*"></label>
    @foreach ($roomTypes as $roomType)
        @php $price = $plan->planRoomPrices->firstWhere('room_type_id', $roomType->id) @endphp
        <label>{{ $roomType->name }} 料金：
            <input type="number" name="prices[{{ $roomType->id }}]" min="0"
                   value="{{ old("prices.{$roomType->id}", $price?->price) }}">
        </label>
    @endforeach
    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <button type="submit">更新</button>
</form>
</body>
</html>
