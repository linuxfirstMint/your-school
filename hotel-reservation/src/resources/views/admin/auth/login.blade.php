@extends('layouts.admin')

@section('title', '管理者ログイン')

@section('content')
<h1>管理者ログイン</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('admin.login.store') }}" novalidate>
    @csrf
    <div>
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div>
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">ログイン</button>
</form>
@endsection
