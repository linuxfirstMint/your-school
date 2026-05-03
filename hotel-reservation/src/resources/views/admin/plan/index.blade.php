@extends('layouts.admin')

@section('title', 'プラン管理')

@section('content')

<div class="container">

    <h1 class="h4 fw-bold mb-4">プラン管理</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- プラン作成フォーム --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-semibold">プラン作成</div>
        <div class="card-body">
            <form action="{{ route('admin.plans.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">プラン名 <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">説明</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">画像</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">部屋タイプ別料金</label>
                    <div class="row g-2">
                        @foreach ($roomTypes as $roomType)
                            <div class="col-sm-4">
                                <label class="form-label small text-muted">{{ $roomType->name }}</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="prices[{{ $roomType->id }}]" min="0"
                                           value="{{ old("prices.{$roomType->id}") }}"
                                           class="form-control @error("prices.{$roomType->id}") is-invalid @enderror"
                                           placeholder="未設定">
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-sm">作成</button>
            </form>
        </div>
    </div>

    {{-- プラン一覧 --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold">プラン一覧</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>プラン名</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plans as $plan)
                            <tr>
                                <td class="text-muted small">{{ $plan->id }}</td>
                                <td>{{ $plan->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.plans.edit', $plan) }}"
                                       class="btn btn-outline-secondary btn-sm me-1">編集</a>
                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                                          style="display:inline" onsubmit="return confirm('削除しますか？')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">プランがありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $plans->links() }}
    </div>

</div>

@endsection
