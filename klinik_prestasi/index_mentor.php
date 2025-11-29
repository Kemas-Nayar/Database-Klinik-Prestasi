<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Mentor</title>
    <style>
        body { font-family: sans-serif; margin: 40px; text-align: center; background-color: #f0f8ff; }
        h1 { color: #0d47a1; }
        .menu-box { display: inline-block; margin: 10px; padding: 20px; border: 1px solid #2196F3; width: 220px; background: #fff; border-radius: 8px; vertical-align: top; height: 100px; }
        a { text-decoration: none; font-weight: bold; color: #1976D2; font-size: 18px; display: block; margin-bottom: 10px; }
        a:hover { text-decoration: underline; color: #0d47a1; }
        p { color: #555; margin-bottom: 30px; }
        span { font-size: 13px; color: #666; line-height: 1.4; display: block; }
        .link-admin { margin-top: 50px; display: block; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Portal Mentor</h1>
    <p>Selamat Datang, Mentor! Silakan kelola review karya.</p>

    <div class="menu-box">
        <a href="peer_review/index.php">✅ Peer Review</a>
        <span>Update status review dan kelola penugasan.</span>
    </div>

    <div class="menu-box">
        <a href="laporan_review/index.php">📝 Laporan Review</a>
        <span>Buat dan lihat laporan hasil feedback.</span>
    </div>

    <a href="index.php" class="link-admin">&larr; Kembali ke Admin</a>
</body>
</html>
