<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";

$title = "Ustadz";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// jika ada msg dari proses-ustadz 

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'deleted') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Delete Ustadz berhasil dihapus, ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Update ustadz gagal, NIP sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'notimage') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah user gagal,file yang diupload bukan gambar ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'oversize') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah ustadz gagal, file Max 1 MB ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah ustadz berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Update Password berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'err1') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Update Password gagal, password lama tidak cocok...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'err2') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Update Password gagal, konfirmasi password tidak sama...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Password</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item active">Ganti Password</li>
            </ol>
         
            <form action="proses-password.php" method="POST">
                   <!-- tampilin alert disini -->
         <?php if ($msg != '' ){
                echo $alert;
            } ?>
            
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Ganti Pasword</span>
                    
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="mb-3">
                            <label for="currPass" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="currPass" name="currPass" 
                            placeholder="masukan password anda saat ini" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3">
                            <label for="newPass" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="newPass" minlength="4" maxlength="20" 
                            name="newPass" placeholder="Masukan password baru anda " required>
                        </div>
                        <div class="col-8">
                            <label for="confPass" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confPass" name="confPass" placeholder="Ulangi password baru anda ">
                        </div>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="submit" name="reset" class="btn btn-danger float-end me-2"><i class="fa-solid fa-xmark"></i> Reset</button>
                </div>
                </form>
            </div>
    </main>

    <?php

    require_once "../template/footer.php";

    ?>