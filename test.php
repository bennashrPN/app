<?php
// Memanggil file konfigurasi
require_once "../config.php";

// Memulai sesi
session_start();

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

// Memeriksa apakah ada sesi pengguna
if (isset($_SESSION["ssUser"])) {
    $username = $_SESSION["ssUser"];
    $query_user = "SELECT * FROM tbl_user WHERE username='$username'";
    $result_user = mysqli_query($koneksi, $query_user);

    // Mengambil data pengguna
    $user_data = mysqli_fetch_assoc($result_user);

    // Mendapatkan hari ini dalam bahasa Indonesia
    function get_current_day()
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $current_day_in_english = date('l'); // Mendapatkan hari dalam bahasa Inggris
        return $days[$current_day_in_english]; // Mengembalikan hari dalam bahasa Indonesia
    }
    // Mendapatkan hari ini dalam bahasa Indonesia
    $current_day = get_current_day();
    // Mengambil jadwal pelajaran sesuai dengan nama pengguna/guru yang sedang login dan hari ini
    $query_jadwal = "SELECT * FROM tbl_jadwalpelajaran WHERE guru='" . $user_data['nama'] . "' AND hari='" . $current_day . "'";
    $result_jadwal = mysqli_query($koneksi, $query_jadwal);
    // Memeriksa apakah pengguna memiliki jadwal pada hari ini
    if (mysqli_num_rows($result_jadwal) == 0) {
        echo '
        <div class="modal" tabindex="-1" id="noScheduleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Afwan Ustadz, ' . $user_data['nama'] . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tidak ada jadwal Mengajar Hari Ini.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    // Jika tidak ada sesi pengguna, redirect ke halaman login
    header("location:../auth/login.php");
    exit;
}
// Mendapatkan jam saat ini dalam zona waktu "Asia/Jakarta"
date_default_timezone_set("Asia/Jakarta");
$current_time = date("H:i:s"); // Format jam:menit:detik
$current_date = date("d F Y"); // Format tanggal: bulan tahun

$title = "Dashboard - Markaz Al Matuq";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <!-- Modal Edit Absen -->
<div class="modal fade" id="mdlEditabsen" tabindex="-1" aria-labelledby="editAbsenModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAbsenModalLabel">Edit Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAbsenForm" class="row gy-2 gx-3 align-items-center">
                    <div class="col-auto mb-3">
                        <label for="autoSizingNama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="autoSizingNama" placeholder="Nama">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="autoSizingSelect" class="form-label">Kehadiran</label>
                        <select class="form-select" id="autoSizingSelect">
                            <option selected>Kehadiran...</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="col-auto mb-3">
                        <label for="autoSizingKelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="autoSizingKelas" placeholder="Kelas">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="autoSizingJenjang" class="form-label">Jenjang</label>
                        <input type="text" class="form-control" id="autoSizingJenjang" placeholder="Jenjang">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="keteranganInputEdit" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keteranganInputEdit" rows="3" placeholder="Masukkan keterangan di sini..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitAbsenForm()">Save</button>
            </div>
        </div>
    </div>
</div>


            <form method="POST" action="proses-simpan-absensi.php">
                <?php
                // Menampilkan semua jadwal pelajaran yang sesuai dengan hari login pengguna
                while ($row = mysqli_fetch_assoc($result_jadwal)) {
                    // Mendapatkan jam saat ini dan jam mulai serta jam selesai jadwal pelajaran
                    $current_timestamp = strtotime($current_time);
                    $start_timestamp = strtotime($row['waktu_mulai']);
                    $end_timestamp = strtotime($row['waktu_selesai']);

                    // Tambahkan toleransi 2 menit ke end_timestamp
                    $end_timestamp += 2 * 60; // Menambahkan 2 menit (120 detik) ke waktu selesai jadwal

                    // Memeriksa apakah waktu saat ini berada dalam rentang waktu jadwal pelajaran (dengan toleransi 2 menit)
                    if ($current_timestamp >= $start_timestamp && $current_timestamp <= $end_timestamp) {

                ?>
                        <h1 class="mt-5">Kelas - <?php echo $row['kelas']; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Ahlan Wa Sahlan, Ustadz <?php echo $user_data['nama']; ?>
                                <span>Di Kelas <?php echo $row['kelas']; ?></h1></span> , <?php echo $row['jenjang']; ?>
                            </li>
                            <input type="hidden" name="jenjangAbsensi" id="jenjangAbsensi" value="<?php echo $row['jenjang']; ?>">
                            <input type="hidden" name="kelasAbsensi" id="kelasAbsensi" value="<?php echo $row['kelas']; ?>">
                        </ol>
                        <div class="row">
                            <div class="col-xl-8 col-md-6">
                                <div class="card bg-light-subtle text-black mb-4">
                                    <div class="card-body">
                                        <i class="fa-solid fa-school-circle-check"></i>
                                        <span class VA="h6 my-2"> <b>Jam Ke - <?php echo $row['jam']; ?></b></span>
                                        <input type="hidden" name="jamAbsensi" id="jamAbsensi" value="<?php echo $row['jam']; ?>">
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <div class="row row-cols-lg-auto g-3 align-items-center">
                                            <div class="col-11">
                                                <label class="from-control" for="inlineFormInputGroupPengajar">Pengajar</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="fa-solid fa-chalkboard-user"></i></div>
                                                    <input type="text" class="form-control" name="inlineFormInputGroupPengajar" id="inlineFormInputGroupPengajar" value="<?php echo $row['guru']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="from-control" for="inlineFormInputGroupPelajaran">Pelajaran</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="fa-solid fa-book"></i></div>
                                                    <input type="text" class="form-control" name="inlineFormInputGroupPelajaran" id="inlineFormInputGroupPelajaran" value="<?php echo $row['pelajaran']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <label class="from-control" for="inlineFormInputGroupWaktuMulai"> Mulai</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="fa-solid fa-clock"></i></div>
                                                    <input type="text" class="form-control" name="inlineFormInputGroupWaktuMulai" id="inlineFormInputGroupWaktuMulai" value="<?php echo $row['waktu_mulai']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <label class="from-control" for="inlineFormInputGroupWaktuSelesai">Selesai</label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="fa-solid fa-clock"></i></div>
                                                    <input type="text" class="form-control" name="inlineFormInputGroupWaktuSelesai" id="inlineFormInputGroupWaktuSelesai" value="<?php echo $row['waktu_selesai']; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
    <div class="card bg-light-subtle text-black mb-4">
        <div class="card-body" style="font-size: 16px;">
            <i class="fas fa-chart-bar me-1" ></i>
            <b>Info Kelas - <?php echo $row['kelas']; ?></b>
            <p>Hari Ini: <?php echo $row['hari']; ?>, <?php echo $current_date; ?></p>
        </div>
        <div class="card-footer">
            <?php
            $current_date_sql = date("Y-m-d");

            // Data Izin dari tbl_perizinan
            $unabsented_found = false; 
            $query_siswa = "SELECT * FROM tbl_perizinan WHERE kelas='" . $row['kelas'] . "' AND tanggal='" . $current_date_sql . "'";
            $result_siswa = mysqli_query($koneksi, $query_siswa);
            // santri Izin
            if (mysqli_num_rows($result_siswa) > 0) {
                echo '<table class="table table-striped mb-0">';
                echo '<thead style="font-size: 13px;"><tr><th>NO</th><th>Nama</th><th>Keterangan</th></tr></thead>';
                echo '<tbody>';
                $no = 1;
                while ($siswa = mysqli_fetch_assoc($result_siswa)) {
                    echo '<tr>';
                    echo '<td>' . $no . '</td>';
                    echo '<td style="font-size: 14px;"><strong>' . $siswa['nama'] . '</strong></td>';
                    echo '<td style="font-size: 14px;">' . $siswa['keterangan'] . '</td>';
                    echo '</tr>';
                    $no++;
                    // Set unabsented_found menjadi true karena ada siswa yang ditemukan
                    $unabsented_found = true;
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                // Menampilkan pesan jika tidak ada siswa yang ditemukan
                echo '<div class="alert alert-warning mb-0" role="alert" style="font-size: 15px; padding: 10px;">Tidak ada info Hari ini.</div>';
            }
            ?>
        </div>
    </div>
</div>
                            <div class="col-xl-8 col-md-6">
        <div class="card bg-light-subtle text-black mb-4">
            <div class="card-body"><b>Agenda Pembelajaran</b></div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <div class="row gy-2 gx-3 align-items-center">
                    <div class="col-auto">
                        <label class="" for="materiPembelajaran">Materi Pembelajaran</label>
                        <textarea class="form-control" name="materiPembelajaran" id="materiPembelajaran" rows="4" placeholder="Masukkan materi pembelajaran di sini..."></textarea>
                    </div>
                    <div class="col-auto">
                        <label class="" for="kegiatanPembelajaran">Kegiatan Pembelajaran</label>
                        <textarea class="form-control" name="kegiatanPembelajaran" id="kegiatanPembelajaran" rows="4" placeholder="Masukkan kegiatan pembelajaran di sini..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-table me-1"></i>
                                        Presensi
                                        <button type="submit" class="btn btn-sm btn-success float-end" name="simpanData" id="simpanData"><i class="fa-solid fa-floppy-disk"></i> Simpan </button>
</div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped" style="width:100%">

                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Kehadiran</th>
                                                        <th>Ket</th>
                                                        <th>Edit</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Kehadiran</th>
                                                        <th>Ket</th>
                                                        <th>Edit</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php
                                                    // Mengambil data siswa beserta keterangan izin dari tabel perizinan berdasarkan kelas dan tanggal
                                                    $query_izin = "SELECT * FROM tbl_perizinan WHERE kelas='" . $row['kelas'] . "' AND tanggal='" . $current_date_sql . "'";
                                                    $result_izin = mysqli_query($koneksi, $query_izin);

                                                    // Menyimpan nama-nama siswa yang telah melakukan izin pada tanggal yang sama ke dalam array
                                                    $siswa_with_permission = [];
                                                    $siswa_with_sickness = [];
                                                    while ($izin = mysqli_fetch_assoc($result_izin)) {
                                                        if ($izin['kehadiran'] == 'Izin') {
                                                            $siswa_with_permission[] = $izin['nama'];
                                                        } elseif ($izin['kehadiran'] == 'Sakit') {
                                                            $siswa_with_sickness[] = $izin['nama'];
                                                        }
                                                    }

                                                    // Mengambil data siswa berdasarkan kelas dari tabel siswa
                                                    $query_siswa = "SELECT * FROM tbl_siswa WHERE kelas='" . $row['kelas'] . "'";
                                                    $result_siswa = mysqli_query($koneksi, $query_siswa);

                                                    // Menampilkan nama-nama siswa beserta radio button kehadiran
                                                    $no = 1;
                                                    while ($siswa = mysqli_fetch_assoc($result_siswa)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no . "</td>";
                                                        echo "<td>" . $siswa['nama'] . "</td>";
                                                        echo "<td>";
                                                        // Radio button untuk kehadiran
                                                        // Periksa apakah siswa sudah melakukan izin pada tanggal yang sama
                                                        if (in_array($siswa['nama'], $siswa_with_permission)) {
                                                            // Jika iya, tandai opsi 'Izin' sebagai default checked
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Hadir'> Hadir</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Izin' checked> Izin</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Sakit'> Sakit</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Alpha'> Alpha</label><br>";
                                                        } elseif (in_array($siswa['nama'], $siswa_with_sickness)) {
                                                            // Jika iya, tandai opsi 'Sakit' sebagai default checked
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Hadir'> Hadir</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Izin'> Izin</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Sakit' checked> Sakit</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Alpha'> Alpha</label><br>";
                                                        } else {
                                                            // Jika tidak, biarkan opsi 'Hadir' sebagai default checked
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Hadir' checked> Hadir</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Izin'> Izin</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Sakit'> Sakit</label><br>";
                                                            echo "<label><input type='radio' name='kehadiran[" . $siswa['nama'] . "]' value='Alpha'> Alpha</label><br>";
                                                        }
                                                        echo "</td>";
                                                        echo "<td>" . "<a href='' data-bs-toggle='modal' data-bs-target='#mdlNoteKelas' id='ModalLabel' name='editabsen' class='btn btn-sm btn-info text-white' title='update data' data-nama='" . "'><i class='bi bi-sticky-fill'></i></a>" . "</td>";
                                                        // Tambahkan pemeriksaan isset di sini
                                                        $kehadiran = isset($siswa['kehadiran']) ? $siswa['kehadiran'] : '';
                                                       $keterangan = isset($siswa['keterangan']) ? htmlspecialchars($siswa['keterangan']) : '';

echo "<td>" . 
     "<a href='' data-bs-toggle='modal' data-bs-target='#mdlEditabsen' id='editabsen' name='editabsen' class='btn btn-sm btn-warning text-white' title='update data' " . 
     "data-nama='" . htmlspecialchars($siswa['nama']) . "' " .
     "data-kehadiran='" . htmlspecialchars($kehadiran) . "' " .
     "data-kelas='" . htmlspecialchars($row['kelas']) . "' " .
     "data-jenjang='" . htmlspecialchars($siswa['jenjang']) . "' " .
     "data-keterangan='" . $keterangan . "'>" . 
     "<i class='fa-solid fa-pen-to-square'></i>" .
     "</a>" . 
     "</td>";

                                                        echo "</tr>";
                                                        $no++;
                                                    }
                                                    ?>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Keterangan -->
<!-- Modal Note Kelas -->
<div class="modal fade" id="mdlNoteKelas" tabindex="-1" aria-labelledby="noteKelasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noteKelasLabel">Catatan Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="noteKelasInput" class="form-control" rows="4"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveNoteButton">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

            </form>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Data Siswa Kelas
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $nomor = 1;
                                $querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE kelas='" . $row['kelas'] . "'");
                                while ($data = mysqli_fetch_array($querySiswa)) {
                                ?>
                                    <tr>
                                        <td><?= $nomor++ ?></td>
                                        <td align="center"><img src="../asset/image/<?= $data['foto'] ?>" class="rounded-circle" width="60px" alt=""></td>
                                        <td><?= $data['nama'] ?></td>
                                        <td class="text-truncate" style="max-width: 150px;">
                                            <span data-bs-toggle="popover" data-bs-trigger="focus" data-bs-content="<?= htmlspecialchars($data['alamat']) ?>" tabindex="0"><?= $data['alamat'] ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php
                    }
                }
?>
</div>
</main>
<script>
    var savedNote = '';

document.getElementById('saveNoteButton').addEventListener('click', function() {
    savedNote = document.getElementById('noteKelasInput').value;
    // Optionally, close the modal after saving
    var noteModal = bootstrap.Modal.getInstance(document.getElementById('mdlNoteKelas'));
    noteModal.hide();
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('mdlEditabsen');
    var editButtons = document.querySelectorAll('[data-bs-target="#mdlEditabsen"]');

    // Event listener for showing edit modal
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var nama = button.getAttribute('data-nama');
        var kehadiran = button.getAttribute('data-kehadiran');
        var kelas = button.getAttribute('data-kelas');
        var jenjang = button.getAttribute('data-jenjang');
        var keterangan = button.getAttribute('data-keterangan');

        var modalNama = editModal.querySelector('#autoSizingNama');
        var modalKehadiran = editModal.querySelector('#autoSizingSelect');
        var modalKelas = editModal.querySelector('#autoSizingKelas');
        var modalJenjang = editModal.querySelector('#autoSizingJenjang');
        var modalKeterangan = editModal.querySelector('#keteranganInputEdit');

        modalNama.value = nama;
        modalKelas.value = kelas;
        modalJenjang.value = jenjang;
        modalKeterangan.value = savedNote || keterangan; // Use savedNote if available, else use existing keterangan

        // Set kehadiran dropdown based on selected radio button
        var radios = document.querySelectorAll('input[name="kehadiran[' + nama + ']"]');
        radios.forEach(function(radio) {
            if (radio.checked) {
                modalKehadiran.value = radio.value;
            }
        });
    });

    // Function to submit attendance form
    window.submitAbsenForm = function() {
        var nama = document.getElementById('autoSizingNama').value;
        var kehadiran = document.getElementById('autoSizingSelect').value;
        var kelas = document.getElementById('autoSizingKelas').value;
        var jenjang = document.getElementById('autoSizingJenjang').value;
        var keterangan = document.getElementById('keteranganInputEdit').value;

        console.log('Nama:', nama);
        console.log('Kehadiran:', kehadiran);
        console.log('Kelas:', kelas);
        console.log('Jenjang:', jenjang);
        console.log('Keterangan:', keterangan);

        // Add code to send data to server as needed

        // Close modal after submit
        var modal = bootstrap.Modal.getInstance(document.getElementById('mdlEditabsen'));
        modal.hide();
    };
});

$(document).ready(function() {
    var table = $('#example').DataTable({
        "lengthMenu": [
            [35, 40, 50, 60],
            [35, 40, 50, 60]
        ], // Opsi jumlah entri per halaman
        "pagingType": "simple_numbers" // Jenis navigasi halaman yang sederhana
    });

    // Fungsi untuk inisialisasi popover
    function initPopover() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Inisialisasi popover saat halaman pertama kali dimuat
    initPopover();

    // Inisialisasi popover setiap kali tabel di-render ulang
    table.on('draw.dt', function() {
        initPopover();
    });
});


</script>
<?php
require_once '../template/footer.php';
?>