<?php
require_once "../config.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

$title = "Dashboard - Absensi";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

date_default_timezone_set('Asia/Jakarta');
$user = mysqli_real_escape_string($koneksi, $_SESSION["ssUser"]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpanData'])) {
    // Escape POST data
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelasAbsensi']);
    $jenjang = mysqli_real_escape_string($koneksi, $_POST['jenjangAbsensi']);
    $pengajar = mysqli_real_escape_string($koneksi, $_POST['inlineFormInputGroupPengajar']);
    $pelajaran = mysqli_real_escape_string($koneksi, $_POST['inlineFormInputGroupPelajaran']);
    $jam = mysqli_real_escape_string($koneksi, $_POST['jamAbsensi']);
    $tanggal = date("Y-m-d");
    $waktu_absen = date('H:i:s');

    $hari = date("l");
    $hari = match ($hari) {
        'Monday'    => "Senin",
        'Tuesday'   => "Selasa",
        'Wednesday' => "Rabu",
        'Thursday'  => "Kamis",
        'Friday'    => "Jumat",
        'Saturday'  => "Sabtu",
        'Sunday'    => "Minggu",
        default     => "Hari Tidak Diketahui",
    };

    $waktu_mulai = mysqli_real_escape_string($koneksi, date('H:i:s', strtotime($_POST['inlineFormInputGroupWaktuMulai'])));
    $waktu_selesai = mysqli_real_escape_string($koneksi, date('H:i:s', strtotime($_POST['inlineFormInputGroupWaktuSelesai'])));
    $waktu = "$waktu_mulai - $waktu_selesai";
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Handle data from Form 1
    $materiIdForm1 = isset($_POST['materiPembelajaranForm1']) ? mysqli_real_escape_string($koneksi, $_POST['materiPembelajaranForm1']) : '';
    $kegiatanPembelajaranForm1 = mysqli_real_escape_string($koneksi, $_POST['kegiatanPembelajaranForm1']);

    // Handle data from Form 2
    $materiPembelajaranForm2 = mysqli_real_escape_string($koneksi, $_POST['materiPembelajaranForm2']);
    $kegiatanPembelajaranForm2 = mysqli_real_escape_string($koneksi, $_POST['kegiatanPembelajaranForm2']);

    // Insert data into tbl_absensi
    $query_check = "SELECT COUNT(*) as count FROM tbl_absensi 
                    WHERE kelas='$kelas' AND guru='$pengajar' AND pelajaran='$pelajaran' 
                    AND jenjang='$jenjang' AND hari='$hari' AND jam='$jam' AND tanggal='$tanggal'";
    $result_check = mysqli_query($koneksi, $query_check);

    if (!$result_check) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error in checking attendance data.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                });
              </script>";
        exit;
    }

    $row_check = mysqli_fetch_assoc($result_check);
    if ($row_check['count'] > 0) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Data absensi sudah ada.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                });
              </script>";
        exit;
    }

    // Fetch students for the given class
    $query_siswa = "SELECT nama FROM tbl_siswa WHERE kelas='$kelas'";
    $result_siswa = mysqli_query($koneksi, $query_siswa);

    if (!$result_siswa) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error in fetching students data.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                });
              </script>";
        exit;
    }

    while ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
        $siswa = mysqli_real_escape_string($koneksi, $row_siswa['nama']);
        $kehadiran = isset($_POST['kehadiran'][$siswa]) ? mysqli_real_escape_string($koneksi, $_POST['kehadiran'][$siswa]) : 'Hadir';

        $query_insert_absensi = "INSERT INTO tbl_absensi (nama, kelas, guru, pelajaran, kehadiran, jenjang, hari, jam, waktu, waktu_absen, tanggal, user) 
                                VALUES ('$siswa', '$kelas', '$pengajar', '$pelajaran', '$kehadiran', '$jenjang', '$hari', '$jam', '$waktu', '$waktu_absen', '$tanggal', '$user')";
        if (!mysqli_query($koneksi, $query_insert_absensi)) {
            echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Error in inserting attendance data.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                    });
                  </script>";
            exit;
        }
    }

    // Insert data from Form 1 into tbl_kegiatanpembelajaran if Form 1 is filled out
    if (!empty($materiIdForm1) && !empty($kegiatanPembelajaranForm1)) {
        // Fetch materi_pembelajaran based on selected ID from Form 1
        $query_materi = "SELECT materi_pembelajaran FROM tbl_dataakp WHERE id='$materiIdForm1'";
        $result_materi = mysqli_query($koneksi, $query_materi);

        if ($result_materi && $row_materi = mysqli_fetch_assoc($result_materi)) {
            $materiPembelajaran = $row_materi['materi_pembelajaran'];
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Materi Pembelajaran tidak ditemukan.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                    });
                  </script>";
            exit;
        }

        $query_insert_dataakp_form1 = "INSERT INTO tbl_kegiatanpembelajaran (guru, pelajaran, materiPembelajaran, kegiatanPembelajaran, keterangan, kelas, hari, tanggal, updateData)
                                       VALUES ('$pengajar', '$pelajaran', '$materiPembelajaran', '$kegiatanPembelajaranForm1', '$keterangan', '$kelas', '$hari', '$tanggal', NOW())";
        if (!mysqli_query($koneksi, $query_insert_dataakp_form1)) {
            echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Error in inserting data from Form 1 into tbl_kegiatanpembelajaran.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                    });
                  </script>";
            exit;
        }
    }

    // Insert data from Form 2 into tbl_kegiatanpembelajaran if Form 2 is filled out
    if (!empty($materiPembelajaranForm2) && !empty($kegiatanPembelajaranForm2)) {
        $query_insert_dataakp_form2 = "INSERT INTO tbl_kegiatanpembelajaran (guru, pelajaran, materiPembelajaran, kegiatanPembelajaran, keterangan, kelas, hari, tanggal, updateData)
                                       VALUES ('$pengajar', '$pelajaran', '$materiPembelajaranForm2', '$kegiatanPembelajaranForm2', '$keterangan', '$kelas', '$hari', '$tanggal', NOW())";
        if (!mysqli_query($koneksi, $query_insert_dataakp_form2)) {
            echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Error in inserting data from Form 2 into tbl_kegiatanpembelajaran.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
                    });
                  </script>";
            exit;
        }
    }

    // If everything is successful
    echo "<script>
            Swal.fire({
                title: 'Success',
                text: 'Data berhasil disimpan.',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                window.location.href = '" . htmlspecialchars($main_url) . "dashboard/dashboard-" . strtolower(htmlspecialchars($_SESSION['role'])) . ".php';
            });
          </script>";
    exit;
}
?>
