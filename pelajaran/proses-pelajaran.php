<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    $pelajaran  = htmlspecialchars($_POST['pelajaran']);
    $jenjang    = $_POST['jenjang'];
    $kelasAngka = $_POST['kelasAngka'];
    $kelasAbjad = $_POST['kelasAbjad'];
    $guru       = $_POST['ustadz'];

    // Gabungkan kelas angka dan abjad dalam satu kolom
    $kelas = $kelasAngka . $kelasAbjad;

    $cekPelajaran = mysqli_query($koneksi, "SELECT * FROM tbl_pelajaran WHERE pelajaran ='$pelajaran'");
    if (mysqli_num_rows($cekPelajaran) > 0) {
        header("location: pelajaran.php?msg=cancel");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_pelajaran VALUES (null, '$pelajaran','$jenjang','$kelas','$guru')");

    header("location: pelajaran.php?msg=added");
    exit;
}

