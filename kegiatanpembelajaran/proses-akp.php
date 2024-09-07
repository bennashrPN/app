<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    // Retrieve posted data
    $pelajaran = mysqli_real_escape_string($koneksi, $_POST['pelajaranAkp']);
    $materi = mysqli_real_escape_string($koneksi, $_POST['materiAkp']);
    $kegiatan = mysqli_real_escape_string($koneksi, $_POST['kegiatanAkp']);
    $guru = $_SESSION['nama'];

    // Fetch classes for the logged-in teacher and selected subject
    $kelasQuery = "SELECT kelas FROM tbl_jadwalpelajaran WHERE guru = ? AND pelajaran = ?";
    if ($kelasStmt = mysqli_prepare($koneksi, $kelasQuery)) {
        mysqli_stmt_bind_param($kelasStmt, 'ss', $guru, $pelajaran);
        mysqli_stmt_execute($kelasStmt);
        $kelasResult = mysqli_stmt_get_result($kelasStmt);
        $classes = [];
        while ($row = mysqli_fetch_assoc($kelasResult)) {
            $classes[] = $row['kelas'];
        }
        mysqli_stmt_close($kelasStmt);

        if (!empty($classes)) {
            // Insert data for each class
            $query = "INSERT INTO tbl_dataakp (kelas, guru, pelajaran, materi_pembelajaran, kegiatan_pembelajaran) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($koneksi, $query)) {
                $result = true;
                foreach ($classes as $kelas) {
                    mysqli_stmt_bind_param($stmt, 'sssss', $kelas, $guru, $pelajaran, $materi, $kegiatan);
                    if (!mysqli_stmt_execute($stmt)) {
                        $result = false;
                    }
                }
                mysqli_stmt_close($stmt);
                if ($result) {
                    header('Location: kegiatanpembelajaran.php?msg=added');
                } else {
                    header('Location: kegiatanpembelajaran.php?msg=error_query');
                }
            } else {
                header('Location: kegiatanpembelajaran.php?msg=error_prepare');
            }
        } else {
            header('Location: kegiatanpembelajaran.php?msg=no_classes');
        }
    } else {
        header('Location: kegiatanpembelajaran.php?msg=error_kelas_query');
    }
} else {
    header('Location: kegiatanpembelajaran.php?msg=invalid_request');
}
?>
