<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM KARYA WHERE ID_Karya = $1";
    $result = pg_query_params($conn, $query, array($id));

    if ($result) {
        echo "<script>alert('Karya dihapus.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal hapus. Data mungkin terhubung dengan Review.'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>
