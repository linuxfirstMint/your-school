@extends('layouts.app')

@section('title'){{ $plan->name }}@endsection

@section('content')

<section class="py-5">
    <div class="container" style="max-width: 800px;">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.plans.index') }}">宿泊プラン一覧</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $plan->name }}</li>
            </ol>
        </nav>

        <h1 class="h2 fw-light mb-4">{{ $plan->name }}</h1>

        @if ($plan->planImages->isNotEmpty())
            <div class="row g-2 mb-4">
                @foreach ($plan->planImages as $image)
                    <div class="col-md-6">
                        <img src="{{ Storage::url($image->image_path) }}"
                             class="img-fluid rounded"
                             style="width: 100%; height: 240px; object-fit: cover;"
                             alt="{{ $image->name }}">
                    </div>
                @endforeach
            </div>
        @endif

        @if ($plan->description)
            <p class="text-muted lh-lg mb-4">{{ $plan->description }}</p>
        @endif

        @if ($plan->planRoomPrices->isNotEmpty())
            <h2 class="h5 fw-semibold mb-3">料金</h2>
            <table class="table table-bordered mb-5">
                <thead class="table-light">
                    <tr>
                        <th>部屋タイプ</th>
                        <th>1泊料金</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plan->planRoomPrices as $price)
                        <tr>
                            <td>{{ $price->roomType->name }}</td>
                            <td>{{ number_format($price->price) }}円</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="d-grid gap-2 col-md-6">
            <a href="{{ route('user.plans.calendar', $plan) }}" class="btn btn-dark btn-lg">
                空室カレンダーを確認する →
            </a>
        </div>

    </div>
</section>

@endsection
