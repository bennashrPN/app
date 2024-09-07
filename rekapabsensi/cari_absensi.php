<?php
// cari_absensi.php

require_once "../config.php";

$guru = $_POST['autoSizingUstadz'];
$pelajaran = $_POST['autoSizingPelajaran'];
$kelas = $_POST['autoSizingKelas'];

$stmt = $koneksi->prepare("SELECT * FROM tbl_absensi WHERE guru=? AND pelajaran=? AND kelas=?");
$stmt->bind_param("sss", $guru, $pelajaran, $kelas);

$stmt->execute();

$result = $stmt->get_result();
// Initialize a counter variable for numbering
// Inisialisasi variabel counter
$counter = 1;

// Iterasi melalui hasil query
while ($data = $result->fetch_assoc()) {
    echo "<tr>";
    // Tampilkan nomor urut menggunakan counter
    echo "<td>" . $counter . "</td>";
    // Tampilkan data lainnya
    echo "<td>" . $data['guru'] . "</td>";
    echo "<td>" . $data['pelajaran'] . "</td>";
    echo "<td>" . $data['kelas'] . "</td>";
    echo "</tr>";
    // Increment counter
    $counter++;
}




