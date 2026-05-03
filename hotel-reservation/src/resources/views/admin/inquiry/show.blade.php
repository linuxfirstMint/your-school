@extends('layouts.admin')

@section('title', 'お問い合わせ詳細')

@section('content')
<div class="container">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary btn-sm">← 一覧に戻る</a>
        <h1 class="h4 fw-bold mb-0">お問い合わせ詳細</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <table class="table table-bordered mb-0">
                <tr>
                    <th class="table-light" style="width:10em;">氏名</th>
                    <td>{{ $inquiry->last_name }} {{ $inquiry->first_name }}</td>
                </tr>
                <tr>
                    <th class="table-light">メールアドレス</th>
                    <td>{{ $inquiry->email }}</td>
                </tr>
                <tr>
                    <th class="table-light">住所</th>
                    <td>{{ $inquiry->address }}</td>
                </tr>
                <tr>
                    <th class="table-light">電話番号</th>
                    <td>{{ $inquiry->phone }}</td>
                </tr>
                <tr>
                    <th class="table-light">お問い合わせ内容</th>
                    <td>{{ $inquiry->message ?? '—' }}</td>
                </tr>
                <tr>
                    <th class="table-light">受付日時</th>
                    <td>{{ $inquiry->created_at->format('Y/m/d H:i') }}</td>
                </tr>
                <tr>
                    <th class="table-light">ステータス</th>
                    <td>
                        @if ($inquiry->status === \App\Enums\InquiryStatus::Pending)
                            <span class="badge bg-danger">{{ $inquiry->status->label() }}</span>
                        @elseif ($inquiry->status === \App\Enums\InquiryStatus::InProgress)
                            <span class="badge bg-warning text-dark">{{ $inquiry->status->label() }}</span>
                        @else
                            <span class="badge bg-success">{{ $inquiry->status->label() }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <form action="{{ route('admin.inquiries.update', $inquiry) }}" method="POST" class="d-flex gap-2 flex-wrap">
        @csrf
        @method('PUT')
        @foreach (\App\Enums\InquiryStatus::cases() as $status)
            @if ($status !== $inquiry->status)
                <button type="submit" name="status" value="{{ $status->value }}"
                        class="btn {{ $status === \App\Enums\InquiryStatus::Resolved ? 'btn-success' : ($status === \App\Enums\InquiryStatus::InProgress ? 'btn-warning' : 'btn-outline-secondary') }}">
                    {{ $status->label() }}にする
                </button>
            @endif
        @endforeach
    </form>

</div>
@endsection
