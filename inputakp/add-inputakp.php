<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../config.php"; // Ensure this file sets up the database connection
$title = "Tambah Izin Guru";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// Koneksi ke database menggunakan config.php
$query = "SELECT * FROM tbl_guru";
$result = mysqli_query($koneksi, $query);
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alerts = [
    'deleted'   => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Delete jabatan berhasil dihapus, ...'],
    'cancel'    => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Update jabatan gagal, ID sudah ada ...'],
    'not_excel' => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'File yang diupload bukan Excel!'],
    'upload_failed' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'File gagal diupload!'],
    'error_query' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat query.'],
    'error_prepare' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat menyiapkan query.'],
    'error_file' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat memproses file.'],
    'added'   => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Alhamdulillah, tambah AKP berhasil'],
];

$alert = isset($alerts[$msg]) ? $alerts[$msg] : null;
?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Agenda Kegiatan Pembelajaran</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item "><a href="inputakp.php">Data AKP</a></li>
                <li class="breadcrumb-item active">Tambah Agenda</li>
            </ol>
            <div class="col-xl-6 col-md-6">
                <?php if ($alert): ?>
                    <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                        <i class="fa-solid <?= $alert['icon'] ?>"></i> <?= $alert['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Form untuk menambahkan izin guru -->
            <form method="post" action="proses-inputakp.php">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Agenda</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="guru" class="form-label ps-3">Ustadz</label>
                                    <label for="guru" class="col-sm-1 col-form-label">:</label>
                                    <select name="guru" id="guru" class="form-select js-example-basic-single" required>
                                        <option value="" disabled selected>- Pilih Ustadz -</option>
                                        <?php while ($dataGuru = mysqli_fetch_assoc($result)): ?>
                                            <option value="<?php echo $dataGuru['nama']; ?>"><?php echo htmlspecialchars($dataGuru['nama']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas" class="form-label ps-3">Kelas</label>
                                    <label for="kelas" class="col-sm-1 col-form-label">:</label>
                                    <select name="kelas" id="kelas" class="form-select js-example-basic-single" required>
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        <?php $query = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_kelas"); ?>
                                        <?php while ($dataKelas = mysqli_fetch_assoc($query)): ?>
                                            <option value="<?= $dataKelas['kelas'] ?>"><?= $dataKelas['kelas'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pelajaran" class="form-label ps-3">Pelajaran</label>
                                    <label for="pelajaran" class="col-sm-1 col-form-label">:</label>
                                    <select name="pelajaran" id="pelajaran" class="form-select js-example-basic-single" required>
                                        <option value="" disabled selected>-- Pilih Pelajaran --</option>
                                        <?php $query = mysqli_query($koneksi, "SELECT DISTINCT pelajaran FROM tbl_jadwalpelajaran"); ?>
                                        <?php while ($dataPelajaran = mysqli_fetch_assoc($query)): ?>
                                            <option value="<?= $dataPelajaran['pelajaran'] ?>"><?= $dataPelajaran['pelajaran'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="materiAKP" class="form-label ps-3">Materi</label>
                                    <label for="materiAKP" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="materiAKP" id="materiAKP" cols="30" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kegiatanAKP" class="form-label ps-3">Kegiatan</label>
                                    <label for="kegiatanAKP" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="kegiatanAKP" id="kegiatanAKP" cols="30" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="ket" class="form-label ps-3">Keterangan</label>
                                    <label for="ket" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="ket" id="ket" cols="30" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="hari" class="form-label ps-3">Hari</label>
                                    <label for="hari" class="col-sm-1 col-form-label">:</label>
                                    <select name="hari" id="hari" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Hari--</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_izin" class="form-label ps-3">Tanggal Izin</label>
                                    <label for="tanggal_izin" class="col-sm-1 col-form-label">:</label>
                                    <input type="date" name="tanggal_izin" id="tanggal_izin" class="form-control" required>
                                </div>
                                
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                                </button>
                                <button type="reset" class="btn btn-danger">
                                    <i class="fa-solid fa-xmark"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    
<script>
    // In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>
<?php
require_once "../template/footer.php";
?>
