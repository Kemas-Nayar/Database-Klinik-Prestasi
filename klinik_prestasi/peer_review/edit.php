<?php
include '../config/db.php';

$id_url = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id_url)) { header("Location: index.php"); exit; }

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_old = $_POST['id_old'];
    $id_mentor = $_POST['id_mentor'];
    $status = $_POST['status'];
    
    // Data Baru
    $hasil = $_POST['hasil'];
    $tanggal = $_POST['tanggal'];

    // Jika status diubah jadi selesai tapi tanggal kosong, isi otomatis hari ini
    if ($status == 'Selesai' && empty($tanggal)) {
        $tanggal = date('Y-m-d');
    }

    $query = "UPDATE PEER_REVIEW SET ID_Mentor=$1, Status_Review=$2, Hasil_Review=$3, Tanggal_Review=$4 WHERE ID_Review=$5";
    $params = array($id_mentor, $status, $hasil, $tanggal, $id_old);
    
    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Data review berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update.');</script>";
    }
}

// AMBIL DATA LAMA
$query_get = "SELECT * FROM PEER_REVIEW WHERE ID_Review = $1";
$data = pg_fetch_assoc(pg_query_params($conn, $query_get, array($id_url)));
if (!$data) die("Data tidak ditemukan.");
$data = array_change_key_case($data, CASE_UPPER);

include '../layout/header.php';
?>

<h2>Proses Review Karya</h2>
<div class="form-box">
    <form action="" method="POST">
        <input type="hidden" name="id_old" value="<?= $data['ID_REVIEW'] ?>">
        
        <label>ID Review / Karya</label>
        <div style="background:#ddd; padding:5px; margin-bottom:10px;">
            <?= $data['ID_REVIEW'] ?> (Karya: <?= $data['ID_KARYA'] ?>)
        </div>

        <label>Mentor Penilai</label>
        <input type="text" name="id_mentor" value="<?= $data['ID_MENTOR'] ?>" required>

        <hr style="margin: 20px 0; border-top: 2px dashed #ccc;">
        <h3>Hasil Penilaian</h3>

        <label>Status Review</label>
        <select name="status" required>
            <option value="Menunggu" <?= ($data['STATUS_REVIEW']=='Menunggu')?'selected':'' ?>>Menunggu / Proses</option>
            <option value="Selesai" <?= ($data['STATUS_REVIEW']=='Selesai')?'selected':'' ?>>Selesai</option>
        </select>

        <label>Feedback / Hasil Review</label>
        <textarea name="hasil" rows="6" placeholder="Tuliskan masukan untuk mahasiswa di sini..." style="width:100%; padding:8px; border:1px solid #ccc;"><?= $data['HASIL_REVIEW'] ?></textarea>

        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal" value="<?= $data['TANGGAL_REVIEW'] ?>">

        <br><br>
        <button type="submit" class="btn btn-blue">Simpan Hasil Review</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
