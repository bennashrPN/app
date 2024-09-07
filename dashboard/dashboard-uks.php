<?php

// Awali dengan session
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}
require_once "../config.php";

// Pastikan ada sesi pengguna
if (isset($_SESSION["ssUser"])) {
    $username = $_SESSION["ssUser"];
    $query_user = "SELECT * FROM tbl_user WHERE username='$username'";
    $result_user = mysqli_query($koneksi, $query_user);

    // Ambil data pengguna
    $user_data = mysqli_fetch_assoc($result_user);
}
date_default_timezone_set('Asia/Jakarta'); // Set timezone ke Asia/Jakarta

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

// Query untuk mengambil data guru yang mengajar pada hari ini
$query = "SELECT * FROM tbl_perizinan WHERE tanggal = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('s', $current_date);
$stmt->execute();
$result_santriIzin = $stmt->get_result();
$title = "staff UKS";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>
<link href="<?= $main_url ?>dashboard/staff.css" rel="stylesheet">
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Dashboard UKS</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item active">Staff</li>
            </ol>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">
                    <b>Ahlan Wa Sahlan, <span>Ustadz</span></b>
                    <span class="gradient-text"><br><?php echo $user_data['nama']; ?> </span>
                    <p>Berikut Informasi Hari Ini , <br> <?php echo $current_day; ?>, <?php echo $current_date; ?></p>
                </li>
            </ol>
            <div class="row">
            <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <b style="font-size: 15px;">Daftar Izin MTS</b>

                        </div>
                        <div class="card-body">
                            <div class="scrollable-list">
                                <?php
                                // Mengambil tanggal hari ini
                                $current_date = date('Y-m-d');

                                // Query untuk mengecek apakah ada perizinan pada hari ini
                                $query_izin = "SELECT nama, kelas, kehadiran, keterangan 
                               FROM tbl_perizinan 
                               WHERE tanggal = ? AND jenjang = 'MTS'";
                                $stmt_izin = $koneksi->prepare($query_izin);
                                $stmt_izin->bind_param('s', $current_date);
                                $stmt_izin->execute();
                                $result_izin = $stmt_izin->get_result();

                                if ($result_izin->num_rows > 0) {
                                    // Menampilkan daftar siswa yang izin
                                    while ($row = $result_izin->fetch_assoc()) {
                                        $nama = $row['nama'];
                                        $kelas = $row['kelas'];
                                        $kehadiran = $row['kehadiran'];
                                        $keterangan = $row['keterangan'];

                                        echo '<div class="me-4 d-flex align-items-center mb-2 izin-info">';
                                        echo '<i class="bi bi-info-circle-fill" style="color: #2A8E45;"></i>';
                                        echo '<div class="ms-4">';
                                        echo '<div class="fw-bold">' . $nama . ' (' . $kelas . ')</div>';
                                        echo '<strong>' . $kehadiran . '</strong>, Keterangan: ' . $keterangan . '.';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    // Menampilkan pesan jika tidak ada siswa yang izin hari ini
                                    echo '<div class="alert alert-info" role="alert">Tidak ada siswa yang izin hari ini.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <b style="font-size: 15px;">Daftar Izin MA</b>

                        </div>
                        <div class="card-body">
                            <div class="scrollable-list">
                                <?php
                                // Mengambil tanggal hari ini
                                $current_date = date('Y-m-d');

                                // Query untuk mengecek apakah ada perizinan pada hari ini
                                $query_izin = "SELECT nama, kelas, kehadiran, keterangan 
                               FROM tbl_perizinan 
                               WHERE tanggal = ? AND jenjang = 'MA'";
                                $stmt_izin = $koneksi->prepare($query_izin);
                                $stmt_izin->bind_param('s', $current_date);
                                $stmt_izin->execute();
                                $result_izin = $stmt_izin->get_result();

                                if ($result_izin->num_rows > 0) {
                                    // Menampilkan daftar siswa yang izin
                                    while ($row = $result_izin->fetch_assoc()) {
                                        $nama = $row['nama'];
                                        $kelas = $row['kelas'];
                                        $kehadiran = $row['kehadiran'];
                                        $keterangan = $row['keterangan'];

                                        echo '<div class="me-4 d-flex align-items-center mb-2 izin-info">';
                                        echo '<i class="bi bi-info-circle-fill" style="color: #2A8E45;"></i>';
                                        echo '<div class="ms-4">';
                                        echo '<div class="fw-bold">' . $nama . ' (' . $kelas . ')</div>';
                                        echo '<strong>' . $kehadiran . '</strong>, Keterangan: ' . $keterangan . '.';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    // Menampilkan pesan jika tidak ada siswa yang izin hari ini
                                    echo '<div class="alert alert-info" role="alert">Tidak ada siswa yang izin hari ini.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
// Function to sort class names
function sortClasses($a, $b) {
    $a_parts = explode(' ', $a);
    $b_parts = explode(' ', $b);
    
    $a_class = (int)$a_parts[0];
    $b_class = (int)$b_parts[0];
    if ($a_class == $b_class) {
        return strcmp($a_parts[1], $b_parts[1]);
    }
    return $a_class - $b_class;
}
// Fetch data
// Store data in an array
$data = [];
while ($row = $result_santriIzin->fetch_assoc()) {
    $data[] = $row;
}
// Sort data by class
usort($data, function($a, $b) {
    return sortClasses($a['kelas'], $b['kelas']);
});
?>
<!-- HTML Output -->
<div class="col-xl-12">
    <div class="card mb-4">
        <div class="card-header">
            Daftar Santri Izin Hari Ini
            <!-- Button to trigger download -->
            <button type="button "  class="btn btn-primary float-end" name="ownloadBtn" id="downloadBtn" aria-label="ambil gambar"><i class="bi bi-download"></i> PNG</button>
        </div>
        <div class="card-body">
            <div id="captureArea">
                <div class="header-title">
                    <h2>Daftar Santri Izin Hari Ini</h2>
                </div>
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>KELAS</th>
                            <th>KETERANGAN</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>KELAS</th>
                            <th>KETERANGAN</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        // Initialize a counter variable for numbering
                        $counter = 1;
                        // Display sorted data
                        foreach ($data as $row) :
                        ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['kelas']; ?></td>
                                <td><?php echo $row['keterangan']; ?></td>
                            </tr>
                        <?php
                            // Increment the counter
                            $counter++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

            </div>
            <!-- Include updated html2canvas script -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script>
                document.getElementById('downloadBtn').addEventListener('click', function () {
                    // Ensure the capture area is fully rendered
                    setTimeout(function() {
                        html2canvas(document.querySelector('#captureArea')).then(canvas => {
                            // Create a download link for the image
                            var link = document.createElement('a');
                            link.href = canvas.toDataURL('image/png'); // Convert canvas to PNG image data URL
                            link.download = 'santri_izin_hari_ini.png'; // Specify the file name
                            link.click(); // Trigger the download
                        }).catch(function (error) {
                            console.error('Error capturing the table: ', error);
                        });
                    }, 1000); // Adjust the timeout if needed to ensure full rendering
                });
            </script>
            <?php
            require_once "../template/footer.php";
            ?>
