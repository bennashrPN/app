<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['namaSantriIzin']);
    $kelas    = $_POST['kelas'];
    $kehadiran    = $_POST['kehadiran'];
    // Get the date from the form input
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];
    $hari = $_POST['tanggal'];
    $tanggal = "$tahun-$bulan-$hari";
    $keterangan = htmlspecialchars($_POST['keterangan']);
    mysqli_query($koneksi, "INSERT INTO tbl_perizinan  VALUES (null, '$nama','$kelas','$kehadiran','$tanggal','$keterangan')");

    header("location: perizinan.php?msg=added");
    exit;
}
if (isset($_POST['update'])) {
    // Ambil data dari formulir
    $id = $_POST['id'];
    $nama = htmlspecialchars($_POST['nama']);
    $kelasAngka = $_POST['kelasAngka'];
    $kelasAbjad = $_POST['kelasAbjad'];
    $kehadiran = $_POST['kehadiran'];
    $tanggal = $_POST['tanggal'];
    $keterangan = htmlspecialchars($_POST['keterangan']);
    // Combine kelasAngka and kelasAbjad into kelas with a space between them
    $kelas = $kelasAngka . ' ' . $kelasAbjad;
    
    // Query untuk memperbarui data perizinan berdasarkan id yang diberikan
    $query = "UPDATE tbl_perizinan SET nama = '$nama', kelas = '$kelas', kehadiran = '$kehadiran', tanggal = '$tanggal', keterangan = '$keterangan' WHERE id = '$id'";
    
    // Jalankan query
    if (mysqli_query($koneksi, $query)) {
        header("Location: perizinan.php?msg=updated");
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
    
    exit;
}
?>
