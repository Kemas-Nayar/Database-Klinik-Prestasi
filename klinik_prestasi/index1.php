<?php
// Pastikan file db.php Anda sudah berisi koneksi PostgreSQL yang benar
include 'db.php';

// --- LOGIKA PHP (POSTGRESQL) ---

// 1. Tentukan Tab Aktif (Default: mahasiswa)
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'mahasiswa';

// 2. Handle Hapus Data (DELETE)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    
    // Gunakan pg_escape_string untuk keamanan dasar
    $id = pg_escape_string($conn, $id);

    if ($tab == 'mahasiswa') {
        // Postgres case-insensitive pada identifier yang tidak dikutip (NIM -> nim)
        $query = "DELETE FROM MAHASISWA WHERE NIM = '$id'";
    } elseif ($tab == 'karya') {
        $query = "DELETE FROM KARYA WHERE ID_Karya = '$id'";
    } elseif ($tab == 'reviews') {
        $query = "DELETE FROM PEER_REVIEW WHERE ID_Review = '$id'";
    }

    $result = pg_query($conn, $query);

    if ($result) {
        header("Location: index.php?tab=$tab&msg=deleted");
        exit;
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}

// 3. Handle Simpan Data (CREATE / UPDATE)
if (isset($_POST['simpan'])) {
    if ($tab == 'mahasiswa') {
        $nim = pg_escape_string($conn, $_POST['nim']);
        $nama = pg_escape_string($conn, $_POST['nama']);
        $angkatan = pg_escape_string($conn, $_POST['angkatan']);
        $kontak = pg_escape_string($conn, $_POST['kontak']);
        $mode = $_POST['mode']; // 'add' atau 'edit'

        if ($mode == 'add') {
            $query = "INSERT INTO MAHASISWA (NIM, Nama, Angkatan, Kontak) VALUES ('$nim', '$nama', '$angkatan', '$kontak')";
        } else {
            $query = "UPDATE MAHASISWA SET Nama='$nama', Angkatan='$angkatan', Kontak='$kontak' WHERE NIM='$nim'";
        }
    } elseif ($tab == 'karya') {
        $id_karya = pg_escape_string($conn, $_POST['id_karya']);
        $judul = pg_escape_string($conn, $_POST['judul']);
        $id_user = pg_escape_string($conn, $_POST['id_user']);
        $bidang = pg_escape_string($conn, $_POST['bidang']);
        $mode = $_POST['mode'];

        if ($mode == 'add') {
            $query = "INSERT INTO KARYA (ID_Karya, ID_User, Judul_Karya, Bidang_Karya) VALUES ('$id_karya', '$id_user', '$judul', '$bidang')";
        } else {
            $query = "UPDATE KARYA SET Judul_Karya='$judul', ID_User='$id_user', Bidang_Karya='$bidang' WHERE ID_Karya='$id_karya'";
        }
    }

    $result = pg_query($conn, $query);

    if ($result) {
        header("Location: index.php?tab=$tab&msg=saved");
        exit;
    } else {
        echo "<script>alert('Error: " . pg_last_error($conn) . "');</script>";
    }
}

// 4. Ambil Data untuk Ditampilkan (READ)
$data = [];
if ($tab == 'mahasiswa') {
    $result = pg_query($conn, "SELECT * FROM MAHASISWA ORDER BY NIM ASC");
} elseif ($tab == 'karya') {
    $result = pg_query($conn, "SELECT * FROM KARYA ORDER BY ID_Karya ASC");
} elseif ($tab == 'reviews') {
    $result = pg_query($conn, "SELECT * FROM PEER_REVIEW ORDER BY ID_Review ASC");
}

if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        // PENTING: PostgreSQL mengembalikan nama kolom dalam lowercase (nim, nama).
        // Kita ubah jadi UPPERCASE agar cocok dengan variabel HTML ($row['NIM']) yang sudah kita buat sebelumnya.
        $data[] = array_change_key_case($row, CASE_UPPER);
    }
} else {
    echo "Gagal mengambil data: " . pg_last_error($conn);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klinik Prestasi (PostgreSQL)</title>
    <!-- Menggunakan Tailwind CSS lewat CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Header -->
    <header class="bg-blue-700 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-full text-blue-700">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Klinik Prestasi</h1>
                    <p class="text-xs text-blue-200">Sistem Manajemen Data (PostgreSQL)</p>
                </div>
            </div>
            <div class="text-sm bg-blue-800 py-1 px-3 rounded-full">Admin Dashboard</div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        
        <!-- Pesan Notifikasi -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php 
                    if ($_GET['msg'] == 'saved') echo "Data berhasil disimpan!";
                    if ($_GET['msg'] == 'deleted') echo "Data berhasil dihapus!";
                ?>
            </div>
        <?php endif; ?>

        <!-- Tab Navigasi -->
        <div class="flex flex-wrap gap-4 mb-8 border-b border-gray-200 pb-2">
            <a href="?tab=mahasiswa" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition <?= $tab == 'mahasiswa' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'text-gray-500 hover:bg-gray-100' ?>">
                <i class="fas fa-user"></i> Data Mahasiswa
            </a>
            <a href="?tab=karya" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition <?= $tab == 'karya' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'text-gray-500 hover:bg-gray-100' ?>">
                <i class="fas fa-file-alt"></i> Data Karya
            </a>
            <a href="?tab=reviews" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition <?= $tab == 'reviews' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'text-gray-500 hover:bg-gray-100' ?>">
                <i class="fas fa-check-double"></i> Peer Review
            </a>
        </div>

        <!-- Tombol Tambah -->
        <div class="flex justify-end mb-6">
            <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-sm">
                <i class="fas fa-plus"></i> Tambah Data
            </button>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                        <tr>
                            <?php if ($tab == 'mahasiswa'): ?>
                                <th class="p-4">NIM</th>
                                <th class="p-4">Nama</th>
                                <th class="p-4">Angkatan</th>
                                <th class="p-4">Kontak</th>
                            <?php elseif ($tab == 'karya'): ?>
                                <th class="p-4">ID Karya</th>
                                <th class="p-4">Judul</th>
                                <th class="p-4">ID User</th>
                                <th class="p-4">Bidang</th>
                            <?php elseif ($tab == 'reviews'): ?>
                                <th class="p-4">ID Review</th>
                                <th class="p-4">ID Karya</th>
                                <th class="p-4">Layanan</th>
                                <th class="p-4">Status</th>
                            <?php endif; ?>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($data) > 0): ?>
                            <?php foreach ($data as $row): ?>
                                <tr class="hover:bg-blue-50 transition">
                                    <?php if ($tab == 'mahasiswa'): ?>
                                        <td class="p-4 font-medium text-blue-600"><?= $row['NIM'] ?></td>
                                        <td class="p-4"><?= $row['NAMA'] ?></td>
                                        <td class="p-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold"><?= $row['ANGKATAN'] ?></span></td>
                                        <td class="p-4 text-gray-500"><?= $row['KONTAK'] ?></td>
                                        <!-- Data hidden untuk edit -->
                                        <td style="display:none;" class="data-json"><?= json_encode($row) ?></td>
                                        
                                    <?php elseif ($tab == 'karya'): ?>
                                        <td class="p-4 font-medium text-blue-600"><?= $row['ID_KARYA'] ?></td>
                                        <td class="p-4 font-medium"><?= $row['JUDUL_KARYA'] ?></td>
                                        <td class="p-4 text-gray-500"><?= $row['ID_USER'] ?></td>
                                        <td class="p-4"><span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold"><?= $row['BIDANG_KARYA'] ?></span></td>
                                        <td style="display:none;" class="data-json"><?= json_encode($row) ?></td>

                                    <?php elseif ($tab == 'reviews'): ?>
                                        <td class="p-4 font-medium text-blue-600"><?= $row['ID_REVIEW'] ?></td>
                                        <td class="p-4"><?= $row['ID_KARYA'] ?></td>
                                        <td class="p-4"><?= $row['ID_LAYANAN'] ?></td>
                                        <td class="p-4">
                                            <span class="<?= $row['STATUS_REVIEW'] == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' ?> px-2 py-1 rounded text-xs font-bold">
                                                <?= $row['STATUS_REVIEW'] ?>
                                            </span>
                                        </td>
                                        <td style="display:none;" class="data-json"><?= json_encode($row) ?></td>
                                    <?php endif; ?>

                                    <td class="p-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <!-- Tombol Edit (Trigger JS) -->
                                            <button onclick='editRow(this)' class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <!-- Tombol Hapus -->
                                            <?php 
                                                $pk = ($tab == 'mahasiswa') ? 'NIM' : (($tab == 'karya') ? 'ID_KARYA' : 'ID_REVIEW');
                                                // Pastikan key ada di array row
                                                $idVal = isset($row[$pk]) ? $row[$pk] : '';
                                            ?>
                                            <a href="index.php?tab=<?= $tab ?>&action=delete&id=<?= $idVal ?>" onclick="return confirm('Yakin hapus data ini?')" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="p-8 text-center text-gray-400">Data Kosong / Error Query</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form (Hidden by default) -->
    <div id="modalForm" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800" id="modalTitle">Tambah Data</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            
            <form action="index.php?tab=<?= $tab ?>" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="mode" id="formMode" value="add">

                <!-- Form Mahasiswa -->
                <?php if ($tab == 'mahasiswa'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                        <input type="text" name="nim" id="input_nim" required class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" id="input_nama" required class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                        <select name="angkatan" id="input_angkatan" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                            <option value="F19">F19</option>
                            <option value="F20">F20</option>
                            <option value="F21">F21</option>
                            <option value="F22">F22</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                        <input type="text" name="kontak" id="input_kontak" required class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                    </div>

                <!-- Form Karya -->
                <?php elseif ($tab == 'karya'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Karya</label>
                        <input type="text" name="id_karya" id="input_id_karya" required class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Karya</label>
                        <input type="text" name="judul" id="input_judul" required class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID User (Penulis)</label>
                        <input type="text" name="id_user" id="input_id_user" required class="w-full border border-gray-300 px-3 py-2 rounded-lg" placeholder="Contoh: U001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                        <select name="bidang" id="input_bidang" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                            <option value="Esai">Esai</option>
                            <option value="KTI">KTI</option>
                            <option value="Bisnis">Bisnis</option>
                            <option value="Design">Design</option>
                        </select>
                    </div>
                
                <?php else: ?>
                    <p class="text-red-500">Form untuk tab ini belum dibuat di contoh sederhana ini.</p>
                <?php endif; ?>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" name="simpan" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Sederhana untuk Modal & Edit -->
    <script>
        function openModal(mode) {
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalForm').classList.add('flex');
            document.getElementById('formMode').value = mode;
            document.getElementById('modalTitle').innerText = (mode == 'add' ? 'Tambah Data' : 'Edit Data');

            // Reset form jika mode add
            if (mode == 'add') {
                const inputs = document.querySelectorAll('#modalForm input:not([type=hidden])');
                inputs.forEach(input => input.value = '');
            }
        }

        function closeModal() {
            document.getElementById('modalForm').classList.add('hidden');
            document.getElementById('modalForm').classList.remove('flex');
        }

        function editRow(button) {
            // Ambil data JSON dari kolom tersembunyi di baris tabel
            const tr = button.closest('tr');
            const jsonStr = tr.querySelector('.data-json').innerText;
            const data = JSON.parse(jsonStr);

            openModal('edit');

            const tab = "<?= $tab ?>";
            
            // Perhatikan Key menggunakan HURUF BESAR karena array_change_key_case di PHP
            if (tab === 'mahasiswa') {
                document.getElementById('input_nim').value = data.NIM;
                document.getElementById('input_nim').readOnly = true; 
                document.getElementById('input_nama').value = data.NAMA;
                document.getElementById('input_angkatan').value = data.ANGKATAN;
                document.getElementById('input_kontak').value = data.KONTAK;
            } else if (tab === 'karya') {
                document.getElementById('input_id_karya').value = data.ID_KARYA;
                document.getElementById('input_id_karya').readOnly = true;
                document.getElementById('input_judul').value = data.JUDUL_KARYA;
                document.getElementById('input_id_user').value = data.ID_USER;
                document.getElementById('input_bidang').value = data.BIDANG_KARYA;
            }
        }
    </script>
</body>
</html>
