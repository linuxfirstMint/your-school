@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
<h1>管理者ダッシュボード</h1>
<p>ログイン中: {{ Auth::guard('admin')->user()->last_name }} {{ Auth::guard('admin')->user()->first_name }}</p>
<form method="POST" action="{{ route('admin.logout') }}">
    @csrf
    @method('DELETE')
    <button type="submit">ログアウト</button>
</form>
@endsection
