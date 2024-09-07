
<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Konfigurasi database
require_once "../config.php";

$title = "Absensi";
require_once "../template/header.php";
require_once "../template/sidebar.php";
require_once "../template/navbar.php";
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Absensi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Absensi Siswa</li>
            </ol>
            <!-- Toast -->
              <!-- Toast -->
              <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 10000;">
                <?php
                // Tampilkan toast untuk pesan sukses
                if (isset($_SESSION['success'])) {
                    echo '
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-success text-white">
                            <strong class="mr-auto">Sukses</strong>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            ' . $_SESSION['success'] . '
                        </div>
                    </div>
                    ';
                    // Hapus pesan sukses setelah ditampilkan
                    unset($_SESSION['success']);
                }

                // Tampilkan toast untuk pesan error
                if (isset($_SESSION['error'])) {
                    echo '
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-danger text-white">
                            <strong class="mr-auto">Error</strong>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            ' . $_SESSION['error'] . '
                        </div>
                    </div>
                    ';
                    // Hapus pesan error setelah ditampilkan
                    unset($_SESSION['error']);
                }
                ?>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table"></i> <b> Pilih Absensi</b>
                        </div>
                        <div class="card-body">
                            <form action="proses-simpan-toast.php" method="POST" id="formAbsensi">
                                <div class="row gy-2 gx-3 align-items-center">
                                    <div class="col-auto">
                                        <label for="autoSizingJenjang" class="form-label">Jenjang</label>
                                        <select class="form-select" name="autoSizingJenjang" id="autoSizingJenjang" required>
                                            <option selected disabled>Jenjang...</option>
                                            <?php
                                            $queryJenjang = mysqli_query($koneksi, "SELECT DISTINCT jenjang FROM tbl_siswa");
                                            while ($dataJenjang = mysqli_fetch_array($queryJenjang)) {
                                                echo "<option value='{$dataJenjang['jenjang']}'>{$dataJenjang['jenjang']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="kelas" class="form-label">Kelas</label>
                                        <select class="form-select" name="kelas" id="kelas" required>
                                            <option selected disabled>Kelas...</option>
                                            <?php
                                            $queryKelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa");
                                            while ($dataKelas = mysqli_fetch_array($queryKelas)) {
                                                echo "<option value='{$dataKelas['kelas']}'>{$dataKelas['kelas']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="ustadz" class="form-label">Ustadz</label>
                                        <select class="form-select" name="ustadz" id="ustadz" required>
                                            <option selected disabled>Ustadz...</option>
                                            <?php
                                            $queryUstadz = mysqli_query($koneksi, "SELECT nama FROM tbl_guru");
                                            while ($dataUstadz = mysqli_fetch_array($queryUstadz)) {
                                                echo "<option value='{$dataUstadz['nama']}'>{$dataUstadz['nama']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="pelajaran" class="form-label">Pelajaran</label>
                                        <select class="form-select" name="pelajaran" id="pelajaran" required>
                                            <option selected disabled>Pelajaran...</option>
                                            <?php
                                            $queryPelajaran = mysqli_query($koneksi, "SELECT pelajaran FROM tbl_pelajaran");
                                            while ($dataPelajaran = mysqli_fetch_array($queryPelajaran)) {
                                                echo "<option value='{$dataPelajaran['pelajaran']}'>{$dataPelajaran['pelajaran']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="hari" class="form-label">Hari</label>
                                        <select class="form-select" name="hari" id="hari" required>
                                            <option selected disabled>Hari...</option>
                                            <?php
                                            $queryHari = mysqli_query($koneksi, "SELECT DISTINCT hari FROM tbl_waktupelajaran");
                                            while ($dataHari = mysqli_fetch_array($queryHari)) {
                                                echo "<option value='{$dataHari['hari']}'>{$dataHari['hari']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="jam" class="form-label">Jam</label>
                                        <select class="form-select" name="jam" id="jam" required>
                                            <option selected disabled>Jam...</option>
                                            <?php
                                            $queryJam = mysqli_query($koneksi, "SELECT DISTINCT jam FROM tbl_waktupelajaran");
                                            while ($dataJam = mysqli_fetch_array($queryJam)) {
                                                echo "<option value='{$dataJam['jam']}'>{$dataJam['jam']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="autoSizingWaktuMulai" class="form-label">Waktu Mulai</label>
                                        <select class="form-select" name="autoSizingWaktuMulai" id="autoSizingWaktuMulai" required>
                                            <option selected disabled>Waktu Mulai...</option>
                                            <?php
                                            $queryWaktuMulai = mysqli_query($koneksi, "SELECT DISTINCT waktu_mulai FROM tbl_waktupelajaran");
                                            while ($dataWaktuMulai = mysqli_fetch_array($queryWaktuMulai)) {
                                                echo "<option value='{$dataWaktuMulai['waktu_mulai']}'>{$dataWaktuMulai['waktu_mulai']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="autoSizingWaktuSelesai" class="form-label">Waktu Selesai</label>
                                        <select class="form-select" name="autoSizingWaktuSelesai" id="autoSizingWaktuSelesai" required>
                                            <option selected disabled>Waktu Selesai...</option>
                                            <?php
                                            $queryWaktuSelesai = mysqli_query($koneksi, "SELECT DISTINCT waktu_selesai FROM tbl_waktupelajaran");
                                            while ($dataWaktuSelesai = mysqli_fetch_array($queryWaktuSelesai)) {
                                                echo "<option value='{$dataWaktuSelesai['waktu_selesai']}'>{$dataWaktuSelesai['waktu_selesai']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                        <button type="submit" class="btn btn-success float-end">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<!-- Impor dependensi JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>
    $(document).ready(function () {
        // Tampilkan toast jika ada
        var toastEl = document.querySelector('.toast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl, { animation: true, delay: 26000 });
            toast.show();
            // Redirect ke dashboard-admin.php setelah 6 detik
            setTimeout(function () {
                window.location.href = '../dashboard/dashboard-admin.php';
            }, 6000);
        }
    });
</script>


<?php
require_once "../template/footer.php";
?>
