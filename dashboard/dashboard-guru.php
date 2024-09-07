<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}
$title = "Dashboard-Guru";
require_once "../config.php";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";



if (isset($_SESSION["ssUser"])) {
    $username = $_SESSION["ssUser"];

    // Use prepared statements for user data retrieval
    $stmt = $koneksi->prepare("SELECT * FROM tbl_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_user = $stmt->get_result();
    $user_data = $result_user->fetch_assoc();

    // Set timezone and get current time and date
    date_default_timezone_set("Asia/Jakarta");
    $current_time = date("H:i:s");
    $current_date = date("d F Y");

    // Function to get current day in Indonesian
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
        return $days[date('l')];
    }
    $current_day = get_current_day();

    // Initialize variables
    $schedules = [];

    // Check if the user is a substitute teacher
    $stmt = $koneksi->prepare("
        SELECT ig.guruIzin
        FROM tbl_izinguru ig
        WHERE ig.guruGanti = ? AND ig.status = 'aktif' AND ig.hari = ?
    ");
    $stmt->bind_param("ss", $user_data['nama'], $current_day);
    $stmt->execute();
    $result_guru_ganti = $stmt->get_result();

    if ($result_guru_ganti->num_rows > 0) {
        while ($izin = $result_guru_ganti->fetch_assoc()) {
            $guruIzin = $izin['guruIzin'];

            // Fetch schedules for each guruIzin
            $stmt = $koneksi->prepare("
                SELECT jp.*, ig.waktu_mulai, ig.waktu_selesai
                FROM tbl_jadwalpelajaran jp
                JOIN tbl_izinguru ig ON jp.guru = ig.guruIzin
                WHERE jp.guru = ? AND jp.hari = ? AND ig.hari = ? AND jp.waktu_mulai >= ig.waktu_mulai AND jp.waktu_selesai <= ig.waktu_selesai
            ");
            $stmt->bind_param("sss", $guruIzin, $current_day, $current_day);
            $stmt->execute();
            $result_jadwal_izin = $stmt->get_result();

            while ($jadwal = $result_jadwal_izin->fetch_assoc()) {
                $jadwal['guruIzin'] = $guruIzin;
                $schedules[] = $jadwal;
            }
        }

        // Fetch schedules for guruGanti if they have their own schedule
        $stmt = $koneksi->prepare("
            SELECT jp.*
            FROM tbl_jadwalpelajaran jp
            WHERE jp.guru = ? AND jp.hari = ?
        ");
        $stmt->bind_param("ss", $user_data['nama'], $current_day);
        $stmt->execute();
        $result_jadwal_guru_ganti = $stmt->get_result();

        while ($jadwal_guru_ganti = $result_jadwal_guru_ganti->fetch_assoc()) {
            $schedules[] = $jadwal_guru_ganti;
        }
    }

    // Query to get today's schedule including classes
    $stmt = $koneksi->prepare("
        SELECT jam, waktu, pelajaran, kelas
        FROM tbl_jadwalpelajaran
        WHERE guru = ? AND hari = ?
    ");
    $stmt->bind_param("ss", $user_data['nama'], $current_day);
    $stmt->execute();
    $result_jadwal = $stmt->get_result();

    // Query to get the entire semester's schedule for the logged-in teacher
    $stmt = $koneksi->prepare("
        SELECT * FROM tbl_jadwalpelajaran
        WHERE guru = ?
    ");
    $stmt->bind_param("s", $user_data['nama']);
    $stmt->execute();
    $result_jadwal_semester = $stmt->get_result();
} else {
    header("location:../auth/login.php");
    exit;
}
?>


<link href="<?= $main_url ?>dashboard/guru.css" rel="stylesheet">
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard Ustadz <?php echo $user_data['nama']; ?></li>
            </ol>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-default text-default mb-4">
                        <div class="card-body bg-default">
                            <div class="row">
                                <div class="col-4">
                                    <img src="../asset/image/<?= $user_data['foto'] ?>" alt="Foto Ustadz" class="img-fluid rounded">
                                </div>
                                <div class="col-8">
                                    <h8 class="card-title">Ahlan Wa Sahlan, Ustadz </h8>
                                    <span class="gradient-text"> <br><?php echo $user_data['nama']; ?></span>
                                    <span class="text-muted"> <br><?php echo $user_data['jabatan']; ?></span>
                                    <br>
                                    <!-- Radio button for online status -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input small-radio" type="radio" name="status" id="onlineStatus" value="online" checked>
                                        <label class="form-check-label small-radio-label" for="onlineStatus">
                                            Online
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-default text-default mb-4 border-0 shadow-lg-sm">
                        <div class="card-body bg-default p-3">
                            <div class="row">
                                <div class="col-12">
                                    <?php
                                    // Query to get lessons per week
                                    $query_pelajaran_per_minggu = "
                    SELECT COUNT(*) AS pelajaran_per_minggu
                    FROM tbl_jadwalpelajaran
                    WHERE guru = '" . mysqli_real_escape_string($koneksi, $user_data['nama']) . "'";

                                    $result_pelajaran_per_minggu = mysqli_query($koneksi, $query_pelajaran_per_minggu);
                                    $row_pelajaran_per_minggu = mysqli_fetch_assoc($result_pelajaran_per_minggu);
                                    $pelajaran_per_minggu = $row_pelajaran_per_minggu['pelajaran_per_minggu'];

                                    // Calculate total lessons for a month
                                    $jumlah_minggu_satu_bulan = 4;
                                    $total_pelajaran_satu_bulan = $pelajaran_per_minggu * $jumlah_minggu_satu_bulan;

                                    // Query to get attendance count
                                    $query_absensi = "
                    SELECT COUNT(DISTINCT pelajaran, kelas) AS absensi_pelajaran
                    FROM tbl_absensi
                    WHERE guru = '" . mysqli_real_escape_string($koneksi, $user_data['nama']) . "'
                    AND DATE(tanggal) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()";

                                    $result_absensi = mysqli_query($koneksi, $query_absensi);
                                    $row_absensi = mysqli_fetch_assoc($result_absensi);
                                    $absensi_pelajaran = $row_absensi['absensi_pelajaran'];

                                    // Calculate attendance percentage
                                    if ($total_pelajaran_satu_bulan > 0) {
                                        $percentage = ($absensi_pelajaran / $total_pelajaran_satu_bulan) * 100;
                                    } else {
                                        $percentage = 0;
                                    }

                                    // Format percentage to two decimal places
                                    $formatted_percentage = number_format($percentage, 2);
                                    ?>
                                    <div class="progress-container">
                                        <div class="progress-label mb-2">
                                            <span>Hudhuur Progress</span>
                                            <span><?php echo $formatted_percentage; ?>%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: <?php echo $formatted_percentage; ?>%;"></div>
                                        </div>
                                    </div>
                                    Indikator dari database presensi siswa
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Progress Card -->

                <!-- Schedule Cards -->
                <div class="row">
    <?php
    $displayed_schedules = []; // Array untuk melacak jadwal yang telah ditampilkan

    if (!empty($schedules)):
        foreach ($schedules as $index => $schedule):
            $key = $schedule['kelas'] . '-' . $schedule['jam'] . '-' . $schedule['pelajaran'];
            
            // Cek jika jadwal sudah ditampilkan
            if (in_array($key, $displayed_schedules)) {
                continue; // Skip jika sudah ditampilkan
            }
            
            // Tandai jadwal sebagai telah ditampilkan
            $displayed_schedules[] = $key;

            $tanggal_hari_ini = date('Y-m-d');
            $current_teacher = $user_data['nama'];
            $guruIzin = isset($schedule['guruIzin']) ? $schedule['guruIzin'] : '';

            // Query untuk mengecek status kehadiran
            $query_absen = "SELECT * FROM tbl_absensi 
                WHERE (guru = '" . mysqli_real_escape_string($koneksi, $current_teacher) . "' 
                OR guru = '" . mysqli_real_escape_string($koneksi, $guruIzin) . "')
                AND hari = '$current_day' 
                AND kelas = '" . mysqli_real_escape_string($koneksi, $schedule['kelas']) . "' 
                AND jam = '" . mysqli_real_escape_string($koneksi, $schedule['jam']) . "' 
                AND pelajaran = '" . mysqli_real_escape_string($koneksi, $schedule['pelajaran']) . "'
                AND tanggal = '$tanggal_hari_ini'";

            $result_absen = mysqli_query($koneksi, $query_absen);
            $absen_data = mysqli_fetch_assoc($result_absen);
            $status_absen = $absen_data ? "1" : "2"; // "1" untuk Hadir, "2" untuk Tidak Hadir

            // Query untuk mendapatkan waktu_mulai dan waktu_selesai
            $query_waktu = "SELECT waktu_mulai, waktu_selesai 
                FROM tbl_jadwalpelajaran 
                WHERE guru = '" . mysqli_real_escape_string($koneksi, $guruIzin) . "' 
                AND kelas = '" . mysqli_real_escape_string($koneksi, $schedule['kelas']) . "' 
                AND jam = '" . mysqli_real_escape_string($koneksi, $schedule['jam']) . "' 
                LIMIT 1";

            $result_waktu = mysqli_query($koneksi, $query_waktu);
            $waktu_data = mysqli_fetch_assoc($result_waktu);

            $waktu_mulai = $waktu_data['waktu_mulai'] ?? $schedule['waktu_mulai'];
            $waktu_selesai = $waktu_data['waktu_selesai'] ?? $schedule['waktu_selesai'];
    ?>
            <?php if ($guruIzin): ?>
                <div class="col-xl-12 col-md-10">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-school-circle-check"></i>
                            <span class="h6 my-2"><b>Kelas <?php echo $schedule['kelas']; ?></b></span>
                            <a href="<?= $main_url ?>autoabsensi/autoabsensi.php" class="btn btn-sm btn-success float-end me-2" onclick="return checkAndRedirect();">
                                <i class="fa-solid fa-circle-plus"></i> Presensi
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-lg-auto g-3 align-items-center">
                                <div class="col-4">
                                    <label class="visually-hidden" for="inlineFormInputGroupJam_<?php echo $index; ?>">Jam</label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                        <input type="text" class="form-control" id="inlineFormInputGroupJam_<?php echo $index; ?>" placeholder="Jam" value="<?php echo $schedule['jam']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <label class="visually-hidden" for="inlineFormInputGroupWaktu_<?php echo $index; ?>">Waktu</label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fa-solid fa-clock"></i></div>
                                        <input type="text" class="form-control" id="inlineFormInputGroupWaktu_<?php echo $index; ?>" placeholder="Waktu" value="<?php echo date('H:i', strtotime($waktu_mulai)) . " - " . date('H:i', strtotime($waktu_selesai)); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="visually-hidden" for="inlineFormInputGroupPelajaran_<?php echo $index; ?>">Pelajaran</label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fa-solid fa-book"></i></div>
                                        <input type="text" class="form-control" id="inlineFormInputGroupPelajaran_<?php echo $index; ?>" placeholder="Pelajaran" value="<?php echo $schedule['pelajaran']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="visually-hidden" for="inlineFormInputGroupGuru_<?php echo $index; ?>">Guru Izin</label>
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fa-solid fa-user"></i></div>
                                        <input type="text" class="form-control" id="inlineFormInputGroupGuru_<?php echo $index; ?>" placeholder="Guru Izin" value="<?php echo $guruIzin; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <label class="visually-hidden" for="inlineFormSelectStatus_<?php echo $index; ?>">Status</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="statusIcon">
                                            <?php if ($status_absen === "1") { ?>
                                                <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                            <?php } elseif ($status_absen === "2") { ?>
                                                <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                            <?php } ?>
                                        </div>
                                        <select class="form-select" id="inlineFormSelectStatus" onchange="setStatusColor(this); setStatusIcon(this);" aria-label="Default select example">
                                            <option value="1" <?php echo ($status_absen === "1" ? 'selected' : ''); ?>>Sudah</option>
                                            <option value="2" <?php echo ($status_absen === "2" ? 'selected' : ''); ?>>Belum</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
            Tidak ada jadwal penggantian pelajaran untuk hari ini.
            </div>
        </div>
    <?php endif; ?>
</div>



            </div>
            <div class="row">
                <!-- Loop to display today's class schedule -->
                <?php
                $index = 1;
                while ($row_jadwal = mysqli_fetch_assoc($result_jadwal)) {
                    $jam_hari_ini = $row_jadwal['jam'];
                    $waktu_hari_ini = $row_jadwal['waktu'];
                    $pelajaran_hari_ini = $row_jadwal['pelajaran'];
                    $kelas_hari_ini = $row_jadwal['kelas'];
                    $tanggal_hari_ini = date('Y-m-d');

                    // Query to check attendance status
                    $query_absen = "SELECT * FROM tbl_absensi 
                    WHERE guru = '" . mysqli_real_escape_string($koneksi, $user_data['nama']) . "' 
                    AND hari = '$current_day' 
                    AND kelas = '" . mysqli_real_escape_string($koneksi, $kelas_hari_ini) . "' 
                    AND jam = '" . mysqli_real_escape_string($koneksi, $jam_hari_ini) . "' 
                    AND pelajaran = '" . mysqli_real_escape_string($koneksi, $pelajaran_hari_ini) . "'
                    AND tanggal = '$tanggal_hari_ini'";

                    $result_absen = mysqli_query($koneksi, $query_absen);
                    $absen_data = mysqli_fetch_assoc($result_absen);

                    $id_jam = 'inlineFormInputGroupJam_' . $index;
                    $id_waktu = 'inlineFormInputGroupWaktu_' . $index;
                    $id_pelajaran = 'inlineFormInputGroupPelajaran_' . $index;

                    $status_absen = $absen_data ? "1" : "2"; // "1" for Present, "2" for Absent
                ?>
                    <div class="col-xl-12 col-md-10">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-school-circle-check"></i>
                                <span class="h6 my-2"> <b>Kelas <?php echo $kelas_hari_ini; ?></b></span>
                                <a href="<?= $main_url ?>autoabsensi/autoabsensi.php" class="btn btn-sm btn-success float-end me-2" onclick="return checkAndRedirect();">
                                    <i class="fa-solid fa-circle-plus"></i> Presensi
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-lg-auto g-3 align-items-center">
                                    <div class="col-4">
                                        <label class="visually-hidden" for="inlineFormInputGroupJam_<?php echo $index; ?>">Jam</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="fa-regular fa-clock"></i></div>
                                            <input type="text" class="form-control" id="<?php echo $id_jam; ?>" placeholder="Jam" value="<?php echo $jam_hari_ini; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <label class="visually-hidden" for="inlineFormInputGroupWaktu_<?php echo $index; ?>">Waktu</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="fa-solid fa-clock"></i></div>
                                            <input type="text" class="form-control" id="<?php echo $id_waktu; ?>" placeholder="Waktu" value="<?php echo $waktu_hari_ini; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="visually-hidden" for="inlineFormInputGroupPelajaran_<?php echo $index; ?>">Pelajaran</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="fa-solid fa-book"></i></div>
                                            <input type="text" class="form-control" id="<?php echo $id_pelajaran; ?>" placeholder="Pelajaran" value="<?php echo $pelajaran_hari_ini; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <label class="visually-hidden" for="inlineFormSelectStatus_<?php echo $index; ?>">Status</label>
                                        <div class="input-group">
                                            <div class="input-group-text" id="statusIcon">
                                                <?php if ($status_absen === "1") { ?>
                                                    <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                                <?php } elseif ($status_absen === "2") { ?>
                                                    <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                                <?php } ?>
                                            </div>
                                            <select class="form-select" id="inlineFormSelectStatus" onchange="setStatusColor(this); setStatusIcon(this);" aria-label="Default select example">
                                                <option value="1" <?php echo ($status_absen === "1" ? 'selected' : ''); ?>>Sudah</option>
                                                <option value="2" <?php echo ($status_absen === "2" ? 'selected' : ''); ?>>Belum</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $index++;
                } // End while loop
                ?>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Semester Gasal Tahun Pelajaran 1445-1446 H / 2024-2025 M
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>HARI</th>
                                <th>JAM</th>
                                <th>WAKTU</th>
                                <th>KELAS</th>
                                <th>PELAJARAN</th>
                                <th>JENJANG</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>NO</th>
                                <th>HARI</th>
                                <th>JAM</th>
                                <th>WAKTU</th>
                                <th>KELAS</th>
                                <th>PELAJARAN</th>
                                <th>JENJANG</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result_jadwal_semester)) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row['hari'] . "</td>";
                                echo "<td>" . $row['jam'] . "</td>";
                                echo "<td>" . $row['waktu'] . "</td>";
                                echo "<td>" . $row['kelas'] . "</td>";
                                echo "<td>" . $row['pelajaran'] . "</td>";
                                echo "<td>" . $row['jenjang'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <a href="generate_pdf.php" class="btn btn-sm btn-primary float-end" id="generate_pdf"><i class="fa-solid fa-download"></i> Download</a>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.querySelectorAll("#inlineFormSelectStatus").forEach(selectElement => {
            selectElement.addEventListener("change", function() {
                setStatusColor(selectElement);
                setStatusIcon(selectElement);
            });

            setStatusColor(selectElement);
            setStatusIcon(selectElement);
        });

        function setStatusColor(selectElement) {
            const value = selectElement.value;
            if (value === "1") {
                selectElement.style.backgroundColor = "green";
                selectElement.style.color = "white";
            } else if (value === "2") {
                selectElement.style.backgroundColor = "red";
                selectElement.style.color = "white";
            } else {
                selectElement.style.backgroundColor = "";
                selectElement.style.color = "";
            }
        }

        function setStatusIcon(selectElement) {
            const selectValue = selectElement.value;
            const statusIcon = selectElement.closest('.input-group').querySelector('#statusIcon');

            if (selectValue === "1") {
                statusIcon.innerHTML = '<i class="fa-solid fa-circle-check" style="color: green;"></i>';
            } else if (selectValue === "2") {
                statusIcon.innerHTML = '<i class="fa-solid fa-circle-xmark" style="color: red;"></i>';
            } else {
                statusIcon.innerHTML = '';
            }
        }
    </script>

    <?php
    require_once  "../template/footer.php";
    ?>