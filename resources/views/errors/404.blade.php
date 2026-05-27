<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | MyTopup</title>
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

        /* Animated grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            z-index: 0;
        }

        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }

        /* Glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.4;
        }
        .orb-1 { width: 400px; height: 400px; background: #6366f1; top: -100px; left: -100px; animation: float 8s ease-in-out infinite; }
        .orb-2 { width: 300px; height: 300px; background: #a855f7; bottom: -80px; right: -80px; animation: float 10s ease-in-out infinite reverse; }

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
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: glitch 3s infinite;
        }

        @keyframes glitch {
            0%, 90%, 100% { text-shadow: none; transform: none; }
            91% { transform: skewX(-2deg); text-shadow: 3px 0 #ec4899, -3px 0 #6366f1; }
            93% { transform: skewX(2deg); text-shadow: -3px 0 #a855f7, 3px 0 #ec4899; }
            95% { transform: none; text-shadow: none; }
        }

        .error-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #c4b5fd;
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
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.6);
        }

        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: #c4b5fd;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }
        .btn-ghost:hover {
            background: rgba(99, 102, 241, 0.15);
            border-color: #6366f1;
            transform: translateY(-2px);
        }

        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            border-radius: 99px;
            margin: 1.25rem auto;
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <span class="error-icon">🎮</span>
        <div class="error-code">404</div>
        <div class="divider"></div>
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        <p class="error-desc">
            Quest yang kamu cari tidak ada di dunia ini.<br>
            Mungkin halaman telah dipindahkan, dihapus, atau URL yang kamu masukkan salah.
        </p>
        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">🏠 Kembali ke Beranda</a>
            <a href="javascript:history.back()" class="btn btn-ghost">← Halaman Sebelumnya</a>
        </div>
    </div>
</body>
</html>
