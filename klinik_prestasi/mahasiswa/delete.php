<?php
include '../config/db.php';

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    pg_query($conn, "BEGIN");

    $query_user = "DELETE FROM USER_MEMBER WHERE NIM = $1";
    $result_user = pg_query_params($conn, $query_user, array($nim));

    $query_mhs = "DELETE FROM MAHASISWA WHERE NIM = $1";
    $result_mhs = pg_query_params($conn, $query_mhs, array($nim));

    if ($result_user && $result_mhs) {
        pg_query($conn, "COMMIT");
        echo "<script>alert('Data Mahasiswa dan User terkait berhasil dihapus.'); window.location='index.php';</script>";
    } else {
        pg_query($conn, "ROLLBACK");
        
        $error = pg_last_error($conn);
        
        if (strpos($error, 'foreign key constraint') !== false) {
            echo "<script>alert('Gagal menghapus! Data ini masih digunakan di tabel lain (Mungkin sebagai Mentor/Admin?). Hapus data terkait terlebih dahulu.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data: $error'); window.location='index.php';</script>";
        }
    }
} else {
    header("Location: index.php");
}
?>
