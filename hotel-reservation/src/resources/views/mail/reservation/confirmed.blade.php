<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約完了のお知らせ</title>
</head>
<body>
    <p>{{ $reservation->last_name }} {{ $reservation->first_name }} 様</p>

    <p>このたびはご予約いただきありがとうございます。<br>
    以下の内容で予約を承りました。</p>

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

    <p>ご不明な点がございましたら、お気軽にお問い合わせください。<br>
    ご来館をお待ちしております。</p>

    <p>{{ config('app.name') }}</p>
</body>
</html>
