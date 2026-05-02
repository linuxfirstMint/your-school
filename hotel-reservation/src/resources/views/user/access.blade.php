@extends('layouts.app')

@section('title', 'アクセス案内')

@section('content')

{{-- ページヘッダー --}}
<section class="bg-light py-5 border-bottom">
    <div class="container text-center">
        <p class="text-muted text-uppercase mb-2" style="letter-spacing: .2em; font-size: .75rem;">Access</p>
        <h1 class="h2 fw-light mb-0">アクセス案内</h1>
    </div>
</section>

{{-- 住所 + 地図 --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <h2 class="h5 fw-semibold mb-4">所在地</h2>
                <address class="mb-4 lh-lg" style="font-style: normal;">
                    〒321-2526<br>
                    栃木県日光市中宮祠2482<br>
                    <a href="tel:0288-55-0001" class="text-decoration-none text-dark">TEL: 0288-55-0001</a><br>
                    <a href="mailto:info@example.com" class="text-decoration-none text-dark">info@example.com</a>
                </address>
                <p class="text-muted small">
                    チェックイン：15:00〜<br>
                    チェックアウト：〜11:00
                </p>
            </div>
            <div class="col-lg-8">
                <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                    <iframe
                        src="https://maps.google.com/maps?q=栃木県日光市中宮祠&output=embed&hl=ja"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="ホテルの場所（Google マップ）">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 交通手段 --}}
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="h5 fw-semibold mb-5 text-center">交通手段</h2>
        <div class="row g-4">

            {{-- 電車 --}}
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3 gap-2">
                            <span class="fs-4">🚃</span>
                            <h3 class="h6 fw-semibold mb-0">電車でお越しの方</h3>
                        </div>
                        <ol class="ps-3 mb-0 small text-muted lh-lg">
                            <li>東武日光線「東武日光駅」下車</li>
                            <li>東武バス「中禅寺温泉」行き乗車（約50分）</li>
                            <li>「中禅寺温泉」バス停から徒歩5分</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- バス --}}
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3 gap-2">
                            <span class="fs-4">🚌</span>
                            <h3 class="h6 fw-semibold mb-0">高速バスでお越しの方</h3>
                        </div>
                        <ol class="ps-3 mb-0 small text-muted lh-lg">
                            <li>新宿バスタ発「日光・中禅寺湖」行き乗車</li>
                            <li>「中禅寺温泉」バス停下車（約2時間30分）</li>
                            <li>バス停から徒歩5分</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- 車 --}}
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3 gap-2">
                            <span class="fs-4">🚗</span>
                            <h3 class="h6 fw-semibold mb-0">お車でお越しの方</h3>
                        </div>
                        <ol class="ps-3 mb-0 small text-muted lh-lg">
                            <li>東北自動車道「宇都宮IC」下車</li>
                            <li>日光宇都宮道路「日光IC」経由</li>
                            <li>いろは坂を上り中禅寺湖方面へ（約25分）</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- 駐車場 --}}
<section class="py-5">
    <div class="container" style="max-width: 720px;">
        <h2 class="h5 fw-semibold mb-4 text-center">駐車場のご案内</h2>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <table class="table table-borderless mb-0 small">
                    <tbody>
                        <tr>
                            <th class="ps-0 text-muted fw-normal" style="width: 8rem;">収容台数</th>
                            <td>50台（普通車 46台・大型車 4台）</td>
                        </tr>
                        <tr>
                            <th class="ps-0 text-muted fw-normal">料金</th>
                            <td>宿泊者無料（チェックイン前日 20:00〜 チェックアウト翌日 12:00 まで）</td>
                        </tr>
                        <tr>
                            <th class="ps-0 text-muted fw-normal">ご予約</th>
                            <td>不要（先着順）</td>
                        </tr>
                        <tr class="border-0">
                            <th class="ps-0 text-muted fw-normal">電気自動車</th>
                            <td>EV 充電スタンド 2基設置（要フロント申請）</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="text-muted small text-center mt-3">
            満車の場合は近隣の有料駐車場をご案内します。フロントへお問い合わせください。
        </p>
    </div>
</section>

@endsection
