<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせありがとうございます</title>
</head>
<body>
    <p>{{ $inquiry->last_name }} {{ $inquiry->first_name }} 様</p>

    <p>お問い合わせをいただきありがとうございます。<br>
    内容を確認のうえ、担当者よりご連絡いたします。</p>

    <table>
        <tr>
            <th>お問い合わせ内容</th>
            <td>{{ $inquiry->message }}</td>
        </tr>
    </table>

    <p>{{ config('app.name') }}</p>
</body>
</html>
