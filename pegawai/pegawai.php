<?php


// awali dengan session

session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$title = "Pegawai";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Pegawai</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php"> Home</a></li>
                <li class="breadcrumb-item active">Pegawai</li>
            </ol>
        <div id="layoutError">
            <div id="layoutError_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center mt-4">
                                    <img class="mb-4 img-error" width="200" src="<?= $main_url ?>asset/image/under-construction.png" />
                                    <p class="lead">Afwan Sedang dalam Proses Pengerjaan.</p>
                                    <a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">
                                        <i class="fas fa-arrow-left me-1"></i>
                                         Balik Ke Halaman Dashboard 
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        </div>
     
    <?php
    require_once "../template/footer.php";
    ?>