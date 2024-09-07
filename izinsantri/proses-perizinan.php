<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$user = mysqli_real_escape_string($koneksi, $_SESSION["ssUser"]);
if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['namaSantriIzin']);
    $kelas    = $_POST['kelas'];
    $kehadiran    = $_POST['kehadiran'];
    $jenjang    = $_POST['jenjang'];
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];
    $hari = $_POST['tanggal'];
    $tanggal = "$tahun-$bulan-$hari";
    $keterangan = htmlspecialchars($_POST['keterangan']);

    // Check for duplicate entry
    $query = "SELECT * FROM tbl_perizinan WHERE nama = '$nama' AND kelas = '$kelas' AND kehadiran = '$kehadiran' AND jenjang = '$jenjang' AND tanggal = '$tanggal'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        // Duplicate found
        header("Location: perizinan.php?msg=duplicate");
        exit;
    } else {
        // No duplicate found, proceed with insert
        mysqli_query($koneksi, "INSERT INTO tbl_perizinan VALUES (null, '$nama', '$kelas', '$kehadiran', '$jenjang', '$tanggal', '$keterangan', '$user', NOW())");
        header("Location: perizinan.php?msg=added");
        exit;
    }
}

if (isset($_POST['update'])) {
    // Ambil data dari formulir
    $id = $_POST['id'];
    $nama = htmlspecialchars($_POST['nama']);
    $kelas = $_POST['kelas'];
    $kehadiran = $_POST['kehadiran'];
    $jenjang = $_POST['jenjang'];
    $tanggal = $_POST['tanggal'];
    $keterangan = htmlspecialchars($_POST['keterangan']);

    // Query untuk memperbarui data perizinan berdasarkan id yang diberikan
    $query = "UPDATE tbl_perizinan SET nama = '$nama', kelas = '$kelas', kehadiran = '$kehadiran', jenjang = '$jenjang', tanggal = '$tanggal', keterangan = '$keterangan' WHERE id = '$id'";
    
    // Jalankan query
    if (mysqli_query($koneksi, $query)) {
        header("Location: perizinan.php?msg=updated");
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
    
    exit;
}
?>
