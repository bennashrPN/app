<?php
// Memanggil file konfigurasi
require_once "../config.php";

// Memulai sesi
session_start();

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

// Memeriksa apakah ada sesi pengguna
if (isset($_SESSION["ssUser"])) {
    $username = $_SESSION["ssUser"];
    $query_user = "SELECT * FROM tbl_user WHERE username='$username'";
    $result_user = mysqli_query($koneksi, $query_user);

    // Mengambil data pengguna
    $user_data = mysqli_fetch_assoc($result_user);

    // Mendapatkan hari ini dalam bahasa Indonesia
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

        $current_day_in_english = date('l'); // Mendapatkan hari dalam bahasa Inggris
        return $days[$current_day_in_english]; // Mengembalikan hari dalam bahasa Indonesia
    }

    // Mendapatkan hari ini dalam bahasa Indonesia
    $current_day = get_current_day();

    // Mengambil jadwal pelajaran sesuai dengan nama pengguna/guru yang sedang login dan hari ini
    $query_jadwal = "SELECT * FROM tbl_jadwalpelajaran WHERE guru='" . $user_data['nama'] . "' AND hari='" . $current_day . "'";
    $result_jadwal = mysqli_query($koneksi, $query_jadwal);

    // Memeriksa apakah pengguna memiliki jadwal pada hari ini
    if (mysqli_num_rows($result_jadwal) == 0) {
        echo '
        <div class="modal" tabindex="-1" id="noScheduleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Maaf</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Anda tidak memiliki jadwal pada hari ini.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    // Jika tidak ada sesi pengguna, redirect ke halaman login
    header("location:../auth/login.php");
    exit;
}

// Mendapatkan jam saat ini dalam zona waktu "Asia/Jakarta"
date_default_timezone_set("Asia/Jakarta");
$current_time = date("H:i:s"); // Format jam:menit:detik
$current_date = date("d F Y"); // Format tanggal: bulan tahun

// Sekarang kita yakin bahwa pengguna memiliki jadwal pada hari ini, lanjutkan menampilkan halaman seperti biasa
$title = "Dashboard - Markaz Al Matuq";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>
<div id="layoutSidenav_content">
    <main>
        <form method="POST" action="proses-simpan-absensi.php">
            <div class="container-fluid px-4">
                <h1 class="mt-5">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <?php
                    // Menampilkan semua jadwal pelajaran yang sesuai dengan hari login pengguna
                    while ($row = mysqli_fetch_assoc($result_jadwal)) {
                        // Mendapatkan jam saat ini dan jam mulai serta jam selesai jadwal pelajaran
                        $current_timestamp = strtotime($current_time);
                        $start_timestamp = strtotime($row['waktu_mulai']);
                        $end_timestamp = strtotime($row['waktu_selesai']);
                    
                        // Tambahkan toleransi 5 menit ke end_timestamp
                        $end_timestamp += 5 * 60; // Menambahkan 5 menit (300 detik) ke waktu selesai jadwal
                    
                        // Memeriksa apakah waktu saat ini berada dalam rentang waktu jadwal pelajaran (dengan toleransi 5 menit)
                        if ($current_timestamp >= $start_timestamp && $current_timestamp <= $end_timestamp) {
                    ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-default text-secondary mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Ahlan Wa Sahlan,</h5>
                                        Ustadz <?php echo $user_data['nama']; ?>
                                        <!-- Anda bisa menambahkan informasi tambahan tentang pengguna di sini -->
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <div class="card-body"><?php echo $row['kelas'] . ' - ' . $row['pelajaran']; ?></div>
                                        <div class="small text-"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
            ?>
            </div>
            </div>
            </form>
            </main>
            <script>
    // Fungsi ini akan dijalankan saat dokumen selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        <?php
        if (mysqli_num_rows($result_jadwal) == 0) {
            echo '
            // Menampilkan modal jika tidak ada jadwal pada hari tersebut
            const noScheduleModal = new bootstrap.Modal(document.getElementById("noScheduleModal"));
            noScheduleModal.show();';
        } else {
            // Mendapatkan jam saat ini dan jam selesai jadwal pelajaran terakhir
            $row = mysqli_fetch_assoc($result_jadwal);
            $end_timestamp = strtotime($row['waktu_selesai']);
            $end_timestamp += 5 * 60; // Menambahkan 5 menit (300 detik) ke waktu selesai jadwal

            if (strtotime($current_time) > $end_timestamp) {
                echo '
                // Menampilkan modal jika waktu pelajaran sudah berakhir
                const pastScheduleModal = new bootstrap.Modal(document.getElementById("pastScheduleModal"));
                pastScheduleModal.show();';
            }
        }
        ?>
    });
</script>
<?php
require_once "../template/footer.php";
?>
