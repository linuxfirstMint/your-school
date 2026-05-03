@extends('layouts.admin')

@section('title', 'プラン編集')

@section('content')

<div class="container" style="max-width: 680px;">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">プラン管理</a></li>
            <li class="breadcrumb-item active" aria-current="page">編集</li>
        </ol>
    </nav>

    <h1 class="h4 fw-bold mb-4">プラン編集</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.plans.update', $plan) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">プラン名 <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $plan->name) }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">説明</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $plan->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">画像（変更する場合のみ）</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                    @if ($plan->planImages->isNotEmpty())
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            @foreach ($plan->planImages as $image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" alt="" style="height:60px; object-fit:cover; border-radius:4px;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label">部屋タイプ別料金</label>
                    <div class="row g-2">
                        @foreach ($roomTypes as $roomType)
                            @php $price = $plan->planRoomPrices->firstWhere('room_type_id', $roomType->id) @endphp
                            <div class="col-sm-4">
                                <label class="form-label small text-muted">{{ $roomType->name }}</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="prices[{{ $roomType->id }}]" min="0"
                                           value="{{ old("prices.{$roomType->id}", $price?->price) }}"
                                           class="form-control @error("prices.{$roomType->id}") is-invalid @enderror"
                                           placeholder="未設定">
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-dark">更新</button>
            </form>
        </div>
    </div>

</div>

@endsection
