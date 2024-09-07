CREATE TABLE tbl_mediapembelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guru VARCHAR(100) NULL,
    materiPembelajaran TEXT, NULL,
    keteranganPembelajaran TEXT, NULL, 
    kelas VARCHAR(50) NOT NULL,
    hari VARCHAR(20) NOT NULL,
    tanggal DATE NOT NULL,
    updateData TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
