<?php
// awali dengan session
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../hudhuur/auth/login.php");
    exit();
}

require_once "../config.php";

$title = "Add User";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// jika ada msg dari proses-add-user.php

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}


if ($msg == 'deleted') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Delete User berhasil dihapus, ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah User gagal, User sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'notimage') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah User gagal,file yang diupload bukan gambar ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'oversize') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah Ustadz gagal, file Max 1 MB ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah User berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../user/user.php">Data Users</a></li>
                <li class="breadcrumb-item active">Mata Pelajaran</li>
            </ol>
            <!-- tambah buat tag form konek ke databse  -->
            <form action="proses-user.php" method="POST" enctype="multipart/form-data">

                <!-- tampilin alert disini -->
                <?php if ($msg != '') {
                    echo $alert;
                } ?>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-square-plus"></i> Tambah User
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="username" class="form-label ps-3">Username</label>
                                    <label for="username" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" maxlength="20" required="" autocomplete="username">
                                </div>
                                <div class="mb-3">
                                    <label for="namaUser" class="form-label ps-3">Nama</label>
                                    <label for="namaUser" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" class="form-control" id="namaUser" name="namaUser" placeholder="Cari nama" required>
                                    <div id="namaListContainer" style="margin-left: 30px; margin-top: 5px;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="emailUser" class="form-label ps-3">Email</label>
                                    <label for="emailUser" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" class="form-control" id="emailUser" name="emailUser" placeholder="@email.com" required="" autocomplete="emailUser"  pattern=".+@.+">
                                </div>
                                <div class="mb-3">
                                    <label for="no_hpUser" class="form-label ps-3">No Hp</label>
                                    <label for="no_hpUser" class="col-sm-1 col-form-label">:</label>
                                    <input type="number" class="form-control" id="no_hpUser" name="no_hpUser" placeholder="Nomor Henpon" maxlength="12" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jabatanUser" class="form-label ps-3">Jabatan</label>
                                    <label for="jabatanUser" class="col-sm-1 col-form-label">:</label>
                                    <select name="jabatan" id="jabatan" class="form-select" required>
                                        <option value="" selected disabled>--Pilih Jabatan--</option>
                                        <!-- proses ambil data Jabatan  dari db -->
                                        <?php
                                        $queryJbtn = mysqli_query($koneksi, "SELECT DISTINCT jabatan FROM tbl_jabatan ");
                                        if (!$queryJbtn) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataJbtn = mysqli_fetch_array($queryJbtn)) {
                                        ?>
                                            <option value="<?= $dataJbtn['jabatan'] ?>" <?= ($data['jabatan'] ?? '') === $dataJbtn['jabatan'] ? 'selected' : '' ?>>
                                                <?= $dataJbtn['jabatan'] ?>
                                            </option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label ps-3">Role</label>
                                    <label for="role" class="col-sm-1 col-form-label">:</label>
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="" selected disabled> -Pilih Role- </option>
                                        <option value="Admin">Admin</option>
                                        <option value="Wali Kelas">Wali Kelas</option>
                                        <option value="Guru">Guru</option>
                                        <option value="Staf">Staf</option>
                                        <option value="Staf UKS">Staf UKS</option>
                                        <option value="Staf Kesantrian">Staf Kesantrian</option>
                                        <option value="Guru">Guru Pengganti</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamatUser" class="form-label ps-3">Alamat</label>
                                    <label for="alamatUser" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="alamatUser" class="form-control" id="alamatUser" name="alamatUser" cols="30" rows="3" placeholder="Alamat" maxlength="20" required></textarea>
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
                <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>          
                <script>
                    $(document).ready(function() {
                        $('#nama').on('input', function() {
                            var inputVal = $(this).val();
                            if (inputVal.length >= 2) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'proses-namelist.php',
                                    data: {
                                        input: inputVal
                                    },
                                    success: function(data) {
                                        $('#namaListContainer').html(data);
                                    }
                                });
                            } else {
                                $('#namaListContainer').empty();
                            }
                        });

                        // Handle hover on suggestion
                        $('#namaListContainer').on('mouseenter', '.nama-suggestion', function() {
                            $(this).css('background-color', '#f0f0f0');
                        });

                        $('#namaListContainer').on('mouseleave', '.nama-suggestion', function() {
                            $(this).css('background-color', 'transparent');
                        });

                        // Handle click on suggestion
                        $('#namaListContainer').on('click', '.nama-suggestion', function() {
                            var selectedName = $(this).text();
                            $('#nama').val(selectedName);
                            $('#namaListContainer').empty();
                        });
                    });
                </script> -->
                <?php
                require_once  "../template/footer.php";

                ?>