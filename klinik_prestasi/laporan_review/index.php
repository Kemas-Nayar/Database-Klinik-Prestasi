<?php
include '../config/db.php';
include '../layout/header_mentor.php';
?>

<h2>Data Laporan Review</h2>
<a href="create.php" class="btn btn-green">+ Buat Laporan Baru</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID Laporan</th>
            <th>ID Karya</th>
            <th>ID User</th>
            <th>ID Mentor</th>
            <th>Hasil Review</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM LAPORAN_REVIEW ORDER BY Tanggal_Laporan DESC";
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            
            // Memotong teks hasil review jika terlalu panjang (agar tabel rapi)
            $hasil_display = $row['HASIL_REVIEW'];
            if (strlen($hasil_display) > 50) {
                $hasil_display = substr($hasil_display, 0, 50) . "...";
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_LAPORAN_REVIEW']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_KARYA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_USER']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_MENTOR']) . "</td>";
            echo "<td>" . htmlspecialchars($hasil_display) . "</td>";
            echo "<td>" . htmlspecialchars($row['TANGGAL_LAPORAN']) . "</td>";
            echo "<td>
                    <a href='edit.php?id=" . $row['ID_LAPORAN_REVIEW'] . "' class='btn btn-blue'>Edit</a>
                    <a href='delete.php?id=" . $row['ID_LAPORAN_REVIEW'] . "' class='btn btn-red' onclick=\"return confirm('Hapus laporan ini?');\">Hapus</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../layout/footer.php'; ?>
