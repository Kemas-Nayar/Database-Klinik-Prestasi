-- ==========================================
-- FILE DATABASE: klinikprestasi.sql
-- Database: PostgreSQL
-- ==========================================

-- 1. BERSIHKAN TABEL LAMA
DROP TABLE IF EXISTS LAPORAN_LAYANAN CASCADE;
DROP TABLE IF EXISTS LAPORAN_REVIEW CASCADE;
DROP TABLE IF EXISTS PEER_REVIEW CASCADE;
DROP TABLE IF EXISTS KARYA CASCADE;
DROP TABLE IF EXISTS LAYANAN CASCADE;
DROP TABLE IF EXISTS ADMIN CASCADE;
DROP TABLE IF EXISTS MENTOR CASCADE;
DROP TABLE IF EXISTS USER_MEMBER CASCADE;
DROP TABLE IF EXISTS MAHASISWA CASCADE;

-- 2. MEMBUAT TABEL (DDL)

-- Tabel MAHASISWA (NIM 11 Digit)
CREATE TABLE MAHASISWA (
    NIM CHAR(11) NOT NULL PRIMARY KEY,
    Nama VARCHAR(50) NOT NULL,
    Angkatan CHAR(3) NOT NULL,
    Kontak VARCHAR(30) NOT NULL,
    CONSTRAINT chk_angkatan CHECK (Angkatan IN ('F19', 'F20', 'F21', 'F22'))
);

-- Tabel USER_MEMBER (ID 4 Digit: U001)
-- Kolom password DIHAPUS sesuai permintaan
CREATE TABLE USER_MEMBER (
    ID_User CHAR(4) NOT NULL PRIMARY KEY,
    NIM CHAR(11) NOT NULL UNIQUE,
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE
);

-- Tabel MENTOR (ID 4 Digit: M001)
CREATE TABLE MENTOR (
    ID_Mentor CHAR(4) NOT NULL PRIMARY KEY,
    ID_User CHAR(4) NOT NULL UNIQUE,
    NIM CHAR(11) NOT NULL UNIQUE,
    Bidang_Keahlian VARCHAR(50),
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE
);

-- Tabel ADMIN (ID 4 Digit: A001)
CREATE TABLE ADMIN (
    ID_Admin CHAR(4) NOT NULL PRIMARY KEY,
    ID_User CHAR(4) NOT NULL UNIQUE,
    NIM CHAR(11) NOT NULL UNIQUE,
    Jabatan VARCHAR(30) NOT NULL,
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    FOREIGN KEY (NIM) REFERENCES MAHASISWA(NIM) ON DELETE CASCADE,
    CONSTRAINT chk_jabatan CHECK (Jabatan IN ('Ketua Divisi', 'Sekbend', 'Anggota'))
);

-- Tabel LAYANAN (ID 4 Digit: L001)
CREATE TABLE LAYANAN (
    ID_Layanan CHAR(4) NOT NULL PRIMARY KEY,
    Nama_Layanan VARCHAR(50) NOT NULL
);

-- Tabel KARYA (ID 4 Digit: K001)
CREATE TABLE KARYA (
    ID_Karya CHAR(4) NOT NULL PRIMARY KEY,
    ID_User CHAR(4) NOT NULL,
    Judul_Karya VARCHAR(100) NOT NULL,
    Bidang_Karya VARCHAR(10) NOT NULL,
    File_Karya VARCHAR(255), -- Tetap dipertahankan untuk menyimpan Link Drive
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    CONSTRAINT chk_bidang_karya CHECK (Bidang_Karya IN ('Esai', 'KTI', 'Bisnis', 'Design'))
);

-- Tabel PEER_REVIEW (ID 4 Digit: R001)
CREATE TABLE PEER_REVIEW (
    ID_Review CHAR(4) NOT NULL PRIMARY KEY,
    ID_Karya CHAR(4) NOT NULL,
    ID_Layanan CHAR(4) NOT NULL,
    ID_User CHAR(4) NOT NULL,
    ID_Mentor CHAR(4) NOT NULL,
    Status_Review VARCHAR(30) NOT NULL,
    FOREIGN KEY (ID_Karya) REFERENCES KARYA(ID_Karya) ON DELETE CASCADE,
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User),
    FOREIGN KEY (ID_Mentor) REFERENCES MENTOR(ID_Mentor),
    CONSTRAINT chk_status_review CHECK (Status_Review IN ('Menunggu', 'Selesai'))
);

-- Tabel LAPORAN_REVIEW (ID 5 Digit: LR001)
CREATE TABLE LAPORAN_REVIEW (
    ID_Laporan_Review CHAR(5) NOT NULL PRIMARY KEY,
    ID_Karya CHAR(4) NOT NULL,
    ID_User CHAR(4) NOT NULL,
    ID_Mentor CHAR(4) NOT NULL,
    Hasil_Review TEXT,
    Tanggal_Laporan DATE NOT NULL,
    FOREIGN KEY (ID_Karya) REFERENCES KARYA(ID_Karya) ON DELETE CASCADE,
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User),
    FOREIGN KEY (ID_Mentor) REFERENCES MENTOR(ID_Mentor),
    CONSTRAINT chk_tgl_laporan_review CHECK (Tanggal_Laporan <= CURRENT_DATE)
);

-- Tabel LAPORAN_LAYANAN (ID 5 Digit: LL001)
CREATE TABLE LAPORAN_LAYANAN (
    ID_Laporan CHAR(5) NOT NULL PRIMARY KEY,
    ID_Admin CHAR(4) NOT NULL,
    ID_Layanan CHAR(4) NOT NULL,
    Hasil_Laporan TEXT,
    Tanggal_Laporan DATE NOT NULL,
    FOREIGN KEY (ID_Admin) REFERENCES ADMIN(ID_Admin),
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    CONSTRAINT chk_tgl_laporan_layanan CHECK (Tanggal_Laporan <= CURRENT_DATE)
);

-- 3. INSERT DATA DUMMY (Tanpa Password)

-- Data MAHASISWA (NIM 11 Digit)
INSERT INTO MAHASISWA (NIM, Nama, Angkatan, Kontak) VALUES
('F2000000001', 'Ani Susanti', 'F20', '081211110001'),
('F2100000002', 'Budi Cahyo', 'F21', '081211110002'),
('F2200000003', 'Citra Dewi', 'F22', '081211110003'),
('F1900000004', 'Dika Pratama', 'F19', '081211110004'),
('F2000000005', 'Eka Fitri', 'F20', '081211110005'),
('F2100000006', 'Ferry Sanjaya', 'F21', '081211110006'),
('F2200000007', 'Gita Mentor', 'F22', '081211110007'),
('F2200000008', 'Hadi Mentor', 'F22', '081211110008'),
('F2200000009', 'Indah Mentor', 'F22', '081211110009'),
('F2200000010', 'Joko Admin', 'F22', '081211110010'),
('F2200000011', 'Kiki Admin', 'F22', '081211110011');

-- Data USER_MEMBER (U001) - Password dihapus dari Insert
INSERT INTO USER_MEMBER (ID_User, NIM) VALUES
('U001', 'F2000000001'),
('U002', 'F2100000002'),
('U003', 'F2200000003'),
('U004', 'F1900000004'),
('U005', 'F2000000005'),
('U006', 'F2100000006'),
('U007', 'F2200000007'),
('U008', 'F2200000008'),
('U009', 'F2200000009'),
('U010', 'F2200000010'),
('U011', 'F2200000011');

-- Data MENTOR (M001)
INSERT INTO MENTOR (ID_Mentor, ID_User, NIM, Bidang_Keahlian) VALUES
('M001', 'U001', 'F2000000001', 'Esai & KTI'),
('M002', 'U004', 'F1900000004', 'Design'),
('M003', 'U007', 'F2200000007', 'Bisnis'),
('M004', 'U008', 'F2200000008', 'KTI'),
('M005', 'U009', 'F2200000009', 'Design');

-- Data ADMIN (A001)
INSERT INTO ADMIN (ID_Admin, ID_User, NIM, Jabatan) VALUES
('A001', 'U006', 'F2100000006', 'Ketua Divisi'),
('A002', 'U010', 'F2200000010', 'Sekbend'),
('A003', 'U011', 'F2200000011', 'Anggota');

-- Data LAYANAN (L001)
INSERT INTO LAYANAN (ID_Layanan, Nama_Layanan) VALUES
('L001', 'Review Esai'),
('L002', 'Review KTI'),
('L003', 'Review Bisnis Plan'),
('L004', 'Review Desain'),
('L005', 'Bimbingan Karya');

-- Data KARYA (K001)
INSERT INTO KARYA (ID_Karya, ID_User, Judul_Karya, Bidang_Karya, File_Karya) VALUES
('K001', 'U002', 'Inovasi Sampah Plastik', 'KTI', 'https://drive.google.com/open?id=123'),
('K002', 'U003', 'Strategi Pemasaran Digital', 'Bisnis', 'https://drive.google.com/open?id=456'),
('K003', 'U002', 'Meningkatkan Kualitas Udara', 'Esai', 'https://drive.google.com/open?id=789'),
('K004', 'U005', 'Poster Lomba Desain Grafis', 'Design', 'https://drive.google.com/open?id=abc'),
('K005', 'U003', 'Rancangan Aplikasi Mobile', 'Design', 'https://drive.google.com/open?id=def');

-- Data PEER_REVIEW (R001)
INSERT INTO PEER_REVIEW (ID_Review, ID_Karya, ID_Layanan, ID_User, ID_Mentor, Status_Review) VALUES
('R001', 'K001', 'L002', 'U002', 'M001', 'Selesai'),
('R002', 'K002', 'L003', 'U003', 'M003', 'Menunggu'),
('R003', 'K003', 'L001', 'U002', 'M001', 'Selesai'),
('R004', 'K004', 'L004', 'U005', 'M002', 'Selesai'),
('R005', 'K005', 'L004', 'U003', 'M005', 'Menunggu');
