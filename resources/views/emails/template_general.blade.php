<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Notifikasi</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #ffffff; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; text-align: center; }
    </style>
</head>
<body style="background-color: #f4f4f7; margin: 0; padding: 0;">
    <div class="container">
        {!! $content !!}
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <div class="footer">
            <p>Hormat kami,<br>Tim SiPTA</p>
        </div>
    </div>
</body>
</html>