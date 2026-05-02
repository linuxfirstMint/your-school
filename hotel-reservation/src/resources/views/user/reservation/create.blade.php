@extends('layouts.app')

@section('title', '予約フォーム')

@section('content')
<h1>予約フォーム</h1>

<table border="1">
    <tr><th>プラン</th><td>{{ $plan->name }}</td></tr>
    <tr><th>日程</th><td>{{ $slot->start->format('Y/m/d') }} 〜 {{ $slot->end->format('Y/m/d') }}</td></tr>
    <tr><th>料金</th><td>{{ number_format($price) }}円</td></tr>
</table>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('user.reservations.confirm') }}" method="POST" novalidate>
    @csrf
    <input type="hidden" name="slot_id" value="{{ $slot->id }}">
    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

    <p>
        <label>姓 <span style="color:red">*</span>：<input type="text" name="last_name" value="{{ old('last_name') }}" required></label>
        <label>名 <span style="color:red">*</span>：<input type="text" name="first_name" value="{{ old('first_name') }}" required></label>
    </p>
    <p><label>メールアドレス <span style="color:red">*</span>：<input type="email" name="email" value="{{ old('email') }}" required></label></p>
    <p><label>住所 <span style="color:red">*</span>：<input type="text" name="address" value="{{ old('address') }}" required size="50"></label></p>
    <p><label>電話番号 <span style="color:red">*</span>：<input type="tel" name="phone" value="{{ old('phone') }}" required></label></p>
    <p><label>ホテルへのメッセージ：<br><textarea name="message" rows="4" cols="50">{{ old('message') }}</textarea></label></p>

    <button type="submit">確認画面へ</button>
</form>
@endsection
