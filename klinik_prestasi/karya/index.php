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
            <th>Penulis</th>
            <th>Bidang</th>
            <th>Link Drive</th> <!-- Label diperbarui -->
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM KARYA ORDER BY ID_Karya ASC";
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            
            // Logika Link Google Drive
            $file_link = "-";
            if (!empty($row['FILE_KARYA'])) {
                // Langsung gunakan isi kolom sebagai href karena isinya adalah URL
                $file_link = "<a href='" . htmlspecialchars($row['FILE_KARYA']) . "' target='_blank' style='color:blue; text-decoration:underline;'>Buka Link</a>";
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID_KARYA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['JUDUL_KARYA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ID_USER']) . "</td>";
            echo "<td>" . htmlspecialchars($row['BIDANG_KARYA']) . "</td>";
            echo "<td>" . $file_link . "</td>";
            echo "<td>
                    <a href='edit.php?id=" . $row['ID_KARYA'] . "' class='btn btn-blue'>Edit</a>
                    <a href='delete.php?id=" . $row['ID_KARYA'] . "' class='btn btn-red' onclick=\"return confirm('Hapus karya ini?');\">Hapus</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../layout/footer.php'; ?>
