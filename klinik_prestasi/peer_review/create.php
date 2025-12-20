<?php
include '../config/db.php';
include '../layout/header.php'; 

function generateIdReview($conn) {
    $query = "SELECT ID_Review FROM PEER_REVIEW ORDER BY ID_Review DESC LIMIT 1";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);

    if ($row) {
        $lastId = $row['id_review'];
        $number = (int) substr($lastId, 1); 
        $newNumber = $number + 1;
    } else {
        $newNumber = 1;
    }

    return 'R' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_review = generateIdReview($conn);
    
    $id_karya = $_POST['id_karya'];
    $id_layanan = $_POST['id_layanan'];
    $id_mentor = $_POST['id_mentor'];
    $status = $_POST['status'];

    if (!empty($id_karya) && !empty($id_mentor)) {
        
        $query = "INSERT INTO PEER_REVIEW (ID_Review, ID_Karya, ID_Layanan, ID_Mentor, Status_Review) VALUES ($1, $2, $3, $4, $5)";
        $params = array($id_review, $id_karya, $id_layanan, $id_mentor, $status);
        
        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            echo "<script>alert('Review berhasil ditambahkan! ID Baru: $id_review'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal: " . pg_last_error($conn) . "');</script>";
        }
    }
}


$q_karya = pg_query($conn, "SELECT ID_Karya, Judul_Karya FROM KARYA ORDER BY ID_Karya ASC");

$q_layanan = pg_query($conn, "SELECT * FROM LAYANAN ORDER BY ID_Layanan ASC");

$q_mentor = pg_query($conn, "
    SELECT M.ID_Mentor, Mhs.Nama, M.Bidang_Keahlian 
    FROM MENTOR M
    JOIN USER_MEMBER U ON M.ID_User = U.ID_User
    JOIN MAHASISWA Mhs ON U.NIM = Mhs.NIM
    WHERE M.Status_Mentor = 'Aktif'
    ORDER BY Mhs.Nama ASC
    ");

$next_review_id = generateIdReview($conn);
?>

<h2>Tambah Penugasan Review</h2>
<div class="form-box">
    <form action="" method="POST">
        <label>ID Review</label>
        <input type="text" value="<?= $next_review_id ?>" disabled style="background-color: #eee; color: #555;">
        <label>Pilih Karya</label>
        <select name="id_karya" required>
            <option value="">- Pilih Judul Karya -</option>
            <?php while ($row = pg_fetch_assoc($q_karya)): ?>
                <option value="<?= $row['id_karya'] ?>">
                    <?= $row['id_karya'] ?> - <?= $row['judul_karya'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Pilih Layanan</label>
        <select name="id_layanan" required>
            <option value="">- Pilih Layanan -</option>
            <?php while ($row = pg_fetch_assoc($q_layanan)): ?>
                <option value="<?= $row['id_layanan'] ?>">
                    <?= $row['nama_layanan'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Pilih Mentor</label>
        <select name="id_mentor" required>
            <option value="">- Pilih Mentor -</option>
            <?php while ($row = pg_fetch_assoc($q_mentor)): ?>
                <option value="<?= $row['id_mentor'] ?>">
                    <?= $row['nama'] ?> (Keahlian: <?= $row['bidang_keahlian'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Status Awal</label>
        <select name="status" required>
            <option value="Menunggu">Menunggu</option>
            <option value="Selesai">Selesai</option>
        </select>

        <br><br>
        <button type="submit" class="btn btn-green">Simpan Penugasan</button>
        <a href="index.php" class="btn btn-gray">Batal</a>
    </form>
</div>

<?php include '../layout/footer.php'; ?>
