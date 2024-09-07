<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";

$title = "Ustadz";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// jika ada msg dari proses-ustadz 

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'deleted') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Delete Ustadz berhasil dihapus, ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Update ustadz gagal, NIP sudah ada ...
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
    <i class="fa-solid fa-xmark"></i> Tambah ustadz gagal, file Max 1 MB ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah ustadz berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Update ustadz berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} 

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Ustadz</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Ustadz</li>
            </ol>
         <!-- tampilin alert disini -->
         <?php if ($msg != '' ){
                echo $alert;
            } ?>

            <div class="card">
                <div class="card-header">
                    <span class="h6 my-2"><i class="fa-solid fa-list-ul"></i> Data Ustadz</span>
                    <a href="<?= $main_url ?>santri/upload-ustadz.php" class="btn btn-sm btn-info float-end" style="color: white;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload</a>
                    <a href="<?= $main_url ?>ustadz/add-ustadz.php" class="btn btn-sm btn-success float-end me-2"><i class="fa-solid fa-circle-plus"></i> Add</a>
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
                                    <center>Foto<center>
                                </th>
                                <th scope="col">
                                    <center>NIP<center>
                                </th>
                                <th scope="col">
                                    <center>Nama<center>
                                </th>
                                <th scope="col">
                                    <center>Jabatan<center>
                                </th>
                                <th scope="col">
                                    <center>Status<center>
                                </th>
                                <th scope="col">
                                    <center>Alamat<center>
                                </th>
                                <th scope="col">
                                    <center>Operasi<center>
                                </th>

                            </tr>
                        </thead>
                        <!-- ini buat isi dalam tabel -->
                        <tbody>

                            <!-- tampilin data dari database -->
                            <?php

                            $no = 1;
                            $querGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
                            while ($data = mysqli_fetch_array($querGuru)) { //penutup kode ada di bawah bagian penutup tag body
                            ?>

                                <tr>
                                    <!-- ini kode untuk nampilin nama nama tabel -->
                                    <th scope="row"><?= $no++ ?></th>
                                    <td align="center"><img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" width="60px" alt=""></td>
                                    <td><?= $data['nip'] ?></td>
                                    <td><?= $data['nama'] ?></td>
                                    <td><?= $data['jabatan'] ?></td>
                                    <td><?= $data['status'] ?></td>
                                    <td><?= $data['alamat'] ?></td>

                                    <!-- ini buat tombol operasi -->

                                    <td align="center">
                                        <a href="edit-ustadz.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update ustadz"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="delete ustadz" data-id="<?= $data['id'] ?>" data-foto="<?= $data['foto'] ?>">
                                        <i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- ini penutup php proses tampil data database k k k k k kabase -->
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
            $("#btnmodalDelete").attr('href', '<?= $main_url ?>ustadz/delete-ustadz.php?id=' + id + '&foto=' + foto);
        })
       
    })
</script>
    <?php


    require_once "../template/footer.php";

    ?>