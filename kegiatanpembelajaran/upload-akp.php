<?php
// Bismillah session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Upload Excel";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alerts = [
    'deleted'   => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Delete jabatan berhasil dihapus, ...'],
    'cancel'    => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Update jabatan gagal, ID sudah ada ...'],
    'not_excel' => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'File yang diupload bukan Excel!'],
    'upload_failed' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'File gagal diupload!'],
    'error_query' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat query.'],
    'error_prepare' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat menyiapkan query.'],
    'error_file' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat memproses file.'],
    'added'   => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Data berhasil diupload!'],
    'data_exists'   => ['type' => 'danger', 'icon' => 'fa-circle-check', 'message' => 'Data sudah ada!'],
];

$alert = isset($alerts[$msg]) ? $alerts[$msg] : null;
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Upload Excel File</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item "><a href="kegiatanpembelajaran.php">Kegiatan Pembelajaran</a></li>
                <li class="breadcrumb-item active">Upload Excel</li>
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
                    <span class="h6 my-2"><i class="fa-solid fa-upload"></i> Upload Excel File</span>
                </div>
                <div class="card-body">
                    <form action="process-upload.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Choose Excel File</label>
                            <input class="form-control" type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" required>
                        </div>
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require_once "../template/footer.php"; ?>
