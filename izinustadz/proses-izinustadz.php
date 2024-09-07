<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";

$guruIzin = $_POST['guruIzin'];
$alasanIzin = $_POST['alasanIzin'];
$waktuMulaiIzin = date('H:i', strtotime($_POST['waktuMulai']));
$waktuSelesaiIzin = date('H:i', strtotime($_POST['waktuSelesai']));
$hari = $_POST['hari'];
$tgl = $_POST['tgl']; // Menggunakan tanggal yang diinput oleh user
$guruGanti = $_POST['guruGanti'];
$status = $_POST['status'];
$id = isset($_POST['id']) ? $_POST['id'] : null;  // Pastikan 'id' diambil jika melakukan update

if ($id) {
    // Update query
    $query = "UPDATE tbl_izinguru SET guruIzin = ?, alasanIzin = ?, waktu_mulai = ?, waktu_selesai = ?, hari = ?, tgl = ?, guruGanti = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssssi', $guruIzin, $alasanIzin, $waktuMulaiIzin, $waktuSelesaiIzin, $hari, $tgl, $guruGanti, $status, $id);
} else {
    // Insert query
    $query = "INSERT INTO tbl_izinguru (guruIzin, alasanIzin, waktu_mulai, waktu_selesai, hari, tgl, guruGanti, status, waktu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssss', $guruIzin, $alasanIzin, $waktuMulaiIzin, $waktuSelesaiIzin, $hari, $tgl, $guruGanti, $status);
}

if (mysqli_stmt_execute($stmt)) {
    if ($id) {
        header('Location: izinustadz.php?msg=updated');
    } else {
        header('Location: add-izinustadz.php?msg=success');
    }
} else {
    echo "Error: " . mysqli_error($koneksi); // Tampilkan pesan kesalahan jika query gagal
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
exit();

