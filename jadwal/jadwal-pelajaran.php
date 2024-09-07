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
$title = "Jadwal Pelajaran";
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
    <i class="fa-solid fa-xmark"></i> Pelajaran berhasil dihapus, ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah User gagal, User sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'notimage') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah User gagal,file yang diupload bukan gambar ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'error') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i>  gagal,  ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah User berhasil,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Berhasil Update,...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>

<!-- isi konten halaman ustadz -->

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Jadwal</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Jadwal Pelajaran</li>
            </ol>
  <!-- tampilin alert disini -->
  <?php if ($msg != '') {
                    echo $alert;
                } ?>
            <!-- ini bagian card bootstrap -->
            <div class="card">
                <div class="card-header">
                    <span class="h6 my-2"><i class="fa-solid fa-list-ul"></i> Data Jadwal Pelajaran</span>
                    <a href="<?= $main_url ?>jadwal/add-jadwal-pelajaran.php" class="btn btn-sm btn-success float-end"> <i class="fa-solid fa-circle-plus"></i> Add</a>
                </div>
                <div class="card-body">
                    <table class=" table table-hover"  id="datatablesSimple"> 
                        <!-- ini buat judul nama tabel -->
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col"><center>Hari<center></th>
                                <th scope="col"><center>Jam<center></th>
                                <th scope="col"><center>Waktu<center></th>
                                <th scope="col"><center>Kelas<center></th>
                                <th scope="col"><center>Mata Pelajaran<center></th>
                                <th scope="col"><center>Ustadz<center></th>
                                <th scope="col"><center>Jenjang<center></th>
                                <th scope="col"><center>Operasi<center></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <!-- tampilin data dari database -->
                            <?php 

                        $no= 1;
                        $queryJadwalpelajaran = mysqli_query($koneksi, "SELECT * FROM tbl_jadwalpelajaran");
                        while ($data = mysqli_fetch_array($queryJadwalpelajaran)) { //penutup kode ada di bawah bagian penutup tag body
                        ?>

                            <tr>
                                <!-- ini kode untuk nampilin nama nama tabel -->
                                <th scope="row"><?= $no++ ?></th>
                                <td align="center"><?= $data['hari']?></td>
                                <td align="center"><?= $data['jam']?></td>
                                <td align="center"><?= $data['waktu']?></td>
                                <td align="center"><?= $data['kelas']?></td>   
                                <td align="center"><?= $data['pelajaran']?></td>
                                <td align="center"><?= $data['guru']?></td>
                                <td align="center"><?= $data['jenjang']?></td>

                                <!-- ini buat tombol operasi -->

                                <td align="center">
                                <a href="edit-jadwal-pelajaran.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update Jadwal"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="deletepelajaran" data-id="<?= $data['id'] ?>" >
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
            $("#btnmodalDelete").attr('href', '<?= $main_url ?>jadwal/delete-pelajaran.php?id=' + id );
        })
       
    });
</script>
    <?php


    require_once "../template/footer.php";

    ?>