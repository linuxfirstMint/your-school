@extends('layouts.app')

@section('title', '宿泊プラン一覧')

@section('content')

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-muted text-uppercase mb-2" style="letter-spacing: .2em; font-size: .75rem;">Plans</p>
            <h1 class="h3 fw-light">宿泊プラン一覧</h1>
        </div>

        @forelse ($plans as $plan)
            <div class="card border-0 shadow-sm mb-4">
                <div class="row g-0 align-items-center">
                    @if ($plan->planImages->isNotEmpty())
                        <div class="col-md-4">
                            <img src="{{ Storage::url($plan->planImages->first()->image_path) }}"
                                 class="img-fluid rounded-start h-100"
                                 style="max-height: 220px; width: 100%; object-fit: cover;"
                                 alt="{{ $plan->planImages->first()->name }}">
                        </div>
                        <div class="col-md-8">
                    @else
                        <div class="col-12">
                    @endif
                            <div class="card-body p-4">
                                <h2 class="card-title h5 fw-semibold mb-2">{{ $plan->name }}</h2>
                                @if ($plan->description)
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($plan->description, 80) }}
                                    </p>
                                @endif
                                @if ($plan->planRoomPrices->isNotEmpty())
                                    <p class="mb-3 fw-semibold">
                                        <span class="text-muted small">1泊 </span>
                                        {{ number_format($plan->planRoomPrices->min('price')) }}円〜
                                    </p>
                                @endif
                                <a href="{{ route('user.plans.show', $plan) }}" class="btn btn-dark btn-sm px-4">
                                    詳細を見る →
                                </a>
                            </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <p class="text-muted">現在公開中のプランはありません。</p>
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $plans->links() }}
        </div>
    </div>
</section>

@endsection
