<?php
session_start();

// Periksa apakah sesi login valid
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

// Sertakan file konfigurasi untuk koneksi ke basis data
require_once "../config.php";

// Atur header konten sebagai JSON
header('Content-Type: application/json');

// Dapatkan istilah pencarian dari permintaan AJAX
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Siapkan kueri untuk mendapatkan nama guru berdasarkan istilah pencarian
$query = "SELECT DISTINCT nama FROM tbl_guru WHERE nama LIKE ? LIMIT 10";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    error_log("Error preparing statement: " . mysqli_error($koneksi));
    http_response_code(500); // Kirim kode status 500 jika terjadi kesalahan
    echo json_encode(["error" => "Error preparing statement."]);
    exit;
}

// Bind parameter pencarian
$searchTerm = '%' . $searchTerm . '%';
mysqli_stmt_bind_param($stmt, 's', $searchTerm);

// Eksekusi kueri
if (!mysqli_stmt_execute($stmt)) {
    error_log("Error executing statement: " . mysqli_stmt_error($stmt));
    http_response_code(500); // Kirim kode status 500 jika terjadi kesalahan
    echo json_encode(["error" => "Error executing statement."]);
    exit;
}

// Ambil hasil kueri
mysqli_stmt_bind_result($stmt, $guruNames);
$results = [];

while (mysqli_stmt_fetch($stmt)) {
    $results[] = $guruNames;
}

// Tutup pernyataan dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

// Kembalikan hasil sebagai JSON
echo json_encode($results);

// Catat istilah pencarian yang diterima untuk debugging
error_log("Received search term: " . $searchTerm);
?>
