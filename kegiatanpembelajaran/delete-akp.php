<?php
// Bismillah session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// Call file yang berkaitan  
require_once "../config.php";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch data untuk ID tersebut
$query = mysqli_query($koneksi, "SELECT * FROM tbl_dataakp WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($data) {
    // Hapus semua entri yang memiliki pelajaran, materi, dan kegiatan yang sama
    $queryDelete = "DELETE FROM tbl_dataakp 
                    WHERE pelajaran = '{$data['pelajaran']}'
                    AND materi_pembelajaran = '{$data['materi_pembelajaran']}'
                    AND kegiatan_pembelajaran = '{$data['kegiatan_pembelajaran']}'";

    $result = mysqli_query($koneksi, $queryDelete);

    if ($result) {
        header("Location: kegiatanpembelajaran.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($koneksi);
    }
} else {
    echo "Data not found.";
}
?>
