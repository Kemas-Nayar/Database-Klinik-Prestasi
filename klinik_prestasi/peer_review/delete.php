<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM PEER_REVIEW WHERE ID_Review = $1";
    $result = pg_query_params($conn, $query, array($id));

    if ($result) {
        echo "<script>alert('Review dihapus.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal hapus data.'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>
