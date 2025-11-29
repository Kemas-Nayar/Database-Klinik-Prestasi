<?php
include '../config/db.php';
include '../layout/header.php';
?>

<h2>Daftar Karya</h2>
<a href="create.php" class="btn btn-green">+ Tambah Karya</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID Karya</th>
            <th>Judul Karya</th>
            <th>Penulis (ID User)</th>
            <th>Bidang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM KARYA ORDER BY ID_Karya ASC";
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_KARYA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['JUDUL_KARYA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_USER']) . "</td>";
            echo "<td>" . htmlspecialchars($row['BIDANG_KARYA']) . "</td>";
            echo "<td>
                    <a href='edit.php?id=" . $row['ID_KARYA'] . "' class='btn btn-blue'>Edit</a>
                    <a href='delete.php?id=" . $row['ID_KARYA'] . "' class='btn btn-red' onclick=\"return confirm('Hapus karya " . $row['JUDUL_KARYA'] . "?');\">Hapus</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../layout/footer.php'; ?>
