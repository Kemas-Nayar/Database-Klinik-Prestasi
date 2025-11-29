<?php
include '../config/db.php';

$id_url = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id_url)) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_old = $_POST['id_old'];
    $id_karya = $_POST['id_karya'];
    $id_user = $_POST['id_user'];
    $id_mentor = $_POST['id_mentor'];
    $hasil = $_POST['hasil'];
    $tanggal = $_POST['tanggal'];

    $query = "UPDATE LAPORAN_REVIEW SET ID_Karya=$1, ID_User=$2, ID_Mentor=$3, Hasil_Review=$4, Tanggal_Laporan=$5 WHERE ID_Laporan_Review=$6";
    $result = pg_query_params($conn, $query, array($id_karya, $id_user, $id_mentor, $hasil, $tanggal, $id_old));

    if ($result) {
        echo "<script>alert('Laporan berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update.');</script>";
    }
}

$query_get = "SELECT * FROM LAPORAN_REVIEW WHERE ID_Laporan_Review = $1";
$data = pg_fetch_assoc(pg_query_params($conn, $query_get, array($id_url)));
if (!$data) die("Data tidak ditemukan.");
$data = array_change_key_case($data, CASE_UPPER);

include '../layout/header_mentor.php';
?>

<h2>Edit Laporan Review</h2>
<div class="form-box">
    <form action="" method="POST">
        <input type="hidden" name="id_old" value="<?= $data['ID_LAPORAN_REVIEW'] ?>">
        
        <label>ID Laporan</label>
        <input type="text" value="<?= $data['ID_LAPORAN_REVIEW'] ?>" disabled style="background:#ddd;">

        <label>ID Karya</label>
        <input type="text" name="id_karya" value="<?= $data['ID_KARYA'] ?>" required>

        <label>ID User</label>
        <input type="text" name="id_user" value="<?= $data['ID_USER'] ?>" required>

        <label>ID Mentor</label>
        <input type="text" name="id_mentor" value="<?= $data['ID_MENTOR'] ?>" required>

        <label>Tanggal Laporan</label>
        <input type="date" name="tanggal" value="<?= $data['TANGGAL_LAPORAN'] ?>" required>

        <label>Hasil Review</label>
        <textarea name="hasil" rows="5" required style="width:100%; padding:8px; border:1px solid #ccc;"><?= $data['HASIL_REVIEW'] ?></textarea>

        <br><br>
        <button type="submit" class="btn btn-blue">Update</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
