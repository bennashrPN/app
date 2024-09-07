<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

// Panggil file yang dibutuhkan
require_once  "../config.php"; 

$title = "Tambah User - hudhuur";
require_once    "../template/header.php";
require_once    "../template/navbar.php";
require_once    "../template/sidebar.php";

if (isset($_POST['input'])) {
    $input = mysqli_real_escape_string($koneksi, $_POST['input']);
    $query = "SELECT nama FROM tbl_guru WHERE nama LIKE '%$input%' 
              UNION 
              SELECT nama FROM tbl_pegawai WHERE nama LIKE '%$input%' LIMIT 5";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="nama-suggestion">' . $row['nama'] . '</div>';
        }
    } else {
        echo '<div class="nama-suggestion">Tidak ada hasil</div>';
    }
}

// Tutup koneksi ke database
mysqli_close($koneksi);

?>