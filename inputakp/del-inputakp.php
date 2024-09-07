<?php
require_once "../config.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM tbl_kegiatanpembelajaran WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}
?>
