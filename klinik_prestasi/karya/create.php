<?php
include '../config/db.php';
include '../layout/header.php';

function generateIdKarya($conn) {
    $query = "SELECT ID_Karya FROM KARYA ORDER BY ID_Karya DESC LIMIT 1";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);

    if ($row) {
        $lastId = $row['id_karya'];
        $number = (int) substr($lastId, 1); 
        $newNumber = $number + 1;
    } else {
        $newNumber = 1;
    }

    return 'K' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_karya = generateIdKarya($conn);
    
    $judul = $_POST['judul'];
    $id_user = $_POST['id_user'];
    $bidang = $_POST['bidang'];
    $link_drive = $_POST['link_drive']; 

    if (!empty($judul) && !empty($id_user)) {
        $query = "INSERT INTO KARYA (ID_Karya, Judul_Karya, ID_User, Bidang_Karya, File_Karya) VALUES ($1, $2, $3, $4, $5)";
        $params = array($id_karya, $judul, $id_user, $bidang, $link_drive);
        
        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            echo "<script>alert('Karya berhasil ditambahkan! ID Baru: $id_karya'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal: " . pg_last_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    }
}

$query_users = "
    SELECT U.ID_User, M.Nama, M.NIM 
    FROM USER_MEMBER U
    JOIN MAHASISWA M ON U.NIM = M.NIM
    WHERE M.Status_Mhs = 'Aktif'  
    ORDER BY M.Nama ASC
";

$result_users = pg_query($conn, $query_users);

$next_id = generateIdKarya($conn);
?>

<h2>Tambah Karya</h2>
<div class="form-box">
    <form action="" method="POST">
        <label>ID Karya</label>
        <input type="text" value="<?= $next_id ?>" disabled style="background-color: #eee; color: #555;">
        <label>Judul Karya</label>
        <input type="text" name="judul" required placeholder="Judul Lengkap">

        <label>Penulis (Mahasiswa)</label>
        <select name="id_user" required>
            <option value="">- Pilih Penulis -</option>
            <?php while ($user = pg_fetch_assoc($result_users)): ?>
                <option value="<?= $user['id_user'] ?>">
                    <?= $user['nama'] ?> (<?= $user['nim'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

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
