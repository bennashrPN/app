<?php

session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location../auth/login.php');
    exit;
}

require_once '../config.php';

// Check if the form is submitted
if (isset($_POST['simpan'])) {
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $bidang = htmlspecialchars($_POST['bidang']);
    $jabatan = htmlspecialchars($_POST['jabatan']);


    // Insert data into the database
    $query = "INSERT INTO tbl_jabatan (jabatan, bidang) VALUES ('$jabatan', '$bidang')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header('Location: jabatan.php?msg=added');
        exit;
    } else {
        header('Location: jabatan.php?msg=error');
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $bidang = htmlspecialchars($_POST['bidang']);

    
    // Mendapatkan data saat ini dari database
    $sqlJabatan = mysqli_query($koneksi, "SELECT * FROM tbl_jabatan WHERE id = '$id'");
    $data = mysqli_fetch_assoc($sqlJabatan);

    // Update data di database
    $updateQuery = "UPDATE tbl_jabatan SET jabatan = '$jabatan', bidang = '$bidang' WHERE id = $id";
    $result = mysqli_query($koneksi, $updateQuery);

    if ($result) {
        header("location: jabatan.php?msg=updated");
        exit;
    } else {
        header("location: jabatan.php?msg=error");
        exit;
    }
}



