<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan Sistem | PT Envirotama Solusindo</title>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .error-card { background: #1e293b; border: 1.5px solid #334155; border-radius: 24px; padding: 40px; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
        .badge-error { display: inline-block; background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 6px 16px; border-radius: 99px; font-size: 0.85rem; font-weight: 800; margin-bottom: 20px; }
        h1 { font-size: 2rem; font-weight: 800; color: #ffffff; margin-bottom: 12px; }
        p { font-size: 0.95rem; color: #94a3b8; line-height: 1.6; margin-bottom: 28px; }
        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 0.9rem; transition: all 0.2s ease; cursor: pointer; border: none; }
        .btn-primary { background: #0284c7; color: #ffffff; }
        .btn-primary:hover { background: #0369a1; }
        .btn-secondary { background: #334155; color: #e2e8f0; }
        .btn-secondary:hover { background: #475569; }
    </style>
</head>
<body>
    <div class="error-card">
        <span class="badge-error">Error 500 - Server Error</span>
        <h1>Terjadi Gangguan Sistem</h1>
        <p>Mohon maaf, sistem sedang memproses permintaan Anda atau terjadi gangguan sementara. Silakan segarkan halaman atau kembali ke beranda.</p>
        <div class="btn-group">
            <button onclick="window.location.reload()" class="btn btn-primary">🔄 Muat Ulang Halaman</button>
            <a href="/" class="btn btn-secondary">🏠 Ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>
