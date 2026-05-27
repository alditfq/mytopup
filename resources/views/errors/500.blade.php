<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error | MyTopup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #0a0a0f;
            color: #e0e0ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(234, 179, 8, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(234, 179, 8, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            z-index: 0;
        }

        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.35;
        }
        .orb-1 { width: 400px; height: 400px; background: #854d0e; top: -100px; left: -100px; animation: float 8s ease-in-out infinite; }
        .orb-2 { width: 300px; height: 300px; background: #713f12; bottom: -80px; right: -80px; animation: float 10s ease-in-out infinite reverse; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 20px) scale(1.05); }
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .error-code {
            font-size: clamp(6rem, 20vw, 12rem);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #fbbf24, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: flicker 3s infinite;
        }

        @keyframes flicker {
            0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% { opacity: 1; }
            20%, 24%, 55% { opacity: 0.6; }
        }

        .error-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            animation: spin-wobble 3s ease-in-out infinite;
        }

        @keyframes spin-wobble {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fde68a;
            margin-bottom: 0.75rem;
        }

        .error-desc {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            color: #fff;
            box-shadow: 0 4px 20px rgba(217, 119, 6, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(217, 119, 6, 0.6);
        }

        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: #fde68a;
            border: 1px solid rgba(217, 119, 6, 0.3);
        }
        .btn-ghost:hover {
            background: rgba(217, 119, 6, 0.12);
            border-color: #d97706;
            transform: translateY(-2px);
        }

        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #fbbf24, #d97706);
            border-radius: 99px;
            margin: 1.25rem auto;
        }

        .info-box {
            background: rgba(234, 179, 8, 0.08);
            border: 1px solid rgba(234, 179, 8, 0.2);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            font-size: 0.85rem;
            color: #fde68a;
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <span class="error-icon">⚙️</span>
        <div class="error-code">500</div>
        <div class="divider"></div>
        <h1 class="error-title">Server Sedang Bermasalah</h1>
        <p class="error-desc">
            Terjadi kesalahan internal pada server kami.<br>
            Tim teknis kami sudah mengetahui masalah ini dan sedang memperbaikinya.
        </p>
        <div class="info-box">
            🛠️ Coba refresh halaman dalam beberapa saat. Jika masalah berlanjut, hubungi support kami.
        </div>
        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">🏠 Kembali ke Beranda</a>
            <a href="javascript:location.reload()" class="btn btn-ghost">🔄 Refresh Halaman</a>
        </div>
    </div>
</body>
</html>
