<nav class="nav flex-column pt-2 px-2">
    <a class="nav-link rounded mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-secondary' : 'text-white-50' }} text-white"
       href="{{ route('admin.dashboard') }}">ダッシュボード</a>

    <div class="text-white-50 small px-3 pt-3 pb-1 text-uppercase" style="font-size:.7rem; letter-spacing:.08em;">予約</div>

    <a class="nav-link rounded mb-1 {{ request()->routeIs('admin.reservations.*') ? 'bg-secondary' : 'text-white-50' }} text-white"
       href="{{ route('admin.reservations.index') }}">予約一覧</a>

    <a class="nav-link rounded mb-1 {{ request()->routeIs('admin.reservation-slots.*') ? 'bg-secondary' : 'text-white-50' }} text-white"
       href="{{ route('admin.reservation-slots.index') }}">予約枠管理</a>

    <div class="text-white-50 small px-3 pt-3 pb-1 text-uppercase" style="font-size:.7rem; letter-spacing:.08em;">コンテンツ</div>

    <a class="nav-link rounded mb-1 {{ request()->routeIs('admin.plans.*') ? 'bg-secondary' : 'text-white-50' }} text-white"
       href="{{ route('admin.plans.index') }}">プラン一覧</a>

    <div class="text-white-50 small px-3 pt-3 pb-1 text-uppercase" style="font-size:.7rem; letter-spacing:.08em;">その他</div>

    <a class="nav-link rounded mb-1 {{ request()->routeIs('admin.inquiries.*') ? 'bg-secondary' : 'text-white-50' }} text-white"
       href="{{ route('admin.inquiries.index') }}">お問い合わせ一覧</a>
</nav>
