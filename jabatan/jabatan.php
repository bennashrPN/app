<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";

$title = "Jabatan";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// jika ada msg dari proses-ustadz 

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alerts = [
    'deleted'   => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Delete jabatan berhasil dihapus, ...'],
    'cancel'    => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Update jabatan gagal, ID sudah ada ...'],
    'notimage'  => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Tambah jabatan gagal, file yang diupload bukan gambar ...'],
    'oversize'  => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Tambah jabatan gagal, file Max 1 MB ...'],
    'added'     => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Tambah jabatan berhasil, ...'],
    'updated'   => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Update jabatan berhasil, ...'],
];

$alert = isset($alerts[$msg]) ? $alerts[$msg] : null;
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Jabatan</h1>
            <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php"> Home</a></li>
                <li class="breadcrumb-item active">Jabatan</li>
            </ol>
         <!-- tampilin alert disini -->
         <?php if ($alert): ?>
                <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                    <i class="fa-solid <?= $alert['icon'] ?>"></i> <?= $alert['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <span class="h6 my-2"><i class="fa-solid fa-list-ul"></i> Data Jabatan</span>
                    <a href="<?= $main_url ?>jabatan/add-jabatan.php" class="btn btn-sm btn-success float-end me-2"><i class="fa-solid fa-circle-plus"></i> Add</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <!-- ini buat judul nama tabel -->
                        <thead>
                            <tr>
                                <th scope="col">
                                    <center>No<center>
                                </th>
                                <th scope="col">
                                    <center>Bidang<center>
                                </th>
                                <th scope="col">
                                    <center>Jabatan<center>
                                </th>
                                <th scope="col">
                                    <center>Operasi<center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $no = 1;
                            $queryJbtn = mysqli_query($koneksi, "SELECT * FROM tbl_jabatan");
                            while ($data = mysqli_fetch_array($queryJbtn)) { 
                            ?>
                                <tr>
                                  <th scope="row"><?= $no++ ?></th>
                                    <td align="center"><?= $data['jabatan'] ?></td>
                                    <td align="center"><?= $data['bidang'] ?></td>
                                    <td align="center">
                                        <a href="edit-jabatan.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update Jabatan"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="delete jabatan" data-id="<?= $data['id'] ?>">
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
        $(document).on('click',"#btnDelete", function() {
            $("#modalDelete").modal('show');
            let id = $(this).data('id');
            let foto = $(this).data('foto');
            $("#btnmodalDelete").attr('href', '<?= $main_url ?>jabatan/delete-jabatan.php?id=' + id );
        })
       
    })
</script>
    <?php


    require_once "../template/footer.php";

    ?>