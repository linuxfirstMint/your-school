<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '管理画面') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @auth('admin')
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">管理画面</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="メニューを開く">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.plans.index') }}">プラン管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reservation-slots.index') }}">予約枠管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reservations.index') }}">予約管理</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-light btn-sm">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <main>
        @yield('content')
    </main>
</body>
</html>
