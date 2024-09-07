<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// call file yang berkaitan  
require_once "../config.php";
$title = "Perizinan ustadz";
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
    <i class="fa-solid fa-xmark"></i> Perizinan berhasil dihapus, ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah Perizinan gagal, Santri sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'notimage') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah perizinan gagal,file yang diupload bukan gambar ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'oversize') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah perizinan gagal, file Max 1 MB ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah Perizinan berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Update Perizinan berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if ($msg == 'invalid_date') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah Perizinan berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>
<!-- isi konten halaman ustadz -->

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Perizinan Ustadz</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Perizinan Ustadz</li>
            </ol>
            <!-- tampilin alert disini -->
            <?php if ($msg != '') {
                echo $alert;
            } ?>
            <!-- ini bagian card bootstrap -->
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2">
                        <i class="fa-solid fa-list-ul"></i> Data
                    </span>
                    <a href="<?= $main_url ?>izinustadz/add-izinustadz.php" class="btn btn-sm btn-success float-end">
                        <i class="fa-solid fa-circle-plus"></i> Add
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <!-- Judul tabel -->
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col" class="text-center">Ustadz Izin</th>
                                <th scope="col" class="text-center">Alasan Izin</th>
                                <th scope="col" class="text-center">Waktu Mulai</th>
                                <th scope="col" class="text-center">Waktu Selesai</th>
                                <th scope="col" class="text-center">Hari</th>
                                <th scope="col" class="text-center">Tanggal</th>
                                <th scope="col" class="text-center">Ustadz Pengganti</th>
                                <th scope="col" class="text-center">Satus</th>
                                <th scope="col" class="text-center">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Tampilkan data dari database -->
                            <?php
                            $no = 1;
                            $queryPerizinan = mysqli_query($koneksi, "SELECT * FROM tbl_izinguru");
                            while ($data = mysqli_fetch_array($queryPerizinan)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++ ?></th>
                                    <td class="text-center"><?= $data['guruIzin'] ?></td>
                                    <td class="text-center"><?= $data['alasanIzin'] ?></td>
                                    <td class="text-center"><?= $data['waktu_mulai'] ?></td>
                                    <td class="text-center"><?= $data['waktu_selesai'] ?></td>
                                    <td class="text-center"><?= $data['hari'] ?></td>
                                    <td class="text-center"><?= $data['tgl'] ?></td>
                                     <td class="text-center"><?= $data['guruGanti'] ?></td>
                                    <td class="text-center"><?= $data['status'] ?></td>
                                    <td class="text-center">
                                        <a href="edit-izinustadz.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning" title="Update perizinan">
                                        <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="Delete perizinan" data-id="<?= $data['id'] ?>">
                                        <i class="fa-solid fa-trash"></i>
                                        </button>
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
                    <p>Yakin dihapus?.</p>
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
                $("#btnmodalDelete").attr('href', '<?= $main_url ?>izinustadz/del-izinustadz.php?id=' + id);
            })
        });
    </script>
    <?php
    require_once "../template/footer.php";
    ?>