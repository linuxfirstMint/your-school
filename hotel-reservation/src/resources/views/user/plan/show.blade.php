@extends('layouts.app')

@section('title'){{ $plan->name }}@endsection

@section('content')
<a href="{{ route('user.plans.index') }}">← プラン一覧へ</a>

<h1>{{ $plan->name }}</h1>

@if ($plan->planImages->isNotEmpty())
    <div>
        @foreach ($plan->planImages as $image)
            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->name }}">
        @endforeach
    </div>
@endif

@if ($plan->description)
    <p>{{ $plan->description }}</p>
@endif

@if ($plan->planRoomPrices->isNotEmpty())
    <h2>料金</h2>
    <table>
        <thead>
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

<a href="{{ route('user.plans.calendar', $plan) }}">空室カレンダーを確認する</a>
@endsection
