@extends('layouts.app')

@section('title', config('app.name'))

@section('content')

{{-- ヒーロー --}}
<section class="hero-section d-flex align-items-center justify-content-center text-white text-center"
         style="min-height: 90vh; background: linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600&q=80') center/cover no-repeat;">
    <div class="px-3">
        <p class="text-uppercase letter-spacing-wide mb-2" style="letter-spacing: .25em; font-size: .875rem;">Welcome to</p>
        <h1 class="display-3 fw-light mb-4">{{ config('app.name') }}</h1>
        <p class="fs-5 mb-5 fw-light" style="max-width: 520px; margin-inline: auto;">
            自然に包まれた静寂の中で、<br>非日常の一夜をお過ごしください。
        </p>
        <a href="{{ route('user.plans.index') }}" class="btn btn-outline-light btn-lg px-5 py-3">
            宿泊プランを見る
        </a>
    </div>
</section>

{{-- コンセプト --}}
<section class="py-6 bg-white">
    <div class="container" style="max-width: 720px;">
        <div class="text-center mb-5">
            <p class="text-muted text-uppercase mb-2" style="letter-spacing: .2em; font-size: .75rem;">Concept</p>
            <h2 class="h3 fw-light mb-4">くつろぎと、出会いの宿</h2>
            <p class="text-muted lh-lg">
                山の緑と川のせせらぎに囲まれた静かな環境の中に佇む当ホテルは、<br>
                訪れるすべての方に「本物の休息」を提供することを使命としています。<br>
                丁寧にセレクトされた食材を使ったお料理と、洗練された客室で<br>
                日常から離れた特別な時間をお楽しみください。
            </p>
        </div>
    </div>
</section>

{{-- 宿泊プラン導線 --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-muted text-uppercase mb-2" style="letter-spacing: .2em; font-size: .75rem;">Plans</p>
            <h2 class="h3 fw-light">宿泊プラン</h2>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-5">
                            <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80"
                                 class="img-fluid rounded-start h-100 object-fit-cover"
                                 style="min-height: 220px; object-fit: cover;"
                                 alt="客室イメージ">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body p-4">
                                <h3 class="card-title h5 fw-semibold mb-3">プランから空室を探す</h3>
                                <p class="card-text text-muted small mb-4">
                                    お好みのプランを選び、ご希望の日程の空室状況をカレンダーでご確認いただけます。
                                    そのままオンラインでご予約が可能です。
                                </p>
                                <a href="{{ route('user.plans.index') }}" class="btn btn-dark px-4">
                                    プラン一覧を見る →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- クイックリンク（客室・アクセス） --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <a href="#" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded" style="height: 240px;">
                        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80"
                             class="w-100 h-100" style="object-fit: cover;" alt="客室紹介">
                        <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white"
                             style="background: linear-gradient(transparent, rgba(0,0,0,.6));">
                            <p class="text-uppercase mb-1" style="letter-spacing: .15em; font-size: .7rem;">Rooms</p>
                            <h3 class="h5 mb-0 fw-light">客室紹介</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-5">
                <a href="#" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded" style="height: 240px;">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80"
                             class="w-100 h-100" style="object-fit: cover;" alt="アクセス案内">
                        <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white"
                             style="background: linear-gradient(transparent, rgba(0,0,0,.6));">
                            <p class="text-uppercase mb-1" style="letter-spacing: .15em; font-size: .7rem;">Access</p>
                            <h3 class="h5 mb-0 fw-light">アクセス案内</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
