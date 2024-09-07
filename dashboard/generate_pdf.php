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

// Mengambil data pengguna yang sedang login dari sesi
if (isset($_SESSION["ssUser"])) {
    $username = $_SESSION["ssUser"];
    $query_user = "SELECT * FROM tbl_user WHERE username='$username'";
    $result_user = mysqli_query($koneksi, $query_user);

    // Memeriksa apakah ada data pengguna yang ditemukan
    if ($result_user) {
        $user_data = mysqli_fetch_assoc($result_user);
    } else {
        $user_data = null;
    }
} else {
    // Jika tidak ada sesi pengguna, arahkan ke halaman login
    header("location:../auth/login.php");
    exit;
}

// Memeriksa apakah data pengguna valid
if ($user_data !== null) {
    // Mengambil jadwal pelajaran sesuai dengan nama guru dan mengurutkan berdasarkan hari
    $query_jadwal = "
        SELECT * 
        FROM tbl_jadwalpelajaran 
        WHERE guru='" . $user_data['nama'] . "' 
        ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')";
    $result_jadwal = mysqli_query($koneksi, $query_jadwal);

    // Membuat objek FPDF dan menyiapkan halaman
    $pdf = new FPDF();
    $pdf->AddPage();

    // Mengatur margin
    $pdf->SetMargins(10, 10, 10);

    // Menambahkan judul dokumen
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(33, 37, 41);
    $pdf->Cell(0, 10, 'Jadwal Pelajaran Semester Gasal ', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Tahun Pelajaran 1445-1446 H / 2024-2025 M', 0, 1, 'C');
    $pdf->Ln(5);

    // Menampilkan nama guru
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetTextColor(52, 58, 64);
    $pdf->Cell(0, 10, 'Ustadz ' . $user_data['nama'], 0, 1, 'C');
    $pdf->Ln(10);

    // Menetapkan warna latar belakang dan teks untuk header tabel
    $pdf->SetFillColor(52, 58, 64); // Background color for header
    $pdf->SetTextColor(255, 255, 255); // Text color for header
    $pdf->SetDrawColor(52, 58, 64); // Border color
    $pdf->SetLineWidth(0.3); // Border width

    // Menetapkan font untuk header tabel
    $pdf->SetFont('Arial', 'B', 10);

    // Menambahkan header tabel
    $pdf->Cell(9, 10, 'NO', 1, 0, 'C', true);
    $pdf->Cell(20, 10, 'HARI', 1, 0, 'C', true);
    $pdf->Cell(15, 10, 'JAM', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'WAKTU', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'KELAS', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'PELAJARAN', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'JENJANG', 1, 0, 'C', true);
    $pdf->Ln();

    // Menambahkan data tabel ke PDF
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(33, 37, 41);
    $pdf->SetFillColor(248, 249, 250); // Background color for rows
    $no = 1;
    $fill = false;

    while ($row = mysqli_fetch_assoc($result_jadwal)) {
        $pdf->Cell(9, 10, $no++, 1, 0, 'C', $fill);
        $pdf->Cell(20, 10, $row['hari'], 1, 0, 'C', $fill);
        $pdf->Cell(15, 10, $row['jam'], 1, 0, 'C', $fill);
        $pdf->Cell(50, 10, $row['waktu'], 1, 0, 'C', $fill);
        $pdf->Cell(30, 10, $row['kelas'], 1, 0, 'C', $fill);
        $pdf->Cell(30, 10, $row['pelajaran'], 1, 0, 'C', $fill);
        $pdf->Cell(30, 10, $row['jenjang'], 1, 0, 'C', $fill);
        $pdf->Ln();
        $fill = !$fill; // Toggle background fill color
    }

    // Menyimpan file PDF
    $pdf->Output('D', 'jadwal_pelajaran.pdf'); // 'D' artinya file akan didownload
} else {
    // Jika user_data null, redirect ke halaman login
    header("location:../auth/login.php");
    exit;
}

// Tutup koneksi database
mysqli_close($koneksi);

?>
