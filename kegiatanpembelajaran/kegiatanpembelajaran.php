<?php
// Bismillah session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";

$title = "AKP";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// jika ada msg dari proses-ustadz 
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
// jika ada msg dari proses-ustadz 
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

switch ($msg) {
    case 'deleted':
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-xmark"></i> Kegiatan pembelajaran berhasil dihapus, ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        break;
    case 'cancel':
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-xmark"></i> Update ustadz gagal, NIP sudah ada ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        break;
    case 'notimage':
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-xmark"></i> Tambah user gagal,file yang diupload bukan gambar ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        break;
    case 'oversize':
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-xmark"></i> Tambah ustadz gagal, file Max 1 MB ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        break;
    case 'added':
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check"></i> Tambah kegiatan pembelajaran berhasil,...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        break;
    case 'updated':
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check"></i> Update kegiatan pembelajaran berhasil,...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        break;
    default:
        $alert = '';
        break;
}
// Fetch the session variable for filtering
$nama_guru = isset($_SESSION["nama"]) ? $_SESSION["nama"] : '';

// Modified query to select unique data based on the session variable
$queryAkp = mysqli_query($koneksi, "SELECT id, pelajaran, materi_pembelajaran, kegiatan_pembelajaran, guru
    FROM tbl_dataakp
    WHERE guru = '$nama_guru'
    GROUP BY pelajaran, materi_pembelajaran, kegiatan_pembelajaran, guru");

// Check if the query was successful
if (!$queryAkp) {
    die("Query failed: " . mysqli_error($koneksi));
}

// Fetch data into $sortedDataAkp
$sortedDataAkp = mysqli_fetch_all($queryAkp, MYSQLI_ASSOC);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Agenda Kegiatan Pembelajaran ( AKP )</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item active">Kegiatan Pembelajaran</li>
            </ol>
            <!-- tampilin alert disini -->
            <?php if ($alert != '') {
                echo $alert;
            } ?>

            <div class="card">
                <div class="card-header">
                    <span class="h6 my-2"><i class="fa-solid fa-list-ul"></i> Data Kegiatan Pembelajaran</span>
                    <a href="<?= $main_url ?>kegiatanpembelajaran/upload-akp.php" class="btn btn-sm btn-info float-end me-2 text-white"><i class="bi bi-cloud-plus-fill"></i> Upload</a>
                    <a href="<?= $main_url ?>kegiatanpembelajaran/add-akp.php" class="btn btn-sm btn-success float-end me-2"><i class="fa-solid fa-circle-plus"></i> Add</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <!-- ini buat judul nama tabel -->
                        <thead>
                            <tr>
                                <th scope="col"><center>No<center></th>
                                <th scope="col"><center>Pelajaran<center></th>
                                <th scope="col"><center>Materi Pembelajaran<center></th>
                                <th scope="col"><center>Kegiatan Pembelajaran<center></th>
                                <th scope="col"><center>Guru<center></th>
                                <th scope="col"><center>Operasi<center></th>
                            </tr>
                        </thead>
                        <!-- ini buat isi dalam tabel -->
                        <tbody>
                            <!-- tampilin data dari database -->
                            <?php
                            $no = 1;
                            if (is_array($sortedDataAkp)) {
                                foreach ($sortedDataAkp as $data) {
                            ?>
                                <tr>
                                    <!-- ini kode untuk nampilin nama nama tabel -->
                                    <th scope="row"><?= $no++ ?></th>
                                    <td><?= $data['pelajaran'] ?></td>
                                    <td><?= $data['materi_pembelajaran'] ?></td>
                                    <td><?= $data['kegiatan_pembelajaran'] ?></td>
                                    <td><?= $data['guru'] ?></td>
                                    <!-- ini buat tombol operasi -->
                                    <td align="center">
                                        <a href="edit-akp.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen" title="Update Akp"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="delete akp" data-id="<?= $data['id'] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No data available</td></tr>";
                            }
                            ?>
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
                $("#btnmodalDelete").attr('href', '<?= $main_url ?>kegiatanpembelajaran/delete-akp.php?id=' + id);
            })
        })
    </script>

    <?php require_once "../template/footer.php"; ?>
</div>
