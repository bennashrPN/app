<?php
// Start the session
session_start();
if (!isset($_SESSION['ssLogin'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Database connection
require_once "../config.php";

$title = "Log Aktivitas";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-5">Log Aktivitas</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?= htmlspecialchars($main_url) ?>dashboard/dashboard-<?= strtolower(htmlspecialchars($_SESSION['role'])) ?>.php">Home</a></li>
                <li class="breadcrumb-item active">Log Aktivitas</li>
            </ol>

            <div id="layoutError">
                <div id="layoutError_content">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="text-center mt-4">
                                    <!-- PHP code to fetch and display data -->
                                    <?php
                                    $sql = "SELECT * FROM log_aktivitas ORDER BY waktu DESC";
                                    $result = $koneksi->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<div class="table-responsive">';
                                        echo '<table class="table table-striped table-bordered">';
                                        echo '<thead><tr>';
                                        echo '<th>User ID</th>';
                                        echo '<th>user</th>';
                                        echo '<th>Aksi</th>';
                                        echo '<th>Tabel</th>';
                                        echo '<th>Data Lama</th>';
                                        echo '<th>Data Baru</th>';
                                        echo '<th>Waktu</th>';
                                        echo '</tr></thead>';
                                        echo '<tbody>';

                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row["user_id"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($row["user"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($row["aksi"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($row["tabel"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($row["data_lama"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($row["data_baru"]) . '</td>';
                                            echo '<td>' . htmlspecialchars($row["waktu"]) . '</td>';
                                            echo '</tr>';
                                        }

                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="alert alert-info" role="alert">No results found.</div>';
                                    }

                                    $koneksi->close();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
require_once "../template/footer.php";
?>

<!-- Bootstrap JS and dependencies -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
