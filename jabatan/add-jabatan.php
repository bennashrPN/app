<?php
// Bismillah, session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Add Jabatan";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Jabatan</h1>
            <ol class="breadcrumb mb-4">
                <!-- Ini buat link antar menu -->
                <li class="breadcrumb-item"><a href="../jabatan/jabatan.php">Home</a></li>
                <li class="breadcrumb-item"><a href="jabatan.php">Jabatan</a></li>
                <li class="breadcrumb-item active">Tambah Jabatan</li>
            </ol>
            <!-- Form untuk menambah data jabatan -->
            <form action="proses-jabatan.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Jabatan</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label ps-3">Jabatan</label>
                                    <input type="text" name="jabatan" id="jabatan" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="bidang" class="form-label ps-3">Bidang</label>
                                    <input type="text" name="bidang" id="bidang" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="simpan" class="btn btn-success">
                                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                                    </button>
                                    <button type="reset" name="reset" class="btn btn-danger">
                                        <i class="fa-solid fa-xmark"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php
    require_once "../template/footer.php";
    ?>
