ada loading baru nih 

<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Santri Markaz Al Ma'tuq";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Delete santri berhasil , ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Update santri gagal, NIS sudah ada ...
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
    <i class="fa-solid fa-xmark"></i> Tambah santri gagal, file Max 1 MB ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah santri berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Update santri berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if ($msg == 'gagal') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Update santri berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
// Tambahkan semua pesan alert lainnya sesuai kebutuhan
?>
<div id="layoutSidenav_content">
    <main>
       
        <div class="container-fluid px-4 data-container">
            <h1 class="mt-5">Santri</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Santri</li>
            </ol>
            <?php if ($msg != '' ) { echo $alert; } ?>
            <div class="card">
                <div class="card-header">
                    <span class="h6 my-2"><i class="fa-solid fa-table"></i> Data Santri</span>
                    <a href="<?= $main_url ?>santri/upload-santri.php" class="btn btn-sm btn-info float-end" style="color: white;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload</a>
                    <a href="<?= $main_url ?>santri/add-santri.php" class="btn btn-sm btn-success float-end me-2"><i class="fa-solid fa-circle-plus"></i> Add</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col"><center>No</center></th>
                                <th scope="col"><center>Foto</center></th>
                                <th scope="col"><center>NIS</center></th>
                                <th scope="col"><center>Nama</center></th>
                                <th scope="col"><center>Kelas</center></th>
                                <th scope="col"><center>Jenjang</center></th>
                                <th scope="col"><center>Kamar</center></th>
                                <th scope="col"><center>Operasi</center></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $nomor = 1;
                        $querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa");
                        while ($data = mysqli_fetch_array($querySiswa)) { ?>
                            <tr>
                                <th scope="row"><?= $nomor++ ?></th>
                                <td align="center"><img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" width="60px" alt=""></td>
                                <td><?= $data['nis'] ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['kelas'] ?></td>
                                <td><?= $data['jenjang'] ?></td>
                                <td><?= $data['kamar'] ?></td>
                                <td align="center">
                                    <a href="edit-santri.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update santri"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="delete santri" data-id="<?= $data['id'] ?>" data-foto="<?= $data['foto'] ?>">
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
    <!-- Modal hapus data -->
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
    
<?php
require_once "../template/footer.php";
?>
