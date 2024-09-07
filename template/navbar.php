<link href="<?= $main_url ?>template/nav.css" rel="stylesheet">
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Markaz Al Matuq</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 btn-icon" id="sidebarToggle" aria-label="Toggle Sidebar" href="#!">
    <i class="bi bi-layout-text-sidebar"></i>
</button>


        <!-- Navbar Search-->
        <form class="d-flex align-items-center mb-3 mb-lg-0">
            <div class="input-group">
                <!-- <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button> -->
            </div>
        </form>
        <!-- Navbar-->
       <ul class="navbar-nav ms-auto me-3 me-lg-4">
    <li class="nav-item">
        <div class="d-flex align-items-center">
            <span class="text-white text-capitalize me-2"><?= $_SESSION['ssUser'] ?></span>
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
                <span class="visually-hidden">User Menu</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" data-bs-toggle="modal" href="#mdlProfileUser">Profile User</a></li>
                <li><a class="dropdown-item" href="<?= $main_url ?>markaz/profile-markaz.php">Profile Markaz</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="<?= $main_url ?>auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </li>
</ul>

    </nav>

    <?php
    $username = $_SESSION["ssUser"];
    $queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$username'");
    $profile = mysqli_fetch_array($queryUser);
    ?>

    <div class="modal" tabindex="-1" id=mdlProfileUser>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= $main_url ?>asset/image/<?= $profile['foto'] ?>" class="img-fluid rounded-start" alt="gambar user">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                <h4 class="card-title mb-3 text-capitalize ps-1"><?= $profile['nama'] ?></h4> <hr>
                                    <div class="row">
                                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control border-0 bg-transparent" id="nama" value=":<?= $profile['nama'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="jabatan" class="col-sm-3 col-form-label pe-0">Jabatan</label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control border-0 bg-transparent" id="jabatan" value=":<?= $profile['jabatan'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control border-0 bg-transparent" id="alamat" value=":<?= $profile['alamat'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>