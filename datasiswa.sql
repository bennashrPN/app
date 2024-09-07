CREATE TABLE tbl_siswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    statusanak VARCHAR(50),
    nama VARCHAR(100),
    kelas VARCHAR(50),
    kamar VARCHAR(50),
    namaArab VARCHAR(100),
    NIL VARCHAR(50),
    NISN VARCHAR(20),
    tempatLahir VARCHAR(50),
    tanggalLahir DATE,
    alamat VARCHAR(255),
    namaIbu VARCHAR(100),
    emailIbu VARCHAR(100),
    noHpIbu VARCHAR(20),
    markaz VARCHAR(50),
    jenjang VARCHAR(50),
    angkatan VARCHAR(20),
    ketNISN TEXT,
    updateData TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);



CREATE TABLE tbl_siswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nis VARCHAR(100),
    nama VARCHAR(100),
    kelas VARCHAR(50),
    kamar VARCHAR(50),
    tempatLahir VARCHAR(50),
    tanggalLahir DATE,
    alamat VARCHAR(255),
    jenjang VARCHAR(50),
    foto VARCHAR(255),
    updateData TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `tbl_siswa` (
  `id` int NOT NULL,
  `nis` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenjang` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(150) COLLATE utf8mb4_general_ci NOT NULL
);
