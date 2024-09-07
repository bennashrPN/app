CREATE TABLE tbl_jadwalpelajaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hari VARCHAR(10) DEFAULT NULL,
  jam VARCHAR(50) DEFAULT NULL,
  waktu_mulai time DEFAULT NULL,
  waktu_selesai time DEFAULT NULL,
  waktu VARCHAR(100) DEFAULT NULL,
  kelas VARCHAR(100) DEFAULT NULL,
  pelajaran VARCHAR(100) DEFAULT NULL,
  guru VARCHAR(100) DEFAULT NULL,
  jenjang VARCHAR(50) DEFAULT NULL
);

CREATE TABLE tbl_absensi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  kelas VARCHAR(100) NOT NULL,
  guru VARCHAR(100) NOT NULL,
  pelajaran VARCHAR(100) NOT NULL,
  kehadiran ENUM('Hadir','Izin','Sakit','Alpha') NOT NULL,
  jenjang VARCHAR(50) DEFAULT NULL,
  hari VARCHAR(50) NOT NULL,
  waktu VARCHAR(100) NOT NULL,
  tanggal DATE,
  keterangan VARCHAR(200) NOT NULL
);


CREATE TABLE tbl_guru (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nip VARCHAR(50) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jabatan VARCHAR(50) NOT NULL,
  status_kerja VARCHAR(50) DEFAULT NULL,
  alamat VARCHAR(150) DEFAULT NULL,
  foto VARCHAR(150) DEFAULT NULL
);

CREATE TABLE tbl_markaz (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) DEFAULT NULL,
  alamat VARCHAR(150) DEFAULT NULL,
  status_akreditasi VARCHAR(50) DEFAULT NULL,
  akreditasi char(1) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  visimisi VARCHAR(200) DEFAULT NULL,
  gambar VARCHAR(150) DEFAULT NULL
);

INSERT INTO tbl_markaz (id, nama, alamat, status, akreditasi, email, visimisi, gambar) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, 'bglogin.png');

CREATE TABLE tbl_pegawai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nip VARCHAR(50) DEFAULT NULL,
  nama VARCHAR(100) DEFAULT NULL,
  jabatan VARCHAR(50) DEFAULT NULL,
  status_kerja VARCHAR(50) DEFAULT NULL,
  alamat VARCHAR(150) DEFAULT NULL,
  foto VARCHAR(150) DEFAULT NULL
);

INSERT INTO tbl_pegawai (id, nip, nama, jabatan, status, alamat, foto) VALUES
(1, '1234567894', 'iwan', 'programing', 'ST', 'cisaat', 'default.png');

CREATE TABLE tbl_pelajaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pelajaran VARCHAR(50) DEFAULT NULL,
  jenjang VARCHAR(50) DEFAULT NULL,
  kelas VARCHAR(100) DEFAULT NULL,
  guru VARCHAR(100) DEFAULT NULL
);

CREATE TABLE tbl_perizinan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) DEFAULT NULL,
  kelas VARCHAR(100) DEFAULT NULL,
  kehadiran enum('Hadir','Izin','Sakit','Alpha') DEFAULT NULL,
  tanggal date DEFAULT NULL,
  keterangan text DEFAULT NULL
);

INSERT INTO tbl_perizinan (id, nama, kelas, kehadiran, tanggal, keterangan) VALUES
(1, 'Finley Desfafian', '7B', 'Izin', '2024-03-23', 'sedang dirawat'),
(2, 'Ananda Kusuma Wardhana', '7B', 'Izin', '2024-03-26', 'Acara keluagra'),
(3, 'Abdul Jabar Sulisyana', '7B', 'Izin', '2024-03-25', 'libur');

CREATE TABLE tbl_siswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nis char(10) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  kelas VARCHAR(100) NOT NULL,
  jenjang VARCHAR(50) NOT NULL,
  alamat VARCHAR(150) NOT NULL,
  foto VARCHAR(150) NOT NULL
);

CREATE TABLE tbl_user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) DEFAULT NULL,
  password VARCHAR(100) DEFAULT NULL,
  nama VARCHAR(100) DEFAULT NULL,
  jabatan VARCHAR(50) DEFAULT NULL,
  role VARCHAR(50) DEFAULT NULL,
  foto VARCHAR(250) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  no_hp VARCHAR(20) DEFAULT NULL,
  alamat VARCHAR(200) DEFAULT NULL
);

CREATE TABLE tbl_waktupelajaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jam VARCHAR(50) DEFAULT NULL,
  waktu_mulai time DEFAULT NULL,
  waktu_selesai time DEFAULT NULL,
  hari VARCHAR(10) DEFAULT NULL
);

CREATE TABLE `tbl_absensi` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama varchar(100) NOT NULL,
  kelas varchar(100) NOT NULL,
  guru varchar(100) NOT NULL,
  pelajaran varchar(50) NOT NULL,
  kehadiran enum('Hadir','Izin','Sakit','Alpha') NOT NULL,
  jenjang varchar(50) DEFAULT NULL,
  hari varchar(10) NOT NULL,
  waktu varchar(100) NOT NULL,
  tanggal date DEFAULT current_timestamp(),
  keterangan text DEFAULT NULL
);

INSERT INTO `tbl_waktupelajaran` (`id`, `jam`, `waktu_mulai`, `waktu_selesai`, `hari`) VALUES
(1, 1, '07:00:00', '07:35:00', 'Senin'),
(2, 2, '07:35:00', '08:10:00', 'Senin'),
(3, 3, '08:10:00', '08:45:00', 'Senin'),
(4, 4, '08:45:00', '09:20:00', 'Senin'),
(5, 'istirahat', '09:20:00', '10:00:00', 'Senin'),
(6, 5, '10:00:00', '10:35:00', 'Senin'),
(7, 6, '10:35:00', '11:10:00', 'Senin'),
(8, 7, '11:00:00', '11:45:00', 'Senin'),
(9, 8, '11:45:00', '12:20:00', 'Senin'),
(10, 1, '07:00:00', '07:35:00', 'Selasa'),
(11, 2, '07:35:00', '08:10:00', 'Selasa'),
(12, 3, '08:10:00', '08:45:00', 'Selasa'),
(13, 4, '08:45:00', '09:20:00', 'Selasa'),
(14, 'istirahat', '09:20:00', '10:00:00', 'Selasa'),
(15, 5, '10:00:00', '10:35:00', 'Selasa'),
(16, 6, '10:35:00', '11:10:00', 'Selasa'),
(17, 7, '11:00:00', '11:45:00', 'Selasa'),
(18, 8, '11:45:00', '12:20:00', 'Selasa'),
(19, 1, '07:00:00', '07:35:00', 'Rabu'),
(20, 2, '07:35:00', '08:10:00', 'Rabu'),
(21, 3, '08:10:00', '08:45:00', 'Rabu'),
(22, 4, '08:45:00', '09:20:00', 'Rabu'),
(23, 'istirahat', '09:20:00', '10:00:00', 'Rabu'),
(24, 5, '10:00:00', '10:35:00', 'Rabu'),
(25, 6, '10:35:00', '11:10:00', 'Rabu'),
(26, 7, '11:00:00', '11:45:00', 'Rabu'),
(27, 8, '11:45:00', '12:20:00', 'Rabu'),
(28, 1, '07:00:00', '07:35:00', 'Kamis'),
(29, 2, '07:35:00', '08:10:00', 'Kamis'),
(30, 3, '08:10:00', '08:45:00', 'Kamis'),
(31, 4, '08:45:00', '09:20:00', 'Kamis'),
(32, 'istirahat', '09:20:00', '10:00:00', 'Kamis'),
(33, 5, '10:00:00', '10:35:00', 'Kamis'),
(34, 6, '10:35:00', '11:10:00', 'Kamis'),
(35, 7, '11:00:00', '11:45:00', 'Kamis'),
(36, 8, '11:45:00', '12:20:00', 'Kamis'),
(37, 1, '07:00:00', '07:35:00', 'Jumat'),
(38, 2, '07:35:00', '08:10:00', 'Jumat'),
(39, 3, '08:10:00', '08:45:00', 'Jumat'),
(40, 4, '08:45:00', '09:20:00', 'Jumat'),
(41, 5, '09:20:00', '09:55:00', 'Jumat'),
(42, 6, '09:55:00', '10:30:00', 'Jumat'),
(43, 7, '13:30:00', '14:50:00', 'Jumat'),
(44, 1, '07:00:00', '07:35:00', 'Sabtu'),
(45, 2, '07:35:00', '08:10:00', 'Sabtu'),
(46, 3, '08:10:00', '08:45:00', 'Sabtu'),
(47, 4, '08:45:00', '09:20:00', 'Sabtu'),
(48, 'istirahat', '09:20:00', '10:00:00', 'Sabtu'),
(49, 5, '10:00:00', '10:35:00', 'Sabtu'),
(50, 6, '10:35:00', '11:10:00', 'Sabtu'),
(51, 7, '11:00:00', '11:45:00', 'Sabtu'),
(52, 8, '11:45:00', '12:20:00', 'Sabtu'),
(53, 'istirahat', '12:40:00', '14:00:00', 'Sabtu'),
(54, 9, '14:00:00', '15:20:00', 'Sabtu');

INSERT INTO `tbl_guru` (`id`, `nip`, `nama`, `jabatan`, `status`, `alamat`, `foto`) VALUES
(1, '1234567891', 'Anfalullah, BA., M.Pd', '', 'GT', '', 'default.png'),
(2, '1234567892', 'Buldan Taufik, BA., M.Pd', '', 'GT', '', 'default.png'),
(3, '1234567893', 'U. Ridwan, S.Pd.I', '', 'GT', '', 'default.png'),
(4, '1234567894', 'Denie Fauzie Ridwan, S.Pi', '', 'GT', '', 'default.png'),
(5, '1234567895', 'Irwansyah Ramdani, S.S., M.Pd', '', 'GT', '', 'default.png'),
(6, '1234567896', 'Yunan Al Manaf, S.Sos.I., M.Pd', '', 'GT', '', 'default.png'),
(7, '1234567897', 'Herwan Hermawandi, SE, M.Pd.', '', 'GT', '', 'default.png'),
(8, '1234567898', 'Yahya Muhammad, S.Pd., M.Pd', '', 'GT', '', 'default.png'),
(9, '1234567899', 'Ryan Andrian Firdausa, S.Si', '', 'GTT', '', 'default.png'),
(10, '1234567900', 'Uus Hanan Alusman, S.Si', '', 'GT', '', 'default.png'),
(11, '1234567901', 'Mochammad Syaepul Bahtiar, M.Pd', '', 'GT', '', 'default.png'),
(12, '1234567902', 'Muslim Kuswardi, S.Pd', '', 'GT', '', 'default.png'),
(13, '1234567903', 'Syam Alfiansyah, S.Pd', '', 'GT', '', 'default.png'),
(14, '1234567904', 'Cecen, S.Ag', '', 'GTT', '', 'default.png'),
(15, '1234567905', 'Asep Fakhruddin, M.Pd', '', 'GT', '', 'default.png'),
(16, '1234567906', 'Harpin Firmansyah, A.md.Kom', '', 'GT', '', 'default.png'),
(17, '1234567907', 'Lutfi Junaedi Abdillah', '', 'GT', '', 'default.png'),
(18, '1234567908', 'Daud Firdaus, S.Pd', '', 'GT', '', 'default.png'),
(19, '1234567909', 'Ali Mukhtar, S.Sos., M.Pd', '', 'GT', '', 'default.png'),
(20, '1234567910', 'Lukmanul Hakim, S.Kom', '', 'GT', '', 'default.png'),
(21, '1234567911', 'Muhammad Ali, Lc., M.Pd', '', 'GT', '', 'default.png'),
(22, '1234567912', 'Drs. Deden Trenggana', '', 'GTT', '', 'default.png'),
(23, '1234567913', 'Tono Martono, S.Pd', '', 'GTT', '', 'default.png'),
(24, '1234567914', 'Muhammad Ichsan, Lc., M.Pd', '', 'GT', '', 'default.png'),
(25, '1234567915', 'Hayatul Makki, S.Pd', '', 'GT', '', 'default.png'),
(26, '1234567916', 'Sumpena, S.Pd', '', 'GT', '', 'default.png'),
(27, '1234567917', 'Agung Irfan, S.Pd', '', 'GT', '', 'default.png'),
(28, '1234567918', 'Saepulloh, S.Sos', '', 'GT', '', 'default.png'),
(29, '1234567919', 'Alen Aliandi, B.Sh', '', 'GT', '', 'default.png'),
(30, '1234567920', 'Fauzan Jaelani, BA., M.Pd.', '', 'GTT', '', 'default.png'),
(31, '1234567921', 'Abdullah Aleng, B.Sh', '', 'GT', '', 'default.png'),
(32, '1234567922', 'Muhammad Rusli Agustian, S.IP', '', 'GT', '', 'default.png'),
(33, '1234567923', 'Nurhuda, B.Sh', '', 'GT', '', 'default.png'),
(34, '1234567924', 'Muhammad Khobir, B.Sh', '', 'GT', '', 'default.png'),
(35, '1234567925', 'Munaji, B.Sh', '', 'GT', '', 'default.png'),
(36, '1234567926', 'Muhammad Haerurohman, S.Pd', '', 'GTT', '', 'default.png'),
(37, '1234567927', 'Mumuh Mukhtarudin, BA', '', 'GT', '', 'default.png'),
(38, '1234567928', 'Aris Setiawan, S.Pd', '', 'GT', '', 'default.png'),
(39, '1234567929', 'Jenal Haris, S.IP', '', 'GTT', '', 'default.png'),
(40, '1234567930', 'Bayu Aprianto, S.Pd', '', 'GTT', '', 'default.png'),
(41, '1234567931', 'Dimas Denisa, S. Pd.', '', 'GT', '', 'default.png'),
(42, '1234567932', 'Ari Ramdani, BA', '', 'GT', '', 'default.png'),
(43, '1234567933', 'Zamzam Jamhur Munawar, BA', '', 'GT', '', 'default.png'),
(44, '1234567934', 'Rusdi Rusdiansyah, S.Pd.', '', 'GT', '', 'default.png'),
(45, '1234567935', 'Lugina Hendarsah, S.Pd', '', 'GT', '', 'default.png'),
(46, '1234567936', 'Beni Safitra, S.Pd', '', 'GTT', '', 'default.png'),
(47, '1234567937', 'Muhammad Zaky Zein', '', 'GTT', '', 'default.png'),
(48, '1234567938', 'Mahfudz, S.Pd.I', '', 'GT', '', 'default.png'),
(49, '1234567939', 'Muhammad Firdaus, B.Sh', '', 'GT', '', 'default.png'),
(50, '1234567940', 'Ujang Mulya, BA', '', 'GT', '', 'default.png'),
(51, '1234567941', 'Dimasyah Abdul Fatah', '', 'GT', '', 'default.png'),
(52, '1234567942', 'Yusuf Zaenudin, B.Sh', '', 'GT', '', 'default.png'),
(53, '1234567943', 'Miftah Farid', '', 'GT', '', 'default.png'),
(54, '1234567944', 'Teguh Saputra', '', 'GT', '', 'default.png'),
(55, '1234567945', 'Muhammad Faishal Halim, SE', '', 'GT', '', 'default.png'),
(56, '1234567946', 'Khalid Abdul Malik, B.Sh', '', 'GT', '', 'default.png'),
(57, '1234567947', 'Septi Nur Gumelar, B. Sh', '', 'GT', '', 'default.png'),
(58, '1234567948', 'Dede Rahman, S.S', '', 'GTT', '', 'default.png'),
(59, '1234567949', 'Prama Nurgama, S.P', '', 'GT', '', 'default.png'),
(60, '1234567950', 'Mochamad Samsul Arif, BA', '', 'GT', '', 'default.png'),
(61, '1234567951', 'Rijal, B.BA', '', 'GT', '', 'default.png'),
(62, '1234567952', 'Ahmad Mukhsin, BA', '', 'GT', '', 'default.png'),
(63, '1234567953', 'Didik Gelar Pemana, BA., ME', '', 'GT', '', 'default.png'),
(64, '1234567954', 'Budi Imam Turmudi ', '', '', '', 'default.png');