<?php

// bismillah session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php"; // Ensure this file sets up the database connection
$title = "Edit Izin Guru";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// Get the izin ID from the URL parameter
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    header("Location: izinustadz.php");
    exit;
}

// Fetch existing izin data based on the ID
$query = "SELECT * FROM tbl_izinguru WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: izinustadz.php?msg=not_found");
    exit;
}

// Fetch all teachers for the dropdowns
$queryGuru = "SELECT nama FROM tbl_guru";
$resultGuru = mysqli_query($koneksi, $queryGuru);

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alerts = [
    'updated' => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Alhamdulillah, izin ustadz berhasil diperbarui'],
    'error_query'    => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat query.'],
];

$alert = isset($alerts[$msg]) ? $alerts[$msg] : null;
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Edit Izin Guru</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../ustadz/ustadz.php">Home</a></li>
                <li class="breadcrumb-item"><a href="izinustadz.php">Izin Guru</a></li>
                <li class="breadcrumb-item active">Edit Izin Guru</li>
            </ol>
            <div class="col-xl-6 col-md-6">
                <?php if ($alert): ?>
                    <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                        <i class="fa-solid <?= $alert['icon'] ?>"></i> <?= $alert['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
            <form method="post" action="proses-izinustadz.php">
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Izin Guru</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="guruIzin" class="form-label ps-3">Guru</label>
                                    <label for="guruIzin" class="col-sm-1 col-form-label">:</label>
                                    <select name="guruIzin" id="guruIzin" class="form-select js-example-basic-single" required>
                                        <option value="" disabled>- Guru Izin -</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultGuru)): ?>
                                            <option value="<?= htmlspecialchars($row['nama']); ?>" <?= $data['guruIzin'] == $row['nama'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($row['nama']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alasanIzin" class="form-label ps-3">Alasan Izin</label>
                                    <label for="alasanIzin" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="alasanIzin" id="alasanIzin" cols="30" rows="3" class="form-control" required><?= htmlspecialchars($data['alasanIzin']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="waktuMulai" class="form-label px-2">Waktu Mulai</label>
                                    <select name="waktuMulai" id="waktuMulai" class="form-select" required>
                                        <option value="" selected disabled>-- Pilih Waktu Mulai --</option>
                                        <?php
                                        $queryWaktupelajaran = mysqli_query($koneksi, "
            SELECT DISTINCT waktu_mulai FROM tbl_waktupelajaran 
            UNION 
            SELECT DISTINCT waktu_mulai FROM tbl_izinguru
        ");
                                        if (!$queryWaktupelajaran) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                        ?>
                                            <option value="<?= $dataWaktupelajaran['waktu_mulai'] ?>" <?= $data['waktu_mulai'] == $dataWaktupelajaran['waktu_mulai'] ? 'selected' : '' ?>>
                                                <?= $dataWaktupelajaran['waktu_mulai'] ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="waktuSelesai" class="form-label px-2">Waktu Selesai</label>
                                    <select name="waktuSelesai" id="waktuSelesai" class="form-select" required>
                                        <option value="" selected disabled>-- Pilih Waktu Selesai --</option>
                                        <?php
                                        $queryWaktupelajaran = mysqli_query($koneksi, "
            SELECT DISTINCT waktu_selesai FROM tbl_waktupelajaran 
            UNION 
            SELECT DISTINCT waktu_selesai FROM tbl_izinguru
        ");
                                        if (!$queryWaktupelajaran) {
                                            die("Query gagal: " . mysqli_error($koneksi));
                                        }

                                        while ($dataWaktupelajaran = mysqli_fetch_array($queryWaktupelajaran)) {
                                        ?>
                                            <option value="<?= $dataWaktupelajaran['waktu_selesai'] ?>" <?= $data['waktu_selesai'] == $dataWaktupelajaran['waktu_selesai'] ? 'selected' : '' ?>>
                                                <?= $dataWaktupelajaran['waktu_selesai'] ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="hari" class="form-label ps-3">Hari</label>
                                    <label for="hari" class="col-sm-1 col-form-label">:</label>
                                    <select name="hari" id="hari" class="form-select" required>
                                        <option value="" disabled>-- Pilih Hari--</option>
                                        <option value="Senin" <?= $data['hari'] == 'Senin' ? 'selected' : '' ?>>Senin</option>
                                        <option value="Selasa" <?= $data['hari'] == 'Selasa' ? 'selected' : '' ?>>Selasa</option>
                                        <option value="Rabu" <?= $data['hari'] == 'Rabu' ? 'selected' : '' ?>>Rabu</option>
                                        <option value="Kamis" <?= $data['hari'] == 'Kamis' ? 'selected' : '' ?>>Kamis</option>
                                        <option value="Jumat" <?= $data['hari'] == 'Jumat' ? 'selected' : '' ?>>Jumat</option>
                                        <option value="Sabtu" <?= $data['hari'] == 'Sabtu' ? 'selected' : '' ?>>Sabtu</option>
                                        <option value="Minggu" <?= $data['hari'] == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl" class="form-label ps-3">Tanggal Izin</label>
                                    <label for="tgl" class="col-sm-1 col-form-label">:</label>
                                    <input type="date" name="tgl" id="tgl" class="form-control" value="<?= htmlspecialchars($data['tgl']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="guruGanti" class="form-label ps-3">Pengganti</label>
                                    <label for="guruGanti" class="col-sm-1 col-form-label">:</label>
                                    <select name="guruGanti" id="guruGanti" class="form-select js-example-basic-single">
                                        <option value="" disabled>- Tidak ada pengganti -</option>
                                        <?php
                                        // Reset the query result for the replacement teacher dropdown
                                        mysqli_data_seek($resultGuru, 0);
                                        while ($row = mysqli_fetch_assoc($resultGuru)) {
                                        ?>
                                            <option value="<?= htmlspecialchars($row['nama']); ?>" <?= $data['guruGanti'] == $row['nama'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($row['nama']); ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label ps-3">Status</label>
                                    <label for="status" class="col-sm-1 col-form-label">:</label>
                                    <select name="status" id="status" class="form-select border-bottom" required>
                                        <option value="" disabled>--Status--</option>
                                        <option value="Aktif" <?= $data['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Selesai" <?= $data['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                                    </button>
                                    <a href="izinustadz.php" class="btn btn-secondary">
                                        <i class="fa-solid fa-arrow-left"></i> Batal
                                    </a>
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
