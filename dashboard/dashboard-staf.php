<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if (isset($_SESSION["ssUser"])) {
    $username = $_SESSION["ssUser"];
    $query_user = "SELECT * FROM tbl_user WHERE username='$username'";
    $result_user = mysqli_query($koneksi, $query_user);
    $user_data = mysqli_fetch_assoc($result_user);
}

date_default_timezone_set('Asia/Jakarta');

// Function to get the current day in Bahasa Indonesia
function get_current_day() {
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    return $days[date('l')];
}

$current_day = get_current_day();
$current_date = date('Y-m-d');

// Attendance categories
$attendance_categories = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
$attendance_counts = array_fill_keys($attendance_categories, 0);

foreach ($attendance_categories as $status) {
    $query = "SELECT COUNT(*) as total FROM tbl_absensi WHERE kehadiran = ? AND tanggal = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('ss', $status, $current_date);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $attendance_counts[$status] = $result['total'];
}

// Attendance details
function get_attendance_details($koneksi, $jenjang, $current_date) {
    $query = "SELECT kehadiran, COUNT(*) as total FROM tbl_absensi WHERE jenjang = ? AND tanggal = ? GROUP BY kehadiran";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('ss', $jenjang, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $attendance = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
    while ($row = $result->fetch_assoc()) {
        $attendance[$row['kehadiran']] = $row['total'];
    }
    return $attendance;
}

$attendance_mts = get_attendance_details($koneksi, 'MTS', $current_date);
$attendance_ma = get_attendance_details($koneksi, 'MA', $current_date);

// Calculate total attendance
function calculate_total_attendance($koneksi, $jenjang, $current_day) {
    $total = 0;

    $query_jadwal = "SELECT DISTINCT kelas FROM tbl_jadwalpelajaran WHERE jenjang = ? AND hari = ?";
    $stmt_jadwal = $koneksi->prepare($query_jadwal);
    $stmt_jadwal->bind_param('ss', $jenjang, $current_day);
    $stmt_jadwal->execute();
    $result_jadwal = $stmt_jadwal->get_result();

    while ($row = $result_jadwal->fetch_assoc()) {
        $kelas = $row['kelas'];

        // Count lessons in class for today
        $query_lessons = "SELECT COUNT(*) AS jumlah_pelajaran FROM tbl_jadwalpelajaran WHERE jenjang = ? AND kelas = ? AND hari = ?";
        $stmt_lessons = $koneksi->prepare($query_lessons);
        $stmt_lessons->bind_param('sss', $jenjang, $kelas, $current_day);
        $stmt_lessons->execute();
        $jumlah_pelajaran = $stmt_lessons->get_result()->fetch_assoc()['jumlah_pelajaran'];

        // Count students in class
        $query_students = "SELECT COUNT(*) AS jumlah_siswa FROM tbl_siswa WHERE jenjang = ? AND kelas = ?";
        $stmt_students = $koneksi->prepare($query_students);
        $stmt_students->bind_param('ss', $jenjang, $kelas);
        $stmt_students->execute();
        $jumlah_siswa = $stmt_students->get_result()->fetch_assoc()['jumlah_siswa'];

        // Calculate total attendance
        $total += $jumlah_siswa * $jumlah_pelajaran;
    }

    return $total;
}

$total_kehadiran_mts = calculate_total_attendance($koneksi, 'MTS', $current_day);
$total_kehadiran_ma = calculate_total_attendance($koneksi, 'MA', $current_day);

// Define students present and absent based on total attendance
$jumlah_siswa_mts_masuk = $attendance_mts['Hadir'];
$jumlah_siswa_mts_belum_masuk = $total_kehadiran_mts - $jumlah_siswa_mts_masuk;

$jumlah_siswa_ma_masuk = $attendance_ma['Hadir'];
$jumlah_siswa_ma_belum_masuk = $total_kehadiran_ma - $jumlah_siswa_ma_masuk;


// Query untuk mengambil data guru yang mengajar pada hari ini
$query = "SELECT * FROM tbl_jadwalpelajaran WHERE hari = ?"; // Query dimodifikasi untuk mendapatkan data hanya untuk hari ini
$stmt = $koneksi->prepare($query);
$stmt->bind_param('s', $current_day);
$stmt->execute();
$result_jadwalHariIni = $stmt->get_result();

$title = "Dashboard - Admin";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<link href="<?= $main_url ?>dashboard/admin.css" rel="stylesheet">
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4" style="font-family: 'Montserrat', sans-serif;">
            <h1 class="mt-4">Dashboard Admin</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">
                    <b>Ahlan Wa Sahlan, <span><br>Ustadz</span></b>
                    <span class="gradient-text"><?php echo $user_data['nama']; ?></span>
                    <p>Berikut Informasi Hari Ini, <br><?php echo $current_day; ?>, <?php echo $current_date; ?></p>
                </li>
            </ol>
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="custom-card d-flex align-items-center mb-4">
                        <div class="me-4 mts-info">
                            <p class="student-info">Total Kehadiran <strong>MTS</strong></p>
                            <p class="big-number"><?php echo $total_kehadiran_mts; ?></p>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 flex-column-adjust">
                            <div class="d-flex flex-nowrap mb-2">
                                <a href="<?=$main_url?>/kehadiran/santri-hadir.php" style="text-decoration: none;" class="status-pill text-white me-2 mb-2">
                                    <?php echo $attendance_mts['Hadir']; ?> Hadir <i class="bi bi-check-circle-fill"></i>
                                </a>
                                <a href="<?=$main_url?>/kehadiran/santri-izin.php" style="text-decoration: none;" class="status-pill text-white me-2 mb-2">
                                    <?php echo $attendance_mts['Izin']; ?> Izin <i class="bi bi-info-circle-fill"></i>
                                </a>
                                <a href="<?=$main_url?>/kehadiran/santri-sakit.php" style="text-decoration: none;" class="status-pill text-white me-2 mb-2">
                                    <?php echo $attendance_mts['Sakit']; ?> Sakit <i class="bi bi-exclamation-circle-fill"></i>
                                </a>
                                <a href="<?=$main_url?>/kehadiran/santri-alpha.php" style="text-decoration: none;" class="status-pill text-white mb-2">
                                    <?php echo $attendance_mts['Alpha']; ?> Alpha <i class="bi bi-x-circle-fill"></i>
                                </a>
                            </div>
                            <div class="d-flex flex-nowrap">
                                <div class="me-4 d-flex align-items-center mb-2 absen-info">
                                    <i class="bi bi-check-circle-fill icon-success-custom-color"></i>
                                    <div class="ms-4">
                                        <p class="m-0">Sudah Presensi <i class="bi bi-caret-down-fill"></i></p>
                                        <p class="sudahabsen-info"><strong class="fs-3"><?php echo $jumlah_siswa_mts_masuk; ?></strong></p>
                                    </div>
                                </div>
                                <div class="me-4 d-flex align-items-center mb-2 absen-info margin-right-adjust">
                                    <i class="bi bi-x-circle-fill icon-danger-custom-color"></i>
                                    <div class="ms-4">
                                        <p class="m-0">Belum Presensi <i class="bi bi-caret-down-fill"></i></p>
                                        <p class="m-0 belumabsen-info"><strong class="fs-3"><?php echo $jumlah_siswa_mts_belum_masuk; ?></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="custom-card d-flex align-items-center mb-4">
                        <div class="me-4 ma-info">
                            <p class="student-info">Total Kehadiran <strong>MA</strong></p>
                            <p class="big-number"><?php echo $total_kehadiran_ma; ?></p>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 flex-column-adjust">
                            <div class="d-flex flex-nowrap mb-2">
                                <a href="<?=$main_url?>/kehadiran/santri-hadir.php" style="text-decoration: none;" class="status-pill text-white me-2 mb-2">
                                    <?php echo $attendance_ma['Hadir']; ?> Hadir <i class="bi bi-check-circle-fill"></i>
                                </a>
                                <a href="<?=$main_url?>/kehadiran/santri-izin.php" style="text-decoration: none;" class="status-pill text-white me-2 mb-2">
                                    <?php echo $attendance_ma['Izin']; ?> Izin <i class="bi bi-info-circle-fill"></i>
                                </a>
                                <a href="<?=$main_url?>/kehadiran/santri-sakit.php" style="text-decoration: none;" class="status-pill text-white me-2 mb-2">
                                    <?php echo $attendance_ma['Sakit']; ?> Sakit <i class="bi bi-exclamation-circle-fill"></i>
                                </a>
                                <a href="<?=$main_url?>/kehadiran/santri-alpha.php" style="text-decoration: none;" class="status-pill text-white mb-2">
                                    <?php echo $attendance_ma['Alpha']; ?> Alpha <i class="bi bi-x-circle-fill"></i>
                                </a>
                            </div>
                            <div class="d-flex flex-nowrap">
                                <div class="me-4 d-flex align-items-center mb-2 absen-info">
                                    <i class="bi bi-check-circle-fill icon-success-custom-color"></i>
                                    <div class="ms-4">
                                        <p class="m-0">Sudah Presensi <i class="bi bi-caret-down-fill"></i></p>
                                        <p class="sudahabsen-info"><strong class="fs-3"><?php echo $jumlah_siswa_ma_masuk; ?></strong></p>
                                    </div>
                                </div>
                                <div class="me-4 d-flex align-items-center mb-2 absen-info margin-right-adjust">
                                    <i class="bi bi-x-circle-fill icon-danger-custom-color"></i>
                                    <div class="ms-4">
                                        <p class="m-0">Belum Presensi <i class="bi bi-caret-down-fill"></i></p>
                                        <p class="belumabsen-info"><strong class="fs-3"><?php echo $jumlah_siswa_ma_belum_masuk; ?></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <b style="font-size: 15px;" >MTS Belum Presensi</b> <a href="mtsbelumabsen.php" class="btn btn-sm btn-info float-end text-white" name="ambilPdfMts" id="ambilPdfMts" aria-label="ambil pdf"><i class="bi bi-download"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="scrollable-list">
                                <?php
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
                                        $kelas = $row['kelas'];
                                        $pelajaran = $row['pelajaran'];
                                        $guru = $row['guru'];
                                        $jam = $row['jam'];

                                        echo '<div class="me-4 d-flex align-items-center mb-2 mts-belum-absen-info">';
                                        echo '<i class="bi bi-x-circle-fill" style="color: #dc3545;"></i>';
                                        echo '<div class="ms-4">';
                                        echo '<div class="fw-bold"> Kelas ' . $kelas . '</div>';
                                        echo 'Pelajaran ' . $pelajaran . ' Jam Ke ' . $jam . ',' . ' Ustadz ' . $guru . ' .';
                                        echo '</div>';
                                        echo '</div>';

                                        // Set unabsented_found menjadi true karena ada pelajaran yang belum diabsen
                                        $unabsented_found = true;
                                    }

                                    // Menampilkan pesan jika semua pelajaran sudah diabsen
                                    if (!$unabsented_found) {
                                        echo '<div class="alert alert-warning" role="alert">Semua pelajaran hari ini sudah diabsen.</div>';
                                    }
                                } else {
                                    // Menampilkan pesan jika tidak ada jadwal pelajaran hari ini
                                    echo '<div class="alert alert-info" role="alert">Tidak ada jadwal pelajaran hari ini.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <b style="font-size: 15px;" >MA Belum Presensi</b> <a href="mabelumabsen.php" class="btn btn-sm btn-info float-end text-white mb-0" name="ambilPdfMa" id="ambilPdfMa" aria-label="ambil pdf"><i class="bi bi-download"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="scrollable-list">
                                <?php
                                // Query untuk mengecek apakah ada jadwal pelajaran pada hari ini
                                $query_jadwal = "SELECT jp.kelas, jp.pelajaran, jp.guru, jp.jam
                         FROM tbl_jadwalpelajaran jp
                         WHERE jp.hari = ? AND jp.jenjang = 'MA'";
                                $stmt_jadwal = $koneksi->prepare($query_jadwal);
                                $stmt_jadwal->bind_param('s', $current_day);
                                $stmt_jadwal->execute();
                                $result_jadwal = $stmt_jadwal->get_result();

                                $jadwal_found = $result_jadwal->num_rows > 0;
                                $unabsented_found = false;

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
                      WHERE jp.hari = ? AND jp.jenjang = 'MA' AND abs.hari IS NULL";
                                    $stmt = $koneksi->prepare($query);
                                    $stmt->bind_param('s', $current_day);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($row = $result->fetch_assoc()) {
                                        $kelas = $row['kelas'];
                                        $pelajaran = $row['pelajaran'];
                                        $guru = $row['guru'];
                                        $jam = $row['jam'];

                                        echo '<div class="me-4 d-flex align-items-center mb-2 mts-belum-absen-info">';
                                        echo '<i class="bi bi-x-circle-fill" style="color: #dc3545;"></i>';
                                        echo '<div class="ms-4">';
                                        echo '<div class="fw-bold"> Kelas ' . $kelas . '</div>';
                                        echo 'Pelajaran ' . $pelajaran . ' Jam Ke ' . $jam .','. ' Ustadz ' . $guru . '.';
                                        echo '</div>';
                                        echo '</div>';

                                        // Set unabsented_found menjadi true karena ada pelajaran yang belum diabsen
                                        $unabsented_found = true;
                                    }

                                    // Menampilkan pesan jika semua pelajaran sudah diabsen
                                    if (!$unabsented_found) {
                                        echo '<div class="alert alert-warning" role="alert">Semua pelajaran hari ini sudah diabsen.</div>';
                                    }
                                } else {
                                    // Menampilkan pesan jika tidak ada jadwal pelajaran hari ini
                                    echo '<div class="alert alert-info" role="alert">Tidak ada jadwal pelajaran hari ini.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        Daftar Guru Yang Mengajar <br>Hari Ini
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                        <table class="table table-hover" id="datatablesSimple">
                                <thead>
                                    <tr>
                                    <th scope="col">
                                    <center>No<center>
                                </th>
                                <th scope="col">
                                    <center>Nama<center>
                                </th>
                                <th scope="col">
                                    <center>PELAJARAN<center>
                                </th>
                                <th scope="col">
                                    <center>KELAS<center>
                                </th>
                                <th scope="col">
                                    <center>WAKTU<center>
                                </th>
                                <th scope="col">
                                    <center>JAM<center>
                                </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Initialize a counter variable for numbering
                                    $counter = 1;
                                    // Iterate through the fetched data and display it
                                    while ($row = $result_jadwalHariIni->fetch_assoc()) :
                                    ?>
                                        <tr>
                                            <td><?php echo $counter; ?></td>
                                            <td><?php echo $row['guru']; ?></td>
                                            <td><?php echo $row['pelajaran']; ?></td>
                                            <td><?php echo $row['kelas']; ?></td>
                                            <td><?php echo $row['waktu_mulai'] . ' - ' . $row['waktu_selesai']; ?></td>
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
