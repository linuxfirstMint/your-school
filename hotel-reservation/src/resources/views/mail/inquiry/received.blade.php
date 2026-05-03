@extends('layouts.mail')

@section('title', 'お問い合わせを受け付けました')

@section('content')
<p style="margin:0 0 16px; font-size:16px;">管理者様</p>

<p style="margin:0 0 24px; font-size:15px; line-height:1.7;">
    新しいお問い合わせを受け付けました。
</p>

<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:24px;">
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">お名前</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $inquiry->last_name }} {{ $inquiry->first_name }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">メールアドレス</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $inquiry->email }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">住所</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $inquiry->address }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">電話番号</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $inquiry->phone }}</td>
    </tr>
    <tr>
        <th style="width:40%; background-color:#f0f0f0; padding:12px 16px; text-align:left; font-size:14px; border:1px solid #dddddd;">お問い合わせ内容</th>
        <td style="padding:12px 16px; font-size:14px; border:1px solid #dddddd;">{{ $inquiry->message }}</td>
    </tr>
</table>
@endsection
