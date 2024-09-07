<?php
require_once "../config.php";

if (isset($_GET['nama'])) {
    $nama = mysqli_real_escape_string($koneksi, $_GET['nama']);
    $query = "SELECT kelas, jenjang FROM tbl_siswa WHERE nama = '$nama' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(['kelas' => '', 'jenjang' => '']);
    }
}
?>
