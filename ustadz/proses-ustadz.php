<?php

session_start();
if (!isset($_SESSION['ssLogin'])) {
    header('Location../auth/login.php');
    exit;
}

require_once '../config.php';

// Check if the form is submitted
if (isset($_POST['simpan'])) {
    $nip = htmlspecialchars($_POST['nip']);
    $nama = htmlspecialchars($_POST['nama']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $status = $_POST['status'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = $_FILES['image']['name'];

    // Validate NIP
    if (!preg_match('/^[0-9]{10,}$/', $nip)) {
        header('Location: ustadz.php?msg=invalidnip');
        exit;
    }

    // Validate other fields as needed

    // Check if NIP already exists
    $cekNip = mysqli_query($koneksi, "SELECT nip FROM tbl_guru WHERE nip = '$nip'");
    if (mysqli_num_rows($cekNip) > 0) {
        header('Location: ustadz.php?msg=cancel');
        exit;
    }

    // Upload image
    if ($foto != null) {
        $url = 'add-ustadz.php';
        $foto = uploadimg($url); // Replace this with your actual uploadimg function
    } else {
        $foto = 'default.png';
    }

    // Insert data into the database
    $query = "INSERT INTO tbl_guru VALUES (null, '$nip', '$nama', '$jabatan', '$status', '$alamat', '$foto')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header('Location: ustadz.php?msg=added');
        exit;
    } else {
        header('Location: ustadz.php?msg=error');
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nip = htmlspecialchars($_POST['nip']);
    $nama = htmlspecialchars($_POST['nama']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $status = $_POST['status'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $fotoLama = htmlspecialchars($_POST['fotoLama']);
    
    // Mendapatkan data saat ini dari database
    $sqlUstadz = mysqli_query($koneksi, "SELECT * FROM tbl_guru WHERE id = '$id'");
    $data = mysqli_fetch_assoc($sqlUstadz);
    $curNip = $data['nip'];

    // Jika NIP berubah
    if ($nip !== $curNip) {
        // Periksa apakah NIP baru sudah ada di database, kecuali untuk record saat ini
        $newNipCheck = mysqli_query($koneksi, "SELECT nip FROM tbl_guru WHERE nip = '$nip' AND id != '$id'");
        if (mysqli_num_rows($newNipCheck) > 0) {
            header("location: ustadz.php?msg=cancel");
            exit;
        }
    }

    // Memproses gambar yang diunggah
    if ($_FILES['image']['error'] === 4) {
        $fotoUstadz = $fotoLama; // Gunakan foto lama jika tidak ada gambar baru yang diunggah
    } else {
        $fotoUstadz = uploadimg('ustadz.php');
        if ($fotoLama !== 'default.png') {
            @unlink('../asset/image/' . $fotoLama); // Hapus foto lama jika bukan default
        }
    }

    // Update data di database
    $updateQuery = "UPDATE tbl_guru SET nip = '$nip', nama = '$nama', jabatan = '$jabatan', status = '$status', alamat = '$alamat', foto = '$fotoUstadz' WHERE id = '$id'";
    $result = mysqli_query($koneksi, $updateQuery);

    if ($result) {
        header("location: ustadz.php?msg=updated");
        exit;
    } else {
        header("location: ustadz.php?msg=error");
        exit;
    }
}

// Additional code if needed


