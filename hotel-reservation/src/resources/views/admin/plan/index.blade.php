<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>宿泊プラン管理</title>
</head>
<body>
<h1>宿泊プラン管理</h1>
<a href="{{ route('admin.dashboard') }}">← ダッシュボードへ</a>

<h2>プラン作成</h2>
<form action="{{ route('admin.plans.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    <label>プラン名 <span style="color:red">*</span>：<input type="text" name="name" value="{{ old('name') }}" required></label>
    <label>説明：<textarea name="description">{{ old('description') }}</textarea></label>
    <label>画像：<input type="file" name="images[]" multiple accept="image/*"></label>
    @foreach ($roomTypes as $roomType)
        <label>{{ $roomType->name }} 料金：
            <input type="number" name="prices[{{ $roomType->id }}]" min="0">
        </label>
    @endforeach
    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <button type="submit">作成</button>
</form>

<h2>プラン一覧</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>プラン名</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($plans as $plan)
            <tr>
                <td>{{ $plan->id }}</td>
                <td>{{ $plan->name }}</td>
                <td>
                    <a href="{{ route('admin.plans.edit', $plan) }}">編集</a>
                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $plans->links() }}
</body>
</html>
