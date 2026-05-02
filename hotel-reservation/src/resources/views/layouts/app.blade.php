<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ url('/') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="メニューを開く">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">TOP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.plans.index') }}">宿泊プラン</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.rooms') }}">客室紹介</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.access') }}">アクセス案内</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">お問い合わせ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1 fw-semibold">{{ config('app.name') }}</p>
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
