<?php
require_once "../config.php";
session_start();
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Sanitize input to prevent SQL Injection
$id = mysqli_real_escape_string($koneksi, $id);

$query = "SELECT kegiatan_pembelajaran FROM tbl_dataakp WHERE id='$id'";
$result = mysqli_query($koneksi, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    echo htmlspecialchars($row['kegiatan_pembelajaran']);
} else {
    echo 'Data tidak ditemukan.';
}
?>
