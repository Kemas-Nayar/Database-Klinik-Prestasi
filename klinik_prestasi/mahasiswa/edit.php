<?php
include '../config/db.php';

$nim_url = isset($_GET['nim']) ? $_GET['nim'] : '';
if (empty($nim_url)) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim_old = $_POST['nim_old']; 
    $nama = $_POST['nama'];
    $angkatan = $_POST['angkatan'];
    $kontak = $_POST['kontak'];
    $status = $_POST['status']; 

    $query = "UPDATE MAHASISWA SET Nama=$1, Angkatan=$2, Kontak=$3, Status_Mhs=$4 WHERE NIM=$5";
    $params = array($nama, $angkatan, $kontak, $status, $nim_old);
    
    $result = pg_query_params($conn, $query, $params);

    if ($result) {
        echo "<script>alert('Data & Status berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update.');</script>";
    }
}

$query_get = "SELECT * FROM MAHASISWA WHERE NIM = $1";
$data = pg_fetch_assoc(pg_query_params($conn, $query_get, array($nim_url)));
$data = array_change_key_case($data, CASE_UPPER);

include '../layout/header.php';
?>

<h2>Edit Mahasiswa</h2>
<div class="form-box">
    <form action="" method="POST">
        <input type="hidden" name="nim_old" value="<?= $data['NIM'] ?>">
        
        <label>NIM</label>
        <input type="text" value="<?= $data['NIM'] ?>" disabled style="background:#ddd;">

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= $data['NAMA'] ?>" required>

        <label>Angkatan</label>
        <select name="angkatan" required>
            <option value="F19" <?= ($data['ANGKATAN'] == 'F19') ? 'selected' : '' ?>>F19</option>
            <option value="F20" <?= ($data['ANGKATAN'] == 'F20') ? 'selected' : '' ?>>F20</option>
            <option value="F21" <?= ($data['ANGKATAN'] == 'F21') ? 'selected' : '' ?>>F21</option>
            <option value="F22" <?= ($data['ANGKATAN'] == 'F22') ? 'selected' : '' ?>>F22</option>
            <option value="F23" <?= ($data['ANGKATAN'] == 'F23') ? 'selected' : '' ?>>F23</option>
        </select>

        <label>Kontak</label>
        <input type="text" name="kontak" value="<?= $data['KONTAK'] ?>" required>

        <label>Status Keanggotaan</label>
        <select name="status" required style="border: 2px solid #007bff;">
            <option value="Aktif" <?= ($data['STATUS_MHS'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
            <option value="Nonaktif" <?= ($data['STATUS_MHS'] == 'Nonaktif') ? 'selected' : '' ?>>Nonaktif (Keluar/Lulus)</option>
        </select>

        <br><br>
        <button type="submit" class="btn btn-blue">Update</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>
<?php include '../layout/footer.php'; ?>
