@extends('layouts.admin')

@section('title', '管理者ログイン')

@section('content')

<div class="container" style="max-width: 420px;">
    <div class="text-center mb-4 mt-4">
        <h1 class="h4 fw-bold">管理者ログイン</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">ログイン</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
