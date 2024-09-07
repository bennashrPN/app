<?php
// awali dengan session
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$title = "Add User";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE id = $id");
$data = mysqli_fetch_array($queryUser);


?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Update User</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="user.php">User</a></li>
                <li class="breadcrumb-item active">Update User</li>
            </ol>
            <form action="proses-user.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-square-plus"></i> Tambah User
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <label for="username" class="form-label ps-3">Username</label>
                                    <label for="username" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" id="username" name="username" class="form-control" value="<?= $data['username'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label ps-3">Nama</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" id="nama" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
                                    <div id="namaListContainer" style="margin-left: 30px; margin-top: 5px;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label ps-3">Email</label>
                                    <label for="email" class="col-sm-1 col-form-label">:</label>
                                    <input type="text" id="email" name="email" class="form-control" value="<?= $data['email'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label ps-3">No Hp</label>
                                    <label for="no_hp" class="col-sm-1 col-form-label">:</label>
                                    <input type="number" id="no_hp" name="no_hp" class="form-control" value="<?= $data['no_hp'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label ps-3">Jabatan</label>
                                    <label for="jabatan" class="col-sm-1 col-form-label">:</label>
                                    <select name="jabatan" id="jabatan" class="form-select" required>
                                        <option value="" disabled>--Pilih Jabatan--</option>
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
                                        <?php
                                        $role = ['Admin', 'Wali Kelas', 'Guru', 'Staf', 'Staf UKS', 'Staf Kesantrian', 'Guru Pengganti'];
                                        foreach ($role as $rol) {
                                            if ($data['role'] == $rol) {
                                        ?><option value="<?= $rol ?>" selected><?= $rol ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $rol ?>"><?= $rol ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label ps-3">Alamat</label>
                                    <label for="alamat" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" required>
                                <?= $data['alamat'] ?></textarea>
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
                                    <input type="hidden" name="fotoLama" value="<?= $data['foto'] ?>">
                                    <img src="../asset/image/<?= $data['foto'] ?>" alt="" class="mb-3" width="40%">
                                    <input type="file" name="image" class="form-control form-control-sm">
                                    <small class="text-secondary">Pilih Foto PNG JPG atau JPEG dengan ukuran maks 1MB</small>
                                    <div><small class="text-secondary">lebar=tinggi</small></div>
                                </div>
                                <!-- tombol -->
                                <button type="submit" name="update" class="btn btn-success float-end">
                                    <i class="fa-solid fa-floppy-disk"></i> Update </button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                require_once  "../template/footer.php";

                ?>