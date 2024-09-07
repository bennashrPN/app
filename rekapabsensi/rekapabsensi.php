<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}
require_once "../config.php";

$title = "Rekap Absensi";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
$nama_guru = isset($_SESSION["nama"]) ? $_SESSION["nama"] : '';

function sort_classes($classes)
{
    // List untuk sort kelas
    $order = array(
        "7 A", "7 B", "7 C", "7 D", "8 A", "8 B", "8 C", "8 D",
        "9 A", "9 B", "9 C", "9 D",
        "10 A", "10 B", "10 C",
        "11 A", "11 B", "11 C",
        "12 A", "12 F", "12 G",
    );
    // Create an associative array for sorting
    $sorted_classes = array();
    foreach ($classes as $class) {
        $sorted_classes[$class] = array_search($class, $order);
    }

    // Sort based on the predefined order
    asort($sorted_classes);

    // Return sorted classes
    return array_keys($sorted_classes);
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Cari Data Presensi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Rekap Presensi</li>
            </ol>
            <div class="row">
                <div class="col-xl-9 col-md-6">
                    <div class="card bg-light-subtle text-black mb-4">
                        <div class="card-body">Rekap Presensi</div>

                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <form id="formCariAbsensi" action="rekapabsensi.php" method="POST">

                                <div class="row gy-2 gx-3 align-items-center">
                                    <div class="col-auto">
                                        <label class="visually-hidden" for="autoSizingUstadz">Ustadz</label>
                                        <select class="form-select" name="autoSizingUstadz" id="autoSizingPelajaran">
                                            <option value="<?= htmlspecialchars($nama_guru) ?>" selected><?= htmlspecialchars($nama_guru) ?></option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label class="visually-hidden" for="autoSizingPelajaran">Pelajaran</label>
                                        <select class="form-select" name="autoSizingPelajaran" id="autoSizingPelajaran">
                                            <option selected disabled>Pelajaran...</option>
                                            <?php
                                            // Prepare and execute query to get subjects based on the teacher's name from tbl_jadwalpelajaran
                                            $queryMapel = mysqli_prepare($koneksi, "
            SELECT DISTINCT pelajaran
            FROM tbl_jadwalpelajaran 
            WHERE guru = ?
        ");
                                            mysqli_stmt_bind_param($queryMapel, "s", $nama_guru);
                                            mysqli_stmt_execute($queryMapel);
                                            $resultMapel = mysqli_stmt_get_result($queryMapel);

                                            if (!$resultMapel) {
                                                die("Query gagal: " . mysqli_error($koneksi));
                                            }

                                            while ($dataMapel = mysqli_fetch_array($resultMapel)) {
                                            ?>
                                                <option value="<?= htmlspecialchars($dataMapel['pelajaran']) ?>"><?= htmlspecialchars($dataMapel['pelajaran']) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-auto">
                                        <label class="visually-hidden" for="autoSizingKelas">Kelas</label>
                                        <select class="form-select" name="autoSizingKelas" id="autoSizingKelas">
                                            <option selected disabled>Kelas...</option>
                                            <?php
                                            // Retrieve the teacher's name from the session
                                            $nama_guru = isset($_SESSION["nama"]) ? $_SESSION["nama"] : '';

                                            // Query to get distinct classes based on the teacher
                                            $queryKelas = mysqli_prepare($koneksi, "
            SELECT DISTINCT kelas 
            FROM tbl_jadwalpelajaran 
            WHERE guru = ?
        ");
                                            mysqli_stmt_bind_param($queryKelas, "s", $nama_guru);
                                            mysqli_stmt_execute($queryKelas);
                                            $resultKelas = mysqli_stmt_get_result($queryKelas);

                                            if (!$resultKelas) {
                                                die("Query gagal: " . mysqli_error($koneksi));
                                            }

                                            // Fetch classes into an array
                                            $kelasArray = array();
                                            while ($dataKelas = mysqli_fetch_array($resultKelas)) {
                                                $kelasArray[] = $dataKelas['kelas'];
                                            }

                                            // Sort classes based on predefined order
                                            $sortedKelas = sort_classes($kelasArray);

                                            // Populate the Kelas dropdown with sorted classes
                                            foreach ($sortedKelas as $kelas) {
                                            ?>
                                                <option value="<?= htmlspecialchars($kelas) ?>"><?= htmlspecialchars($kelas) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Tambahkan rentang waktu dengan label kecil -->
                                    <div class="col-auto">
                                        <label for="startDate" style="display: block; font-size: small;">Tanggal Mulai:</label>
                                        <input type="date" class="form-control" name="startDate" id="startDate" required>
                                    </div>
                                    <div class="col-auto">
                                        <label for="endDate" style="display: block; font-size: small;">Tanggal Akhir:</label>
                                        <input type="date" class="form-control" name="endDate" id="endDate" required>
                                    </div>

                                    <div class="col-auto">
                                        <button type="submit" id="btnCari" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                                    </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                <span class="h6 my-2"><i class="fa-solid fa-list-ul"></i> Data Presensi</span>
                <a href="../asset/export/export_excel.php" class="btn btn-sm btn-success float-end" id="simpanDataBtn"><i class="fa-solid fa-file-excel"></i> Download</a>
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>Pelajaran</th>
                            <th>Kelas</th>
                            <th>Nama</th>
                            <th>kehadiran</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>Pelajaran</th>
                            <th>Kelas</th>
                            <th>Nama </th>
                            <th>kehadiran</th>
                            <th>Tanggal</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                       // Check if the form has been submitted and if the POST keys exist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['autoSizingUstadz'], $_POST['autoSizingPelajaran'], $_POST['autoSizingKelas'], $_POST['startDate'], $_POST['endDate'])) {
    $_SESSION['searchCriteria'] = [
        'guru' => $_POST['autoSizingUstadz'],
        'pelajaran' => $_POST['autoSizingPelajaran'],
        'kelas' => $_POST['autoSizingKelas'],
        'startDate' => $_POST['startDate'],
        'endDate' => $_POST['endDate']
    ];
    $guru = $_POST['autoSizingUstadz'];
    $pelajaran = $_POST['autoSizingPelajaran'];
    $kelas = $_POST['autoSizingKelas'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    // Prepare and execute the SQL query
    $stmt = $koneksi->prepare("SELECT guru, pelajaran, kelas, nama, kehadiran, tanggal FROM tbl_absensi WHERE guru = ? AND pelajaran = ? AND kelas = ? AND tanggal BETWEEN ? AND ?");
    $stmt->bind_param("sssss", $guru, $pelajaran, $kelas, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $counter = 1;
    while ($data = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $counter . "</td>";
        echo "<td>" . htmlspecialchars($data['guru']) . "</td>";
        echo "<td>" . htmlspecialchars($data['pelajaran']) . "</td>";
        echo "<td>" . htmlspecialchars($data['kelas']) . "</td>";
        echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($data['kehadiran']) . "</td>";
        echo "<td>" . htmlspecialchars($data['tanggal']) . "</td>";
        echo "</tr>";
        $counter++;
    }
}
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
    <?php
    require_once  "../template/footer.php";
    ?>