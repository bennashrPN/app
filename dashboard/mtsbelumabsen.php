<?php

// Memulai sesi
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

// Memuat konfigurasi database
require_once "../config.php";

// Memuat pustaka FPDF
require_once "../asset/lib/fpdf/fpdf.php";

// Function to get the current day in Bahasa Indonesia
function get_current_day()
{
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $current_day_in_english = date('l'); // Get the current day in English
    return $days[$current_day_in_english]; // Return the day in Bahasa Indonesia
}

$current_day = get_current_day();
$current_date = date('Y-m-d'); // Get the current date in YYYY-MM-DD format

// Query untuk mengecek apakah ada jadwal pelajaran pada hari ini
$query_jadwal = "SELECT jp.kelas, jp.pelajaran, jp.guru, jp.jam
    FROM tbl_jadwalpelajaran jp
    WHERE jp.hari = ? AND jp.jenjang = 'MTS'";
$stmt_jadwal = $koneksi->prepare($query_jadwal);
$stmt_jadwal->bind_param('s', $current_day);
$stmt_jadwal->execute();
$result_jadwal = $stmt_jadwal->get_result();

$jadwal_found = $result_jadwal->num_rows > 0;
$unabsented_found = false;

$data = [];
if ($jadwal_found) {
    // Query untuk mendapatkan data jadwal pelajaran yang belum diabsen
    $query = "SELECT jp.kelas, jp.pelajaran, jp.guru, jp.jam
        FROM tbl_jadwalpelajaran jp
        LEFT JOIN tbl_absensi abs ON jp.hari = abs.hari
        AND jp.kelas = abs.kelas
        AND jp.pelajaran = abs.pelajaran
        AND jp.guru = abs.guru
        AND jp.jam = abs.jam
        AND abs.tanggal = CURDATE()
        WHERE jp.hari = ? AND jp.jenjang = 'MTS' AND abs.hari IS NULL";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $current_day);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'kelas' => $row['kelas'],
            'pelajaran' => $row['pelajaran'],
            'guru' => $row['guru'],
            'jam' => $row['jam']
        ];
        $unabsented_found = true;
    }
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, 'Daftar Kelas MTS  Belum Presensi', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Hari: $current_day, Tanggal: $current_date", 0, 1, 'C');

// Adding table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(20, 10, 'Kelas', 1, 0, 'C');
$pdf->Cell(50, 10, 'Pelajaran', 1, 0, 'C');
$pdf->Cell(70, 10, 'Guru', 1, 0, 'C');
$pdf->Cell(20, 10, 'Jam', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
if ($unabsented_found) {
    $no = 1;
    foreach ($data as $item) {
        $pdf->Cell(10, 10, $no++, 1, 0, 'C');
        $pdf->Cell(20, 10, $item['kelas'], 1, 0, 'C');
        $pdf->Cell(50, 10, $item['pelajaran'], 1, 0, 'C');
        $pdf->Cell(70, 10, $item['guru'], 1, 0, 'C');
        $pdf->Cell(20, 10, $item['jam'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(170, 10, 'Semua pelajaran hari ini sudah presensi.', 1, 1, 'C');
}

// Output PDF untuk diunduh
$pdf->Output('D', 'daftar_kelas_mts_belum_presensi_' . strtolower($current_day) . '.pdf');
?>
