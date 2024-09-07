<?php
require_once "../config.php";

$querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa");
$santriData = [];

while ($data = mysqli_fetch_assoc($querySiswa)) {
    $santriData[] = $data;
}

header('Content-Type: application/json');
echo json_encode($santriData);
?>
