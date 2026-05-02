@extends('layouts.app')

@section('title', '予約内容確認')

@section('content')
<h1>予約内容確認</h1>
<p>以下の内容で予約を確定します。よろしければ「予約を確定する」ボタンを押してください。</p>

<table border="1">
    <tr><th>プラン</th><td>{{ $plan->name }}</td></tr>
    <tr><th>日程</th><td>{{ $slot->start->format('Y/m/d') }} 〜 {{ $slot->end->format('Y/m/d') }}</td></tr>
    <tr><th>料金</th><td>{{ number_format($price) }}円</td></tr>
    <tr><th>氏名</th><td>{{ $validated['last_name'] }} {{ $validated['first_name'] }}</td></tr>
    <tr><th>メールアドレス</th><td>{{ $validated['email'] }}</td></tr>
    <tr><th>住所</th><td>{{ $validated['address'] }}</td></tr>
    <tr><th>電話番号</th><td>{{ $validated['phone'] }}</td></tr>
    <tr><th>メッセージ</th><td>{{ $validated['message'] ?? '—' }}</td></tr>
</table>

<form action="{{ route('user.reservations.store') }}" method="POST">
    @csrf
    @foreach ($validated as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <button type="submit">予約を確定する</button>
</form>

<a href="{{ route('user.reservations.create', ['slot_id' => $slot->id, 'plan_id' => $plan->id]) }}">← 入力に戻る</a>
@endsection
