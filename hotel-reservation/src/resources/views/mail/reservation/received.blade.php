<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約受付のお知らせ</title>
</head>
<body>
    <p>管理者様</p>

    <p>新しい予約を受け付けました。</p>

    <table>
        <tr>
            <th>氏名</th>
            <td>{{ $reservation->last_name }} {{ $reservation->first_name }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>{{ $reservation->email }}</td>
        </tr>
        <tr>
            <th>プラン名</th>
            <td>{{ $reservation->plan_name }}</td>
        </tr>
        <tr>
            <th>料金</th>
            <td>{{ number_format($reservation->price) }} 円</td>
        </tr>
    </table>

    <p>{{ config('app.name') }}</p>
</body>
</html>
