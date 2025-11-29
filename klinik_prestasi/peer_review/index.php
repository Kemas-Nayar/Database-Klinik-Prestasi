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
            <th>ID Layanan</th>
            <th>Mentor</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM PEER_REVIEW ORDER BY ID_Review ASC";
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            $statusColor = ($row['STATUS_REVIEW'] == 'Selesai') ? 'green' : 'orange';
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_REVIEW']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_KARYA']) . "</td>";
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
