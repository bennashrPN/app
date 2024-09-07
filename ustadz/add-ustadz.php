<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Add Ustadz";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";


?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Ustadz</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../ustadz/ustadz.php">Home</a></li>
                <li class="breadcrumb-item "><a href="ustadz.php">Ustadz</a></li>
                <li class="breadcrumb-item active">Tambah Ustadz</li>
            </ol>

            <!-- tambah buat tag form konek ke databse  -->
            <form action="proses-ustadz.php" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Ustadz</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="nip" class="form-label ps-3">NIP</label>
                                    <label for="nip" class="col-sm-1 col-form-label">:</label>
                                    <input type="number" name="nip" pattern="[0-9]{10,}" title="NIP harus terdiri dari 10 angka" class="form-control" placeholder="NIP" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label ps-3">Nama</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="nama" class="form-control " required>
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label ps-3">Jabatan</label>
                                    <label for="jabatan" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="jabatan" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label ps-3">Status</label>
                                    <label for="jabatan" class="col-sm-1 col-form-label">:</label>
                                    <!-- drop dwonpilihan -->
                                    <select name="status" id="status" class="form-select border-bottom" required>
                                        <option value="" selected>--Pilih Status--</option>
                                        <option value="GT" selected>GT</option>
                                        <option value="GTT" selected>GTT</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label ps-3">Alamat</label>
                                    <label for="alamat" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" required></textarea>
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
                                        <!-- kode input foto -->
                                        <img src="../asset/image/default.png" alt="" class="mb-3" width="20%">
                                        <input type="file" name="image" class="form-control form-control-sm">
                                        <small class="text-secondary">Pilih Foto PNG JPG atau JPEG dengan ukuran maks 1MB</small>
                                        <div><small class="text-secondary">lebar=tinggi</small></div>
                                    </div>
                                    <button type="submit" name="simpan" class="btn btn-success ">
                                        <i class="fa-solid fa-floppy-disk"></i> Simpan </button>
                                    <button type="reset" name="reset" class="btn btn-danger  ">
                                        <i class="fa-solid fa-xmark"></i> Reset </button>
                                </div>
                            </div>
                        </div>
            </form>
            <?php
            require_once "../template/footer.php";
            ?>