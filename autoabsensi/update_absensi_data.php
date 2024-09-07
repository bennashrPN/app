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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kehadiran = $_POST['kehadiran'];
    $kelas = $_POST['kelas'];
    $jenjang = $_POST['jenjang'];

    $stmt = $conn->prepare("UPDATE tbl_absensi SET nama = ?, kehadiran = ?, kelas = ?, jenjang = ? WHERE id = ?");
    $stmt->bind_param("sssii", $nama, $kehadiran, $kelas, $jenjang, $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo 'Data updated successfully';
    } else {
        echo 'Failed to update data';
    }
}
?>
