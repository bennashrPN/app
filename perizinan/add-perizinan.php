    <?php
    // awali dengan session
    session_start();
    if (!isset($_SESSION['ssLogin'])) {
        header("location:../auth/login.php");
        exit();
    }

    require_once "../config.php";

    $title = "Tambah Perizinan";
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
        <i class="fa-solid fa-circle-xmark"></i> Tambah perizinan gagal, Santri Izin sudah ada ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

    if ($msg == 'added') {
        $alert = '<div class="alert alert-success alert-dismissible fade show" id="added" role="alert"> 
        <i class="fa-solid fa-check"></i> Tambah perizinan berhasil ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

    ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-5">Tambah Perizinan</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="../perizinan/perizinan.php">Data Perizinan</a></li>
                    <li class="breadcrumb-item active">Mata Pelajaran</li>
                </ol>
                <!-- tambah buat tag form konek ke databse  -->
                <form action="proses-perizinan.php" method="POST" enctype="multipart/form-data">

                    <!-- tampilin alert disini -->
                    <?php if ($msg != '') {
                        echo $alert;
                    } ?>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fa-solid fa-square-plus"></i> Tambah Perizinan
                                </div>
                                <div class="card-body">
                                            <div class="mb-3">
                                        <label for="namaSantriIzin" class="form-label ps-1">Nama Santri</label>
                                        <!-- Elemen input untuk autocomplete -->
                                        <input type="text" id="namaSantriIzinAutocomplete" class="form-control" placeholder="Ketik nama santri..." style="width:100%;">
                                        <!-- Elemen select -->
                                        <select name="namaSantriIzin" id="namaSantriIzin" class="form-select" required style="display: none;">
                                            <option value="" selected disabled>-- Pilih Santri --</option>
                                            <!-- Proses ambil data guru dari db -->
                                            <?php
                                            $querySantri = mysqli_query($koneksi, "SELECT * FROM tbl_siswa");
                                            if (!$querySantri) {
                                                die("Query gagal: " . mysqli_error($koneksi));
                                            }

                                            while ($dataSantri = mysqli_fetch_array($querySantri)) {
                                            ?>
                                                <option value="<?= $dataSantri['nama'] ?>"><?= $dataSantri['nama'] ?></option>
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
                                        <label for="kehadiran" class="form-label ps-3">Kehadiran</label>
                                        <label for="" class="col-sm-1 col-form-label">:</label>
                                        <select name="kehadiran" id="kehadiran" class="form-select" required>
                                            <option value="" selected disabled> -Pilih Kehadiran- </option>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alpa">Alpha</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tanggal" class="form-label px-2">Tanggal</label>
                                        <div class="d-flex">
                                            <select name="tanggal" id="tanggal" class="form-select" required>
                                                <option value="" selected disabled>-- Pilih Tanggal --</option>
                                                <!-- Tambahkan pilihan tanggal (1-31) sesuai kebutuhan -->
                                                <!-- Contoh untuk 1-31 -->
                                                <?php for ($i = 1; $i <= 31; $i++) : ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <select name="bulan" id="bulan" class="form-select mx-2" required>
                                                <option value="" selected disabled>-- Pilih Bulan --</option>
                                                <!-- Tambahkan pilihan bulan (1-12) sesuai kebutuhan -->
                                                <!-- Contoh untuk Januari-Desember -->
                                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                    <option value="<?php echo $i; ?>"><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <select name="tahun" id="tahun" class="form-select" required>
                                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                                <!-- Tambahkan pilihan tahun (contohnya 2022-2030) sesuai kebutuhan -->
                                                <?php for ($i = 2022; $i <= 2030; $i++) : ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>  
                                        <!-- tombol -->
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label ps-3">Keterangan</label>
                                        <label for="" class="col-sm-1 col-form-label">:</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" cols="30" rows="3" placeholder="Alasan Izin" maxlength="20" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fa-solid fa-square-plus"></i> Tambah Foto
                                </div>
                                <div class="card-body">

                                    <div class="mb-3">
                                        <img src="../asset/image/default.png" alt="" class="mb-3" width="20%">
                                        <input type="file" name="image" class="form-control form-control-sm">
                                        <small class="text-secondary">Pilih Foto PNG JPG atau JPEG dengan ukuran maks 1MB</small>
                                        <div><small class="text-secondary">lebar=tinggi</small></div>
                                    </div>
                                    <!-- tombol -->
                                    <button type="submit" class="btn btn-success" name="simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                                    <button type="reset" class="btn btn-danger" name="reset"><i class="fa-solid fa-xmark"></i> Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sertakan jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Sertakan jQuery UI -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/themes/smoothness/jquery-ui.css">
    <script>
        $(document).ready(function() {
            // Ambil daftar Santri dari elemen select
            var santriList = [];
            $('#namaSantriIzin option').each(function() {
                var value = $(this).val();
                if (value !== "") {
                    santriList.push(value);
                }
            });

            // Implementasikan fitur autocomplete menggunakan jQuery UI pada elemen input
            $('#namaSantriIzinAutocomplete').autocomplete({
                source: santriList,
                minLength: 1, // Minimal panjang karakter sebelum autocomplete aktif
                select: function(event, ui) {
                    // Setel nilai elemen select berdasarkan hasil pemilihan autocomplete
                    $('#namaSantriIzin').val(ui.item.value);
                },
                autoFocus: true, // Fokus langsung pada hasil autocomplete
                // Atur tampilan daftar autocomplete
                open: function() {
                    // Sesuaikan tinggi daftar jika diperlukan
                    $(this).autocomplete('widget').css({
                        'max-height': '150px', // Batasi tinggi daftar
                        'overflow-y': 'auto' // Tambahkan scroll jika daftar tinggi
                    });
                    // Sesuaikan lebar daftar autocomplete dengan elemen input
            var inputWidth = $(this).width();
            $(this).autocomplete('widget').width(inputWidth);
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
            background-color: #f0f8ff;  /* Warna latar belakang yang diinginkan */
        }
    </style>

    <?php
    require_once "../template/footer.php";
    ?>