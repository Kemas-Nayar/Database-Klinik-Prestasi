<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Utama</title>
    <style>
        body { font-family: sans-serif; margin: 40px; text-align: center; }
        .menu-box { display: inline-block; margin: 10px; padding: 20px; border: 1px solid #ccc; width: 220px; background: #f9f9f9; border-radius: 8px; vertical-align: top; height: 100px; }
        a { text-decoration: none; font-weight: bold; color: #007bff; font-size: 18px; display: block; margin-bottom: 10px; }
        a:hover { text-decoration: underline; color: #0056b3; }
        p { color: #666; margin-bottom: 30px; }
        span { font-size: 13px; color: #555; line-height: 1.4; display: block; }
    </style>
</head>
<body>
    <h1>Selamat Datang di Klinik Prestasi</h1>
    <p>Silakan pilih menu di bawah ini untuk mengelola data:</p>

    <div class="menu-box">
        <!-- Link ke folder mahasiswa -->
        <a href="mahasiswa/index.php">📦 Data Mahasiswa</a>
        <span>Kelola data mahasiswa, angkatan, dan kontak.</span>
    </div>

    <div class="menu-box">
        <!-- Link ke folder karya -->
        <a href="karya/index.php">📄 Data Karya</a>
        <span>Kelola judul karya, penulis, dan bidang.</span>
    </div>

    <div class="menu-box">
        <!-- Link ke folder peer_review -->
        <a href="peer_review/index.php">✅ Peer Review</a>
        <span>Kelola status review, mentor, dan layanan.</span>
    </div>
</body>
</html>
