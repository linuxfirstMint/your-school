<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約キャンセルのお知らせ</title>
</head>
<body>
    <p>{{ $reservation->last_name }} {{ $reservation->first_name }} 様</p>

    <p>以下の予約をキャンセルいたしました。</p>

    <table>
        <tr>
            <th>プラン名</th>
            <td>{{ $reservation->plan_name }}</td>
        </tr>
        <tr>
            <th>料金</th>
            <td>{{ number_format($reservation->price) }} 円</td>
        </tr>
    </table>

    <p>またのご利用をお待ちしております。</p>

    <p>{{ config('app.name') }}</p>
</body>
</html>
