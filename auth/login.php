<?php
session_start();
if (isset($_SESSION["ssLogin"])) {
    header("location: ../index.php");
    exit;
}
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - Hudhuur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= $main_url ?>auth/login.css">
    <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="<?= $main_url ?>asset/image/markaz.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .toast {
            max-width: 100px;
            /* Set maximum width for the toast */
            min-width: 200px;
            /* Set minimum width for the toast */
        }

        .toast-header {
            padding: 0.25rem 0.75rem;
            /* Reduce padding */
            font-size: 0.875rem;
            /* Reduce font size */
        }

        .toast-body {
            padding: 0.5rem;
            /* Reduce padding */
            font-size: 0.875rem;
            /* Reduce font size */
        }

        .toast-container {
            z-index: 10000;
        }
    </style>
</head>
<body>
    <!-- Toast Notifications -->
    <div id="loginMessage" class="toast-container position-fixed top-0 start-50 translate-middle-x" style="z-index: 10000;">
        <?php
        if (isset($_SESSION['success'])) {
            echo '
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Sukses</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['success'] . '
            </div>
        </div>
        ';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['error'] . '
            </div>
        </div>
        ';
            unset($_SESSION['error']);
        }
        ?>
    </div>

    <!-- Login Form -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #198754;">
                <div class="featured-image mt-3">
                    <img src="<?= $main_url ?>asset/image/logologin.webp" class="img-fluid" style="width: 250px;">
                </div>
            </div>
            <div class="col-md-6 right-box">
                <form id="loginForm">
                    <div class="row align-items-center">
                        <div class="header-text text-center mb-4">
                            <h2>Ahlan Wa Sahlan</h2>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg bg-light fs-6" id="username" name="username" placeholder="Username" required autocomplete="off">
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" id="inputPassword" name="password" placeholder="Password" required autocomplete="off">
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-lg btn-success w-100 fs-6" name="login">Login</button>
                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="text-muted small">Copyright &copy; Markaz Al Matuq <?= date("Y") ?></div>
                        </div>
                    </div>
                </form>
                <!-- Trigger Button for Modal -->
                <button type="button" class="btn btn-info mt-3 text-red" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                  Update Info Hudhuur <br> {Klik di sini}</br>
                </button>
            </div>
        </div>
    </div>
    <!-- Information Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Info</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Informasi Update Data</p>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Guru</td>
                            <td class="status-update">Up to date</td>
                        </tr>
                        <tr>
                            <td>Siswa</td>
                            <td class="status-update">Up to date</td>
                        </tr>
                        <tr>
                            <td>Jadwal Pelajaran</td>
                            <td class="status-unupdate">In Progress</td>
                        </tr>
                    </tbody>
                </table>
                <p>Bila ada pertanyaan dapat hub kami di <a href="https://wa.me/+6289522597210" target="_blank">Whatsapp</a></p>
    <h6>Riwayat Perubahan:</h6>
    <ul>
        <li>Tambah fitur Agenda Kegiatan Pembelajaran.</li>
        <li>Alhamdulillah , semua data sudah terupdate , bila ada penulisan , nama , gelar yang keliru/ 
            tidak sesuai atau tidak terdaftar silahkan hubungi admin.</li>
            <li>##Fixing
            kolom file pdf belum presensi.</li>

        
    </ul>
            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Baik</button>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            $('.toast').toast('show');
            $('#loginForm').submit(function(event) {
                event.preventDefault();
                const username = $('#username').val();
                const password = $('#inputPassword').val();
                $.ajax({
                    url: 'proseslogin.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        username: username,
                        password: password
                    }),
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#loginMessage').html('<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header bg-success text-white"><strong class="me-auto">Sukses</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">Login successful!</div></div>');
                            setTimeout(function() {
                                switch (response.role) {
                                    case 'Admin':
                                        window.location.href = '../dashboard/dashboard-admin.php';
                                        break;
                                    case 'Walikelas':
                                        window.location.href = '../dashboard/dashboard-walikelas.php';
                                        break;
                                    case 'Guru':
                                        window.location.href = '../dashboard/dashboard-guru.php';
                                        break;
                                    case 'Staf':
                                        window.location.href = '../dashboard/dashboard-staf.php';
                                        break;
                                    case 'UKS':
                                        window.location.href = '../dashboard/dashboard-uks.php';
                                        break;
                                    case 'Staf Kesantrian':
                                        window.location.href = '../dashboard/dashboard-staf-kesantrian.php';
                                        break;    
                                    default:
                                        window.location.href = '../index.php';
                                        break;
                                }
                            }, 1000);
                        } else {
                            $('#loginMessage').html('<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header bg-danger text-white"><strong class="me-auto">Error</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">' + response.message + '</div></div>');
                            $('.toast').toast('show');
                        }
                    },
                    error: function() {
                        $('#loginMessage').html('<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header bg-danger text-white"><strong class="me-auto">Error</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">There was an error processing your request.</div></div>');
                        $('.toast').toast('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
