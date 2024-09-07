<?php
// proses-nama-siswa.php

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kelas = $_POST['kelas'];

    // Query untuk mengambil data siswa berdasarkan kelas
    $querySiswa = mysqli_query($koneksi, "SELECT nama FROM tbl_siswa WHERE kelas = '$kelas'");
    if (!$querySiswa) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    // Bangun opsi nama siswa dengan nomor urut
    $options = array();
    $nomor = 1;
    while ($dataSiswa = mysqli_fetch_assoc($querySiswa)) {
        $namaSiswa = $dataSiswa['nama'];
        $options[] = array(
            'nomor' => $nomor,
            'nama' => $namaSiswa,
            'radioName' => 'kehadiran_' . str_replace('_', '_', $namaSiswa),
        );
        $nomor++;
    }

    // Mengembalikan opsi nama siswa sebagai respons
    echo json_encode($options);
}
?>
