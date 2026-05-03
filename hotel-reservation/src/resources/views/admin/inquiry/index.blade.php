@extends('layouts.admin')

@section('title', 'お問い合わせ管理')

@section('content')
<div class="container">

    <h1 class="h4 fw-bold mb-4">お問い合わせ管理</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>氏名</th>
                            <th>メールアドレス</th>
                            <th>受付日時</th>
                            <th>ステータス</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inquiries as $inquiry)
                            <tr>
                                <td class="text-muted small">{{ $inquiry->id }}</td>
                                <td>{{ $inquiry->last_name }} {{ $inquiry->first_name }}</td>
                                <td>{{ $inquiry->email }}</td>
                                <td class="small text-muted">{{ $inquiry->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    @if ($inquiry->status === \App\Enums\InquiryStatus::Pending)
                                        <span class="badge bg-danger">{{ $inquiry->status->label() }}</span>
                                    @elseif ($inquiry->status === \App\Enums\InquiryStatus::InProgress)
                                        <span class="badge bg-warning text-dark">{{ $inquiry->status->label() }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $inquiry->status->label() }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}"
                                       class="btn btn-sm btn-outline-primary">詳細</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">お問い合わせはありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $inquiries->links() }}
    </div>

</div>
@endsection
