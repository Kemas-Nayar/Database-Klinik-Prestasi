<?php
include '../config/db.php';

$id_url = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id_url)) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_old = $_POST['id_old'];
    $id_karya = $_POST['id_karya'];
    $id_layanan = $_POST['id_layanan'];
    $id_user = $_POST['id_user'];
    $id_mentor = $_POST['id_mentor'];
    $status = $_POST['status'];

    $query = "UPDATE PEER_REVIEW SET ID_Karya=$1, ID_Layanan=$2, ID_User=$3, ID_Mentor=$4, Status_Review=$5 WHERE ID_Review=$6";
    $result = pg_query_params($conn, $query, array($id_karya, $id_layanan, $id_user, $id_mentor, $status, $id_old));

    if ($result) {
        echo "<script>alert('Review berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update.');</script>";
    }
}

$query_get = "SELECT * FROM PEER_REVIEW WHERE ID_Review = $1";
$data = pg_fetch_assoc(pg_query_params($conn, $query_get, array($id_url)));
if (!$data) die("Data tidak ditemukan.");
$data = array_change_key_case($data, CASE_UPPER);

include '../layout/header_mentor.php';
?>

<h2>Edit Peer Review</h2>
<div class="form-box">
    <form action="" method="POST">
        <input type="hidden" name="id_old" value="<?= $data['ID_REVIEW'] ?>">
        
        <label>ID Review</label>
        <input type="text" value="<?= $data['ID_REVIEW'] ?>" disabled style="background:#ddd;">

        <label>ID Karya</label>
        <input type="text" name="id_karya" value="<?= $data['ID_KARYA'] ?>" required>

        <label>ID Layanan</label>
        <input type="text" name="id_layanan" value="<?= $data['ID_LAYANAN'] ?>" required>

        <label>ID User</label>
        <input type="text" name="id_user" value="<?= $data['ID_USER'] ?>" required>

        <label>ID Mentor</label>
        <input type="text" name="id_mentor" value="<?= $data['ID_MENTOR'] ?>" required>

        <label>Status Review</label>
        <select name="status" required>
            <option value="Menunggu" <?= ($data['STATUS_REVIEW']=='Menunggu')?'selected':'' ?>>Menunggu</option>
            <option value="Selesai" <?= ($data['STATUS_REVIEW']=='Selesai')?'selected':'' ?>>Selesai</option>
        </select>

        <br>
        <button type="submit" class="btn btn-blue">Update</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
