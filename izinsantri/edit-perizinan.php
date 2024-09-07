<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Edit Perizinan";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$querySantri = mysqli_query($koneksi, "SELECT * FROM tbl_perizinan WHERE id = $id");
$data = mysqli_fetch_array($querySantri);


?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Edit Perizinan</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../izinsantri/perizinan.php">Perizinan</a></li>
                <li class="breadcrumb-item "><a href="perizinan.php">Perizinan</a></li>
                <li class="breadcrumb-item active">Edit Perizinan</li>
            </ol>

            <!-- tambah buat tag form konek ke databse  -->
            <form action="proses-perizinan.php" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Perizinan</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <label for="nama" class="form-label ps-3">Nama</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas" class="form-label ps-3">Kelas</label>
                                    <label for="kelas" class="col-sm-1 col-form-label">:</label>
                                    <!-- drop dwon buat pilihan -->
                                    <select name="kelas" id="kelas" class="form-select" required>
                                        <?php
                                        $queryKelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa");
                                        while ($dataKelas = mysqli_fetch_assoc($queryKelas)) {
                                            if ($dataKelas['kelas'] == $data['kelas']) {
                                        ?>
                                                <option value="<?= $dataKelas['kelas'] ?>" selected><?= $dataKelas['kelas'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $dataKelas['kelas'] ?>"><?= $dataKelas['kelas'] ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kehadiran" class="form-label ps-3">Kehadiran</label>
                                    <label for="kehadiran" class="col-sm-1 col-form-label">:</label>
                                    <!-- drop dwon buat pilihan -->
                                    <select name="kehadiran" id="kehadiran" class="form-select" required>
                                        <?php
                                        $kehadiran = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
                                        foreach ($kehadiran as $khdrn) {
                                            if ($data['kehadiran'] == $khdrn) {
                                        ?><option value="<?= $khdrn ?>" selected><?= $khdrn ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $khdrn ?>"><?= $khdrn ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jenjang" class="form-label ps-3">Jenjang</label>
                                    <label for="jenjang" class="col-sm-1 col-form-label">:</label>
                                    <select name="jenjang" id="jenjang" class="form-select" required>
                                        <option selected disabled>--Pilih Jenjang--</option>
                                        <option value="MTS" <?= ($data['jenjang'] == 'MTS') ? 'selected' : '' ?>>MTS</option>
                                        <option value="MA" <?= ($data['jenjang'] == 'MA') ? 'selected' : '' ?>>MA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label ps-3">Tanggal</label>
                                    <label for="tanggal" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label ps-3">Keterangan</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="keterangan" class="form-control" id="keterangan" name="keterangan" cols="30" rows="3" placeholder="Alasan Izin" maxlength="60" required>
                                    <?= $data['keterangan'] ?></textarea>
                                    </textarea>
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
                                <i class="fa-solid fa-square-plus"></i> Foto Santri Izin
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <!-- Mengambil foto santri dari tbl_siswa -->
                                    <?php
                                    // Mengambil nama santri dari data perizinan
                                    $namaSantri = $data['nama'];

                                    // Query untuk mengambil data foto santri dari tbl_siswa berdasarkan nama santri
                                    $querySantri = mysqli_query($koneksi, "SELECT foto FROM tbl_siswa WHERE nama = '$namaSantri'");
                                    $dataSantri = mysqli_fetch_array($querySantri);

                                    // Menampilkan foto santri jika ditemukan
                                    if ($dataSantri) {
                                        echo '<img src="../asset/image/' . $dataSantri['foto'] . '" alt="Foto Santri" class="mb-3" width="40%">';
                                    } else {
                                        echo '<p>Santri belum ada foto.</p>';
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