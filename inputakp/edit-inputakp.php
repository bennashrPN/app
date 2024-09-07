<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$title = "Edit Agenda Kegiatan Pembelajaran";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

// Mengambil ID kegiatan yang akan diedit dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    header('Location: inputakp.php?msg=error');
    exit;
}

// Ambil data kegiatan pembelajaran berdasarkan ID
$query = "SELECT * FROM tbl_kegiatanpembelajaran WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
if (!$data) {
    header('Location: inputakp.php?msg=error');
    exit;
}

// Ambil daftar guru untuk dropdown
$queryGuru = "SELECT * FROM tbl_guru";
$resultGuru = mysqli_query($koneksi, $queryGuru);

// Ambil daftar kelas untuk dropdown
$queryKelas = "SELECT DISTINCT kelas FROM tbl_kelas";
$resultKelas = mysqli_query($koneksi, $queryKelas);

// Ambil daftar pelajaran untuk dropdown
$queryPelajaran = "SELECT DISTINCT Pelajaran FROM tbl_jadwalpelajaran";
$resultPelajaran = mysqli_query($koneksi, $queryPelajaran);

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alerts = [
    'updated' => ['type' => 'success', 'icon' => 'fa-circle-check', 'message' => 'Agenda Kegiatan Pembelajaran berhasil diperbarui.'],
    'error' => ['type' => 'danger', 'icon' => 'fa-xmark', 'message' => 'Terjadi kesalahan saat memperbarui data.'],
];

$alert = isset($alerts[$msg]) ? $alerts[$msg] : null;
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Edit Agenda Kegiatan Pembelajaran</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard/dashboard-<?= strtolower($_SESSION['role']) ?>.php">Home</a></li>
                <li class="breadcrumb-item"><a href="inputakp.php">Data AKP</a></li>
                <li class="breadcrumb-item active">Edit Agenda</li>
            </ol>
           
            <div class="col-xl-6 col-md-6">
                <?php if ($alert): ?>
                    <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                        <i class="fa-solid <?= $alert['icon'] ?>"></i> <?= $alert['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>

            <form method="post" action="proses-inputakp.php">
                <div class="row">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Edit Agenda</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="guru" class="form-label ps-3">Ustadz</label>
                                    <label for="guru" class="col-sm-1 col-form-label">:</label>
                                    <select name="guru" id="guru" class="form-select js-example-basic-single" required>
                                        <option value="" disabled>- Pilih Ustadz -</option>
                                        <?php while ($dataGuru = mysqli_fetch_assoc($resultGuru)): ?>
                                            <option value="<?= htmlspecialchars($dataGuru['nama']); ?>" <?= $dataGuru['nama'] == $data['guru'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dataGuru['nama']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas" class="form-label ps-3">Kelas</label>
                                    <label for="kelas" class="col-sm-1 col-form-label">:</label>
                                    <select name="kelas" id="kelas" class="form-select js-example-basic-single" required>
                                        <option value="" disabled>-- Pilih Kelas --</option>
                                        <?php while ($dataKelas = mysqli_fetch_assoc($resultKelas)): ?>
                                            <option value="<?= htmlspecialchars($dataKelas['kelas']); ?>" <?= $dataKelas['kelas'] == $data['kelas'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dataKelas['kelas']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pelajaran" class="form-label ps-3">Pelajaran</label>
                                    <label for="pelajaran" class="col-sm-1 col-form-label">:</label>
                                    <select name="pelajaran" id="pelajaran" class="form-select js-example-basic-single" required>
                                        <option value="" disabled>-- Pilih Pelajaran --</option>
                                        <?php while ($dataPelajaran = mysqli_fetch_assoc($resultPelajaran)): ?>
                                            <option value="<?= htmlspecialchars($dataPelajaran['Pelajaran']); ?>" <?= $dataPelajaran['Pelajaran'] == $data['Pelajaran'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dataPelajaran['Pelajaran']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="materiAKP" class="form-label ps-3">Materi</label>
                                    <label for="materiAKP" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="materiAKP" id="materiAKP" cols="30" rows="3" class="form-control" required><?= htmlspecialchars($data['materiPembelajaran']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kegiatanAKP" class="form-label ps-3">Kegiatan</label>
                                    <label for="kegiatanAKP" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="kegiatanAKP" id="kegiatanAKP" cols="30" rows="3" class="form-control" required><?= htmlspecialchars($data['kegiatanPembelajaran']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="ket" class="form-label ps-3">Keterangan</label>
                                    <label for="ket" class="col-sm-1 col-form-label">:</label>
                                    <textarea name="ket" id="ket" cols="30" rows="3" class="form-control" required><?= htmlspecialchars($data['keterangan']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="hari" class="form-label ps-3">Hari</label>
                                    <label for="hari" class="col-sm-1 col-form-label">:</label>
                                    <select name="hari" id="hari" class="form-select" required>
                                        <option value="" disabled>-- Pilih Hari --</option>
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
                                    <label for="tanggal_izin" class="form-label ps-3">Tanggal Izin</label>
                                    <label for="tanggal_izin" class="col-sm-1 col-form-label">:</label>
                                    <input type="date" name="tanggal_izin" id="tanggal_izin" class="form-control" value="<?= htmlspecialchars($data['tanggal']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                                </button>
                                <a href="inputakp.php" class="btn btn-secondary">
                                    <i class="fa-solid fa-arrow-left"></i> Batal
                                </a>
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
