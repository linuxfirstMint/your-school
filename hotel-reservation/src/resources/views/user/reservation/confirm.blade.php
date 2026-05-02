@extends('layouts.app')

@section('title', '予約内容確認')

@section('content')

<section class="py-5">
    <div class="container" style="max-width: 680px;">

        <h1 class="h3 fw-light mb-2">予約内容確認</h1>
        <p class="text-muted mb-4">以下の内容で予約を確定します。よろしければ「予約を確定する」ボタンを押してください。</p>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <table class="table table-bordered mb-0">
                    <tr><th class="table-light" style="width:9em;">プラン</th><td>{{ $plan->name }}</td></tr>
                    <tr><th class="table-light">日程</th><td>{{ $slot->start->format('Y/m/d') }} 〜 {{ $slot->end->format('Y/m/d') }}</td></tr>
                    <tr><th class="table-light">料金</th><td class="fw-semibold">{{ number_format($price) }}円</td></tr>
                    <tr><th class="table-light">氏名</th><td>{{ $validated['last_name'] }} {{ $validated['first_name'] }}</td></tr>
                    <tr><th class="table-light">メールアドレス</th><td>{{ $validated['email'] }}</td></tr>
                    <tr><th class="table-light">住所</th><td>{{ $validated['address'] }}</td></tr>
                    <tr><th class="table-light">電話番号</th><td>{{ $validated['phone'] }}</td></tr>
                    <tr><th class="table-light">メッセージ</th><td>{{ $validated['message'] ?? '—' }}</td></tr>
                </table>
            </div>
        </div>

        <form action="{{ route('user.reservations.store') }}" method="POST">
            @csrf
            @foreach ($validated as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-dark btn-lg">予約を確定する</button>
            </div>
        </form>

        <div class="text-center">
            <a href="{{ route('user.reservations.create', ['slot_id' => $slot->id, 'plan_id' => $plan->id]) }}"
               class="text-muted small">← 入力に戻る</a>
        </div>

    </div>
</section>

@endsection
