<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// Including necessary files
require_once "../config.php";
$title = "Perizinan us";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = $_GET['msg'] ?? '';

$alerts = [
    'deleted' => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Delete berhasil, ...'],
    'cancel' => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'Update jabatan gagal, ID sudah ada ...'],
    'not_excel' => ['type' => 'warning', 'icon' => 'fa-xmark', 'message' => 'File yang diupload bukan Excel!'],
    'upload_failed' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'File gagal diupload!'],
    'error_query' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat query.'],
    'error_prepare' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat menyiapkan query.'],
    'error_file' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat memproses file.'],
    'success' => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Alhamdulillah, izin ustadz berhasil'],
    'updated' => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Alhamdulillah, Update berhasil'],
];

$alert = $alerts[$msg] ?? null;
?>

<!-- isi konten halaman ustadz -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Input AKP</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="<?= $main_url ?>dashboard/dashboard.php-<?= strtolower($_SESSION["role"]) ?>.php">Home</a>
                </li>
                <li class="breadcrumb-item active">Data Input AKP</li>
            </ol>
            <div class="col-xl-12 col-md-6">
                <?php if ($alert): ?>
                    <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                        <i class="fa-solid <?= $alert['icon'] ?>"></i> <?= $alert['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2">
                        <i class="fa-solid fa-list-ul"></i> Data
                    </span>
                    <a href="<?= $main_url ?>inputakp/add-inputakp.php" class="btn btn-sm btn-success float-end">
                        <i class="fa-solid fa-circle-plus"></i> Add
                    </a>
                </div>
                <div class="loading text-center" style="display:none;">
                    <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col" class="text-center">Ustadz</th>
                                    <th scope="col" class="text-center">Kelas</th>
                                    <th scope="col" class="text-center">Pelajaran</th>
                                    <th scope="col" class="text-center">Materi Pembelajaran</th>
                                    <th scope="col" class="text-center">Kegiatan Pembelajaran</th>
                                    <!-- <th scope="col" class="text-center">Keterangan</th> -->
                                    <th scope="col" class="text-center">Hari</th>
                                    <th scope="col" class="text-center">Tanggal</th>
                                    <th scope="col" class="text-center">Operasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $queryInputAkp = mysqli_query($koneksi, "SELECT * FROM tbl_kegiatanpembelajaran");
                                while ($data = mysqli_fetch_array($queryInputAkp)) { ?>
                                    <tr>
                                        <th scope="row"><?= $no++ ?></th>
                                        <td class="text-center"><?= $data['guru'] ?></td>
                                        <td class="text-center"><?= $data['kelas'] ?></td>
                                        <td class="text-center"><?= $data['Pelajaran'] ?></td>
                                        <td class="text-center"><?= $data['materiPembelajaran'] ?></td>
                                        <td class="text-center"><?= $data['kegiatanPembelajaran'] ?></td>
                                        <!-- <td class="text-center"><?= $data['keterangan'] ?></td> -->
                                        <td class="text-center"><?= $data['hari'] ?></td>
                                        <td class="text-center"><?= $data['tanggal'] ?></td>
                                        <td class="text-center">
                                            <a href="edit-inputakp.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning" title="Update perizinan">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" id="btnDelete" title="Delete perizinan" data-id="<?= $data['id'] ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
       $(document).ready(function() {
    var table = $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "../inputakp/get_data_akp.php",
            "type": "POST",
            "data": function(d) {
                d.search = $('#searchInput').val(); // Add any additional parameters if needed
            },
            "beforeSend": function() {
                $('.loading').show(); // Show loading indicator
                $('#example').hide(); // Hide table while loading
            },
            "complete": function() {
                $('.loading').hide(); // Hide loading indicator
                $('#example').show(); // Show table after loading
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "guru" },
            { "data": "kelas" },
            { "data": "Pelajaran" },
            { "data": "materiPembelajaran" },
            { "data": "kegiatanPembelajaran" },
            { "data": "hari" },
            { "data": "tanggal" },
            { "data": "operasi", "orderable": false, "searchable": false }
        ],
        "lengthMenu": [
            [5, 10, 15, 20],
            [5, 10, 15, 20]
        ],
        "pagingType": "simple_numbers",
        "order": [[7, 'desc']] // Order by 'tanggal' column in descending order
    });


    // Debounce for search input
    let debounceTimer;
    $('#searchInput').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            table.ajax.reload(null, false); // Reload DataTables, preserving the current page
        }, 300); // 300ms delay
    });

    // Delete operation
    $(document).on('click', '#btnDelete', function() {
        var id = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: '../inputakp/del-inputakp.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        table.row(row).remove().draw(false); // Remove row and redraw table
                    } else {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    alert('Gagal menghubungi server. Coba lagi nanti.');
                }
            });
        }
    });

    // Periodic data refresh
    setInterval(function() {
        table.ajax.reload(null, false); // Reload data every 5 minutes, preserving the current page
    }, 300000); // 300000ms = 5 minutes
});

    </script>
    <?php require_once "../template/footer.php"; ?>
</div>
