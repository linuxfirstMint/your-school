<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>【リマインド】明日のご宿泊について</title>
</head>
<body>
    <p>{{ $reservation->last_name }} {{ $reservation->first_name }} 様</p>

    <p>明日のご宿泊日が近づきましたのでご連絡いたします。<br>
    以下の内容でご予約をお受けしております。</p>

    <table>
        <tr>
            <th>プラン名</th>
            <td>{{ $reservation->plan_name }}</td>
        </tr>
        <tr>
            <th>チェックイン</th>
            <td>{{ $reservation->reservationSlot->start->format('Y年m月d日') }}</td>
        </tr>
        <tr>
            <th>チェックアウト</th>
            <td>{{ $reservation->reservationSlot->end->format('Y年m月d日') }}</td>
        </tr>
        <tr>
            <th>料金</th>
            <td>{{ number_format($reservation->price) }} 円</td>
        </tr>
    </table>

    <p>ご不明な点がございましたら、お気軽にお問い合わせください。<br>
    ご来館を心よりお待ちしております。</p>

    <p>{{ config('app.name') }}</p>
</body>
</html>
