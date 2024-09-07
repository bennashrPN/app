<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Edit Santri";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$querySantri = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE id = $id");
$data = mysqli_fetch_array($querySantri);


?>

<!-- isi konten ada tombol   -->

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Update Santri</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../santri/santri.php">Home</a></li>
                <li class="breadcrumb-item "><a href="santri.php">Santri</a></li>
                <li class="breadcrumb-item active">Update Santri</li>
            </ol>

            <!-- tambah buat tag form konek ke databse  -->
            <form action="proses-santri.php" method="POST" enctype="multipart/form-data">

                <div class="card">
                    <!-- ini tambah santri ada tombolnya -->
                    <div class="card-header">
                        <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Update Santri</span>
                        <!-- tombol untuk tambah dan reset -->
                        <button type="submit" name="update" class="btn btn-success float-end">
                            <i class="fa-solid fa-floppy-disk"></i> Update </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                <div class="mb-3 row">
                                    <label for="nis" class="col-sm-2 col-form-label">NIS</label>
                                    <label for="nis" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="text" name="nis" title="NIS harus terdiri dari 10 angka" class="form-control border-0 border-bottom ps-2" value="<?= $data['nis'] ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="text" name="nama" class="form-control border-0 border-bottom ps-2" value="<?= $data['nama'] ?>" required>
                                    </div>
                                </div>
                                <!-- Bagian untuk memilih Kelas -->
                                <div class="mb-3 row">
                                    <label for="kelasAngka" class="col-sm-2 col-form-label">Kelas</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <select name="kelasAngka" id="kelasAngka" class="form-select border-bottom border-0" required>
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
                                </div>

                                <!-- Bagian untuk memilih Abjad -->
                                <div class="mb-3 row">
                                    <label for="kelasAbjad" class="col-sm-2 col-form-label">Abjad</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
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
                                </div>



                                <div class="mb-3 row">
                                    <label for="jenjang" class="col-sm-2 col-form-label">Jenjang</label>
                                    <label for="jenjang" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <select name="jenjang" id="jenjang" class="form-select border-bottom border-0" required>
                                            <option value="">--Pilih Jenjang--</option>
                                            <!-- Gunakan kondisi untuk menentukan opsi mana yang terpilih -->
                                            <option value="MTS" <?= isset($data['jenjang']) && $data['jenjang'] == 'MTS' ? 'selected' : '' ?>>MTS</option>
                                            <option value="MA" <?= isset($data['jenjang']) && $data['jenjang'] == 'MA' ? 'selected' : '' ?>>MA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                    <label for="alamat" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" required>
                                <?= $data['alamat'] ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- kode input foto -->

                            <div class="col-4 text-center px-5">
                                <input type="hidden" name="fotoLama" value="<?= $data['foto'] ?>">
                                <img src="../asset/image/<?= $data['foto'] ?>" alt="" class="mb-3" width="40%">
                                <input type="file" name="image" class="form-control form-control-sm">
                                <small class="text-secondary">Pilih Foto PNG JPG atau JPEG dengan ukuran maks 1MB</small>
                                <div><small class="text-secondary">lebar=tinggi</small></div>
                            </div>
            </form>
        </div>
    </main>

    <?php


    require_once "../template/footer.php";

    ?>