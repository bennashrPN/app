<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
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

$current_day = get_current_day(); // Mendapatkan hari dalam bahasa Indonesia

// Function to calculate total attendance for a given level
function calculate_total_attendance($koneksi, $jenjang, $current_day) {
    $total_kehadiran = 0;

    // Query untuk mendapatkan data pelajaran di jenjang tertentu untuk hari ini
    $query_jadwal_pelajaran = "
        SELECT DISTINCT kelas 
        FROM tbl_jadwalpelajaran 
        WHERE jenjang = ? AND hari = ?
    ";
    $stmt_jadwal_pelajaran = $koneksi->prepare($query_jadwal_pelajaran);
    $stmt_jadwal_pelajaran->bind_param('ss', $jenjang, $current_day);
    $stmt_jadwal_pelajaran->execute();
    $result_jadwal_pelajaran = $stmt_jadwal_pelajaran->get_result();

    while ($row = $result_jadwal_pelajaran->fetch_assoc()) {
        $kelas = $row['kelas'];

        // Hitung jumlah siswa di kelas
        $query_jumlah_siswa = "SELECT COUNT(*) AS jumlah_siswa FROM tbl_siswa WHERE jenjang = ? AND kelas = ?";
        $stmt_jumlah_siswa = $koneksi->prepare($query_jumlah_siswa);
        $stmt_jumlah_siswa->bind_param('ss', $jenjang, $kelas);
        $stmt_jumlah_siswa->execute();
        $result_jumlah_siswa = $stmt_jumlah_siswa->get_result();
        $jumlah_siswa = $result_jumlah_siswa->fetch_assoc()['jumlah_siswa'];

        // Hitung jumlah pelajaran di kelas untuk hari ini
        $query_jumlah_pelajaran = "SELECT COUNT(*) AS jumlah_pelajaran FROM tbl_jadwalpelajaran WHERE jenjang = ? AND kelas = ? AND hari = ?";
        $stmt_jumlah_pelajaran = $koneksi->prepare($query_jumlah_pelajaran);
        $stmt_jumlah_pelajaran->bind_param('sss', $jenjang, $kelas, $current_day);
        $stmt_jumlah_pelajaran->execute();
        $result_jumlah_pelajaran = $stmt_jumlah_pelajaran->get_result();
        $jumlah_pelajaran = $result_jumlah_pelajaran->fetch_assoc()['jumlah_pelajaran'];

        // Hitung total kehadiran untuk kelas ini
        $total_kehadiran += $jumlah_siswa * $jumlah_pelajaran;
    }

    return $total_kehadiran;
}

$total_kehadiran_mts = calculate_total_attendance($koneksi, 'MTS', $current_day);
$total_kehadiran_ma = calculate_total_attendance($koneksi, 'MA', $current_day);

$title = "Total Kehadiran Jenjang MTS dan MA";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<link href="<?= $main_url ?>dashboard/admin.css" rel="stylesheet">
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4" style="font-family: 'Montserrat', sans-serif;">
            <h1 class="mt-4">
                Total Kehadiran Jenjang MTS dan MA
            </h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">
                    <b>Ahlan Wa Sahlan, <span><br>Ustadz</span></b>
                    <span class="gradient-text"> <?php echo $user_data['nama']; ?> </span>
                </li>
            </ol>
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="custom-card mb-4">
                        <p>Total Kehadiran untuk Jenjang MTS pada hari ini:</p>
                        <h2><?php echo $total_kehadiran_mts; ?></h2>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="custom-card mb-4">
                        <p>Total Kehadiran untuk Jenjang MA pada hari ini:</p>
                        <h2><?php echo $total_kehadiran_ma; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once "../template/footer.php"; ?>