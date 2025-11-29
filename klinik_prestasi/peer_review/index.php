<?php
include '../config/db.php';
include '../layout/header.php';
?>

<h2>Data Peer Review</h2>
<a href="create.php" class="btn btn-green">+ Tambah Review</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID Review</th>
            <th>ID Karya</th>
            <th>Link Karya</th> <!-- Kolom Baru -->
            <th>ID Layanan</th>
            <th>Mentor</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Menggunakan JOIN untuk mengambil Link (File_Karya) dari tabel KARYA
        $query = "SELECT P.*, K.File_Karya 
                  FROM PEER_REVIEW P 
                  JOIN KARYA K ON P.ID_Karya = K.ID_Karya 
                  ORDER BY P.ID_Review ASC";
        
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            $statusColor = ($row['STATUS_REVIEW'] == 'Selesai') ? 'green' : 'orange';
            
            // Logika Link Google Drive
            $link_drive = "-";
            if (!empty($row['FILE_KARYA'])) {
                $link_drive = "<a href='" . htmlspecialchars($row['FILE_KARYA']) . "' target='_blank' style='color:blue; text-decoration:underline;'>Buka Drive</a>";
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_REVIEW']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_KARYA']) . "</td>";
            echo "<td>" . $link_drive . "</td>"; // Menampilkan Link
            echo "<td>" . htmlspecialchars($row['ID_LAYANAN']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_MENTOR']) . "</td>";
            echo "<td style='color:$statusColor; font-weight:bold;'>" . htmlspecialchars($row['STATUS_REVIEW']) . "</td>";
            echo "<td>
                    <a href='edit.php?id=" . $row['ID_REVIEW'] . "' class='btn btn-blue'>Edit</a>
                    <a href='delete.php?id=" . $row['ID_REVIEW'] . "' class='btn btn-red' onclick=\"return confirm('Hapus review ini?');\">Hapus</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../layout/footer.php'; ?>
