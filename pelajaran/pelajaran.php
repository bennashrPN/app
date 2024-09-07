<?php
// awali dengan session
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$title = "Mata Pelajaran";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> Tambah pelajaran gagal, Pelajaran sudah ada ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" id="added" role="alert"> 
    <i class="fa-solid fa-check"></i> Tambah pelajaran berhasil ...
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Mata Pelajaran</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Mata Pelajaran</li>
            </ol>
            <!-- menampilkan alert -->

            <?php
            if ($msg !== '') {
                echo $alert;
            }
            ?>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa-solid fa-square-plus"></i> Tambah Pelajaran
                        </div>
                        <div class="card-body">
                            <form action="proses-pelajaran.php" method="POST">
                                <div class="mb-3">
                                    <label for="pelajaran" class="form-label ps-3">Pelajaran</label>
                                    <input type="text" class="form-control" id="pelajaran" name="pelajaran" placeholder="Nama Pelajaran" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenjang" class="form-label ps-1">Jenjang</label>
                                    <select name="jenjang" id="jenjang" class="form-select" required>
                                        <option value="" selected>-- Pilih Jenjang --</option>
                                        <option value="mutawasitah">MTS</option>
                                        <option value="tsanawiyah">MA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelasAngka" class="form-label ps-1">Kelas</label>
                                    <select name="kelasAngka" id="kelasAngka" class="form-select" required>
                                        <option value="" selected>-- Pilih Kelas --</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelasAbjad" class="form-label ps-1">Abjad</label>
                                    <div class="ms-2">
                                        <label class="form-check-label">
                                            <input type="radio" name="kelasAbjad" value="A" class="form-check-input"> A
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" name="kelasAbjad" value="B" class="form-check-input"> B
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" name="kelasAbjad" value="C" class="form-check-input"> C
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" name="kelasAbjad" value="D" class="form-check-input"> D
                                        </label>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label for="ustadz" class="form-label ps-1">Ustadz Pengajar</label>
                                    <select name="ustadz" id="ustadz" class="form-select" required>
                                        <option value="" selected>-- Pilih Ustadz --</option>

                                        <!-- proses ambil data guru dari db -->
                                        <?php
                                        $queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
                                        if (!$queryGuru) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataGuru = mysqli_fetch_array($queryGuru)) {
                                        ?>
                                            <option value="<?= $dataGuru['nama'] ?>"><?= $dataGuru['nama'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- tombol -->
                                <button type="submit" class="btn btn-success" name="simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                                <button type="reset" class="btn btn-danger" name="reset"><i class="fa-solid fa-xmark"></i> Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa-solid fa-list"></i> Data Pelajaran
                        </div>
                        <div class="card-body">
                            <table class="table table-hover" id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <center>NO</center>
                                        </th>
                                        <th scope="col">
                                            <center>Mata Pelajaran</center>
                                        </th>
                                        <th scope="col">
                                            <center>Jenjang</center>
                                        </th>
                                        <th scope="col">
                                            <center>Kelas</center>
                                        </th>
                                        <th scope="col">
                                            <center>Ustadz</center>
                                        </th>
                                        <th scope="col">
                                            <center>Operasi</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- untuk manampilkan data  -->
                                    <?php
                                    $no = 1;
                                    $queryPelajaran = mysqli_query($koneksi, "SELECT * FROM tbl_pelajaran");

                                    //buat looping  
                                    while ($data = mysqli_fetch_array($queryPelajaran)) { ?>

                                        <tr>
                                            <th scope="row"><?= $no++ ?></th>
                                            <td><?= $data['pelajaran'] ?></td>
                                            <td><?= $data['jenjang'] ?></td>
                                            <td><?= $data['kelas'] ?></td>
                                            <td><?= $data['guru'] ?></td>
                                            <td align="center">
                                                <a href="" class="btn btn-sm btn-warning" title="update pelajaran"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <button type="button" id="btnHapus" class="btn btn-sm btn-danger" title="hapus pelajaran"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- sebelum buat popup notif kasih id dulu di msg  -->


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#added').fadeOut('slow');
            }, 3000);
        });
    </script>


    <?php
    require_once "../template/footer.php";
    ?>