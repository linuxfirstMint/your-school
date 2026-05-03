@extends('layouts.app')

@section('title', 'お問い合わせ完了')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="h3 mb-3">お問い合わせを受け付けました</h1>
            <p class="text-muted mb-4">
                お問い合わせいただきありがとうございます。<br>
                確認メールをお送りしましたのでご確認ください。<br>
                担当者よりご連絡いたします。
            </p>
            <a href="{{ route('user.plans.index') }}" class="btn btn-outline-primary">
                プラン一覧に戻る
            </a>
        </div>
    </div>
</div>
@endsection
