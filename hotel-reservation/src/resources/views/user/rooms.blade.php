@extends('layouts.app')

@section('title', '客室紹介')

@section('content')

{{-- ページヘッダー --}}
<section class="bg-light py-5 border-bottom">
    <div class="container text-center">
        <p class="text-muted text-uppercase mb-2" style="letter-spacing: .2em; font-size: .75rem;">Rooms</p>
        <h1 class="h2 fw-light mb-0">客室紹介</h1>
    </div>
</section>

{{-- スタンダードルーム --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80"
                     class="img-fluid rounded shadow-sm w-100"
                     style="height: 360px; object-fit: cover;"
                     alt="スタンダードルーム">
            </div>
            <div class="col-lg-6 ps-lg-5">
                <p class="text-muted text-uppercase mb-1" style="letter-spacing: .2em; font-size: .7rem;">Standard Room</p>
                <h2 class="h3 fw-light mb-3">スタンダードルーム</h2>
                <p class="text-muted mb-4 lh-lg">
                    中禅寺湖を望む落ち着いた和洋室。
                    木のぬくもりを活かしたインテリアと、快適な眠りを提供するプレミアムマットレスで
                    ゆったりとした時間をお過ごしいただけます。
                </p>
                <dl class="row small text-muted mb-4">
                    <dt class="col-sm-4">広さ</dt>
                    <dd class="col-sm-8">32㎡</dd>
                    <dt class="col-sm-4">定員</dt>
                    <dd class="col-sm-8">1〜2名</dd>
                    <dt class="col-sm-4">ベッド</dt>
                    <dd class="col-sm-8">ダブルベッド × 1</dd>
                    <dt class="col-sm-4">客室数</dt>
                    <dd class="col-sm-8">3室</dd>
                </dl>
                <div class="mb-4">
                    <p class="small fw-semibold mb-2">設備・アメニティ</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (['Wi-Fi（無料）', '薄型テレビ', '冷蔵庫', 'ミニバー', 'バスルーム', 'ドライヤー', 'アメニティセット', 'セーフティボックス'] as $item)
                            <span class="badge bg-light text-dark border small">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('user.plans.index') }}" class="btn btn-dark px-4">
                    このお部屋のプランを見る →
                </a>
            </div>
        </div>
    </div>
</section>

<hr class="my-0">

{{-- デラックスルーム --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-center flex-lg-row-reverse">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80"
                     class="img-fluid rounded shadow-sm w-100"
                     style="height: 360px; object-fit: cover;"
                     alt="デラックスルーム">
            </div>
            <div class="col-lg-6 pe-lg-5">
                <p class="text-muted text-uppercase mb-1" style="letter-spacing: .2em; font-size: .7rem;">Deluxe Room</p>
                <h2 class="h3 fw-light mb-3">デラックスルーム</h2>
                <p class="text-muted mb-4 lh-lg">
                    湖畔の絶景を独り占めできる、開放的なリビングスペースを備えた上質な客室。
                    専用バルコニーからは四季折々の中禅寺湖を一望でき、
                    特別なひとときをお楽しみいただけます。
                </p>
                <dl class="row small text-muted mb-4">
                    <dt class="col-sm-4">広さ</dt>
                    <dd class="col-sm-8">52㎡</dd>
                    <dt class="col-sm-4">定員</dt>
                    <dd class="col-sm-8">1〜3名</dd>
                    <dt class="col-sm-4">ベッド</dt>
                    <dd class="col-sm-8">キングサイズベッド × 1</dd>
                    <dt class="col-sm-4">客室数</dt>
                    <dd class="col-sm-8">2室</dd>
                </dl>
                <div class="mb-4">
                    <p class="small fw-semibold mb-2">設備・アメニティ</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (['Wi-Fi（無料）', '薄型テレビ', '冷蔵庫', 'ミニバー', 'バスルーム', 'シャワーブース', 'ドライヤー', 'アメニティセット', 'セーフティボックス', '専用バルコニー'] as $item)
                            <span class="badge bg-light text-dark border small">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('user.plans.index') }}" class="btn btn-dark px-4">
                    このお部屋のプランを見る →
                </a>
            </div>
        </div>
    </div>
</section>

<hr class="my-0">

{{-- スイートルーム --}}
<section class="py-5 mb-3">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80"
                     class="img-fluid rounded shadow-sm w-100"
                     style="height: 360px; object-fit: cover;"
                     alt="スイートルーム">
            </div>
            <div class="col-lg-6 ps-lg-5">
                <p class="text-muted text-uppercase mb-1" style="letter-spacing: .2em; font-size: .7rem;">Suite Room</p>
                <h2 class="h3 fw-light mb-3">スイートルーム</h2>
                <p class="text-muted mb-4 lh-lg">
                    最上階に位置する、当ホテル最高峰の滞在体験を提供するプレミアムスイート。
                    専用ジャグジーバス、リビング・ダイニングスペースを完備し、
                    記念日や特別なご旅行に最適な至極の空間です。
                </p>
                <dl class="row small text-muted mb-4">
                    <dt class="col-sm-4">広さ</dt>
                    <dd class="col-sm-8">96㎡</dd>
                    <dt class="col-sm-4">定員</dt>
                    <dd class="col-sm-8">1〜4名</dd>
                    <dt class="col-sm-4">ベッド</dt>
                    <dd class="col-sm-8">キングサイズベッド × 1 ＋ ソファベッド × 1</dd>
                    <dt class="col-sm-4">客室数</dt>
                    <dd class="col-sm-8">1室</dd>
                </dl>
                <div class="mb-4">
                    <p class="small fw-semibold mb-2">設備・アメニティ</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (['Wi-Fi（無料）', '薄型テレビ × 2', '冷蔵庫', 'ミニバー', 'ジャグジーバス', 'シャワーブース', 'ドライヤー', 'プレミアムアメニティ', 'セーフティボックス', '専用バルコニー', 'エスプレッソマシン', 'バトラーサービス'] as $item)
                            <span class="badge bg-light text-dark border small">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('user.plans.index') }}" class="btn btn-dark px-4">
                    このお部屋のプランを見る →
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
