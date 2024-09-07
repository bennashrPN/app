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
$query = "SELECT nama FROM tbl_guru";
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
    'success'   => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Alhamdulillah, izin ustadz berhasil'],
];

$alert = isset($alerts[$msg]) ? $alerts[$msg] : null;
?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Izin Guru</h1>
            <ol class="breadcrumb mb-4">
                <!-- ini buat link antar menu -->
                <li class="breadcrumb-item "><a href="../ustadz/ustadz.php">Home</a></li>
                <li class="breadcrumb-item "><a href="izinustadz.php">Izin Guru</a></li>
                <li class="breadcrumb-item active">Tambah Izin Guru</li>
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
            <form method="post" action="proses-izinustadz.php">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Izin Guru</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="guruIzin" class="form-label ps-3">Guru</label>
                                    <label for="guruIzin" class="col-sm-1 col-form-label">:</label>
                                    <select name="guruIzin" id="guruIzin" class="form-select js-example-basic-single" required>
                                        <option value="" disabled selected>- Guru Izin -</option>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <option value="<?php echo $row['nama']; ?>"><?php echo htmlspecialchars($row['nama']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alasanIzin" class="form-label ps-3">Alasan Izin</label>
                                    <label for="alasanIzin" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="alasanIzin" id="alasanIzin" cols="30" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                            <label for="waktuMulai" class="form-label px-2">Waktu Mulai</label>
                            <select name="waktuMulai" id="waktuMulai" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Waktu Mulai --</option>
                                <!-- proses ambil data Waktu Mulai dari db -->
                                <?php
                                $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT waktu_mulai FROM tbl_waktupelajaran ");
                                if (!$queryWaktupelajaran) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                ?>
                                    <option value="<?= $dataWaktupelajaran['waktu_mulai'] ?>"><?= $dataWaktupelajaran['waktu_mulai'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="waktuSelesai" class="form-label px-2">Waktu Selesai</label>
                            <select name="waktuSelesai" id="waktuSelesai" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Waktu Selesai --</option>
                                <!-- proses ambil data Waktu Mulai dari db -->
                                <?php
                                $queryWaktupelajaran = mysqli_query($koneksi, "SELECT DISTINCT waktu_selesai FROM tbl_waktupelajaran ");
                                if (!$queryWaktupelajaran) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }

                                while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                ?>
                                    <option value="<?= $dataWaktupelajaran['waktu_selesai'] ?>"><?= $dataWaktupelajaran['waktu_selesai'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
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
                                    <label for="tgl" class="form-label ps-3">Tanggal Izin</label>
                                    <label for="tgl" class="col-sm-1 col-form-label">:</label>
                                    <input type="date" name="tgl" id="tgl" class="form-control" required>
                                </div>  
                        
                                <div class="mb-3">
                                    <label for="guruGanti" class="form-label ps-3">Pengganti</label>
                                    <label for="guruGanti" class="col-sm-1 col-form-label">:</label>
                                    <select name="guruGanti" id="guruGanti" class="form-select js-example-basic-single">
                                        <option value="" disabled selected>- Tidak ada pengganti -</option>
                                        <?php
                                        // Reset hasil query untuk dropdown pengganti
                                        mysqli_data_seek($result, 0);
                                        while ($row = mysqli_fetch_assoc($result)): ?>
                                            <option value="<?php echo $row['nama']; ?>"><?php echo htmlspecialchars($row['nama']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label ps-3">Status</label>
                                    <label for="status" class="col-sm-1 col-form-label">:</label>
                                    <select name="status" id="status" class="form-select border-bottom" required>
                                        <option value="Aktif" selected>Aktif</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
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
