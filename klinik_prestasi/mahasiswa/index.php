<?php
include '../config/db.php';
include '../layout/header.php';
?>

<h2>Daftar Mahasiswa</h2>
<a href="create.php" class="btn btn-green">+ Tambah Mahasiswa</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Angkatan</th>
            <th>Kontak</th>
            <th>Status</th> 
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM MAHASISWA ORDER BY NIM ASC";
        $result = pg_query($conn, $query);

        while ($row = pg_fetch_assoc($result)) {
            $row = array_change_key_case($row, CASE_UPPER);
            
            $statusColor = ($row['STATUS_MHS'] == 'Aktif') ? 'green' : 'red';

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['NIM']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NAMA']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ANGKATAN']) . "</td>";
            echo "<td>" . htmlspecialchars($row['KONTAK']) . "</td>";
            
            echo "<td style='color:$statusColor; font-weight:bold;'>" . htmlspecialchars($row['STATUS_MHS']) . "</td>";
            
            echo "<td>
                    <a href='edit.php?nim=" . $row['NIM'] . "' class='btn btn-blue'>Edit</a>
                    <!-- Tombol Hapus dihapus atau diberi peringatan keras -->
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../layout/footer.php'; ?>
