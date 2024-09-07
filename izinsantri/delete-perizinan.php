<?php
// bismillah session start
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

// Call file yang berkaitan  
require_once "../config.php";

// Fungsi logging aktivitas
function log_aktivitas($user, $aksi, $tabel, $data_lama, $data_baru) {
    global $koneksi; // Pastikan koneksi global tersedia

    // Prepare the SQL statement
    $stmt = $koneksi->prepare("INSERT INTO log_aktivitas (user, aksi, tabel, data_lama, data_baru) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . $koneksi->error);
    }

    // Bind parameters
    $stmt->bind_param('sssss', $user, $aksi, $tabel, $data_lama, $data_baru);

    // Execute the statement
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    // Close the statement
    $stmt->close();
}

// Ambil ID dari parameter GET
$id = $_GET["id"];

// Ambil data yang akan dihapus untuk logging
$stmt = $koneksi->prepare("SELECT * FROM tbl_perizinan WHERE id = ?");
$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    die('Query failed: ' . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die('Result fetching failed: ' . $koneksi->error);
}

$data_lama = $result->fetch_assoc();
$stmt->close();

// Convert data lama ke JSON
$data_lama_json = json_encode($data_lama);

// Proses delete data
$stmt = $koneksi->prepare("DELETE FROM tbl_perizinan WHERE id = ?");
$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    die('Delete query failed: ' . $stmt->error);
}
$stmt->close();

// Log aktivitas
if (!isset($_SESSION["ssUser"])) {
    die("user not found in session.");
}

$user = $_SESSION["ssUser"]; // Ambil user dari session
log_aktivitas($user, 'delete', 'tbl_perizinan', $data_lama_json, null);

// Redirect setelah operasi delete
header("Location:perizinan.php?msg=deleted");
exit;
?>
