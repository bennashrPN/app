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
                <li class="breadcrumb-item "><a href="../perizinan/perizinan.php">Perizinan</a></li>
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
                                    <label for="kelasAngka" class="form-label ps-3">Kelas</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <select name="kelasAngka" id="kelasAngka" class="form-select" required>
                                        <option value="" disabled>--Pilih Kelas--</option>
                                        <!-- Gunakan kondisi untuk menentukan opsi mana yang terpilih -->
                                        <option value="VII" <?= ($data['kelasAngka'] ?? '') === 'VII' ? 'selected' : '' ?>>7</option>
                                        <option value="VIII" <?= ($data['kelasAngka'] ?? '') === 'VIII' ? 'selected' : '' ?>>8</option>
                                        <option value="IX" <?= ($data['kelasAngka'] ?? '') === 'IX' ? 'selected' : '' ?>>9</option>
                                        <option value="X" <?= ($data['kelasAngka'] ?? '') === 'X' ? 'selected' : '' ?>>10</option>
                                        <option value="XI" <?= ($data['kelasAngka'] ?? '') === 'XI' ? 'selected' : '' ?>>11</option>
                                        <option value="XII" <?= ($data['kelasAngka'] ?? '') === 'XII' ? 'selected' : '' ?>>12</option>
                                    </select>
                                </div>
                                <!-- Bagian untuk memilih Abjad -->
                                <div class="mb-3">
                                    <label for="kelasAbjad" class="form-label ps-3">Abjad</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <!-- Gunakan kondisi untuk menentukan opsi mana yang terpilih -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="abjadA" name="kelasAbjad" value="A" class="form-check-input" <?= ($data['kelasAbjad'] ?? '') === 'A' ? 'checked' : '' ?>>
                                        <label for="abjadA" class="form-check-label">A</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="abjadB" name="kelasAbjad" value="B" class="form-check-input" <?= ($data['kelasAbjad'] ?? '') === 'B' ? 'checked' : '' ?>>
                                        <label for="abjadB" class="form-check-label">B</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="abjadC" name="kelasAbjad" value="C" class="form-check-input" <?= ($data['kelasAbjad'] ?? '') === 'C' ? 'checked' : '' ?>>
                                        <label for="abjadC" class="form-check-label">C</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="abjadD" name="kelasAbjad" value="D" class="form-check-input" <?= ($data['kelasAbjad'] ?? '') === 'D' ? 'checked' : '' ?>>
                                        <label for="abjadD" class="form-check-label">D</label>
                                    </div>
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
                                    <label for="tanggal" class="form-label ps-3">Tanggal</label>
                                    <label for="tanggal" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label ps-3">Keterangan</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="keterangan" class="form-control" id="keterangan" name="keterangan" cols="30" rows="3" placeholder="Alasan Izin" maxlength="20" required>
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