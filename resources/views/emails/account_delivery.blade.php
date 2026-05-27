<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kredensial Akun Game Pembelian Anda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #ec4899);
            padding: 32px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 12px;
            font-weight: 600;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .info-card {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .info-title {
            font-size: 11px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
            line-height: 1.5;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #1e293b;
            font-weight: 800;
            font-family: monospace;
            word-break: break-all;
            flex-grow: 1;
            text-align: left;
        }
        .credentials-card {
            background-color: #ecfdf5;
            border: 1.5px dashed #10b981;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .credentials-title {
            font-size: 11px;
            font-weight: 800;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .warning-box {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 16px;
            padding: 16px;
            font-size: 12px;
            color: #b45309;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .warning-box strong {
            color: #92400e;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
        }
        .footer p {
            margin: 0;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Akun Game Anda</h1>
            <p>Invoice: {{ $invoice }}</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $buyerName }}!</div>
            <div class="intro">
                Terima kasih telah melakukan pembelian di GameTopup. Pembayaran Anda telah kami verifikasi dengan sukses. Berikut adalah detail kredensial login untuk akun game premium yang Anda beli:
            </div>

            <div class="info-card">
                <div class="info-title">Informasi Akun Game</div>
                <div class="info-item">
                    <div class="info-label">Game:</div>
                    <div class="info-value" style="font-family: inherit;">{{ $gameName }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Deskripsi Akun:</div>
                    <div class="info-value" style="font-family: inherit;">{{ $accountTitle }}</div>
                </div>
            </div>

            <div class="credentials-card">
                <div class="credentials-title">🔑 Kredensial Login Akun</div>
                <div class="info-item">
                    <div class="info-label">Email / Username:</div>
                    <div class="info-value">{{ $accountEmail }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Password:</div>
                    <div class="info-value">{{ $accountPassword }}</div>
                </div>
                @if($notes)
                    <div class="info-item" style="margin-top: 12px; border-top: 1px dashed #a7f3d0; padding-top: 10px;">
                        <div class="info-label">Catatan Tambahan:</div>
                        <div class="info-value" style="font-family: inherit; font-weight: bold; color: #065f46;">{{ $notes }}</div>
                    </div>
                @endif
            </div>

            <div class="warning-box">
                ⚠️ <strong>PERINGATAN KEAMANAN PENTING:</strong><br>
                Demi keamanan akun Anda, harap <strong>segera melakukan login dan mengganti kata sandi (password)</strong> serta mengaitkan email/nomor HP Anda pada akun game tersebut setelah login pertama kali. GameTopup tidak bertanggung jawab atas kelalaian penggantian password setelah status pengiriman selesai.
            </div>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem fulfillment <a href="#">GameTopup</a>.</p>
            <p style="margin-top: 6px;">&copy; {{ date('Y') }} GameTopup. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
