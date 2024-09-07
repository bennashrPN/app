<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "config.php";

$title = "Dashboard - Staff";
require_once "template/header.php";
require_once "template/navbar.php";
require_once "template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard Staff</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard Staff</li>
            </ol>
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        DataTable Example
                    </div>
                    <div class="card-body">
                        <?php
                        // Set unabsented_found to true karena ada pelajaran yang belum diabsen
                        $unabsented_found = true;

                        // Menampilkan pesan jika tidak ada pelajaran yang belum diabsen
                        if (!$unabsented_found) {
                            echo '<div class="alert alert-warning" role="alert">MAAF HALAMAN INI DALAM PEMBUATAN</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
