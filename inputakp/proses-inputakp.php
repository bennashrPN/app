<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";

// Ambil data dari formulir dan sanitasi input untuk menghindari SQL Injection
$guru = mysqli_real_escape_string($koneksi, trim($_POST['guru']));
$kelas = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
$pelajaran = mysqli_real_escape_string($koneksi, trim($_POST['pelajaran']));
$materiAKP = mysqli_real_escape_string($koneksi, trim($_POST['materiAKP']));
$kegiatanAKP = mysqli_real_escape_string($koneksi, trim($_POST['kegiatanAKP']));
$keterangan = mysqli_real_escape_string($koneksi, trim($_POST['ket']));
$hari = mysqli_real_escape_string($koneksi, trim($_POST['hari']));
$tanggal = mysqli_real_escape_string($koneksi, trim($_POST['tanggal_izin']));
$id = isset($_POST['id']) ? mysqli_real_escape_string($koneksi, trim($_POST['id'])) : null;

// Cek apakah ada input yang kosong
if (empty($guru) || empty($kelas) || empty($pelajaran) || empty($materiAKP) || empty($kegiatanAKP) || empty($keterangan) || empty($hari) || empty($tanggal)) {
    header('Location: add-inputakp.php?msg=empty_fields');
    exit;
}

if ($id) {
    // Update data yang sudah ada
    $query = "UPDATE tbl_kegiatanpembelajaran SET guru = ?, pelajaran = ?, materiPembelajaran = ?, kegiatanPembelajaran = ?, keterangan = ?, kelas = ?, hari = ?, tanggal = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssssssi', $guru, $pelajaran, $materiAKP, $kegiatanAKP, $keterangan, $kelas, $hari, $tanggal, $id);
        mysqli_stmt_execute($stmt);

        // Redirect atau berikan feedback
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header('Location: inputakp.php?msg=updated');
        } else {
            header('Location: inputakp.php?msg=error');
        }
        mysqli_stmt_close($stmt);
    } else {
        header('Location: inputakp.php?msg=query_failed');
    }
} else {
    // Simpan data baru ke tabel kegiatanpembelajaran
    $query = "INSERT INTO tbl_kegiatanpembelajaran (guru, pelajaran, materiPembelajaran, kegiatanPembelajaran, keterangan, kelas, hari, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssssss', $guru, $pelajaran, $materiAKP, $kegiatanAKP, $keterangan, $kelas, $hari, $tanggal);
        mysqli_stmt_execute($stmt);

        // Redirect atau berikan feedback
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header('Location: add-inputakp.php?msg=added');
        } else {
            header('Location: add-inputakp.php?msg=error');
        }
        mysqli_stmt_close($stmt);
    } else {
        header('Location: add-inputakp.php?msg=query_failed');
    }
}

mysqli_close($koneksi);
exit();
