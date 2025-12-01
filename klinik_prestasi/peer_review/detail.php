<?php
include '../config/db.php';

$id_url = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id_url)) { header("Location: index.php"); exit; }

// Ambil data lengkap berdasarkan ID
$query = "SELECT P.*, K.Judul_Karya, K.File_Karya 
          FROM PEER_REVIEW P 
          LEFT JOIN KARYA K ON P.ID_Karya = K.ID_Karya 
          WHERE P.ID_Review = $1";

$result = pg_query_params($conn, $query, array($id_url));
$data = pg_fetch_assoc($result);

if (!$data) die("Data tidak ditemukan.");
$data = array_change_key_case($data, CASE_UPPER);

include '../layout/header.php'; 
?>

<h2>Detail Hasil Review</h2>

<div class="form-box" style="max-width: 800px;"> <!-- Box lebih lebar -->
    
    <!-- Informasi Dasar (Tampil Rapi) -->
    <table style="border:none; width:100%;">
        <tr style="background:none;">
            <td style="border:none; width:150px; font-weight:bold;">ID Review</td>
            <td style="border:none;">: <?= $data['ID_REVIEW'] ?></td>
        </tr>
        <tr style="background:none;">
            <td style="border:none; font-weight:bold;">Judul Karya</td>
            <td style="border:none;">: <?= $data['JUDUL_KARYA'] ?></td>
        </tr>
        <tr style="background:none;">
            <td style="border:none; font-weight:bold;">Mentor</td>
            <td style="border:none;">: <?= $data['ID_MENTOR'] ?></td>
        </tr>
        <tr style="background:none;">
            <td style="border:none; font-weight:bold;">Status</td>
            <td style="border:none;">
                : <span style="font-weight:bold; color: <?= ($data['STATUS_REVIEW']=='Selesai')?'green':'orange' ?>">
                    <?= $data['STATUS_REVIEW'] ?>
                  </span>
            </td>
        </tr>
        <tr style="background:none;">
            <td style="border:none; font-weight:bold;">Link Karya</td>
            <td style="border:none;">
                : <?php 
                    if (!empty($data['FILE_KARYA'])) {
                        echo "<a href='" . htmlspecialchars($data['FILE_KARYA']) . "' target='_blank' style='color:blue;'>Buka Google Drive</a>";
                    } else {
                        echo "-";
                    }
                  ?>
            </td>
        </tr>
    </table>

    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

    <!-- Bagian Hasil Review Lengkap -->
    <h3>📝 Hasil & Masukan Lengkap</h3>
    <div style="background: #fff; padding: 15px; border: 1px solid #ccc; border-radius: 4px; min-height: 100px; white-space: pre-wrap; line-height: 1.6;">
        <?= !empty($data['HASIL_REVIEW']) ? htmlspecialchars($data['HASIL_REVIEW']) : "<em style='color:gray'>Belum ada masukan review.</em>" ?>
    </div>

    <br>
    <div style="text-align: right;">
        <!-- Tombol Edit jika ingin mengubah -->
        <a href="edit.php?id=<?= $data['ID_REVIEW'] ?>" class="btn btn-blue">Edit / Update</a>
        <!-- Tombol Kembali -->
        <a href="index.php" class="btn btn-gray">Kembali</a>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
