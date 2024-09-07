<?php
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location:..auth/login.php');
    exit();
}
require_once "../config.php";
$title = "Absensi";
require_once "../template/header.php";
require_once "../template/sidebar.php";
require_once "../template/navbar.php";
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5"> Absensi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Absensi Siswa</li>
            </ol>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-table-list"></i> <b> Pilih Absensi</b>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" id="formAbsensi">
                                <div class="mb-3">
                                    <div class="row gy-2 gx-3 align-items-center">
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingJenjang">Jenjang</label>
                                            <select class="form-select" name="autoSizingJenjang" id="autoSizingJenjang" required>
                                                <option selected disabled value="">Jenjang...</option>
                                                <?php
                                                $queryJenjang = mysqli_query($koneksi, "SELECT DISTINCT jenjang FROM tbl_siswa ");
                                                if (!$queryJenjang) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }

                                                while ($dataJenjang = mysqli_fetch_array($queryJenjang)) {
                                                ?>
                                                    <option value="<?= $dataJenjang['jenjang'] ?>"><?= $dataJenjang['jenjang'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingKelas">Kelas</label>
                                            <select class="form-select" name="kelas" id="autoSizingKelas" required>
                                                <option selected disabled value="">Kelas...</option>
                                                <?php
                                                $querySiswa = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa ");
                                                if (!$querySiswa) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }

                                                while ($dataSiswa = mysqli_fetch_array($querySiswa)) {
                                                ?>
                                                    <option value="<?= $dataSiswa['kelas'] ?>"><?= $dataSiswa['kelas'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingUstadz">Ustadz</label>
                                            <select class="form-select" name="ustadz" id="autoSizingUstadz" required>
                                                <option selected disabled value="">Ustadz...</option>
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
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingPelajaran">Pelajaran</label>
                                            <select class="form-select" name="pelajaran" id="autoSizingPelajaran" required>
                                                <option selected disabled value="">Pelajaran...</option>
                                                <?php
                                                $queryPelajaran = mysqli_query($koneksi, "SELECT * FROM tbl_pelajaran");
                                                if (!$queryPelajaran) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }

                                                while ($dataPelajaran = mysqli_fetch_array($queryPelajaran)) {
                                                ?>
                                                    <option value="<?= $dataPelajaran['pelajaran'] ?>"><?= $dataPelajaran['pelajaran'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingHari">Hari</label>
                                            <select class="form-select" name="hari" id="autoSizingHari" required>
                                                <option selected disabled value="">Hari...</option>
                                                <?php
                                                $queryHari = mysqli_query($koneksi, "SELECT DISTINCT hari FROM tbl_haripelajaran ");
                                                if (!$queryHari) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }

                                                while ($dataHari = mysqli_fetch_array($queryHari)) {
                                                ?>
                                                    <option value="<?= $dataHari['hari'] ?>"><?= $dataHari['hari'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingJam">Jam</label>
                                            <select class="form-select" name="jam" id="autoSizingJam" required>
                                                <option selected disabled value="">Jam...</option>
                                                <?php
                                                $queryJam = mysqli_query($koneksi, "SELECT DISTINCT jam FROM tbl_waktupelajaran ");
                                                if (!$queryJam) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }

                                                while ($dataJam = mysqli_fetch_array($queryJam)) {
                                                ?>
                                                    <option value="<?= $dataJam['jam'] ?>"><?= $dataJam['jam'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingWaktuMulai">Waktu Mulai</label>
                                            <select class="form-select" name="autoSizingWaktuMulai" id="autoSizingWaktuMulai" required>
                                                <option selected disabled value="">Waktu Mulai...</option>
                                                <?php
                                                $queryWaktuMulai = mysqli_query($koneksi, "SELECT DISTINCT waktu_mulai FROM tbl_waktupelajaran ");
                                                if (!$queryWaktuMulai) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }
                                                while ($dataWaktuMulai = mysqli_fetch_array($queryWaktuMulai)) {
                                                ?>
                                                    <option value="<?= $dataWaktuMulai['waktu_mulai'] ?>"><?= $dataWaktuMulai['waktu_mulai'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="autoSizingWaktuSelesai">Waktu Selesai</label>
                                            <select class="form-select" name="autoSizingWaktuSelesai" id="autoSizingWaktuSelesai" required>
                                                <option selected disabled value="">Waktu Selesai...</option>
                                                <?php
                                                $queryWaktuSelesai = mysqli_query($koneksi, "SELECT DISTINCT waktu_selesai FROM tbl_waktupelajaran ");
                                                if (!$queryWaktuSelesai) {
                                                    die("Query gagal: " . mysqli_error($koneksi));
                                                }

                                                while ($dataWaktuSelesai = mysqli_fetch_array($queryWaktuSelesai)) {
                                                ?>
                                                    <option value="<?= $dataWaktuSelesai['waktu_selesai'] ?>"><?= $dataWaktuSelesai['waktu_selesai'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <div class="col-auto">
                                                <label for="autoSizingTanggal" style="display: block; font-size: small;">Tanggal:</label>
                                                <input type="date" class="form-control" name="autoSizingTanggal" id="autoSizingTanggal" required>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="reset" class="btn btn-danger" name="reset"><i class="fa-solid fa-xmark"></i> Reset</button>
                                            <button type="submit" class="btn btn-success" name="simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                                        </div>
                            </form>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-table-list"></i> <b>Data</b>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="row gy-2 gx-3 align-items-center">
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingJenjangAbsensi">Jenjang</label>
                                <select class="form-select" name="autoSizingJenjangAbsensi" id="autoSizingJenjangAbsensi" disabled>
                                    <option selected>Jenjang</option>
                                    <?php
                                    $queryJenjang = mysqli_query($koneksi, "SELECT DISTINCT jenjang FROM tbl_siswa");
                                    if (!$queryJenjang) {
                                        die("Query gagal: " . mysqli_error($koneksi));
                                    }
                                    while ($dataJenjang = mysqli_fetch_array($queryJenjang)) {
                                    ?>
                                        <option value="<?= $dataJenjang['jenjang'] ?>"><?= $dataJenjang['jenjang'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingKelasAbsensi">Kelas</label>
                                <select class="form-select" name="kelas" id="autoSizingKelasAbsensi" disabled>
                                    <option selected>Kelas...</option>
                                    <?php
                                    $querySiswa = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM tbl_siswa ");
                                    if (!$querySiswa) {
                                        die("Query gagal: " . mysqli_error($koneksi));
                                    }
                                    while ($dataSiswa = mysqli_fetch_array($querySiswa)) {
                                    ?>
                                        <option value="<?= $dataSiswa['kelas'] ?>"><?= $dataSiswa['kelas'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingUstadzAbsensi">Ustadz</label>
                                <select class="form-select" name="ustadz" id="autoSizingUstadzAbsensi" disabled>
                                    <option selected>Ustadz...</option>
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
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingPelajaranAbsensi">Pelajaran</label>
                                <select class="form-select" name="pelajaran" id="autoSizingPelajaranAbsensi" disabled>
                                    <option selected>Pelajaran...</option>
                                    <?php
                                    $queryPelajaran = mysqli_query($koneksi, "SELECT * FROM tbl_pelajaran");
                                    if (!$queryPelajaran) {
                                        die("Query gagal: " . mysqli_error($koneksi));
                                    }

                                    while ($dataPelajaran = mysqli_fetch_array($queryPelajaran)) {
                                    ?>
                                        <option value="<?= $dataPelajaran['pelajaran'] ?>"><?= $dataPelajaran['pelajaran'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingHariAbsensi">Hari</label>
                                <select class="form-select" name="hari" id="autoSizingHariAbsensi" disabled>
                                    <option selected disabled value="">Hari...</option>
                                    <?php
                                    $queryHari = mysqli_query($koneksi, "SELECT DISTINCT hari FROM tbl_haripelajaran ");
                                    if (!$queryHari) {
                                        die("Query gagal: " . mysqli_error($koneksi));
                                    }

                                    while ($dataHari = mysqli_fetch_array($queryHari)) {
                                    ?>
                                        <option value="<?= $dataHari['hari'] ?>"><?= $dataHari['hari'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingJamAbsensi">Jam</label>
                                <select class="form-select" name="jam" id="autoSizingJamAbsensi" disabled>
                                    <option selected disabled value="">Jam...</option>
                                    <?php
                                    $queryJam = mysqli_query($koneksi, "SELECT DISTINCT jam FROM tbl_waktupelajaran ");
                                    if (!$queryJam) {
                                        die("Query gagal: " . mysqli_error($koneksi));
                                    }

                                    while ($dataJam = mysqli_fetch_array($queryJam)) {
                                    ?>
                                        <option value="<?= $dataJam['jam'] ?>"><?= $dataJam['jam'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingWaktuAbsensi">Waktu</label>
                                <input type="text" class="form-control" name="autoSizingWaktuAbsensi" id="autoSizingWaktuAbsensi" readonly>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="autoSizingTanggalAbsensi">Tanggal</label>
                                <input type="text" class="form-control" name="autoSizingTanggalAbsensi" id="autoSizingTanggalAbsensi" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-list "></i> Absensi</span>
                    <button type="button" class="btn btn-sm btn-success float-end" id="simpanDataBtn"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</button>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <center>NO</center>
                                </th>
                                <th scope="col">
                                    <center>Nama Santri</center>
                                </th>
                                <th scope="col">
                                    <center>Kehadiran</center>
                                </th>
                                <th scope="col">
                                    <center>Operasi</center>
                                </th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th scope="col">
                                    <center>NO</center>
                                </th>
                                <th scope="col">
                                    <center>Nama Santri</center>
                                </th>
                                <th scope="col">
                                    <center>Kehadiran</center>
                                </th>
                                <th scope="col">
                                    <center>Operasi</center>
                                </th>
                            </tr>
                        </tfoot>
                        <tbody id="namaSantriBody">
                            <!-- Data nama santri akan muncul di sini melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   // Menangani klik tombol "Simpan"
    $(document).ready(function() {
        // Menangani klik tombol "Simpan" pada formulir pilih absensi
        $('#formAbsensi').submit(function(event) {
            event.preventDefault(); // Mencegah pengiriman formulir default
            // Menggabungkan waktu mulai dan waktu selesai dengan tanda "-" dan memasukkannya ke dalam kolom #autoSizingWaktuAbsensi
            var waktuMulai = $('#autoSizingWaktuMulai').val();
            var waktuSelesai = $('#autoSizingWaktuSelesai').val();
            // Jika waktu mulai dan selesai dipilih, gabungkan keduanya
            if (waktuMulai && waktuSelesai) {
                var waktuGabungan = waktuMulai + ' - ' + waktuSelesai;
                $('#autoSizingWaktuAbsensi').val(waktuGabungan);
            } else {
                // Jika belum ada yang dipilih, kosongkan kolom waktuAbsensi
                $('#autoSizingWaktuAbsensi').val('');
            }
            // Mengambil data formulir
            var jenjang = $('#autoSizingJenjang').val();
            var kelas = $('#autoSizingKelas').val();
            var pengajar = $('#autoSizingUstadz').val();
            var pelajaran = $('#autoSizingPelajaran').val();
            var hari = $('#autoSizingHari').val();
            var jam = $('#autoSizingJam').val();
            var waktu = $('#autoSizingWaktuAbsensi').val(); // Mengambil waktu yang baru diatur
            var tanggal = $('#autoSizingTanggal').val();
            // Menyisipkan data formulir ke kolom absensi
            $('#autoSizingJenjangAbsensi').val(jenjang);
            $('#autoSizingKelasAbsensi').val(kelas);
            $('#autoSizingUstadzAbsensi').val(pengajar);
            $('#autoSizingPelajaranAbsensi').val(pelajaran);
            $('#autoSizingHariAbsensi').val(hari);
            $('#autoSizingJamAbsensi').val(jam);
            $('#autoSizingWaktuAbsensi').val(waktu);
            $('#autoSizingTanggalAbsensi').val(tanggal);

            // Mengirim permintaan AJAX untuk mendapatkan nama siswa berdasarkan kelas
            $.ajax({
                type: 'POST',
                url: 'proses-nama-siswa.php',
                data: {
                    kelas: kelas
                },
                dataType: 'json', // Mengatur tipe data yang diharapkan dari server
                success: function(response) {
                    // Menyisipkan data nama santri ke dalam tabel
                    var options = '';
                    $.each(response, function(index, data) {
                        options += "<tr>" +
                            "<th scope='row'><center>" + data.nomor + "</center></th>" +
                            "<td><center>" + data.nama + "</center></td>" +
                            "<td><center>" +
                            "<input type='radio' name='" + data.radioName + "' value='Hadir' checked> Hadir " +
                            "<input type='radio' name='" + data.radioName + "' value='Izin'> Izin " +
                            "<input type='radio' name='" + data.radioName + "' value='Sakit'> Sakit " +
                            "<input type='radio' name='" + data.radioName + "' value='Alpha'> Alpha " +
                            "</center></td>" +
                            "<td align='center'>" +
                            "<a href='' class='btn btn-sm btn-warning' title='update pelajaran'><i class='fa-solid fa-pen-to-square'></i></a>" +
                            "<button type='button' class='btn btn-sm btn-danger' title='hapus pelajaran'><i class='fa-solid fa-trash'></i></button>" +
                            "</td>" +
                            "</tr>";
                    });

                    $('tbody').html(options);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
        // Menangani klik tombol "Simpan Data"
        $('#simpanDataBtn').click(function() {
            // Mengambil data absensi dari formulir
            var jenjang = $('#autoSizingJenjangAbsensi').val();
            var kelas = $('#autoSizingKelasAbsensi').val();
            var pengajar = $('#autoSizingUstadzAbsensi').val();
            var pelajaran = $('#autoSizingPelajaranAbsensi').val();
            var hari = $('#autoSizingHariAbsensi').val();
            var jam = $('#autoSizingJamAbsensi').val();
            var waktu = $('#autoSizingWaktuAbsensi').val();
            var tanggal = $('#autoSizingTanggalAbsensi').val();

            // Mengambil data kehadiran dari radio button
            var kehadiranData = [];
            $('tbody tr').each(function() {
                var nomor = $(this).find('td:eq(0)').text();
                var kehadiran = $(this).find('input:checked').val();
                kehadiranData.push({
                    nomor: nomor,
                    kehadiran: kehadiran
                });
            });
            // Mengirim data absensi ke server untuk disimpan
            $.ajax({
                type: 'POST',
                url: 'proses-simpan-absensi.php',
                data: {
                    jenjang: jenjang,
                    kelas: kelas,
                    pengajar: pengajar,
                    pelajaran: pelajaran,
                    hari: hari,
                    jam: jam,
                    waktu: waktu,
                    tanggal: tanggal,
                    kehadiranData: kehadiranData
                },
                success: function(response) {
                    // Menanggapi respons dari server
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Jika penyimpanan berhasil, tampilkan popup Toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "center",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: data.message, // Menggunakan pesan dari respons server
                            background: '#4CAF50' // Background hijau
                        });
                    } else {
                        // Jika terjadi kesalahan, tampilkan popup Toast dengan pesan kesalahan
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "center",
                            showConfirmButton: false,
                            timer: 6000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "error",
                            title: data.message // Menggunakan pesan dari respons server
                        });
                    }
                }
            });
        });
    });
</script>
<style>
    .swal2-container .swal2-popup .swal2-timer-progress-bar {
        background: #4CAF50 !important;
        /* Warna hijau */
    }
</style>

<?php
require_once "../template/footer.php";
