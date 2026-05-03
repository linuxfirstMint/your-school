@extends('layouts.mail')

@section('title', '【リマインド】明日のご宿泊について')

@section('content')
<p style="margin:0 0 16px; font-size:16px;">{{ $reservation->last_name }} {{ $reservation->first_name }} 様</p>

<p style="margin:0 0 24px; font-size:15px; line-height:1.7;">
    明日のご宿泊日が近づきましたのでご連絡いたします。<br>
    以下の内容でご予約をお受けしております。
</p>

<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:24px;">
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">プラン名</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $reservation->plan_name }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">チェックイン</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $reservation->reservationSlot->start->format('Y年m月d日') }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">チェックアウト</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $reservation->reservationSlot->end->format('Y年m月d日') }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">料金</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ number_format($reservation->price) }} 円</td>
    </tr>
</table>

<p style="margin:0; font-size:15px; line-height:1.7;">
    ご不明な点がございましたら、お気軽にお問い合わせください。<br>
    ご来館を心よりお待ちしております。
</p>
@endsection
