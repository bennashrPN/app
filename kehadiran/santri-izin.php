<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

// Function to get the current day in Bahasa Indonesia
function get_current_day()
{
    // Array untuk mapping hari dalam Bahasa Indonesia
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    // Ambil hari dalam Bahasa Indonesia berdasarkan hari dalam bahasa Inggris
    $current_day_in_english = date('l'); // Ambil hari dalam bahasa Inggris
    return $days[$current_day_in_english]; // Kembalikan hari dalam Bahasa Indonesia
}

// Get the current day in Bahasa Indonesia
$current_day = get_current_day();

// Query untuk mengambil data guru yang mengajar pada hari ini
$query = "SELECT * FROM tbl_absensi WHERE hari = ? AND kehadiran = 'izin'";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('s', $current_day);
$stmt->execute();
$result_jadwal = $stmt->get_result();



// Judul halaman
$title = "Santri Izin";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Santri Izin</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-admin.php">Home</a></li>
                <li class="breadcrumb-item active">Santri Izin</li>
            </ol>
         <!-- tampilin alert disini -->


         <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-1"></i>
                            Daftar Santri Izin Hari Ini : 
                            <div><?php echo get_current_day() . ", " . date('j F Y'); ?></div>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>KELAS</th>
                                            <th>PELAJARAN</th>
                                            <th>WAKTU</th>
                                            <th>JAM</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>KELAS</th>
                                            <th>PELAJARAN</th>
                                            <th>WAKTU</th>
                                            <th>JAM</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        // Initialize a counter variable for numbering
                                        $counter = 1;
                                        // Iterate through the fetched data and display it
                                        while ($row = $result_jadwal->fetch_assoc()) :
                                        ?>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td><?php echo $row['nama']; ?></td>
                                                <td><?php echo $row['kelas']; ?></td>
                                                <td><?php echo $row['pelajaran']; ?></td>
                                                <td><?php echo $row['waktu']; ?></td>
                                                <td><?php echo $row['jam']; ?></td>
                                            </tr>
                                        <?php
                                            // Increment the counter
                                            $counter++;
                                        endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </main>
    <?php


    require_once "../template/footer.php";

    ?>