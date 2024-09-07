<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";

// Function to upload images
function uploadImage($uploadDir, $file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $validExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $maxSize = 1 * 1024 * 1024; // 1MB

    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = uniqid() . '.' . $fileExt;

    if (!in_array($fileType, $allowedTypes) || !in_array($fileExt, $validExtensions) || $fileSize > $maxSize) {
        return false;
    }

    $targetFilePath = $uploadDir . '/' . $fileName;
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return $fileName;
    }
    return false;
}

// Check if 'simpan' form was submitted
if (isset($_POST['simpan'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash(1234, PASSWORD_DEFAULT); // For demo purposes; use a real password
    $nama = htmlspecialchars($_POST['namaUser']);
    $email = htmlspecialchars($_POST['emailUser']);
    $no_hp = htmlspecialchars($_POST['no_hpUser']);
    $jabatan = htmlspecialchars($_POST['jabatanUser']);
    $role = htmlspecialchars($_POST['role']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = $_FILES['image']['name'];

    $stmt = $koneksi->prepare("SELECT * FROM tbl_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: user.php?msg=username_exists");
        exit();
    }

    if ($foto) {
        $uploadDir = '../asset/image';
        $foto = uploadImage($uploadDir, $_FILES['image']);
        if (!$foto) {
            header("Location: user.php?msg=image_error");
            exit();
        }
    } else {
        $foto = 'default.png';
    }

    $stmt = $koneksi->prepare("INSERT INTO tbl_user (username, password, nama, email, no_hp, jabatan, role, alamat, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $username, $password, $nama, $email, $no_hp, $jabatan, $role, $alamat, $foto);

    if ($stmt->execute()) {
        header("Location: user.php?msg=added");
        exit();
    } else {
        header("Location: user.php?msg=error");
        exit();
    }
}

// Check if 'update' form was submitted
if (isset($_POST['update'])) {
    $id = htmlspecialchars($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash(1234, PASSWORD_DEFAULT); // For demo purposes; use a real password
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $role = htmlspecialchars($_POST['role']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $fotoLama = htmlspecialchars($_POST['fotoLama']);

    $stmt = $koneksi->prepare("SELECT * FROM tbl_user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($_FILES['image']['error'] === 4) {
        $fotoUser = $fotoLama;
    } else {
        $uploadDir = '../asset/image';
        $fotoUser = uploadImage($uploadDir, $_FILES['image']);
        if (!$fotoUser) {
            header("Location: user.php?msg=image_error");
            exit();
        }
        if ($fotoLama !== 'default.png') {
            unlink($uploadDir . '/' . $fotoLama);
        }
    }

    $stmt = $koneksi->prepare("UPDATE tbl_user SET username = ?, password = ?, nama = ?, email = ?, no_hp = ?, jabatan = ?, role = ?, alamat = ?, foto = ? WHERE id = ?");
    $stmt->bind_param("sssssssssi", $username, $password, $nama, $email, $no_hp, $jabatan, $role, $alamat, $fotoUser, $id);

    if ($stmt->execute()) {
        header("Location: user.php?msg=updated");
        exit();
    } else {
        header("Location: user.php?msg=error");
        exit();
    }
}
?>
