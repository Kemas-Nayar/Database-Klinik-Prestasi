<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_karya = $_POST['id_karya'];
    $judul = $_POST['judul'];
    $id_user = $_POST['id_user'];
    $bidang = $_POST['bidang'];
    $link_drive = $_POST['link_drive']; // Ambil link dari input

    if (!empty($id_karya) && !empty($judul)) {
        // Kita simpan Link ke kolom File_Karya
        $query = "INSERT INTO KARYA (ID_Karya, Judul_Karya, ID_User, Bidang_Karya, File_Karya) VALUES ($1, $2, $3, $4, $5)";
        $params = array($id_karya, $judul, $id_user, $bidang, $link_drive);
        
        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            echo "<script>alert('Karya berhasil ditambahkan!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal: " . pg_last_error($conn) . "');</script>";
        }
    }
}
include '../layout/header.php';
?>

<h2>Tambah Karya</h2>
<div class="form-box">
    <form action="" method="POST">
        <label>ID Karya</label>
        <input type="text" name="id_karya" required placeholder="Contoh: K001">

        <label>Judul Karya</label>
        <input type="text" name="judul" required placeholder="Judul Lengkap">

        <label>ID User (Penulis)</label>
        <input type="text" name="id_user" required placeholder="Contoh: U001">

        <label>Bidang Karya</label>
        <select name="bidang" required>
            <option value="Esai">Esai</option>
            <option value="KTI">KTI</option>
            <option value="Bisnis">Bisnis</option>
            <option value="Design">Design</option>
        </select>

        <label>Link Google Drive</label>
        <input type="url" name="link_drive" required placeholder="https://drive.google.com/..." style="border: 1px solid #28a745;">

        <br><br>
        <button type="submit" class="btn btn-green">Simpan</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>
<?php include '../layout/footer.php'; ?>
