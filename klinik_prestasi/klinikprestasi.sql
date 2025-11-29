-- ==========================================
-- FILE DATABASE: klinikprestasi.sql
-- Database: PostgreSQL
-- ==========================================

-- 1. HAPUS TABEL LAMA (Urutan penting karena Foreign Key)
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

-- Tabel MAHASISWA
CREATE TABLE MAHASISWA (
    NIM CHAR(10) NOT NULL PRIMARY KEY,
    Nama VARCHAR(50) NOT NULL,
    Angkatan CHAR(3) NOT NULL,
    Kontak VARCHAR(30) NOT NULL,
    CONSTRAINT chk_angkatan CHECK (Angkatan IN ('F19', 'F20', 'F21', 'F22'))
);

-- Tabel USER_MEMBER
CREATE TABLE USER_MEMBER (
    ID_User CHAR(10) NOT NULL PRIMARY KEY,
    NIM CHAR(10) NOT NULL UNIQUE,
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

-- Tabel KARYA
CREATE TABLE KARYA (
    ID_Karya CHAR(10) NOT NULL PRIMARY KEY,
    ID_User CHAR(10) NOT NULL,
    Judul_Karya VARCHAR(100) NOT NULL,
    Bidang_Karya VARCHAR(10) NOT NULL,
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User) ON DELETE CASCADE,
    CONSTRAINT chk_bidang_karya CHECK (Bidang_Karya IN ('Esai', 'KTI', 'Bisnis', 'Design'))
);

-- Tabel PEER_REVIEW
CREATE TABLE PEER_REVIEW (
    ID_Review CHAR(10) NOT NULL PRIMARY KEY,
    ID_Karya CHAR(10) NOT NULL,
    ID_Layanan CHAR(10) NOT NULL,
    ID_User CHAR(10) NOT NULL,
    ID_Mentor CHAR(10) NOT NULL,
    Status_Review VARCHAR(30) NOT NULL,
    FOREIGN KEY (ID_Karya) REFERENCES KARYA(ID_Karya) ON DELETE CASCADE,
    FOREIGN KEY (ID_Layanan) REFERENCES LAYANAN(ID_Layanan),
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User),
    FOREIGN KEY (ID_Mentor) REFERENCES MENTOR(ID_Mentor),
    CONSTRAINT chk_status_review CHECK (Status_Review IN ('Menunggu', 'Selesai'))
);

-- Tabel LAPORAN_REVIEW
CREATE TABLE LAPORAN_REVIEW (
    ID_Laporan_Review CHAR(10) NOT NULL PRIMARY KEY,
    ID_Karya CHAR(10) NOT NULL,
    ID_User CHAR(10) NOT NULL,
    ID_Mentor CHAR(10) NOT NULL,
    Hasil_Review TEXT,
    Tanggal_Laporan DATE NOT NULL,
    FOREIGN KEY (ID_Karya) REFERENCES KARYA(ID_Karya) ON DELETE CASCADE,
    FOREIGN KEY (ID_User) REFERENCES USER_MEMBER(ID_User),
    FOREIGN KEY (ID_Mentor) REFERENCES MENTOR(ID_Mentor),
    CONSTRAINT chk_tgl_laporan_review CHECK (Tanggal_Laporan <= CURRENT_DATE)
);

-- Tabel LAPORAN_LAYANAN
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

-- Data USER_MEMBER
INSERT INTO USER_MEMBER (ID_User, NIM) VALUES
('U001', 'F20000001'),
('U002', 'F21000002'),
('U003', 'F22000003'),
('U004', 'F19000004'),
('U005', 'F20000005'),
('U006', 'F21000006'),
('U007', 'F22000007'),
('U008', 'F22000008'),
('U009', 'F22000009'),
('U010', 'F22000010'),
('U011', 'F22000011');

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

-- Data KARYA
INSERT INTO KARYA (ID_Karya, ID_User, Judul_Karya, Bidang_Karya) VALUES
('K001', 'U002', 'Inovasi Sampah Plastik', 'KTI'),
('K002', 'U003', 'Strategi Pemasaran Digital', 'Bisnis'),
('K003', 'U002', 'Meningkatkan Kualitas Udara', 'Esai'),
('K004', 'U005', 'Poster Lomba Desain Grafis', 'Design'),
('K005', 'U003', 'Rancangan Aplikasi Mobile', 'Design');

-- Data PEER_REVIEW
INSERT INTO PEER_REVIEW (ID_Review, ID_Karya, ID_Layanan, ID_User, ID_Mentor, Status_Review) VALUES
('R001', 'K001', 'L002', 'U002', 'M001', 'Selesai'),
('R002', 'K002', 'L003', 'U003', 'M003', 'Menunggu'),
('R003', 'K003', 'L001', 'U002', 'M001', 'Selesai'),
('R004', 'K004', 'L004', 'U005', 'M002', 'Selesai'),
('R005', 'K005', 'L004', 'U003', 'M005', 'Menunggu');

-- (Tambahan) PENGUMPULAN KARYA
ALTER TABLE KARYA ADD COLUMN File_Karya VARCHAR(255);
