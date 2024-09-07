<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$title = "Tambah Perizinan";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alert = '';
if ($msg === 'cancel') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-xmark"></i> Tambah perizinan gagal, Santri Izin sudah ada ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
} elseif ($msg === 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
        <i class="fa-solid fa-check"></i> Tambah perizinan berhasil ...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}

$querySantri = mysqli_query($koneksi, "SELECT nama FROM tbl_siswa");
$santriList = [];
while ($dataSantri = mysqli_fetch_assoc($querySantri)) {
    $santriList[] = $dataSantri['nama'];
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Tambah Perizinan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../izinsantri/perizinan.php">Data Perizinan</a></li>
                <li class="breadcrumb-item active">Tambah Perizinan</li>
            </ol>
            <form action="proses-perizinan.php" method="POST" enctype="multipart/form-data">
                <?php if ($msg !== '') {
                    echo $alert;
                } ?>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-square-plus"></i> Tambah Perizinan
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="namaSantriIzin" class="form-label ps-1">Nama Santri</label>
                                    <input type="text" id="namaSantriIzinAutocomplete" class="form-control" placeholder="Ketik nama santri..." style="width:100%;">
                                    <select name="namaSantriIzin" id="namaSantriIzin" class="form-select" required style="display: none;">
                                        <option value="" selected disabled>-- Pilih Santri --</option>
                                        <?php foreach ($santriList as $santri) : ?>
                                            <option value="<?= htmlspecialchars($santri); ?>"><?= htmlspecialchars($santri); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas" class="form-label px-2">Kelas</label>
                                    <select name="kelas" id="kelas" class="form-select" required>
                                        <option value="" selected disabled>-- Kelas --</option>
                                        <?php
                                        $queryKelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa");
                                        while ($dataKelas = mysqli_fetch_assoc($queryKelas)) {
                                            echo '<option value="' . htmlspecialchars($dataKelas['kelas']) . '">' . htmlspecialchars($dataKelas['kelas']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jenjang" class="form-label px-2">Jenjang</label>
                                    <select name="jenjang" id="jenjang" class="form-select" required>
                                        <option value="" selected disabled>-- Jenjang --</option>
                                        <?php
                                        $queryJenjang = mysqli_query($koneksi, "SELECT DISTINCT jenjang FROM tbl_siswa");
                                        while ($dataJenjang = mysqli_fetch_assoc($queryJenjang)) {
                                            echo '<option value="' . htmlspecialchars($dataJenjang['jenjang']) . '">' . htmlspecialchars($dataJenjang['jenjang']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kehadiran" class="form-label ps-3">Kehadiran</label>
                                    <select name="kehadiran" id="kehadiran" class="form-select" required>
                                        <option value="" selected disabled> -Pilih Kehadiran- </option>
                                        <option value="Hadir">Hadir</option>
                                        <option value="Izin">Izin</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Alpa">Alpha</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label px-2">Tanggal</label>
                                    <div class="d-flex">
                                        <select name="tanggal" id="tanggal" class="form-select" required>
                                            <option value="" selected disabled>-- Pilih Tanggal --</option>
                                            <?php for ($i = 1; $i <= 31; $i++) : ?>
                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <select name="bulan" id="bulan" class="form-select mx-2" required>
                                            <option value="" selected disabled>-- Pilih Bulan --</option>
                                            <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                <option value="<?= $i; ?>"><?= date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <select name="tahun" id="tahun" class="form-select" required>
                                            <option value="" selected disabled>-- Pilih Tahun --</option>
                                            <?php for ($i = 2022; $i <= 2030; $i++) : ?>
                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label ps-3">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" cols="30" rows="3" placeholder="Alasan Izin" maxlength="60" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-square-plus"></i> Tambah Foto
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <img src="../asset/image/default.png" alt="" class="mb-3" width="20%">
                                    <input type="file" name="image" class="form-control form-control-sm">
                                    <small class="text-secondary">Pilih Foto PNG JPG atau JPEG dengan ukuran maks 1MB</small>
                                    <div><small class="text-secondary">lebar=tinggi</small></div>
                                </div>
                                <button type="submit" class="btn btn-success" name="simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                                <button type="reset" class="btn btn-danger" name="reset"><i class="fa-solid fa-xmark"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/jquery-ui.min.js"></script>
                <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/themes/smoothness/jquery-ui.css">
                <script>
                   $(document).ready(function() {
    var santriList = <?= json_encode($santriList); ?>;
    $('#namaSantriIzinAutocomplete').autocomplete({
        source: santriList,
        minLength: 1,
        select: function(event, ui) {
            $('#namaSantriIzin').val(ui.item.value).trigger('change');
            
            $.ajax({
                url: 'getSantriData.php',
                type: 'GET',
                data: { nama: ui.item.value },
                dataType: 'json',
                success: function(response) {
                    $('#kelas').val(response.kelas);
                    $('#jenjang').val(response.jenjang);
                }
            });
        },
        autoFocus: true,
        open: function() {
            $(this).autocomplete('widget').css({
                'max-height': '150px',
                'overflow-y': 'auto'
            });
            var inputWidth = $(this).width();
            $(this).autocomplete('widget').width(inputWidth);
        }
    });
});

                </script>
                
                <style>
                    .ui-autocomplete {
                        font-size: 16px;
                        font-family: Arial, sans-serif;
                        background-color: #f0f8ff;
                    }
                </style>
            </form>
        </div>
        </div>
    </main>
</div>

<?php require_once "../template/footer.php"; ?>
