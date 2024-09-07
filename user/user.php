<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// Call related files
require_once "../config.php";
$title = "User";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $alertTypes = [
        'deleted' => 'alert-warning',
        'cancel' => 'alert-warning',
        'notimage' => 'alert-warning',
        'oversize' => 'alert-warning',
        'added' => 'alert-success',
        'image_error' => 'alert-danger',
        'error' => 'alert-danger',
        'updated' => 'alert-success'
    ];
    $alertMessages = [
        'deleted' => 'User berhasil dihapus, ...',
        'cancel' => 'Tambah User gagal, User sudah ada ...',
        'notimage' => 'Tambah User gagal, file yang diupload bukan gambar ...',
        'oversize' => 'Tambah User gagal, file Max 1 MB ...',
        'added' => 'Tambah User berhasil, ...',
        'image_error' => 'Tambah User gagal, terjadi kesalahan saat mengupload gambar ...',
        'error' => 'Terjadi kesalahan, silakan coba lagi ...',
        'updated' => 'Update User berhasil, ...'
    ];
    $alert = '<div class="alert ' . $alertTypes[$msg] . ' alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> ' . $alertMessages[$msg] . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<!-- isi konten halaman ustadz -->

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Users</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">User</li>
            </ol>

            <!-- tampilin alert disini -->
            <?php if (isset($alert)) {
                echo $alert;
            } ?>

            <!-- ini bagian card bootstrap -->
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list-ul"></i> Data User
                    <a href="<?= $main_url ?>user/add-user.php" class="btn btn-sm btn-success float-end"><i class="fa-solid fa-plus"></i> Tambah User</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <!-- ini buat judul nama tabel -->
                        <thead>
                            <tr>
                                <th scope="col">
                                    <center>No</center>
                                </th>
                                <th scope="col">
                                    <center>Foto</center>
                                </th>
                                <th scope="col">
                                    <center>Nama</center>
                                </th>
                                <th scope="col">
                                    <center>Username</center>
                                </th>
                                <th scope="col">
                                    <center>Email</center>
                                </th>
                                <th scope="col">
                                    <center>Jabatan</center>
                                </th>
                                <th scope="col">
                                    <center>Role</center>
                                </th>
                                <th scope="col">
                                    <center>No Hp</center>
                                </th>
                                <th scope="col">
                                    <center>Operasi</center>
                                </th>
                            </tr>
                        </thead>
                        <!-- ini buat isi dalam tabel -->
                        <tbody>
                            <!-- tampilin data dari database -->
                            <?php
                            $no = 1;
                            $queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user");
                            while ($data = mysqli_fetch_array($queryUser)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++ ?></th>
                                    <td><img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" width="60px" alt=""></td>
                                    <td><?= $data['nama'] ?></td>
                                    <td><?= $data['username'] ?></td>
                                    <td><?= $data['email'] ?></td>
                                    <td><?= $data['jabatan'] ?></td>
                                    <td><?= $data['role'] ?></td>
                                    <td><?= $data['no_hp'] ?></td>
                                    <td align="center">
                                        <a href="edit-user.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update user"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="Delete user" data-id="<?= $data['id'] ?>" data-foto="<?= $data['foto'] ?>">
                                            <i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- modal hapus data -->
    <div class="modal" id="modalDelete" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin dihapus?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="" id="btnmodalDelete" class="btn btn-primary">Ya</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on('click', "#btnDelete", function() {
                $("#modalDelete").modal('show');
                let id = $(this).data('id');
                let foto = $(this).data('foto');
                $("#btnmodalDelete").attr('href', '<?= $main_url ?>user/delete-user.php?id=' + id + '&foto=' + foto);
            })
        })
    </script>

    <?php require_once "../template/footer.php"; ?>
</div>
