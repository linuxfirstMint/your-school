<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>管理者ダッシュボード</h1>
    <p>ログイン中: {{ Auth::guard('admin')->user()->last_name }} {{ Auth::guard('admin')->user()->first_name }}</p>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        @method('DELETE')
        <button type="submit">ログアウト</button>
    </form>
</body>
</html>
