<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Dashboard</div>
                    <a class="nav-link" href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">
                        <div class="sb-nav-link-icon"><i class="bi bi-columns-gap"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Management</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                        <div class="sb-nav-link-icon"><i class="bi bi-people-fill"></i></div>
                        User
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #000000 !important;"></i></div>
                    </a>
                    <div class="collapse" id="collapseUser" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <?php
                            if (!empty($_SESSION) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
                            ?>
                                <a class="nav-link" href="<?= $main_url ?>user/user.php">Data User</a>
                                <a class="nav-link" href="<?= $main_url ?>user/add-user.php">Tambah User</a>
                            <?php
                            }
                            ?>
                            <a class="nav-link" href="<?= $main_url ?>user/ganti-password.php">Ganti Password</a>
                        </nav>
                    </div>
                    <?php
                    if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Guru' || $_SESSION['role'] === 'Staf')) {
                    ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAbsensi" aria-expanded="false" aria-controls="collapseAbsensi">
                            <div class="sb-nav-link-icon"><i class="bi bi-person-lines-fill"></i></div>
                            Presensi
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #000000 !important;"></i></div>
                        </a>
                        <div class="collapse" id="collapseAbsensi" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php
                                if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf')) {
                                ?>
                                    <a class="nav-link" href="<?= $main_url ?>absensi/absensi.php">Input Presensi</a>
                                <?php
                                }
                                ?>
                                <?php
                                if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Guru')) {
                                ?>
                                <a class="nav-link" href="<?= $main_url ?>autoabsensi/autoabsensi.php">Presensi Kelas</a>
                                <a class="nav-link" href="<?= $main_url ?>rekapabsensi/rekapabsensi.php">Rekap Presensi</a>
                                <?php
                                }
                                ?>
                            </nav>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                     if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf' || $_SESSION['role'] === 'UKS')) {
                    ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePerizinan" aria-expanded="false" aria-controls="collapsePerizinan">
                            <div class="sb-nav-link-icon"><i class="bi bi-journal-check"></i></i></div>
                            Prerizinan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #000000 !important;"></i></div>
                        </a>
                        <div class="collapse" id="collapsePerizinan" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                     
                                    <a class="nav-link"  href="<?= $main_url ?>izinsantri/perizinan.php">Santri</a>
                                    <?php
                     if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf')) {
                    ?>
                                    <a class="nav-link" href="<?= $main_url ?>izinustadz/izinustadz.php">Ustadz</a>
                                    <?php
                    }
                    ?>
                            </nav>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                     if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf' || $_SESSION['role'] === 'Guru')) {
                    ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAkp" aria-expanded="false" aria-controls="collapseAkp">
                            <div class="sb-nav-link-icon"><i class="bi bi-journal-text"></i></div>
                            Kegiatan Pembelajaran
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #000000 !important;"></i></div>
                        </a>
                        <?php
                     if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Guru')) {
                    ?>
                        <div class="collapse" id="collapseAkp" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= $main_url ?>kegiatanpembelajaran/kegiatanpembelajaran.php">
                            <div class="sb-nav-link-icon"></div>
                            Bank Data AKP</a>
                            </nav>
                        </div>
                        <?php
                    }
                    ?>
                        <?php
                     if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf')) {
                    ?>
                        <div class="collapse" id="collapseAkp" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= $main_url ?>inputakp/inputakp.php">
                            <div class="sb-nav-link-icon"></div>
                            Input Akp</a>
                            </nav>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    }
                    ?>
                    <!-- <?php
                    if (!empty($_SESSION) && isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Guru')) {
                    ?>
                    
                        <a class="nav-link" href="<?= $main_url ?>kegiatanpembelajaran/kegiatanpembelajaran.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-journal-check"></i></i></div>
                            Kegiatan Pembelajaran
                        </a>
                    <?php
                    }
                    ?> -->
                    <?php
                    if (!empty($_SESSION) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
                    ?>
                        <div class="sb-sidenav-menu-heading">Data</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseData" aria-expanded="false" aria-controls="collapseData">
                            <div class="sb-nav-link-icon"><i class="bi bi-database-fill"></i></div>
                            Data
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #000000 !important;"></i></div>
                        </a>
                        <div class="collapse" id="collapseData" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionData">
                                <a class="nav-link" href="<?= $main_url ?>jadwal/jadwal-pelajaran.php">Pelajaran</a>
                                <a class="nav-link" href="<?= $main_url ?>kamar/kamar.php">Kamar</a>
                                <a class="nav-link" href="<?= $main_url ?>halaqoh/halaqoh.php">Halaqoh</a>
                                <a class="nav-link" href="<?= $main_url ?>ustadz/ustadz.php">Ustadz</a>
                                <a class="nav-link" href="<?= $main_url ?>santri/santri.php">Santri</a>
                                <a class="nav-link" href="<?= $main_url ?>pegawai/pegawai.php">Pegawai</a>
                                <a class="nav-link" href="<?= $main_url ?>jabatan/jabatan.php">Jabatan</a>
                                <a class="nav-link" href="<?= $main_url ?>logAktivitas/logAktivitas.php">Log</a>


                            </nav>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (!empty($_SESSION) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
                    ?>
                        <div class="sb-sidenav-menu-heading">Grafik</div>
                        <a class="nav-link" href="<?= $main_url ?>grafik/report.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-graph-up"></i></div>
                            Report
                        </a>
                        <a class="nav-link" href="<?= $main_url ?>grafik/charts.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-graph-up"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="<?= $main_url ?>grafik/tables.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-table"></i></div>
                            Tables
                        </a>
                        <a class="nav-link" href="<?= $main_url ?>grafik/test.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-table"></i></div>
                            Tes
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="sb-sidenav-footer border">
                <div class="small">Logged in as:</div>
                <?= $_SESSION['nama'] ?>
            </div>
        </nav>
    </div>
    <style>
.sb-sidenav, .sb-sidenav-menu, .sb-sidenav-footer {
    background-color: #F7F7F7 !important; 
}

.sb-sidenav-menu-heading {
    color: #000000 !important;
}

.sb-sidenav .nav-link {
    color: #000000 !important; 
}

.sb-sidenav .nav-link:hover {
    background-color: rgba(72, 132, 245, 0.8); /* Biru untuk latar belakang saat hover */
    color: #ffffff !important;
    padding: 20px;
    border-radius: 8px;
}

.sb-sidenav .sb-nav-link-icon i {
    color: #000000 !important;
}

.sb-sidenav-footer .small {
    color: #ffffff !important; 
}

.sb-sidenav-footer {
    background-color: #212529 !important; 
}

.sb-sidenav .sb-sidenav-collapse-arrow i {
    color: #000000 !important; 
}


.sb-nav-link-icon i {
    color: #000000 !important; 
}
</style>