<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_laporan = $_POST['id_laporan'];
    $id_karya = $_POST['id_karya'];
    $id_user = $_POST['id_user'];
    $id_mentor = $_POST['id_mentor'];
    $hasil = $_POST['hasil'];
    $tanggal = $_POST['tanggal'];

    if (!empty($id_laporan) && !empty($id_karya)) {
        $query = "INSERT INTO LAPORAN_REVIEW (ID_Laporan_Review, ID_Karya, ID_User, ID_Mentor, Hasil_Review, Tanggal_Laporan) VALUES ($1, $2, $3, $4, $5, $6)";
        $params = array($id_laporan, $id_karya, $id_user, $id_mentor, $hasil, $tanggal);
        
        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            echo "<script>alert('Laporan berhasil ditambahkan!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal: " . pg_last_error($conn) . "');</script>";
        }
    }
}

include '../layout/header_mentor.php';
?>

<h2>Buat Laporan Review</h2>
<div class="form-box">
    <form action="" method="POST">
        <label>ID Laporan</label>
        <input type="text" name="id_laporan" required placeholder="Contoh: LR001">

        <label>ID Karya</label>
        <input type="text" name="id_karya" required placeholder="Contoh: K001">

        <label>ID User (Mahasiswa)</label>
        <input type="text" name="id_user" required placeholder="Contoh: U001">

        <label>ID Mentor</label>
        <input type="text" name="id_mentor" required placeholder="Contoh: M001">

        <label>Tanggal Laporan</label>
        <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>">

        <label>Hasil Review / Feedback</label>
        <textarea name="hasil" rows="5" required placeholder="Tulis detail feedback di sini..." style="width:100%; padding:8px; border:1px solid #ccc;"></textarea>

        <br><br>
        <button type="submit" class="btn btn-green">Simpan</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
