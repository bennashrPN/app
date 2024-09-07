<?php
ob_start(); // Start output buffering

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Edit AKP";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data untuk ID tersebut
$query = mysqli_query($koneksi, "SELECT * FROM tbl_dataakp WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelajaran = mysqli_real_escape_string($koneksi, $_POST['pelajaran']);
    $materi = mysqli_real_escape_string($koneksi, $_POST['materi']);
    $kegiatan = mysqli_real_escape_string($koneksi, $_POST['kegiatan']);

    // Update semua entri yang memiliki pelajaran, materi, dan kegiatan yang sama
    $queryUpdate = "UPDATE tbl_dataakp 
                    SET pelajaran = '$pelajaran', materi_pembelajaran = '$materi', kegiatan_pembelajaran = '$kegiatan'
                    WHERE pelajaran = '{$data['pelajaran']}'
                    AND materi_pembelajaran = '{$data['materi_pembelajaran']}'
                    AND kegiatan_pembelajaran = '{$data['kegiatan_pembelajaran']}'";

    $result = mysqli_query($koneksi, $queryUpdate);

    if ($result) {
        header("Location: kegiatanpembelajaran.php?msg=updated");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($koneksi);
    }
}
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Update Agenda Kegiatan Pembelajaran</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item "><a href="kegiatanpembelajaran.php">Agenda Kegiatan Pembelajaran</a></li>
                <li class="breadcrumb-item active">Edit Agenda Kegiatan Pembelajaran</li>
            </ol>

            <!-- tambah buat tag form konek ke databse  -->
           
            <form method="post">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Agenda Kegiatan Pembelajaran</span>
                            </div>
                            <div class="card-body">
                             
                                <div class="mb-3">
                                <label for="pelajaran">Pelajaran:</label>
                                <input type="text" name="pelajaran" value="<?= htmlspecialchars($data['pelajaran']) ?>" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                <label for="materi">Materi Pembelajaran:</label>
                                <input type="text" name="materi" value="<?= htmlspecialchars($data['materi_pembelajaran']) ?>" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                <label for="kegiatan">Kegiatan Pembelajaran:</label>
                                <input type="text" name="kegiatan" value="<?= htmlspecialchars($data['kegiatan_pembelajaran']) ?>" class="form-control" required>
                                </div>
                                         <div class="mb-3">
                                         <input type="submit" value="Update" class="btn btn-primary mt-3">
                                         <a href="kegiatanpembelajaran.php" class="btn btn-secondary mt-3">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
        </div>
        <?php
require_once "../template/footer.php";
ob_end_flush(); // Flush the output buffer and end output buffering
?>
