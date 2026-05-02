<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>宿泊プラン一覧</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<h1>宿泊プラン一覧</h1>

@forelse ($plans as $plan)
    <div>
        <h2>{{ $plan->name }}</h2>
        @if ($plan->description)
            <p>{{ $plan->description }}</p>
        @endif
        <a href="{{ route('user.plans.show', $plan) }}">詳細を見る</a>
    </div>
@empty
    <p>現在公開中のプランはありません。</p>
@endforelse

{{ $plans->links() }}
</body>
</html>
