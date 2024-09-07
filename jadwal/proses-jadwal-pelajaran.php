<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit;
}

require_once '../config.php';

// Check if the form is submitted
if (isset($_POST['simpan'])) {
    $hari = $_POST['hari'];
    $jam = $_POST['jam'];
    $kelas = $_POST['kelas'];
    $pelajaran = $_POST['pelajaran'];
    $guru = $_POST['ustadz'];
    $jenjang = $_POST['jenjang'];

    // Ambil data dari form
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];

    // Format waktu mulai dan selesai
    $waktu_mulai = date('H:i', strtotime($waktu_mulai)); // Format H:i:s sudh diganti jadi H:i
    $waktu_selesai = date('H:i', strtotime($waktu_selesai)); // Format H:i:s sudh diganti jadi H:i

    // Gabungkan waktu mulai dan selesai dengan tanda strip
    $waktu = $waktu_mulai . ' - ' . $waktu_selesai;

    // Menyiapkan pernyataan SQL dengan parameter
    $query = "INSERT INTO tbl_jadwalpelajaran (hari, jam, waktu_mulai, Waktu_selesai, waktu, kelas, pelajaran, guru, jenjang) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameter ke pernyataan
    mysqli_stmt_bind_param($stmt, "sssssssss", $hari, $jam, $waktu_mulai, $waktu_selesai, $waktu, $kelas, $pelajaran, $guru, $jenjang);

    // Eksekusi pernyataan
    $result = mysqli_stmt_execute($stmt);

    // Tutup pernyataan
    mysqli_stmt_close($stmt);

    // Tutup koneksi
    mysqli_close($koneksi);

    // Cek hasil eksekusi
    if ($result) {
        header('Location: add-jadwal-pelajaran.php?msg=added');
        exit;
    } else {
        header('Location: add-jadwal-pelajaran.php?msg=error');
        exit;
    }
}
// Menangani permintaan pembaruan
if (isset($_POST['update'])) {
    // Ambil data dari form
    $id = $_POST['id'];
    $hari = $_POST['hari'];
    $jam = $_POST['jam'];
    $kelas = $_POST['kelas'];
    $pelajaran = $_POST['pelajaran'];
    $guru = $_POST['ustadz'];
    $jenjang = $_POST['jenjang'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    // Format waktu mulai dan selesai
    $waktu_mulai = date('H:i:s', strtotime($waktu_mulai));
    $waktu_selesai = date('H:i:s', strtotime($waktu_selesai));

    // Gabungkan waktu mulai dan selesai dengan tanda strip
    $waktu = $waktu_mulai . ' - ' . $waktu_selesai;

    $query = "UPDATE tbl_jadwalpelajaran SET hari = ?, jam = ?, waktu_mulai = ?, waktu_selesai = ?, waktu = ?, kelas = ?, pelajaran = ?, guru = ?, jenjang = ? WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $query);

// Bind parameters with proper types. s = string, etc.
mysqli_stmt_bind_param($stmt, "sssssssssi", $hari, $jam, $waktu_mulai, $waktu_selesai, $waktu, $kelas, $pelajaran, $guru, $jenjang, $id);

// Execute statement
if (mysqli_stmt_execute($stmt)) {
    header("Location: jadwal-pelajaran.php?msg=updated");
} else {
    echo "Terjadi kesalahan: " . mysqli_stmt_error($stmt);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

}
?>
