<?php
// awali dengan session
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$title = "Mata Pelajaran";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> Tambah pelajaran gagal, Pelajaran sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" id="added" role="alert"> 
    <i class="fa-solid fa-check"></i> Tambah pelajaran berhasil ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Jadwal Pelajaran</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="../jadwal/jadwal-pelajaran.php">Jadwal Pelajaran</a></li>
                <li class="breadcrumb-item active">Tambah Jadwal Pelajaran</li>
            </ol>
            <!-- menampilkan alert -->

            <?php
            if ($msg !== '') {
                echo $alert;
            }
            ?>
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-square-plus"></i> Tambah Jadwal Pelajaran
                </div>
                <div class="card-body">
                    <form action="proses-jadwal-pelajaran.php" method="POST">
                    <div class="mb-3">
                            <label for="ustadz" class="form-label ps-1">Ustadz Pengajar</label>
                            <!-- Elemen input untuk autocomplete -->
                            <input type="text" id="ustadzAutocomplete" class="form-control" placeholder="Ketik nama ustadz..." style="width:100%;">
                            <!-- Elemen select -->
                            <select name="ustadz" id="ustadz" class="form-select" required style="display: none;">
                                <option value="" selected>-- Pilih Ustadz --</option>
                                <!-- Proses ambil data guru dari db -->
                                <?php
                                $queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
                                if (!$queryGuru) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }
                                while ($dataGuru = mysqli_fetch_array($queryGuru)) {
                                ?>
                                    <option value="<?= $dataGuru['nama'] ?>"><?= $dataGuru['nama'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label px-2">Hari</label>
                            <select name="hari" id="hari" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Hari --</option>
                                <!-- proses ambil data kelas dari db -->
                                <?php
                                $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT hari FROM tbl_haripelajaran ");
                                if (!$queryWaktupelajaran) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                ?>
                                    <option value="<?= $dataWaktupelajaran['hari'] ?>"><?= $dataWaktupelajaran['hari'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jam" class="form-label px-2">Jam</label>
                            <select name="jam" id="jam" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jam --</option>
                                <!-- proses ambil data kelas dari db -->
                                <?php
                                $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT jam FROM tbl_waktupelajaran ");
                                if (!$queryWaktupelajaran) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                ?>
                                    <option value="<?= $dataWaktupelajaran['jam'] ?>"><?= $dataWaktupelajaran['jam'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_mulai" class="form-label px-2">Waktu Mulai</label>
                            <select name="waktu_mulai" id="waktu_mulai" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Waktu Mulai --</option>
                                <!-- proses ambil data Waktu Mulai dari db -->
                                <?php
                                $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT waktu_mulai FROM tbl_waktupelajaran ");
                                if (!$queryWaktupelajaran) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                ?>
                                    <option value="<?= $dataWaktupelajaran['waktu_mulai'] ?>"><?= $dataWaktupelajaran['waktu_mulai'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_selesai" class="form-label px-2">Waktu Selesai</label>
                            <select name="waktu_selesai" id="waktu_selesai" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Waktu Selesai --</option>
                                <!-- proses ambil data Waktu Mulai dari db -->
                                <?php
                                $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT waktu_selesai FROM tbl_waktupelajaran ");
                                if (!$queryWaktupelajaran) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                ?>
                                    <option value="<?= $dataWaktupelajaran['waktu_selesai'] ?>"><?= $dataWaktupelajaran['waktu_selesai'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label px-2">Kelas</label>
                            <select name="kelas" id="kelas" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kelas --</option>
                                <!-- proses ambil data kelas Mulai dari db -->
                                <?php
                                $querySiswa = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa ");
                                if (!$querySiswa) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataSiswa = mysqli_fetch_array($querySiswa)) {
                                ?>
                                    <option value="<?= $dataSiswa['kelas'] ?>"><?= $dataSiswa['kelas'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pelajaran" class="form-label px-2">Pelajaran</label>
                            <select name="pelajaran" id="pelajaran" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Mepel --</option>
                                <!-- proses ambil data mapel Mulai dari db -->
                                <?php
                                $queryMapel = mysqli_query($koneksi, "SELECT DISTINCT * FROM tbl_pelajaran ");
                                if (!$queryMapel) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataMapel = mysqli_fetch_array($queryMapel)) {
                                ?>
                                    <option value="<?= $dataMapel['pelajaran'] ?>"><?= $dataMapel['pelajaran'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jenjang" class="form-label px-2">Jenjang</label>
                            <select name="jenjang" id="jenjang" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jenjang --</option>
                                <!-- proses ambil data kelas Mulai dari db -->
                                <?php
                                $querySiswa = mysqli_query($koneksi, "SELECT DISTINCT jenjang FROM tbl_siswa ");
                                if (!$querySiswa) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataSiswa = mysqli_fetch_array($querySiswa)) {
                                ?>
                                    <option value="<?= $dataSiswa['jenjang'] ?>"><?= $dataSiswa['jenjang'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <!-- tombol -->
                        <button type="submit" class="btn btn-success" name="simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        <button type="reset" class="btn btn-danger" name="reset"><i class="fa-solid fa-xmark"></i> Reset</button>
                    </form>
                </div>
            </div>
        </div>
</div>
</div>
</main>

<!-- Sertakan jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Sertakan jQuery UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/themes/smoothness/jquery-ui.css">
<script>
    $(document).ready(function() {
        // Ambil daftar ustadz dari elemen select
        var ustadzList = [];
        $('#ustadz option').each(function() {
            var value = $(this).val();
            if (value !== "") {
                ustadzList.push(value);
            }
        });

        // Implementasikan fitur autocomplete menggunakan jQuery UI pada elemen input
        $('#ustadzAutocomplete').autocomplete({
            source: ustadzList,
            minLength: 1, // Minimal panjang karakter sebelum autocomplete aktif
            select: function(event, ui) {
                // Setel nilai elemen select berdasarkan hasil pemilihan autocomplete
                $('#ustadz').val(ui.item.value);
            },
            autoFocus: true, // Fokus langsung pada hasil autocomplete
            // Atur tampilan daftar autocomplete
            open: function() {
                // Sesuaikan tinggi daftar jika diperlukan
                $(this).autocomplete('widget').css({
                    'max-height': '150px', // Batasi tinggi daftar
                    'overflow-y': 'auto' // Tambahkan scroll jika daftar tinggi
                });
            }
        });
    });
</script>
<style>
    /* Atur ukuran dan jenis font hasil search pada autocomplete jQuery UI */
    .ui-autocomplete {
        font-size: 16px;
        /* Ubah ukuran font sesuai kebutuhan Anda */
        font-family: Arial, sans-serif;
        /* Ubah jenis font sesuai kebutuhan Anda */
    }
</style>

<?php
require_once "../template/footer.php";
?>