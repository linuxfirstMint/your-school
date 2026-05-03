@extends('layouts.app')

@section('title', 'お問い合わせ内容の確認')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-2">お問い合わせ内容の確認</h1>
    <p class="text-muted mb-4">以下の内容でお問い合わせを送信します。よろしければ「送信する」ボタンを押してください。</p>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <table class="table table-bordered mb-0">
                        <tr><th class="table-light" style="width:9em;">姓名</th><td>{{ $validated['last_name'] }} {{ $validated['first_name'] }}</td></tr>
                        <tr><th class="table-light">メールアドレス</th><td>{{ $validated['email'] }}</td></tr>
                        <tr><th class="table-light">住所</th><td>{{ $validated['address'] }}</td></tr>
                        <tr><th class="table-light">電話番号</th><td>{{ $validated['phone'] }}</td></tr>
                        <tr><th class="table-light">お問い合わせ内容</th><td>{{ $validated['message'] ?? '—' }}</td></tr>
                    </table>
                </div>
            </div>

            <form action="{{ route('user.contact.store') }}" method="POST">
                @csrf
                @foreach ($validated as $key => $value)
                    @if ($value !== null)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <div class="d-flex gap-2">
                    <a href="{{ route('user.contact.create') }}" class="btn btn-outline-secondary flex-fill">
                        戻って修正する
                    </a>
                    <button type="submit" class="btn btn-primary flex-fill">
                        送信する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
