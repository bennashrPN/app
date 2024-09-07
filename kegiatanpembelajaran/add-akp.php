<?php
// Bismillah session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// Retrieve the teacher's name from the session
$nama_guru = isset($_SESSION["nama"]) ? $_SESSION["nama"] : '';
require_once "../config.php";
$title = "Add AKP";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

function sort_classes($classes)
{
    // List untuk sort kelas
    $order = array(
        "7 A", "7 B", "7 C", "7 D", "8 A", "8 B", "8 C", "8 D",
        "9 A", "9 B", "9 C", "9 D",
        "10 A", "10 B", "10 C",
        "11 A", "11 B", "11 C",
        "12 A", "12 F", "12 G",
    );
    // Create an associative array for sorting
    $sorted_classes = array();
    foreach ($classes as $class) {
        $sorted_classes[$class] = array_search($class, $order);
    }

    // Sort based on the predefined order
    asort($sorted_classes);

    // Return sorted classes
    return array_keys($sorted_classes);
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Agenda Kegiatan Pembelajaran</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../kegiatanpembelajaran/kegiatanpembelajaran.php">Home</a></li>
                <li class="breadcrumb-item "><a href="kegiatanpembelajaran.php">Agenda Kegiatan Pembelajaran</a></li>
                <li class="breadcrumb-item active">Tambah Agenda Kegiatan Pembelajaran</li>
            </ol>

            <!-- Form untuk menambah agenda -->
            <form action="proses-akp.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Agenda</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="pelajaranAkp" class="form-label ps-3">Pelajaran</label>
                                    <label for="pelajaranAkp" class="col-sm-1 col-form-label">:</label>
                                    <select class="form-select" name="pelajaranAkp" id="pelajaranAkp" required>
                                        <option selected disabled>Pelajaran...</option>
                                        <?php
                                        // Prepare and execute query to get subjects based on the teacher's name from tbl_jadwalpelajaran
                                        $queryMapel = mysqli_prepare($koneksi, "
                                            SELECT DISTINCT pelajaran 
                                            FROM tbl_jadwalpelajaran WHERE guru = ?
                                        ");
                                        mysqli_stmt_bind_param($queryMapel, "s", $nama_guru);
                                        mysqli_stmt_execute($queryMapel);
                                        $resultMapel = mysqli_stmt_get_result($queryMapel);

                                        if (!$resultMapel) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataMapel = mysqli_fetch_array($resultMapel)) {
                                        ?>
                                            <option value="<?= htmlspecialchars($dataMapel['pelajaran']) ?>"><?= htmlspecialchars($dataMapel['pelajaran']) ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="materiAkp" class="form-label ps-3">Materi Pembelajaran</label>
                                    <label for="materiAkp" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="materiAkp" id="materiAkp" cols="30" rows="3" class="form-control" placeholder="Isi materi pembelajaran di sini ... " required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kegiatanAkp" class="form-label ps-3">Kegiatan Pembelajaran</label>
                                    <label for="kegiatanAkp" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="kegiatanAkp" id="kegiatanAkp" cols="30" rows="3" class="form-control" placeholder="Isi kegiatan pembelajaran di sini ... " required></textarea>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="simpan" class="btn btn-success">
                                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                                    </button>
                                    <button type="reset" name="reset" class="btn btn-danger">
                                        <i class="fa-solid fa-xmark"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php
    require_once "../template/footer.php";
    ?>
</div>
