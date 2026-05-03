<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせを受け付けました</title>
</head>
<body>
    <p>管理者様</p>

    <p>新しいお問い合わせを受け付けました。</p>

    <table>
        <tr>
            <th>お名前</th>
            <td>{{ $inquiry->last_name }} {{ $inquiry->first_name }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>{{ $inquiry->email }}</td>
        </tr>
        <tr>
            <th>住所</th>
            <td>{{ $inquiry->address }}</td>
        </tr>
        <tr>
            <th>電話番号</th>
            <td>{{ $inquiry->phone }}</td>
        </tr>
        <tr>
            <th>お問い合わせ内容</th>
            <td>{{ $inquiry->message }}</td>
        </tr>
    </table>

    <p>{{ config('app.name') }}</p>
</body>
</html>
