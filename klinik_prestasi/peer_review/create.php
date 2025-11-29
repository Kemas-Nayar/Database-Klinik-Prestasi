<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_review = $_POST['id_review'];
    $id_karya = $_POST['id_karya'];
    $id_layanan = $_POST['id_layanan'];
    $id_user = $_POST['id_user'];
    $id_mentor = $_POST['id_mentor'];
    $status = $_POST['status'];

    $query = "INSERT INTO PEER_REVIEW (ID_Review, ID_Karya, ID_Layanan, ID_User, ID_Mentor, Status_Review) VALUES ($1, $2, $3, $4, $5, $6)";
    $params = array($id_review, $id_karya, $id_layanan, $id_user, $id_mentor, $status);
    
    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Review berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . pg_last_error($conn) . "');</script>";
    }
}

include '../layout/header_mentor.php';
?>

<h2>Tambah Peer Review</h2>
<div class="form-box">
    <form action="" method="POST">
        <label>ID Review</label>
        <input type="text" name="id_review" required placeholder="Contoh: R001">

        <label>ID Karya</label>
        <input type="text" name="id_karya" required placeholder="Contoh: K001">

        <label>ID Layanan</label>
        <input type="text" name="id_layanan" required placeholder="Contoh: L001">

        <label>ID User (Pemohon)</label>
        <input type="text" name="id_user" required placeholder="Contoh: U001">

        <label>ID Mentor</label>
        <input type="text" name="id_mentor" required placeholder="Contoh: M001">

        <label>Status Review</label>
        <select name="status" required>
            <option value="Menunggu">Menunggu</option>
            <option value="Selesai">Selesai</option>
        </select>

        <br>
        <button type="submit" class="btn btn-green">Simpan</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
