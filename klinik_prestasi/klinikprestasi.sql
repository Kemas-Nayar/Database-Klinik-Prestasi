-- ==========================================
-- DATABASE FINAL: KLINIK PRESTASI
-- ==========================================

-- 1. BERSIHKAN TABEL LAMA (Drop urutan foreign key)
DROP TABLE IF EXISTS LAPORAN_REVIEW CASCADE;
DROP TABLE IF EXISTS LAPORAN_LAYANAN CASCADE;
DROP TABLE IF EXISTS PEER_REVIEW CASCADE;
DROP TABLE IF EXISTS KARYA CASCADE;
DROP TABLE IF EXISTS LAYANAN CASCADE;
DROP TABLE IF EXISTS ADMIN CASCADE;
DROP TABLE IF EXISTS MENTOR CASCADE;
DROP TABLE IF EXISTS USER_MEMBER CASCADE;
DROP TABLE IF EXISTS MAHASISWA CASCADE;

-- 2. DDL (STRUKTUR TABEL BARU)

-- Tabel MAHASISWA (Master Data)
CREATE TABLE MAHASISWA (
    NIM CHAR(11) NOT NULL PRIMARY KEY, -- Panjang NIM disesuaikan 11 digit
    Nama VARCHAR(50) NOT NULL,
    Angkatan CHAR(3) NOT NULL,
    Kontak VARCHAR(30) NOT NULL,
    CONSTRAINT chk_angkatan CHECK (Angkatan IN ('F19', 'F20', 'F21', 'F22', 'F23'))
);

-- Tabel USER_MEMBER (Akun)
CREATE TABLE USER_MEMBER (
    ID_User CHAR(4) NOT NULL PRIMARY KEY, -- ID User 4 Digit (Ex: U001)
    NIM CHAR(11) NOT NULL UNIQUE,
    -- Password dihapus sesuai permintaan
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE
);

-- Tabel MENTOR (Peran Khusus)
CREATE TABLE MENTOR (
    ID_Mentor CHAR(4) NOT NULL PRIMARY KEY, -- ID Mentor 4 Digit (Ex: M001)
    ID_User CHAR(4) NOT NULL UNIQUE,
    Bidang_Keahlian VARCHAR(50),
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE
);

-- Tabel ADMIN (Peran Khusus)
CREATE TABLE ADMIN (
    ID_Admin CHAR(4) NOT NULL PRIMARY KEY, -- ID Admin 4 Digit (Ex: A001)
    ID_User CHAR(4) NOT NULL UNIQUE,
    Jabatan VARCHAR(30) NOT NULL,
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    CONSTRAINT chk_jabatan CHECK (Jabatan IN ('Ketua Divisi', 'Sekbend', 'Anggota'))
);

-- Tabel LAYANAN (Master Layanan)
CREATE TABLE LAYANAN (
    ID_Layanan CHAR(4) NOT NULL PRIMARY KEY, -- ID Layanan 4 Digit (Ex: L001)
    Nama_Layanan VARCHAR(50) NOT NULL
);

-- Tabel KARYA (Transaksi Upload)
CREATE TABLE KARYA (
    ID_Karya CHAR(4) NOT NULL PRIMARY KEY, -- ID Karya 4 Digit (Ex: K001)
    ID_User CHAR(4) NOT NULL, -- Penulis (FK ke User)
    Judul_Karya VARCHAR(100) NOT NULL,
    Bidang_Karya VARCHAR(10) NOT NULL,
    File_Karya VARCHAR(255), -- Link Google Drive
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    CONSTRAINT chk_bidang_karya CHECK (Bidang_Karya IN ('Esai', 'KTI', 'Bisnis', 'Design'))
);

-- Tabel PEER_REVIEW (Transaksi Penilaian)
-- Perbaikan Utama: Kolom ID_User dihapus karena redundan (sudah ada di tabel KARYA)
CREATE TABLE PEER_REVIEW (
    ID_Review CHAR(4) NOT NULL PRIMARY KEY, -- ID Review 4 Digit (Ex: R001)
    ID_Karya CHAR(4) NOT NULL,
    ID_Layanan CHAR(4) NOT NULL,
    ID_Mentor CHAR(4) NOT NULL,
    Status_Review VARCHAR(30) NOT NULL,
    Hasil_Review TEXT,           -- Feedback Mentor (Gabungan Laporan)
    Tanggal_Review DATE,         -- Tanggal Selesai (Gabungan Laporan)
    FOREIGN KEY (ID_Karya) REFERENCES KARYA(ID_Karya) ON DELETE CASCADE,
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    FOREIGN KEY (ID_Mentor) REFERENCES MENTOR(ID_Mentor),
    CONSTRAINT chk_status_review CHECK (Status_Review IN ('Menunggu', 'Selesai'))
);

-- Tabel LAPORAN_LAYANAN (Laporan Administratif)
CREATE TABLE LAPORAN_LAYANAN (
    ID_Laporan CHAR(4) NOT NULL PRIMARY KEY, -- ID Laporan 4 Digit (Ex: LL01)
    ID_Admin CHAR(4) NOT NULL,
    ID_Layanan CHAR(4) NOT NULL,
    Hasil_Laporan TEXT,
    Tanggal_Laporan DATE NOT NULL,
    FOREIGN KEY (ID_Admin) REFERENCES ADMIN(ID_Admin),
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    CONSTRAINT chk_tgl_laporan_layanan CHECK (Tanggal_Laporan <= CURRENT_DATE)
);

-- 3. DML (INSERT DATA DUMMY YANG SESUAI)

-- Insert MAHASISWA (NIM 11 Digit)
INSERT INTO MAHASISWA (NIM, Nama, Angkatan, Kontak) VALUES
('F2001234567', 'Ani Susanti', 'F20', '081211110001'),
('F2101234567', 'Budi Cahyo', 'F21', '081211110002'),
('F2201234567', 'Citra Dewi', 'F22', '081211110003'),
('F1901234567', 'Dika Pratama', 'F19', '081211110004'),
('F2001234568', 'Eka Fitri', 'F20', '081211110005'),
('F2101234568', 'Ferry Sanjaya', 'F21', '081211110006'),
('F2201234569', 'Gita Mentor', 'F22', '081211110007'),
('F2201234570', 'Hadi Mentor', 'F22', '081211110008'),
('F2201234571', 'Indah Mentor', 'F22', '081211110009'),
('F2201234572', 'Joko Admin', 'F22', '081211110010'),
('F2201234573', 'Kiki Admin', 'F22', '081211110011');

-- Insert USER_MEMBER (ID 4 Digit)
INSERT INTO USER_MEMBER (ID_User, NIM) VALUES
('U001', 'F2001234567'), -- Ani (Mentor)
('U002', 'F2101234567'), -- Budi (Member)
('U003', 'F2201234567'), -- Citra (Member)
('U004', 'F1901234567'), -- Dika (Mentor)
('U005', 'F2001234568'), -- Eka (Member)
('U006', 'F2101234568'), -- Ferry (Admin)
('U007', 'F2201234569'), -- Gita (Mentor)
('U008', 'F2201234570'), -- Hadi (Mentor)
('U009', 'F2201234571'), -- Indah (Mentor)
('U010', 'F2201234572'), -- Joko (Admin)
('U011', 'F2201234573'); -- Kiki (Admin)

-- Insert MENTOR (ID 4 Digit)
INSERT INTO MENTOR (ID_Mentor, ID_User, Bidang_Keahlian) VALUES
('M001', 'U001', 'Esai & KTI'),
('M002', 'U004', 'Design'),
('M003', 'U007', 'Bisnis'),
('M004', 'U008', 'KTI'),
('M005', 'U009', 'Design');

-- Insert ADMIN (ID 4 Digit)
INSERT INTO ADMIN (ID_Admin, ID_User, Jabatan) VALUES
('A001', 'U006', 'Ketua Divisi'),
('A002', 'U010', 'Sekbend'),
('A003', 'U011', 'Anggota');

-- Insert LAYANAN (ID 4 Digit)
INSERT INTO LAYANAN (ID_Layanan, Nama_Layanan) VALUES
('L001', 'Review Esai'),
('L002', 'Review KTI'),
('L003', 'Review Bisnis Plan'),
('L004', 'Review Desain'),
('L005', 'Bimbingan Karya');

-- Insert KARYA (ID 4 Digit, dengan Link Dummy)
INSERT INTO KARYA (ID_Karya, ID_User, Judul_Karya, Bidang_Karya, File_Karya) VALUES
('K001', 'U002', 'Inovasi Sampah Plastik', 'KTI', 'https://drive.google.com/dummy1'),
('K002', 'U003', 'Strategi Pemasaran Digital', 'Bisnis', 'https://drive.google.com/dummy2'),
('K003', 'U002', 'Meningkatkan Kualitas Udara', 'Esai', 'https://drive.google.com/dummy3'),
('K004', 'U005', 'Poster Lomba Desain Grafis', 'Design', 'https://drive.google.com/dummy4'),
('K005', 'U003', 'Rancangan Aplikasi Mobile', 'Design', 'https://drive.google.com/dummy5');

-- Insert PEER_REVIEW (ID 4 Digit, Tanpa ID_User)
INSERT INTO PEER_REVIEW (ID_Review, ID_Karya, ID_Layanan, ID_Mentor, Status_Review, Hasil_Review, Tanggal_Review) VALUES
('R001', 'K001', 'L002', 'M001', 'Selesai', 'Revisi minor pada bab 2, selebihnya bagus.', '2025-10-15'),
('R002', 'K002', 'L003', 'M003', 'Menunggu', NULL, NULL),
('R003', 'K003', 'L001', 'M001', 'Selesai', 'Sangat inspiratif, siap lomba.', '2025-11-01'),
('R004', 'K004', 'L004', 'M002', 'Selesai', 'Perbaiki kontras warna.', '2025-11-10'),
('R005', 'K005', 'L004', 'M005', 'Menunggu', NULL, NULL);

-- Insert LAPORAN_LAYANAN (ID 4 Digit)
INSERT INTO LAPORAN_LAYANAN (ID_Laporan, ID_Admin, ID_Layanan, Hasil_Laporan, Tanggal_Laporan) VALUES
('L001', 'A001', 'L001', 'Total 10 Esai telah selesai direview bulan ini.', '2025-11-15'),
('L002', 'A001', 'L002', 'Ada 5 permintaan KTI baru.', '2025-11-15'),
('L003', 'A002', 'L003', 'Perlu tambahan mentor di bidang bisnis.', '2025-11-10');
