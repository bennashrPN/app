<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header('location:../auth/login.php');
    exit;
}

require_once "../config.php";
$title = "Tambah Santri";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah santri gagal, NIS sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'notimage') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah santri gagal,file yang diupload bukan gambar ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'gagal') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah santri gagal, NIS harus berisi 10 digit angka ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah Santri berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Santri</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="santri.php">Santri</a></li>
                <li class="breadcrumb-item active">Tambah Santri</li>
            </ol>
            <form action="proses-santri.php" method="post" enctype="multipart/form-data">

                <!-- tampilin alert disini -->
                <?php if ($msg != '') {
                    echo $alert;
                } ?>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-square-plus"></i> Tambah Santri
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="nis" class="form-label ps-3">NIS</label>
                                    <label for="nis" class="col-sm-1 col-form-label">:</label>
                                    <input type="number" name="nis" class="form-control" id="nis" placeholder="NIS" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label ps-3">Nama</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="nama" required class="form-control" id="nama" placeholder="Nama Santri">
                                </div>
                                <div class="mb-3">
                                    <label for="kelasAngka" class="form-label ps-3">Kelas</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <select name="kelasAngka" id="kelasAngka" class="form-select " required>
                                        <option selected>--Pilih Kelas--</option>
                                        <option name="kelasAngka" value="VII">7</option>
                                        <option name="kelasAngka" value="VIII">8</option>
                                        <option name="kelasAngka" value="IX">9</option>
                                        <option name="kelasAngka" value="X">10</option>
                                        <option name="kelasAngka" value="XI">X</option>
                                        <option name="kelasAngka" value="XII">XII</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelasAbjad" class="form-label ps-3">Abjad</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>

                                    <label class="form-check-label" required>
                                        <input type="radio" name="kelasAbjad" value="A" class="form-check-input"> A
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" name="kelasAbjad" value="B" class="form-check-input"> B
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" name="kelasAbjad" value="C" class="form-check-input"> C
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" name="kelasAbjad" value="D" class="form-check-input"> D
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <label for="jenjang" class="col-sm-2 col-form-label">Jenjang</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>

                                    <select name="jenjang" id="jenjang" class="form-select  " required>
                                        <option selected>--Pilih Jenjang--</option>
                                        <option value="MTS">MTS</option>
                                        <option value="MA">MA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label ps-3">Alamat</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="alamat" class="form-control" id="alamat" name="alamat" cols="30" rows="3" placeholder="Alamat" maxlength="20" required></textarea>
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
            </form>
            <?php
            require_once "../template/footer.php";
            ?>