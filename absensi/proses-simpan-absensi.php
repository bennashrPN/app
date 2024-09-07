<?php
session_start(); // Ensure the session is started
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari POST
    $kelas = $_POST['kelas'] ?? '';
    $pengajar = $_POST['pengajar'] ?? '';
    $pelajaran = $_POST['pelajaran'] ?? '';
    $jenjang = $_POST['jenjang'] ?? '';
    $hari = $_POST['hari'] ?? '';
    $jam = $_POST['jam'] ?? '';
    $waktu = $_POST['waktu'] ?? '';
    $kehadiranData = $_POST['kehadiranData'] ?? [];
    $tanggal = $_POST['tanggal'] ?? '';
    $user = $_SESSION["ssUser"] ?? '';

    // Validasi tanggal
    if (!checkdate((int)substr($tanggal, 5, 2), (int)substr($tanggal, 8, 2), (int)substr($tanggal, 0, 4))) {
        echo json_encode(["success" => false, "message" => "Tanggal tidak valid."]);
        exit();
    }

    // Validasi input lainnya
    if (empty($kelas) || empty($pengajar) || empty($pelajaran) || empty($tanggal) || empty($kehadiranData)) {
        echo json_encode(["success" => false, "message" => "Data kelas, pengajar, pelajaran, tanggal, dan kehadiran harus diisi."]);
        exit();
    }

    // Cek apakah data absensi untuk kelas, pengajar, pelajaran, dan tanggal yang sama sudah ada
    $stmt_check = $koneksi->prepare("SELECT COUNT(*) as count FROM tbl_absensi WHERE kelas=? AND guru=? AND pelajaran=? AND jenjang=? AND hari=? AND jam=? AND tanggal=?");
    $stmt_check->bind_param("sssssss", $kelas, $pengajar, $pelajaran, $jenjang, $hari, $jam, $tanggal);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();
    
    if ($row_check['count'] > 0) {
        echo json_encode(["success" => false, "message" => "Data absensi untuk kelas $kelas dengan pengajar $pengajar untuk pelajaran $pelajaran pada jenjang $jenjang di hari $hari jam $jam pada tanggal $tanggal sudah ada."]);
        exit();
    }

    // Siapkan statement untuk menyimpan data ke tabel 'tbl_absensi'
    $stmt = $koneksi->prepare(
        "INSERT INTO tbl_absensi (nama, kelas, guru, pelajaran, kehadiran, jenjang, hari, jam, waktu, waktu_absen, tanggal, user) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)"
    );

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Prepare statement failed: " . $koneksi->error]);
        exit();
    }

    // Memproses kehadiran untuk setiap nama siswa
    foreach ($kehadiranData as $data) {
        $namaSiswa = $data['nomor'] ?? '';
        $kehadiran = $data['kehadiran'] ?? '';

        // Validasi data kehadiran
        if (empty($namaSiswa) || empty($kehadiran)) {
            continue;
        }

        // Bind parameter dan eksekusi statement
        $stmt->bind_param(
            "sssssssssss",
            $namaSiswa, $kelas, $pengajar, $pelajaran, $kehadiran,
            $jenjang, $hari, $jam, $waktu, $tanggal, $user
        );
        
        if (!$stmt->execute()) {
            echo json_encode(["success" => false, "message" => "Gagal menyimpan data absensi untuk siswa $namaSiswa: " . $stmt->error]);
            $stmt->close();
            exit();
        }
    }

    // Tutup statement
    $stmt->close();

    // Semua data berhasil disimpan
    echo json_encode(["success" => true, "message" => "Data kehadiran kelas $kelas berhasil disimpan."]);
}
?>
