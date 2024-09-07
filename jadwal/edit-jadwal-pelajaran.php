<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Edit Jadwal Pelajaran";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$queryJadwalpelajaran = mysqli_query($koneksi, "SELECT * FROM tbl_jadwalpelajaran WHERE id = $id");
$data = mysqli_fetch_array($queryJadwalpelajaran);


?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Edit Jadwal Pelajaran</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="../jadwal/jadwal-pelajaran.php">Jadwal Pelajaran</a></li>
                <li class="breadcrumb-item active">Edit Jadwal Pelajaran</li>
            </ol>

            <!-- tambah buat tag form konek ke databse  -->
            <form action="proses-jadwal-pelajaran.php" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Jadwal Pelajaran</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <label for="ustadz" class="form-label ps-3">ustadz</label>
                                    <label for="ustadz" class="col-sm-1 col-form-label">:</label>
                                    <select name="ustadz" id="ustadz" class="form-select" required>
                                        <option value="" disabled>--Pilih ustadz--</option>
                                        <!-- proses ambil data ustadz dari db -->
                                        <?php
                                        $queryustadzPelajaran = mysqli_query($koneksi, "SELECT guru FROM tbl_jadwalpelajaran ");
                                        if (!$queryustadzPelajaran) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }
                                        while ($dataustadzPelajaran = mysqli_fetch_array($queryustadzPelajaran)) {
                                        ?>
                                            <option value="<?= $dataustadzPelajaran['guru'] ?>" <?= ($data['guru'] ?? '') === $dataustadzPelajaran['guru'] ? 'selected' : '' ?>>
                                                <?= $dataustadzPelajaran['guru'] ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="hari" class="form-label ps-3">Hari</label>
                                    <label for="hari" class="col-sm-1 col-form-label">:</label>
                                    <select name="hari" id="hari" class="form-select" required>
                                        <option value="" disabled>--Pilih Hari--</option>
                                        <!-- proses ambil data Pelajaran  dari db -->
                                        <?php
                                        $queryHari = mysqli_query($koneksi, "SELECT DISTINCT hari FROM tbl_haripelajaran ");
                                        if (!$queryHari) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataHari = mysqli_fetch_array($queryHari)) {
                                        ?>
                                            <option value="<?= $dataHari['hari'] ?>" <?= ($data['hari'] ?? '') === $dataHari['hari'] ? 'selected' : '' ?>>
                                                <?= $dataHari['hari'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jam" class="form-label ps-3">Jam</label>
                                    <label for="jam" class="col-sm-1 col-form-label">:</label>
                                    <select name="jam" id="jam" class="form-select" required>
                                        <option value="" disabled>--Pilih Jam--</option>
                                        <!-- proses ambil data kelas dari db -->
                                        <?php
                                        $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT jam FROM tbl_waktupelajaran ");
                                        if (!$queryWaktupelajaran) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                        ?>
                                            <option value="<?= $dataWaktupelajaran['jam'] ?>" <?= ($data['jam'] ?? '') === $dataWaktupelajaran['jam'] ? 'selected' : '' ?>>
                                                <?= $dataWaktupelajaran['jam'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="waktu_mulai" class="form-label ps-3">Waktu Mulai</label>
                                    <label for="waktu_mulai" class="col-sm-1 col-form-label">:</label>
                                    <select name="waktu_mulai" id="waktu_mulai" class="form-select" required>
                                        <option value="" disabled>--Pilih Waktu Mulai--</option>
                                        <!-- proses ambil data kelas dari db -->
                                        <?php
                                        $queryWaktumulai = mysqli_query($koneksi, "SELECT DISTINCT waktu_mulai FROM tbl_waktupelajaran ");
                                        if (!$queryWaktumulai) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataWaktumulai = mysqli_fetch_array($queryWaktumulai)) {
                                        ?>
                                            <option value="<?= $dataWaktumulai['waktu_mulai'] ?>" <?= ($data['waktu_mulai'] ?? '') === $dataWaktumulai['waktu_mulai'] ? 'selected' : '' ?>>
                                                <?= $dataWaktumulai['waktu_mulai'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="waktu_selesai" class="form-label ps-3">Waktu Selesai</label>
                                    <label for="waktu_selesai" class="col-sm-1 col-form-label">:</label>
                                    <select name="waktu_selesai" id="waktu_selesai" class="form-select" required>
                                        <option value="" disabled>--Pilih Waktu Selesai--</option>
                                        <!-- proses ambil data Waktu selesai dari db -->
                                        <?php
                                        $queryWaktuselesai = mysqli_query($koneksi, "SELECT DISTINCT waktu_selesai FROM tbl_waktupelajaran ");
                                        if (!$queryWaktuselesai) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataWaktuSelesai = mysqli_fetch_array($queryWaktuselesai)) {
                                        ?>
                                            <option value="<?= $dataWaktuSelesai['waktu_selesai'] ?>" <?= ($data['waktu_selesai'] ?? '') === $dataWaktuSelesai['waktu_selesai'] ? 'selected' : '' ?>>
                                                <?= $dataWaktuSelesai['waktu_selesai'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas" class="form-label px-2">Kelas</label>
                                    <select name="kelas" id="kelas" class="form-select" required>
                                        <option value="" disabled>-- Pilih Kelas --</option>
                                        <!-- proses ambil data kelas Mulai dari db -->
                                        <?php
                                        $queryKelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa ");
                                        if (!$queryKelas) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataKelas = mysqli_fetch_array($queryKelas)) {
                                        ?>
                                            <option value="<?= $dataKelas['kelas'] ?>" <?= ($data['kelas'] ?? '') === $dataKelas['kelas'] ? 'selected' : '' ?>>
                                                <?= $dataKelas['kelas'] ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pelajaran" class="form-label ps-3">Pelajaran</label>
                                    <label for="pelajaran" class="col-sm-1 col-form-label">:</label>
                                    <select name="pelajaran" id="pelajaran" class="form-select" required>
                                        <option value="" disabled>--Pilih Pelajaran--</option>
                                        <!-- proses ambil data Pelajaran  dari db -->
                                        <?php
                                        $queryPelajaran = mysqli_query($koneksi, "SELECT pelajaran FROM tbl_pelajaran ");
                                        if (!$queryPelajaran) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataPelajaran = mysqli_fetch_array($queryPelajaran)) {
                                        ?>
                                            <option value="<?= $dataPelajaran['pelajaran'] ?>" <?= ($data['pelajaran'] ?? '') === $dataPelajaran['pelajaran'] ? 'selected' : '' ?>>
                                                <?= $dataPelajaran['pelajaran'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jenjang" class="form-label ps-3">Jenjang</label>
                                    <label for="jenjang" class="col-sm-1 col-form-label">:</label>
                                    <select name="jenjang" id="jenjang" class="form-select" required>
                                        <option value="" disabled>--Pilih Jenjang--</option>
                                        <!-- proses ambil jenjang selesai dari db -->
                                        <?php
                                        $queryJenjang = mysqli_query($koneksi, "SELECT * FROM tbl_jenjang ");
                                        if (!$queryJenjang) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataJenjang = mysqli_fetch_array($queryJenjang)) {
                                        ?>
                                            <option value="<?= $dataJenjang['singkatan'] ?>" <?= ($data['singkatan'] ?? '') === $dataJenjang['singkatan'] ? 'selected' : '' ?>>
                                                <?= $dataJenjang['singkatan'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <button type="submit" name="update" class="btn btn-success float-end">
                                    <i class="fa-solid fa-floppy-disk"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-square-plus"></i> Foto ustadz
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <!-- Mengambil foto ustadz dari tbl_guru -->
                                    <?php
                                    // Mendapatkan nama ustadz dari data jadwal pelajaran
                                    $namaustadz = $data['guru'] ?? '';  // Gunakan kunci yang sesuai dalam $data untuk mendapatkan nama ustadz

                                    if (!empty($namaustadz)) {
                                        // Query untuk mengambil data foto ustadz dari tbl_guru berdasarkan nama ustadz
                                        $queryustadz = mysqli_query($koneksi, "SELECT foto FROM tbl_guru WHERE nama = '" . mysqli_real_escape_string($koneksi, $namaustadz) . "'");

                                        if ($queryustadz) {
                                            $dataustadz = mysqli_fetch_array($queryustadz);

                                            // Menampilkan foto ustadz jika ditemukan
                                            if ($dataustadz && !empty($dataustadz['foto'])) {
                                                echo '<img src="../asset/image/' . $dataustadz['foto'] . '" alt="Foto ustadz" class="mb-3" width="40%">';
                                            } else {
                                                echo '<p>ustadz belum ada foto.</p>';
                                            }
                                        } else {
                                            echo '<p>Error: Tidak dapat mengambil data dari database.</p>';
                                        }
                                    } else {
                                        echo '<p>Error: Nama ustadz tidak tersedia.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
        <?php
        require_once "../template/footer.php";
        ?>