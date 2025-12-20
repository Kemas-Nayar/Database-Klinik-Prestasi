<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $angkatan = $_POST['angkatan'];
    $kontak = $_POST['kontak'];
    
    $id_user = $_POST['id_user'];

    if (!empty($nim) && !empty($nama) && !empty($id_user)) {
        

        if (strlen($nim) !== 11) {
            echo "<script>alert('Gagal: Panjang NIM harus tepat 11 karakter (Contoh: F2000000001)!'); window.history.back();</script>";
            exit; 
        }

        $cek_nim = pg_query_params($conn, "SELECT NIM FROM MAHASISWA WHERE NIM = $1", array($nim));
        if (pg_num_rows($cek_nim) > 0) {
            echo "<script>alert('Gagal: NIM $nim sudah terdaftar! Gunakan NIM lain.'); window.history.back();</script>";
            exit;
        }

        $cek_user = pg_query_params($conn, "SELECT ID_User FROM USER_MEMBER WHERE ID_User = $1", array($id_user));
        if (pg_num_rows($cek_user) > 0) {
            echo "<script>alert('Gagal: ID User $id_user sudah digunakan! Gunakan ID lain.'); window.history.back();</script>";
            exit;
        }

        pg_query($conn, "BEGIN");

        $query_mhs = "INSERT INTO MAHASISWA (NIM, Nama, Angkatan, Kontak) VALUES ($1, $2, $3, $4)";
        $params_mhs = array($nim, $nama, $angkatan, $kontak);
        $res_mhs = pg_query_params($conn, $query_mhs, $params_mhs);

        $query_user = "INSERT INTO USER_MEMBER (ID_User, NIM) VALUES ($1, $2)";
        $params_user = array($id_user, $nim);
        $res_user = pg_query_params($conn, $query_user, $params_user);

        if ($res_mhs && $res_user) {
            pg_query($conn, "COMMIT");
            echo "<script>alert('Mahasiswa berhasil ditambahkan! NIM: $nim'); window.location='index.php';</script>";
        } else {
            pg_query($conn, "ROLLBACK");
            $error = pg_last_error($conn);
            echo "<script>alert('Gagal menambah data: $error');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    }
}

include '../layout/header.php';
?>

<h2>Tambah Mahasiswa & User</h2>
<div class="form-box">
    <form action="" method="POST">
        <h3>Data Pribadi</h3>
        <label>NIM</label>
        <input type="text" name="nim" required placeholder="Contoh: F2000000001 (Wajib 11 Digit)">

        <label>Nama Lengkap</label>
        <input type="text" name="nama" required placeholder="Nama Mahasiswa">

        <label>Angkatan</label>
        <select name="angkatan" required>
            <option value="">- Pilih Angkatan -</option>
            <option value="F19">F19</option>
            <option value="F20">F20</option>
            <option value="F21">F21</option>
            <option value="F22">F22</option>
        </select>

        <label>Kontak</label>
        <input type="text" name="kontak" required placeholder="Nomor HP/WA">

        <hr style="margin: 20px 0; border: 0; border-top: 1px dashed #ccc;">

        <h3>Data Akun User</h3>
        <label>ID User (Buat Baru)</label>
        <input type="text" name="id_user" required placeholder="Contoh: U001">

        <br>
        <button type="submit" class="btn btn-green">Simpan Mahasiswa & User</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
