<?php
require_once "../config.php";
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}
$title = "Dashboard - Absensi";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['nama']) && isset($_GET['tanggal'])) {
    $nama = $_GET['nama'];
    $tanggal = $_GET['tanggal'];

    $query = "SELECT * FROM tbl_absensi WHERE nama='$nama' AND tanggal='$tanggal'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'Parameter tidak lengkap']);
}
?>