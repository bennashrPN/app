<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

$title = "Dashboard - Markaz Al Matuq";
require_once "../layout/header.php";
require_once "../layout/navbar.php";
require_once "../layout/sidebar.php";

?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="card bg-default text-default mb-4">
                        <div class="card-body bg-default">
                            <div class="row">
                                <!-- Kolom untuk foto di sisi kiri -->
                                <div class="col-4 mx-auto text-start">
                                    <div class="h5 my-0 mx-1">Santri MTS</div> <!-- Add margin utilities here -->
                                    <h3 class="my-0 mx-1" style="font-size: 60px;"><strong>30</strong></h3>
                                </div>
                                <!-- Kolom untuk data pengguna di sisi kanan -->
                                <div class="col-8">
                                    <h8 class="card-title">Ahlan Wa Sahlan,</h8>
                                    <p class="text-default h5">Ustadz </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="card bg-default text-default mb-4">
                        <div class="card-body bg-default">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center py-0 px-1"> <!-- Add padding utilities here -->
                                    <div class="db-info">
                                        <div class="h5 my-0 mx-1">KELAS MTS</div> <!-- Add margin utilities here -->
                                        <h3 class="my-0 mx-1" style="font-size: 60px;"><strong>30</strong></h3> <!-- Add margin utilities here -->
                                    </div>
                                    <div class="db-icon">
                                        <img src="assets/img/icons/teacher-icon-01.svg" alt="Dashboard Icon">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </main>


    <?php
    require_once "../layout/footer.php";
    ?>