<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../asset/lib/PhpSpreadsheet/vendor/autoload.php';





use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set the active sheet
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Rekap Absensi');

// Set headers
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Guru');
$sheet->setCellValue('C1', 'Pelajaran');
$sheet->setCellValue('D1', 'Kelas');
$sheet->setCellValue('E1', 'Nama');
$sheet->setCellValue('F1', 'Kehadiran');
$sheet->setCellValue('G1', 'Tanggal');

// Set header styles
$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// Fetch data from database
if (isset($_SESSION['searchCriteria'])) {
    $guru = $_SESSION['searchCriteria']['guru'];
    $pelajaran = $_SESSION['searchCriteria']['pelajaran'];
    $kelas = $_SESSION['searchCriteria']['kelas'];
    $startDate = $_SESSION['searchCriteria']['startDate'];
    $endDate = $_SESSION['searchCriteria']['endDate'];

    $stmt = $koneksi->prepare("SELECT guru, pelajaran, kelas, nama, kehadiran, tanggal FROM tbl_absensi WHERE guru = ? AND pelajaran = ? AND kelas = ? AND tanggal BETWEEN ? AND ?");
    $stmt->bind_param("sssss", $guru, $pelajaran, $kelas, $startDate, $endDate);
} else {
    // Jika tidak ada kriteria pencarian, jangan ambil data apa pun
    $stmt = $koneksi->prepare("SELECT guru, pelajaran, kelas, nama, kehadiran, tanggal FROM tbl_absensi WHERE 1=0");
}
$stmt->execute();
$result = $stmt->get_result();

// Set row counter
$row = 2;

// Iterate through the results and add them to the spreadsheet
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $row - 1);
    $sheet->setCellValue('B' . $row, $data['guru']);
    $sheet->setCellValue('C' . $row, $data['pelajaran']);
    $sheet->setCellValue('D' . $row, $data['kelas']);
    $sheet->setCellValue('E' . $row, $data['nama']);
    $sheet->setCellValue('F' . $row, $data['kehadiran']);
    $sheet->setCellValue('G' . $row, $data['tanggal']);
    
    $row++;
}

// Auto size columns
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set response headers for download
$filename = 'Rekap_Absensi.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Save the spreadsheet to Excel5 file format
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
