<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Konfigurasi database
require_once "../config.php";

// Ambil data dari form
$jenjang = mysqli_real_escape_string($koneksi, $_POST['autoSizingJenjang']);
$kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
$guru = mysqli_real_escape_string($koneksi, $_POST['ustadz']);
$pelajaran = mysqli_real_escape_string($koneksi, $_POST['pelajaran']);
$hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
$jam = mysqli_real_escape_string($koneksi, $_POST['jam']);
$waktu_mulai = mysqli_real_escape_string($koneksi, $_POST['autoSizingWaktuMulai']);
$waktu_selesai = mysqli_real_escape_string($koneksi, $_POST['autoSizingWaktuSelesai']);
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

// Periksa keberadaan data di tbl_toast
$queryCheck = "SELECT * FROM tbl_toast WHERE 
    jenjang = '$jenjang' AND 
    kelas = '$kelas' AND 
    guru = '$guru' AND 
    pelajaran = '$pelajaran' AND 
    hari = '$hari' AND 
    jam = '$jam' AND 
    waktu_mulai = '$waktu_mulai' AND 
    waktu_selesai = '$waktu_selesai' AND 
    tanggal = '$tanggal'";

$resultCheck = mysqli_query($koneksi, $queryCheck);

// Jika data sudah ada, setel notifikasi data sudah ada di sesi dan arahkan ke testtoast.php
if (mysqli_num_rows($resultCheck) > 0) {
    $_SESSION['error'] = "Data sudah ada!"; // Gunakan error untuk menandai data sudah ada
    header('Location: testtoast.php');
    exit();
}

// Simpan data baru ke tbl_toast
$queryInsert = "INSERT INTO tbl_toast (jenjang, kelas, guru, pelajaran, hari, jam, waktu_mulai, waktu_selesai, tanggal) VALUES ('$jenjang', '$kelas', '$guru', '$pelajaran', '$hari', '$jam', '$waktu_mulai', '$waktu_selesai', '$tanggal')";
$resultInsert = mysqli_query($koneksi, $queryInsert);

if (!$resultInsert) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Setel pesan sukses di sesi
$_SESSION['success'] = "Data berhasil disimpan!";

// Arahkan ke testtoast.php untuk menampilkan toast
header('Location: testtoast.php');
mysqli_close($koneksi);
?>
