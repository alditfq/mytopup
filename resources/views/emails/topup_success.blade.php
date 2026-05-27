<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembelian Sukses - GameTopup</title>
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
            background: linear-gradient(135deg, #10b981, #059669);
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
            color: #10b981;
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
            text-align: right;
        }
        .total-item {
            margin-top: 12px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
            font-size: 14px;
        }
        .total-item .info-label {
            color: #1e293b;
        }
        .total-item .info-value {
            color: #10b981;
            font-size: 16px;
            font-weight: 900;
        }
        .success-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 16px;
            padding: 16px;
            font-size: 12px;
            color: #065f46;
            line-height: 1.5;
            margin-bottom: 24px;
            text-align: center;
        }
        .success-box strong {
            color: #047857;
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
            color: #10b981;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Transaksi Top Up Berhasil!</h1>
            <p>Invoice: {{ $transaction->invoice }}</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $transaction->user ? $transaction->user->name : $transaction->nickname }}!</div>
            <div class="intro">
                Terima kasih atas pesanan Anda di GameTopup. Pembayaran Anda telah kami verifikasi dengan sukses. Item game pilihan Anda telah berhasil diproses dan dikirim langsung ke akun game Anda secara instan.
            </div>

            <div class="info-card">
                <div class="info-title">Rincian Transaksi Resmi</div>
                <div class="info-item">
                    <div class="info-label">Nama Game:</div>
                    <div class="info-value" style="font-family: inherit;">{{ $transaction->game->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Spesifikasi Item:</div>
                    <div class="info-value" style="font-family: inherit; color: #4f46e5;">{{ $transaction->nominal_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Player ID (Server):</div>
                    <div class="info-value">{{ $transaction->target_id }}{{ $transaction->zone_id ? ' (' . $transaction->zone_id . ')' : '' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nickname Player:</div>
                    <div class="info-value" style="font-family: inherit;">{{ $transaction->nickname }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Metode Pembayaran:</div>
                    <div class="info-value" style="font-family: inherit;">{{ $transaction->paymentMethod->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Transaksi:</div>
                    <div class="info-value" style="font-family: inherit;">{{ $transaction->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                
                <div class="info-item total-item">
                    <div class="info-label">Total Pembayaran:</div>
                    <div class="info-value">Rp {{ number_format($transaction->total_payment, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="success-box">
                ✅ <strong>PENGIRIMAN SUKSES & INSTAN:</strong><br>
                Diamonds / item game Anda sudah berhasil masuk ke saldo game kamu. Silakan restart/relogin game kamu untuk memperbarui saldo. Terima kasih telah berbelanja di platform terpercaya kami!
            </div>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem billing <a href="#">GameTopup</a>.</p>
            <p style="margin-top: 6px;">&copy; {{ date('Y') }} GameTopup. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
