<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// call file yang berkaitan  
require_once "../config.php";
$title = "Absensi Kelas";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Absensi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Absensi Kelas</li>
            </ol>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header">
                        <i class="fa-solid fa-table-list"></i>
                            Pilih Kelas
                        </div>
                        <div class="card-body">   <label for="kelas" class="form-label px-2">Kelas</label>
                                    <select name="kelas" id="kelas" class="form-select" required>
                                        <option value="" selected>-- Pilih Kelas --</option>
                                        <!-- proses ambil data kelas dari db -->
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
                                    </select></div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Absensi
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kehadiran Siswa Kelas</th>
                                            <th>Operasi</th>
                                            
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                        <th>No</th>
                                            <th>Nama</th>
                                            <th>Kehadiran</th>
                                            <th>Operasi</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        
                                    </tbody>
                                </table>
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Sertakan jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Skrip khusus untuk menangani perubahan pada elemen "kelas" -->
<script>
$(document).ready(function() {
    // Menangani perubahan pada elemen "kelas"
    $('#kelas').change(function() {
        var selectedClass = $(this).val();

        // Kirim permintaan AJAX untuk mendapatkan data siswa berdasarkan kelas
        $.ajax({
            type: 'POST',
            url: 'proses-nama-siswa.php', // Sesuaikan dengan URL yang benar
            data: { kelas: selectedClass },
            dataType: 'json',
            success: function(response) {
    // Tanggapi respons dari server dan isi tabel dengan data siswa
    var options = '';
    $.each(response, function(index, data) {
        options += "<tr>" +
            "<td>" + data.nomor + "</td>" +
            "<td>" + data.nama + "</td>" +
            "<td>" +
            "<input type='radio' name='kehadiran[" + data.radioName + "]' value='Hadir' checked> H " +
            "<input type='radio' name='kehadiran[" + data.radioName + "]' value='Izin'> I " +
            "<input type='radio' name='kehadiran[" + data.radioName + "]' value='Sakit'> S " +
            "<input type='radio' name='kehadiran[" + data.radioName + "]' value='Alpha'> A " +
            "</td>" +
            "<td>" +
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
});
</script>

<?php
require_once  "../template/footer.php";



?>