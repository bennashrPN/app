<?php
require_once "../config.php"; // Pastikan file konfigurasi koneksi database Anda sudah termasuk

// Ambil nilai jenjang dari POST
$jenjang = $_POST['jenjang'];

// Query untuk mengambil kelas berdasarkan jenjang
$queryKelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa WHERE jenjang = '$jenjang'");
if (!$queryKelas) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Array untuk menyimpan data kelas
$dataKelas = array();

// Ambil hasil query dan masukkan ke dalam array
while ($data = mysqli_fetch_array($queryKelas)) {
    $dataKelas[] = $data;
}

// Mengirim data ke client dalam format JSON
echo json_encode($dataKelas);
?>
