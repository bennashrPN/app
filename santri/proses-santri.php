<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header('Location:../auth/login.php');
    exit;
}

require_once "../config.php";

// Inisialisasi nilai input
$nis = $nama = $kelas = $jenjang = $alamat = '';

if (isset($_POST['simpan'])) {
    $nis = $_POST['nis'];

    // Validasi panjang dan format NIS
    if (!is_numeric($nis) || strlen($nis) !== 10) {
        header('Location: add-santri.php?msg=gagal');
        exit;
    }

    // Cek duplikasi NIS
    $check_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_siswa WHERE nis = '$nis'");
    $result = mysqli_fetch_assoc($check_query);
    if ($result['total'] > 0) {
        header('Location: add-santri.php?msg=cancel');
        exit;
    }


    // Lakukan penyimpanan data
    $nama = htmlspecialchars($_POST['nama']);
    $kelasAngka = $_POST['kelasAngka'];
    $kelasAbjad = $_POST['kelasAbjad'];
    $jenjang = $_POST['jenjang'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = htmlspecialchars($_FILES["image"]["name"]);

    // Gabungkan kelas angka dan abjad dalam satu kolom
    $kelas = $kelasAngka . $kelasAbjad;

    // Upload image
    if ($foto != null) {
        $url = 'add-santri.php';
        $foto = uploadimg($url); // Replace this with your actual uploadimg function
    } else {
        $foto = 'default.png';
    }

// Insert data into the database
$query = "INSERT INTO tbl_siswa VALUES (null, '$nis', '$nama', '$kelas', '$jenjang', '$alamat', '$foto')";
$result = mysqli_query($koneksi, $query);

if ($result) {
    header('Location: add-santri.php?msg=added');
    exit;
} else {
    header('Location: add-santri.php?msg=error');
    exit;
}
}

if (isset($_POST['update'])) {
    // Retrieve form inputs
    $id = $_POST['id'];
    $nis = htmlspecialchars($_POST['nis']);
    $nama = htmlspecialchars($_POST['nama']);
    $kelasAngka = $_POST['kelasAngka'];
    $kelasAbjad = $_POST['kelasAbjad'];
    $jenjang = $_POST['jenjang'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $fotoLama = htmlspecialchars($_POST['fotoLama']);

    // Combine kelasAngka and kelasAbjad into kelas with a space between them
    $kelas = $kelasAngka . ' ' . $kelasAbjad;


    // Get current data from the database
    $sqlSantri = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE id = '$id'");
    $data = mysqli_fetch_assoc($sqlSantri);
    $curNis = $data['nis'];

    // If NIS has changed
    if ($nis !== $curNis) {
        // Check if the new NIS already exists in the database (excluding the current record)
        $newNisCheck = mysqli_query($koneksi, "SELECT nis FROM tbl_siswa WHERE nis = '$nis' AND id != '$id'");
        if (mysqli_num_rows($newNisCheck) > 0) {
            header("Location: santri.php?msg=cancel");
            exit;
        }
    }

    // Process the uploaded image
    if ($_FILES['image']['error'] === 4) {
        $fotoSantri = $fotoLama; // Use the old photo if no new image is uploaded
    } else {
        $fotoSantri = uploadimg('santri.php');
        if ($fotoLama !== 'default.png') {
            @unlink('../asset/image/' . $fotoLama); // Delete the old photo if it is not the default
        }
    }

    // Update data in the database
    $updateQuery = "UPDATE tbl_siswa SET nis = '$nis', nama = '$nama', kelas = '$kelas', jenjang = '$jenjang', alamat = '$alamat', foto = '$fotoSantri' WHERE id = '$id'";
    $result = mysqli_query($koneksi, $updateQuery);

    if ($result) {
        header("Location: santri.php?msg=updated");
        exit;
    } else {
        header("Location: santri.php?msg=error");
        exit;
    }
}