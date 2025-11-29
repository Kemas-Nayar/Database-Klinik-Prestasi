<?php
include '../config/db.php';

// Ambil NIM dari URL
$nim_url = isset($_GET['nim']) ? $_GET['nim'] : '';
if (empty($nim_url)) {
    header("Location: index.php");
    exit;
}

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // NIM lama diambil dari hidden input agar konsisten
    $nim_old = $_POST['nim_old']; 
    $nama = $_POST['nama'];
    $angkatan = $_POST['angkatan'];
    $kontak = $_POST['kontak'];

    if (!empty($nama) && !empty($angkatan)) {
        // Query Update dengan Parameter Binding
        $query = "UPDATE MAHASISWA SET Nama=$1, Angkatan=$2, Kontak=$3 WHERE NIM=$4";
        $params = array($nama, $angkatan, $kontak, $nim_old);
        
        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            echo "<script>alert('Data berhasil diubah!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal mengubah data.');</script>";
        }
    }
}

// Ambil Data Lama untuk ditampilkan di form
$query_get = "SELECT * FROM MAHASISWA WHERE NIM = $1";
$result_get = pg_query_params($conn, $query_get, array($nim_url));
$data = pg_fetch_assoc($result_get);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
$data = array_change_key_case($data, CASE_UPPER);
include '../layout/header.php';
?>

<h2>Edit Mahasiswa</h2>
<div class="form-box">
    <form action="" method="POST">
        <!-- Simpan NIM lama di hidden input -->
        <input type="hidden" name="nim_old" value="<?= $data['NIM'] ?>">

        <label>NIM (Tidak bisa diubah)</label>
        <input type="text" value="<?= $data['NIM'] ?>" disabled style="background-color: #ddd;">

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= $data['NAMA'] ?>" required>

        <label>Angkatan</label>
        <select name="angkatan" required>
            <option value="F19" <?= ($data['ANGKATAN'] == 'F19') ? 'selected' : '' ?>>F19</option>
            <option value="F20" <?= ($data['ANGKATAN'] == 'F20') ? 'selected' : '' ?>>F20</option>
            <option value="F21" <?= ($data['ANGKATAN'] == 'F21') ? 'selected' : '' ?>>F21</option>
            <option value="F22" <?= ($data['ANGKATAN'] == 'F22') ? 'selected' : '' ?>>F22</option>
        </select>

        <label>Kontak</label>
        <input type="text" name="kontak" value="<?= $data['KONTAK'] ?>" required>

        <br>
        <button type="submit" class="btn btn-blue">Update</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
