<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Edit Jabatan";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_jabatan WHERE id = $id");
$data = mysqli_fetch_array($queryGuru);

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Update Jabatan</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../jabatan/jabatan.php">Home</a></li>
                <li class="breadcrumb-item "><a href="jabatan.php">jabatan</a></li>
                <li class="breadcrumb-item active">Edit jabatan</li>
            </ol>

            <!-- tambah buat tag form konek ke databse  -->
            <form action="proses-jabatan.php" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit jabatan</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label ps-3">Nama</label>
                                    <label for="jabatan" class="col-sm-1 col-form-label">:</label>       
                                    <input type="text" name="jabatan" class="form-control" value="<?= $data['jabatan'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="bidang" class="form-label ps-3">Jabatan</label>
                                    <label for="bidang" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" name="bidang" class="form-control" value="<?= $data['bidang'] ?>" required>
                                </div>
                                <div class="mb-3">
                                <button type="submit" name="update" class="btn btn-success float-end">
                                <i class="fa-solid fa-floppy-disk"></i> Update </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            </form>
        </div>
        <?php
        require_once "../template/footer.php";
        ?>