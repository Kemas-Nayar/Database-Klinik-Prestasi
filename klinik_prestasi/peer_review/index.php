<?php
include '../config/db.php';
include '../layout/header.php';
?>

<h2>Data Peer Review & Hasil</h2>
<a href="create.php" class="btn btn-green">+ Tugaskan Mentor</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul Karya</th>
            <th>Mentor</th>
            <th>Status</th>
            <th>Hasil</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT P.*, K.Judul_Karya 
                  FROM PEER_REVIEW P 
                  LEFT JOIN KARYA K ON P.ID_Karya = K.ID_Karya 
                  ORDER BY P.ID_Review ASC";
        
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            $statusColor = ($row['STATUS_REVIEW'] == 'Selesai') ? 'green' : 'orange';
            
            $hasil_full = $row['HASIL_REVIEW'];
            $hasil_cut = !empty($hasil_full) ? substr($hasil_full, 0, 40) . "..." : "-";

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_REVIEW']) . "</td>";
            echo "<td>" . htmlspecialchars($row['JUDUL_KARYA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_MENTOR']) . "</td>";
            echo "<td style='color:$statusColor; font-weight:bold;'>" . htmlspecialchars($row['STATUS_REVIEW']) . "</td>";
            echo "<td style='color:#555; font-style:italic;'>" . htmlspecialchars($hasil_cut) . "</td>";
            echo "<td>
                    <!-- Tombol Lihat Detail (Baru) -->
                    <a href='detail.php?id=" . $row['ID_REVIEW'] . "' class='btn btn-gray' title='Lihat Selengkapnya'>Lihat</a>
                    
                    <a href='edit.php?id=" . $row['ID_REVIEW'] . "' class='btn btn-blue'>Edit</a>
                    <a href='delete.php?id=" . $row['ID_REVIEW'] . "' class='btn btn-red' onclick=\"return confirm('Hapus data ini?');\">Hapus</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../layout/footer.php'; ?>
