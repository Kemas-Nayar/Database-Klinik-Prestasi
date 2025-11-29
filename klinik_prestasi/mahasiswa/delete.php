<?php
include '../config/db.php';

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    // Mulai Transaksi Database
    pg_query($conn, "BEGIN");

    // LANGKAH 1: Hapus data di USER_MEMBER terlebih dahulu
    // (Karena tabel ini mereferensikan MAHASISWA, jadi harus dihapus duluan)
    $query_user = "DELETE FROM USER_MEMBER WHERE NIM = $1";
    $result_user = pg_query_params($conn, $query_user, array($nim));

    // LANGKAH 2: Hapus data di MAHASISWA
    $query_mhs = "DELETE FROM MAHASISWA WHERE NIM = $1";
    $result_mhs = pg_query_params($conn, $query_mhs, array($nim));

    if ($result_user && $result_mhs) {
        // Jika kedua proses berhasil, simpan perubahan (COMMIT)
        pg_query($conn, "COMMIT");
        echo "<script>alert('Data Mahasiswa dan User terkait berhasil dihapus.'); window.location='index.php';</script>";
    } else {
        // Jika ada error, batalkan semua (ROLLBACK)
        pg_query($conn, "ROLLBACK");
        
        // Ambil pesan error untuk debugging
        $error = pg_last_error($conn);
        
        // Pesan user friendly
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
