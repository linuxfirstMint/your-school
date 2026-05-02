@extends('layouts.app')

@section('title', '予約完了')

@section('content')

<section class="py-5">
    <div class="container" style="max-width: 560px;">
        <div class="text-center py-5">
            <div class="mb-4" style="font-size: 3rem;">✓</div>
            <h1 class="h3 fw-light mb-3">予約が完了しました</h1>
            <p class="text-muted mb-5">
                ご予約ありがとうございます。<br>
                確認メールをお送りします。
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('user.plans.index') }}" class="btn btn-dark px-4">他のプランを見る</a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4">TOPページへ</a>
            </div>
        </div>
    </div>
</section>

@endsection
