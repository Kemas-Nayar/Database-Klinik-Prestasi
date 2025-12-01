-- ==========================================
-- FINAL DATABASE SCRIPT: KLINIK PRESTASI
-- Database Engine: PostgreSQL
-- Updated: Mencakup fitur Link Drive, Password, & Gabungan Review
-- ==========================================

-- 1. BERSIHKAN TABEL LAMA (Urutan penting karena Foreign Key)
DROP TABLE IF EXISTS LAPORAN_REVIEW CASCADE; -- Tabel lama dihapus
DROP TABLE IF EXISTS LAPORAN_LAYANAN CASCADE;
DROP TABLE IF EXISTS PEER_REVIEW CASCADE;
DROP TABLE IF EXISTS KARYA CASCADE;
DROP TABLE IF EXISTS LAYANAN CASCADE;
DROP TABLE IF EXISTS ADMIN CASCADE;
DROP TABLE IF EXISTS MENTOR CASCADE;
DROP TABLE IF EXISTS USER_MEMBER CASCADE;
DROP TABLE IF EXISTS MAHASISWA CASCADE;

-- 2. MEMBUAT TABEL (DDL)

-- Tabel MAHASISWA
CREATE TABLE MAHASISWA (
    NIM CHAR(10) NOT NULL PRIMARY KEY,
    Nama VARCHAR(50) NOT NULL,
    Angkatan CHAR(3) NOT NULL,
    Kontak VARCHAR(30) NOT NULL,
    CONSTRAINT chk_angkatan CHECK (Angkatan IN ('F19', 'F20', 'F21', 'F22', 'F23'))
);

-- Tabel USER_MEMBER (Ditambah kolom Password)
CREATE TABLE USER_MEMBER (
    ID_User CHAR(10) NOT NULL PRIMARY KEY,
    NIM CHAR(10) NOT NULL UNIQUE,
    password VARCHAR(255) DEFAULT '12345', -- Default password
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE
);

-- Tabel MENTOR
CREATE TABLE MENTOR (
    ID_Mentor CHAR(10) NOT NULL PRIMARY KEY,
    ID_User CHAR(10) NOT NULL UNIQUE,
    NIM CHAR(10) NOT NULL UNIQUE,
    Bidang_Keahlian VARCHAR(50),
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE
);

-- Tabel ADMIN
CREATE TABLE ADMIN (
    ID_Admin CHAR(10) NOT NULL PRIMARY KEY,
    ID_User CHAR(10) NOT NULL UNIQUE,
    NIM CHAR(10) NOT NULL UNIQUE,
    Jabatan VARCHAR(30) NOT NULL,
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE,
    CONSTRAINT chk_jabatan CHECK (Jabatan IN ('Ketua Divisi', 'Sekbend', 'Anggota'))
);

-- Tabel LAYANAN
CREATE TABLE LAYANAN (
    ID_Layanan CHAR(10) NOT NULL PRIMARY KEY,
    Nama_Layanan VARCHAR(50) NOT NULL
);

-- Tabel KARYA (Ditambah kolom File_Karya untuk Link Drive)
CREATE TABLE KARYA (
    ID_Karya CHAR(10) NOT NULL PRIMARY KEY,
    ID_User CHAR(10) NOT NULL,
    Judul_Karya VARCHAR(100) NOT NULL,
    Bidang_Karya VARCHAR(10) NOT NULL,
    File_Karya VARCHAR(255), -- Menyimpan Link Google Drive
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    CONSTRAINT chk_bidang_karya CHECK (Bidang_Karya IN ('Esai', 'KTI', 'Bisnis', 'Design'))
);

-- Tabel PEER_REVIEW (Digabung dengan Hasil Review)
CREATE TABLE PEER_REVIEW (
    ID_Review CHAR(10) NOT NULL PRIMARY KEY,
    ID_Karya CHAR(10) NOT NULL,
    ID_Layanan CHAR(10) NOT NULL,
    ID_User CHAR(10) NOT NULL,   -- Mahasiswa yang meminta review
    ID_Mentor CHAR(10) NOT NULL, -- Mentor yang mereview
    Status_Review VARCHAR(30) NOT NULL,
    Hasil_Review TEXT,           -- Kolom baru: Feedback Mentor
    Tanggal_Review DATE,         -- Kolom baru: Tanggal Selesai
    FOREIGN KEY (ID_Karya) REFERENCES KARYA(ID_Karya) ON DELETE CASCADE,
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User),
    FOREIGN KEY (ID_Mentor) REFERENCES MENTOR(ID_Mentor),
    CONSTRAINT chk_status_review CHECK (Status_Review IN ('Menunggu', 'Selesai'))
);

-- Tabel LAPORAN_LAYANAN (Opsional, untuk admin)
CREATE TABLE LAPORAN_LAYANAN (
    ID_Laporan CHAR(10) NOT NULL PRIMARY KEY,
    ID_Admin CHAR(10) NOT NULL,
    ID_Layanan CHAR(10) NOT NULL,
    Hasil_Laporan TEXT,
    Tanggal_Laporan DATE NOT NULL,
    FOREIGN KEY (ID_Admin) REFERENCES ADMIN(ID_Admin),
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    CONSTRAINT chk_tgl_laporan_layanan CHECK (Tanggal_Laporan <= CURRENT_DATE)
);

-- 3. INSERT DATA DUMMY (DML)

-- Data MAHASISWA
INSERT INTO MAHASISWA (NIM, Nama, Angkatan, Kontak) VALUES
('F20000001', 'Ani Susanti', 'F20', '081211110001'),
('F21000002', 'Budi Cahyo', 'F21', '081211110002'),
('F22000003', 'Citra Dewi', 'F22', '081211110003'),
('F19000004', 'Dika Pratama', 'F19', '081211110004'),
('F20000005', 'Eka Fitri', 'F20', '081211110005'),
('F21000006', 'Ferry Sanjaya', 'F21', '081211110006'),
('F22000007', 'Gita Mentor', 'F22', '081211110007'),
('F22000008', 'Hadi Mentor', 'F22', '081211110008'),
('F22000009', 'Indah Mentor', 'F22', '081211110009'),
('F22000010', 'Joko Admin', 'F22', '081211110010'),
('F22000011', 'Kiki Admin', 'F22', '081211110011');

-- Data USER_MEMBER (Password default '12345')
INSERT INTO USER_MEMBER (ID_User, NIM, password) VALUES
('U001', 'F20000001', '12345'),
('U002', 'F21000002', '12345'),
('U003', 'F22000003', '12345'),
('U004', 'F19000004', '12345'),
('U005', 'F20000005', '12345'),
('U006', 'F21000006', '12345'),
('U007', 'F22000007', '12345'),
('U008', 'F22000008', '12345'),
('U009', 'F22000009', '12345'),
('U010', 'F22000010', '12345'),
('U011', 'F22000011', '12345');

-- Data MENTOR
INSERT INTO MENTOR (ID_Mentor, ID_User, NIM, Bidang_Keahlian) VALUES
('M001', 'U001', 'F20000001', 'Esai & KTI'),
('M002', 'U004', 'F19000004', 'Design'),
('M003', 'U007', 'F22000007', 'Bisnis'),
('M004', 'U008', 'F22000008', 'KTI'),
('M005', 'U009', 'F22000009', 'Design');

-- Data ADMIN
INSERT INTO ADMIN (ID_Admin, ID_User, NIM, Jabatan) VALUES
('A001', 'U006', 'F21000006', 'Ketua Divisi'),
('A002', 'U010', 'F22000010', 'Sekbend'),
('A003', 'U011', 'F22000011', 'Anggota');

-- Data LAYANAN
INSERT INTO LAYANAN (ID_Layanan, Nama_Layanan) VALUES
('L001', 'Review Esai'),
('L002', 'Review KTI'),
('L003', 'Review Bisnis Plan'),
('L004', 'Review Desain'),
('L005', 'Bimbingan Karya');

-- Data KARYA (Dengan Link Dummy)
INSERT INTO KARYA (ID_Karya, ID_User, Judul_Karya, Bidang_Karya, File_Karya) VALUES
('K001', 'U002', 'Inovasi Sampah Plastik', 'KTI', 'https://drive.google.com/file/d/dummy1'),
('K002', 'U003', 'Strategi Pemasaran Digital', 'Bisnis', 'https://drive.google.com/file/d/dummy2'),
('K003', 'U002', 'Meningkatkan Kualitas Udara', 'Esai', 'https://drive.google.com/file/d/dummy3'),
('K004', 'U005', 'Poster Lomba Desain Grafis', 'Design', 'https://drive.google.com/file/d/dummy4'),
('K005', 'U003', 'Rancangan Aplikasi Mobile', 'Design', 'https://drive.google.com/file/d/dummy5');

-- Data PEER_REVIEW (Denormalisasi: Hasil & Tanggal digabung)
INSERT INTO PEER_REVIEW (ID_Review, ID_Karya, ID_Layanan, ID_User, ID_Mentor, Status_Review, Hasil_Review, Tanggal_Review) VALUES
('R001', 'K001', 'L002', 'U002', 'M001', 'Selesai', 'Revisi minor pada bab 2, selebihnya bagus.', '2025-10-15'),
('R002', 'K002', 'L003', 'U003', 'M003', 'Menunggu', NULL, NULL),
('R003', 'K003', 'L001', 'U002', 'M001', 'Selesai', 'Sangat inspiratif, siap lomba.', '2025-11-01'),
('R004', 'K004', 'L004', 'U005', 'M002', 'Selesai', 'Perbaiki kontras warna.', '2025-11-10'),
('R005', 'K005', 'L004', 'U003', 'M005', 'Menunggu', NULL, NULL);

-- Data LAPORAN_LAYANAN
INSERT INTO LAPORAN_LAYANAN (ID_Laporan, ID_Admin, ID_Layanan, Hasil_Laporan, Tanggal_Laporan) VALUES
('LL001', 'A001', 'L001', 'Total 10 Esai telah selesai direview bulan ini.', '2025-11-15'),
('LL002', 'A001', 'L002', 'Ada 5 permintaan KTI baru.', '2025-11-15'),
('LL003', 'A002', 'L003', 'Perlu tambahan mentor di bidang bisnis.', '2025-11-10');
