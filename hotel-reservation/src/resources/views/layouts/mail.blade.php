<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:'Helvetica Neue', Arial, sans-serif; color:#333333;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:32px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                    {{-- ヘッダー --}}
                    <tr>
                        <td style="background-color:#1a1a2e; padding:24px 32px; text-align:center;">
                            <p style="margin:0; color:#ffffff; font-size:20px; font-weight:bold; letter-spacing:0.05em;">
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    {{-- 本文 --}}
                    <tr>
                        <td style="padding:32px;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- フッター --}}
                    <tr>
                        <td style="background-color:#f8f8f8; padding:16px 32px; text-align:center; border-top:1px solid #eeeeee;">
                            <p style="margin:0; font-size:12px; color:#999999;">
                                このメールは {{ config('app.name') }} より自動送信されています。
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
