@extends('layouts.app')

@section('title', '予約フォーム')

@section('content')

<section class="py-5">
    <div class="container" style="max-width: 680px;">

        <h1 class="h3 fw-light mb-4">予約フォーム</h1>

        {{-- 予約内容サマリー --}}
        <div class="card border-0 bg-light mb-4">
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th class="text-muted fw-normal" style="width:6em;">プラン</th><td class="fw-semibold">{{ $plan->name }}</td></tr>
                    <tr><th class="text-muted fw-normal">日程</th><td>{{ $slot->start->format('Y/m/d') }} 〜 {{ $slot->end->format('Y/m/d') }}</td></tr>
                    <tr><th class="text-muted fw-normal">料金</th><td class="fw-semibold">{{ number_format($price) }}円</td></tr>
                </table>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.reservations.confirm') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="slot_id" value="{{ $slot->id }}">
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">姓 <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           class="form-control @error('last_name') is-invalid @enderror" required>
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label">名 <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           class="form-control @error('first_name') is-invalid @enderror" required>
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">メールアドレス <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">住所 <span class="text-danger">*</span></label>
                <input type="text" name="address" value="{{ old('address') }}"
                       class="form-control @error('address') is-invalid @enderror" required>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">電話番号 <span class="text-danger">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       class="form-control @error('phone') is-invalid @enderror" required>
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label">ホテルへのメッセージ</label>
                <textarea name="message" rows="4" class="form-control">{{ old('message') }}</textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-dark btn-lg">確認画面へ →</button>
            </div>
        </form>

    </div>
</section>

@endsection
