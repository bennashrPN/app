<?php
require_once "../config.php";
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama']) && isset($_POST['kehadiran']) && isset($_POST['kelas']) && isset($_POST['jenjang'])) {
    // Escape variables to prevent SQL injection and syntax errors
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kehadiran = mysqli_real_escape_string($koneksi, $_POST['kehadiran']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jenjang = mysqli_real_escape_string($koneksi, $_POST['jenjang']);

    // Query untuk melakukan update kehadiran berdasarkan nama, kelas, dan jenjang
    $query = "UPDATE tbl_absensi SET kehadiran='$kehadiran' WHERE nama='$nama' AND kelas='$kelas' AND jenjang='$jenjang'";

    if (mysqli_query($koneksi, $query)) {
        $response = [
            'success' => true,
            'message' => 'Data absensi berhasil diperbarui.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Gagal memperbarui data absensi: ' . mysqli_error($koneksi)
        ];
    }

    echo json_encode($response);
} else {
    $response = [
        'success' => false,
        'message' => 'Request tidak valid atau data tidak lengkap.'
    ];
    echo json_encode($response);
}
?>
