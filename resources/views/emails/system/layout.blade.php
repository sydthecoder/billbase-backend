<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $subject ?? 'Bill Base' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: #f4f4f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: #18181b;
        }

        .wrapper {
            width: 100%;
            padding: 40px 16px;
        }

        .card {
            background: #ffffff;
            max-width: 560px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e4e4e7;
        }

        .header {
            padding: 28px 40px;
            border-bottom: 1px solid #e4e4e7;
        }

        .header .brand {
            font-size: 18px;
            font-weight: 700;
            color: #18181b;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .body {
            padding: 36px 40px;
        }

        .body h1 {
            font-size: 20px;
            font-weight: 600;
            color: #18181b;
            margin-bottom: 12px;
            letter-spacing: -0.3px;
        }

        .body p {
            color: #52525b;
            margin-bottom: 16px;
        }

        .body p:last-child {
            margin-bottom: 0;
        }

        .btn {
            display: inline-block;
            margin: 8px 0 20px;
            padding: 11px 24px;
            background-color: #18181b;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .divider {
            border: none;
            border-top: 1px solid #e4e4e7;
            margin: 24px 0;
        }

        .footer {
            padding: 20px 40px;
            border-top: 1px solid #e4e4e7;
            background: #fafafa;
        }

        .footer p {
            font-size: 12px;
            color: #a1a1aa;
            margin-bottom: 4px;
        }

        .footer a {
            color: #a1a1aa;
        }

        @media (max-width: 600px) {
            .body, .header, .footer { padding-left: 24px; padding-right: 24px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">

            {{-- Header --}}
            <div class="header">
                <span class="brand">Bill Base</span>
            </div>

            {{-- Email body injected here --}}
            <div class="body">
                @yield('content')
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>© {{ date('Y') }} Bill Base. All rights reserved.</p>
                <p>You are receiving this email because you have an account with Bill Base.</p>
            </div>

        </div>
    </div>
</body>
</html>