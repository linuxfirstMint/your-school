<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '管理画面') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    {{-- ===== 固定ヘッダー ===== --}}
    <nav class="navbar bg-dark navbar-dark shadow-sm fixed-top" style="height:56px;">
        <div class="container-fluid px-3">

            {{-- モバイル：ハンバーガーボタン + サイト名 --}}
            @auth('admin')
            <button class="navbar-toggler d-lg-none me-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#adminOffcanvas"
                    aria-controls="adminOffcanvas" aria-label="メニューを開く">
                <span class="navbar-toggler-icon"></span>
            </button>
            @endauth

            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">管理画面</a>

            <div class="ms-auto d-flex align-items-center gap-2">
                {{-- 表サイトへ --}}
                <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm" target="_blank">
                    表サイトへ
                </a>
                {{-- ログアウト --}}
                @auth('admin')
                <form method="POST" action="{{ route('admin.logout') }}" class="mb-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-light btn-sm">ログアウト</button>
                </form>
                @endauth
            </div>
        </div>
    </nav>

    @auth('admin')
    {{-- ===== モバイル用 Offcanvas サイドメニュー ===== --}}
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1"
         id="adminOffcanvas" aria-labelledby="adminOffcanvasLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title" id="adminOffcanvasLabel">管理メニュー</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="閉じる"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('layouts.admin-nav')
        </div>
    </div>
    @endauth

    <div class="d-flex" style="padding-top:56px; min-height:100vh;">

        @auth('admin')
        {{-- ===== 左固定サイドバー（デスクトップのみ表示）===== --}}
        <aside class="d-none d-lg-block bg-dark text-white"
               style="width:220px; min-width:220px; position:fixed; top:56px; left:0; height:calc(100vh - 56px); overflow-y:auto; z-index:99;">
            @include('layouts.admin-nav')
        </aside>

        {{-- ===== メインコンテンツ ===== --}}
        <main class="flex-grow-1 p-4" id="adminMain">
            @yield('content')
        </main>

        <style>
            @media (min-width: 992px) {
                #adminMain { margin-left: 220px; }
            }
        </style>

        @else
        {{-- 未認証（ログイン画面など） --}}
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
        @endauth

    </div>

</body>
</html>
