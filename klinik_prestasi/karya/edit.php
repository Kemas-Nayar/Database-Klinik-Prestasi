<?php
include '../config/db.php';

$id_url = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id_url)) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_old = $_POST['id_old'];
    $judul = $_POST['judul'];
    $id_user = $_POST['id_user'];
    $bidang = $_POST['bidang'];
    $link_drive = $_POST['link_drive']; // Ambil link baru

    // Update query termasuk kolom File_Karya (untuk Link)
    $query = "UPDATE KARYA SET Judul_Karya=$1, ID_User=$2, Bidang_Karya=$3, File_Karya=$4 WHERE ID_Karya=$5";
    $params = array($judul, $id_user, $bidang, $link_drive, $id_old);
    
    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Data karya berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data.');</script>";
    }
}

// Ambil data lama
$query_get = "SELECT * FROM KARYA WHERE ID_Karya = $1";
$data = pg_fetch_assoc(pg_query_params($conn, $query_get, array($id_url)));
if (!$data) die("Data tidak ditemukan.");
$data = array_change_key_case($data, CASE_UPPER);

include '../layout/header.php';
?>

<h2>Edit Karya</h2>
<div class="form-box">
    <form action="" method="POST">
        <input type="hidden" name="id_old" value="<?= $data['ID_KARYA'] ?>">
        
        <label>ID Karya (Tetap)</label>
        <input type="text" value="<?= $data['ID_KARYA'] ?>" disabled style="background:#ddd;">

        <label>Judul Karya</label>
        <input type="text" name="judul" value="<?= $data['JUDUL_KARYA'] ?>" required>

        <label>ID User</label>
        <input type="text" name="id_user" value="<?= $data['ID_USER'] ?>" required>

        <label>Bidang Karya</label>
        <select name="bidang" required>
            <option value="Esai" <?= ($data['BIDANG_KARYA']=='Esai')?'selected':'' ?>>Esai</option>
            <option value="KTI" <?= ($data['BIDANG_KARYA']=='KTI')?'selected':'' ?>>KTI</option>
            <option value="Bisnis" <?= ($data['BIDANG_KARYA']=='Bisnis')?'selected':'' ?>>Bisnis</option>
            <option value="Design" <?= ($data['BIDANG_KARYA']=='Design')?'selected':'' ?>>Design</option>
        </select>

        <label>Link Google Drive</label>
        <!-- Menampilkan link lama di value -->
        <input type="url" name="link_drive" value="<?= isset($data['FILE_KARYA']) ? $data['FILE_KARYA'] : '' ?>" placeholder="https://drive.google.com/..." style="border: 1px solid #28a745;">

        <br><br>
        <button type="submit" class="btn btn-blue">Update</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
