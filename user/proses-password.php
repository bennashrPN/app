<?php

session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config.php';

if (isset($_POST['simpan'])) {
    // Mengambil password dari form
    $curPass = trim(htmlspecialchars($_POST['currPass']));
    $newPass = trim(htmlspecialchars($_POST['newPass']));
    $confPass = trim(htmlspecialchars($_POST['confPass']));
    
    if (empty($curPass) || empty($newPass) || empty($confPass)) {
        // Jika ada input yang kosong, kembalikan ke halaman dengan pesan error
        header("Location: ganti-password.php?msg=err1");
        exit;
    }

    // Mendapatkan data pengguna dari database
    $userName = $_SESSION['ssUser'];
    $queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$userName'");
    $data = mysqli_fetch_array($queryUser);
    
    if ($data && password_verify($curPass, $data['password'])) {
        // Verifikasi jika password baru cocok dengan konfirmasi password
        if ($newPass === $confPass) {
            // Hash password baru
            $pass = password_hash($newPass, PASSWORD_DEFAULT);
            
            // Update password di database
            mysqli_query($koneksi, "UPDATE tbl_user SET password = '$pass' WHERE username = '$userName'");
            
            // Arahkan pengguna dengan pesan sukses
            header("Location: ganti-password.php?msg=updated");
            exit;
        } else {
            // Jika password baru dan konfirmasi password tidak cocok
            header("Location: ganti-password.php?msg=err2");
            exit;
        }
    } else {
        // Jika password lama tidak cocok
        header("Location: ganti-password.php?msg=err1");
        exit;
    }
}




?>